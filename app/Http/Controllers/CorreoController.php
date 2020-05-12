<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Correo;
use App\User;
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
    public function enviarEmail(Conjunto $conjunto, $users, $subject, $content, $file = null)
    {

        try {

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
            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }


    public function enviarEmailPrueba($email){
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
            $mail->msgHTML(view('emails.plantilla')->with('contenido', 'Correo de prueba de '.$conjunto->nombre));

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
     * send emails saved in the DB and delete from BD
     * 
     */
    public function sendsEmailSaved()
    {
        $emails = Correo::get();
        foreach ($emails as $email) {
            $users = array();
            $data = json_decode($email->users);
            foreach ($data as $key) {
                $user = User::find($key);
                if($user != null){
                    $users[] = $user;
                }
            }
            //send email
            if($this->enviarEmail($email->conjunto,$users,$email->subject,$email->content,$email->file)){
                $email->delete();
            }
        }
    }

    /**
     * Save the email in the BD
     */
    public function saveEmail($conjunto, $users, $subject, $content, $file = null)
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
        $correo->conjunto_id = $conjunto;
        $correo->save();
    }
}
