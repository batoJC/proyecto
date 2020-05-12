<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Cuota_extOrd extends Model
{
    // ************************************************
    protected $table = 'cuota_adm_ex_ord';

    public function conjunto()
    {
        return $this->belongsTo('App\Conjunto', 'id_conjunto');
    }

    public function unidades()
    {
        return $this->belongsToMany(Unidad::class, 'extraordinaria_unidades', 'cuota_id', 'unidad_id')->withPivot('estado', 'valor');
    }

    public function calcularInteres($fecha = null)
    {

        $calculoIntereses = 0;

        if ($this->interes) {
            //tabla de interes
            $datos = Tabla_intereses::where([
                ['fecha_vigencia_inicio', '<=', $this->vigencia_fin],
                ['fecha_vigencia_fin', '>=', $this->vigencia_fin]
            ])->first();

            if ($datos) {

                $interes = 0;
                $interesesPagados = 0;
                if (!$fecha) {
                    //suma de cartera de los interes pagados a esta cuenta
                    $fecha = date('Y-m-d');
                    $interesesPagados = DB::table("carteras")
                        ->where([
                            ["tipo_de_cuota", 'Interes Cuota Extraordinaria'],
                            ['movimiento', $this->id],
                            ['unidad_id', $this->pivot->unidad_id],
                            ['fecha', '<=', $fecha]
                        ])->sum(DB::raw("valor"));
                } else {
                    //suma de cartera de los interes pagados a esta cuenta
                    $interesesPagados = DB::table("carteras")
                        ->where([
                            ["tipo_de_cuota", 'Interes Cuota Extraordinaria'],
                            ['movimiento', $this->id],
                            ['unidad_id', $this->pivot->unidad_id],
                            ['fecha', '<=',  date("Y-m-d", strtotime($fecha . "- 0 days"))]
                        ])->sum(DB::raw("valor"));
                }
                $fechaHoy = date_create($fecha);
                $costo = $this->pivot->valor;


                //suma de cartera de los interes pagados a esta cuenta
                $interesesPagados = DB::table("carteras")
                    ->where([
                        ["tipo_de_cuota", 'Interes Cuota Extraordinaria'],
                        ['movimiento', $this->id],
                        ['unidad_id', $this->pivot->unidad_id],
                        ['fecha', '<=', $fecha]
                    ])->sum(DB::raw("valor"));

                $pagoSaldo = Cartera::where([
                    ["tipo_de_cuota", 'Cuota Extraordinaria'],
                    ['unidad_id', $this->pivot->unidad_id],
                    ['movimiento', $this->id],
                    ['fecha', '<=', $fecha]
                ])->get();

                //suma de los intereses que genera la cuenta
                $fechaAnterior = date_create($this->vigencia_fin);
                $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
                if ($dias > 0) {
                    foreach ($pagoSaldo as $pago) {
                        $dias = date_diff($fechaAnterior, date_create($pago->fecha))->format('%R%a');
                        $fechaAnterior = date_create($pago->fecha);
                        $interes += ($dias * $datos->tasa_diaria) * $costo;
                        $costo -= $pago->valor;
                    }
                    $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
                    $interes += ($dias * $datos->tasa_diaria) * $costo;
                }

                $calculoIntereses = round(($interes - $interesesPagados) / 100) * 100;
            }
        }

        if ($calculoIntereses <= 0 && $this->calcularValor($fecha) <= 0) {
            $this->pivot->estado = 'Pago';
            $this->pivot->save();
        }
        return $calculoIntereses;
    }

    public function calcularValor($fecha = null)
    {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        } else {
            $fecha = date("Y-m-d", strtotime($fecha . "- 0 days"));
        }
        //suma de el valor
        $valorPagado = DB::table("carteras")
            ->where([
                ["tipo_de_cuota", 'Cuota Extraordinaria'],
                ['movimiento', $this->id],
                ['unidad_id', $this->pivot->unidad_id],
                ['fecha', '<=', $fecha]
            ])->sum(DB::raw("valor"));

        return $this->pivot->valor - $valorPagado;
    }
}
