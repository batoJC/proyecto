<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Correo;
use App\Jobs\ProcessEmail;
use App\User;
use Exception;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Crypt;


class CorreoController extends Controller
{
    //

    /**
     * Este es el controlador encargado del envió de emails
     */

    /**
     * try send email
     *
     * @param $conjunto Conjunto where the mail is sent from
     * @param $users users for send email
     * @param $subject subject of email
     * @param $content this is message this can to be html or text plain
     * @param $file This is a file or not
     */
    public function enviarEmailToPerson(Conjunto $conjunto, $users, $subject, $content, $file = null)
    {

        //settigs for send mail
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;

        //data of conjunto
        $mail->Username = $conjunto->correo;
        $mail->Password = Crypt::decrypt($conjunto->password);
        $mail->setFrom($conjunto->correo, utf8_decode($conjunto->nombre));

        //mail data
        $mail->Subject = $subject;
        $mail->msgHTML(view('emails.plantilla')->with('contenido', $content));

        //users for to send
        foreach ($users as $user) {
            $mail->addAddress($user->email, utf8_decode($user->nombre_completo));
        }

        if ($file != null) {
            $mail->addAttachment(public_path($file));
        }

        //send mail
        if(!$mail->send()){
            return new Exception($mail->ErrorInfo);
        }

        return true;
    }

    /**
     * try send email
     *
     * @param $conjunto Conjunto where the mail is sent from
     * @param $users users for send email
     * @param $subject subject of email
     * @param $content this is message this can to be html or text plain
     * @param $file This is a file or not
     */
    public function enviarEmail(Conjunto $conjunto, $users, $subject, $content, $file = null)
    {
        $conjunto_id = null;
        if ($conjunto->id != 0) {
            $conjunto_id = $conjunto->id;
        }

        $correo = $this->saveEmail($conjunto_id, $users, $subject, $content, $file);

        //TODO: add logic for delay between emails
        $process = new ProcessEmail($correo);
        $process->dispatch($correo)->onQueue('high');

        return true;
    }


    public function enviarEmailPrueba($email)
    {
        try {
            $conjunto = Conjunto::find(session('conjunto'));
            //settigs for send mail
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAuth = true;

            //data of conjunto
            $mail->Username = $conjunto->correo;
            $mail->Password = Crypt::decrypt($conjunto->password);

            $mail->setFrom($conjunto->correo, utf8_decode($conjunto->nombre));

            //mail data
            $mail->Subject = 'Correo de prueba';
            $mail->msgHTML(view('emails.plantilla')->with('contenido', 'Correo de prueba de ' . $conjunto->nombre));

            $mail->addAddress($email, 'Nombre del usuario');


            //send mail
            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }    

    /**
     * Save the email in the BD
     */
    public function saveEmail($conjunto_id, $users, $subject, $content, $file = null)
    {
        $correo = new Correo();
        $ids = array();
        foreach ($users as $user) {
            $ids[] = $user->id;
        }

        $correo->users = json_encode($ids);
        $correo->subject = $subject;
        $correo->content = $content;
        $correo->file = $file;
        $correo->conjunto_id = $conjunto_id;
        $correo->save();

        return $correo;
    }
}
