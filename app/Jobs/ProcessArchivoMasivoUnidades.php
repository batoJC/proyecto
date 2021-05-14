<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ArchivoCargaMasiva;

class ProcessArchivoMasivoUnidades implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $archivoMasivo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ArchivoCargaMasiva $archivoMasivo)
    {
        $this->archivoMasivo = $archivoMasivo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ArchivoCargaMasiva $archivo)
    {
        // $archivo = ArchivoCargaMasiva::find(4);

        for ($i=0; $i < 2; $i++) {
            for ($j=0; $j < 2; $j++) {
                for ($k=0; $k < 10000; $k++) {
                    $this->archivoMasivo->procesados++;
                    $this->archivoMasivo->save();
                }
            }
        }
    }
}
