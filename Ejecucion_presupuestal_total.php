<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ejecucion_presupuestal_total extends Model
{
    protected $table = 'ejecucion_presupuestal_total';

    //*****************************

    //retorna el valor total del presupuesto sumando 
    //todos los presupuestos individuales
    public function valor_total($id = null)
    {

        if($id){
            return DB::table("ejecucion_presupuestal_individual")
            ->where("id_ejecucion_pre_total", $this->id)
            ->where("id",'!=', $id)
            ->sum(DB::raw("total"));
        }else{
            return DB::table("ejecucion_presupuestal_individual")
                ->where("id_ejecucion_pre_total", $this->id)
                ->sum(DB::raw("total"));
        }

    }

    public function detalles()
    {
        return $this->hasMany(Ejecucion_presupuestal_individual::class,'id_ejecucion_pre_total');
    }

    //debe de calcular el total ejecutado
    //si es de ingreso de acuerdo a el total recaudado
    //y si es de egresos de acuerdo al total de comprobantes
    public function total_ejecutado()
    {
        $total = 0;
        foreach ($this->detalles as $detalle) {
            // dd($detalle);
            $total += $detalle->totalEjecuado();
        }
        return $total;
    }
}
