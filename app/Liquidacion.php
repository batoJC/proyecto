<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    //
    protected $table = 'liquidaciones';

    public function empleado(){
        return $this->hasOne(EmpleadosConjunto::class,'id','empleado_conjunto_id');
    }

    public function deducciones()
    {
        return $this->hasMany(Deduccion::class, 'liquidacion_id');
    }

    public function devengos()
    {
        return $this->hasMany(Devengo::class, 'liquidacion_id');
    }

    public function total_devengos()
    {
        $valor = DB::table("devengos")
            ->where([
                ["liquidacion_id", $this->id],
            ])->sum(DB::raw("valor"));
        $transporte = $this->dias_transporte*$this->subsidio_transporte/30;

        return $valor + $transporte;
    }


    public function total_deducciones()
    {
        $valor = DB::table("deduccions")
            ->where([
                ["liquidacion_id", $this->id],
            ])->sum(DB::raw("valor"));
        return $valor;
    }

    public function total(){
        return $this->total_devengos()-$this->total_deducciones();
    }

}
