<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class saldoInicial extends Model
{
    //
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
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
                            ["tipo_de_cuota", 'Interes Saldo Inicial'],
                            ['movimiento', $this->id],
                            ['unidad_id', $this->unidad_id],
                            ['fecha', '<=', $fecha]
                        ])->sum(DB::raw("valor"));
                } else {
                    //suma de cartera de los interes pagados a esta cuenta
                    $interesesPagados = DB::table("carteras")
                        ->where([
                            ["tipo_de_cuota", 'Interes Saldo Inicial'],
                            ['movimiento', $this->id],
                            ['unidad_id', $this->unidad_id],
                            ['fecha', '<=', date("Y-m-d", strtotime($fecha . "- 0 days"))]
                        ])->sum(DB::raw("valor"));
                }
                $fechaHoy = date_create($fecha);
                $costo = $this->valor;


                //suma de cartera de los interes pagados a esta cuenta
                $interesesPagados = DB::table("carteras")
                    ->where([
                        ["tipo_de_cuota", 'Interes Saldo Inicial'],
                        ['movimiento', $this->id],
                        ['unidad_id', $this->unidad_id],
                        ['fecha', '<=', $fecha]
                    ])->sum(DB::raw("valor"));

                $pagoSaldo = Cartera::where([
                    ["tipo_de_cuota", 'Saldo Inicial'],
                    ['unidad_id', $this->unidad_id],
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
            $this->estado = 'Pago';
            $this->save();
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
                ["tipo_de_cuota", 'Saldo Inicial'],
                ['movimiento', $this->id],
                ['unidad_id', $this->unidad_id],
                ['fecha', '<=', $fecha]
            ])->sum(DB::raw("valor"));

        return $this->valor - $valorPagado;
    }
}
