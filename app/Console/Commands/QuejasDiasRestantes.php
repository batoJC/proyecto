<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class QuejasDiasRestantes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quejas:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Descuenta los días restantes de las quejas';

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
        $dia = $this->saber_dia(date('Y-m-d'));

        if ($dia != 'Domingo' && $dia != 'Sabado') {

            DB::table('quejas_reclamos')->where([
                ['estado','Pendiente'],
                ['dias_restantes','>',0]
            ])->orWhere([
                ['estado','En proceso'],
                ['dias_restantes','>',0]
            ])->decrement('dias_restantes');
        }
    }

    private function saber_dia($nombredia)
    {
        $dias = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
        $fecha = $dias[date('N', strtotime($nombredia)) - 1];
        return $fecha;
    }
}
