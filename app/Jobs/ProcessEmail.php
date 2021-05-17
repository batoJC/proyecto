<?php

namespace App\Jobs;

use App\Conjunto;
use App\Http\Controllers\CorreoController;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const LETRAS_ERROR = 300;

    protected $correo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($correo)
    {
        $this->correo = $correo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $conjunto = $this->correo->conjunto;
        try {
            if (!$conjunto){
                $conjunto = new Conjunto();
                $conjunto->nombre = 'Gestión copropietario';
                $conjunto->correo = env("MAIL_USERNAME");
                $conjunto->password = Crypt::encrypt(env("MAIL_PASSWORD"));
            }

            $users = array();
            $data = json_decode($this->correo->users);
            foreach ($data as $key) {
                $user = User::find($key);
                if ($user != null) {
                    $users[] = $user;
                }
            }

            $senderCorreo = new CorreoController();

            $res = $senderCorreo->enviarEmailToPerson($conjunto,$users,$this->correo->subject,$this->correo->content);
            if (!$res){
                 Log::channel('slack')->critical("Error, no se pudo enviar el email de la cola para:
                \n Email id: {$this->correo->id}
                \nAsunto: {$this->correo->subject}
                \nEmail donde se intento enviar: {$conjunto->email}
                \nMensaje: {$this->correo->content}");
            }

            $this->correo->delete();

        } catch (\Throwable $th) {
            $error = $th->getMessage();
             Log::channel('slack')->critical("Error, Ocurrio un errror al enviar el correo desde la cola:
                \n Email id: {$this->correo->id}
                \nAsunto: {$this->correo->subject}
                \nEmail donde se intento enviar: {$conjunto->email}
                \nMensaje: {$this->correo->content}
                \nError: {$error}");
        }
    }
}
