<?php

namespace App\Console\Commands;

use App\Http\Controllers\CorreoController;
use App\Mantenimiento;
use App\Conjunto;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Console\Command;

class RecordatorioMantenimientos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mantenimientos:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $mantenimientos = Mantenimiento::where([
            ['realizado',false],
            ['fecha','<=',date('Y-m-d')]
        ])->get();

        foreach ($mantenimientos as $mantenimiento) {
            $administrador = User::where([
                ['id_conjunto',$mantenimiento->conjunto_id],
                ['id_rol',2]
            ])->first();
            $correo = new CorreoController();
            $contenido = "<b>Tienes el siguinete mantenimineto programado</b><br>
                        {$mantenimiento->descripcion}";

            //para enviar desde el correo de la app
            $conjunto = new Conjunto();
            $conjunto->nombre = 'Gestión copropietario';
            $conjunto->correo = 'gestioncopropietario@gmail.com';
            $conjunto->password = Crypt::encrypt(env("MAIL_PASSWORD"));

            $correo->enviarEmail($conjunto,[$administrador],'Mantenimiento programado',$contenido);
        }

    }
}
