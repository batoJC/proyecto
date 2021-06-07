<?php

namespace App\Console\Commands;

use App\ArchivoCargaMasiva;
use Illuminate\Console\Command;

class EliminarArchivosAntiguos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archivos:eliminar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina archivos antiguos guardados en el servidor';

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
        //Eliminar archivos de la carga masiva que llevan más de un mes en el servidor
        $fechaActual = date("Y-m-d");
        $fechaLimite = date("Y-m-d", strtotime($fechaActual . "- 1 month"));
        $archivos = ArchivoCargaMasiva::where(
            [
                ["created_at", "<=", $fechaLimite],
            ]
        )->get();

        foreach ($archivos as $archivoCargaMasiva) {
            if ($archivoCargaMasiva->ruta != '') {
                // Elimina el archivo
                @unlink(public_path('archivos_masivos/' . $archivoCargaMasiva->ruta));
            }
            $archivoCargaMasiva->delete();
        }
    }
}
