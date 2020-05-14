<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Ejecucion_presupuestal_individual extends Model
{
    protected $table = 'ejecucion_presupuestal_individual';

    // ***********************************

    public function Tipo_ejecucion_pre()
    {
        return $this->belongsTo('App\Tipo_ejecucion_pre', 'id_tipo_ejecucion');
    }

    public function excluidas()
    {
        return $this->belongsToMany(Unidad::class, 'excluir_presupuesto', 'presupuesto_id', 'unidad_id');
    }

    public function ejecucion_presupuestal_total()
    {
        return $this->belongsTo('App\Ejecucion_presupuestal_total', 'id_ejecucion_pre_total');
    }

    public function porcentaje_ejecutado()
    {
        if ($this->total == 0) {
            return '$ ' . number_format($this->totalEjecuado());
        } else {
            return round(($this->totalEjecuado() * 100) / $this->total, 2) . ' %';
        }
    }

    public function totalEjecuado()
    {
        if ($this->ejecucion_presupuestal_total->tipo == 'egreso') {
            $total = Egreso::join('detalle_egresos', 'egresos.id', '=', 'detalle_egresos.egreso_id')
                ->where([
                    ['egresos.anulado', false],
                    ['detalle_egresos.presupuesto_id', $this->id]
                ])->sum(DB::raw("detalle_egresos.valor"));
            return $total;
        } else {
            $total = Cartera::where([
                ['presupuesto_individual_id', $this->id],
            ])->sum(DB::raw("carteras.valor"));
            return $total;
        }
    }

    public function porcentaje_total()
    {
        $total = ($this->ejecucion_presupuestal_total->valor_total() == 0) ? 1 : $this->ejecucion_presupuestal_total->valor_total();
        if ($total) {

            return round(($this->total * 100) / $total, 2);
        } else {
            return 0;
        }
    }
}
