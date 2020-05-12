<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cuota_admon extends Model
{
    // ************************************************
    protected $table = 'cuota_admon';

    // public function acta(){
    // 	return $this->belongsTo('App\Acta', 'acta_id');
    // }

    public function conjunto()
    {
        return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }

    public function unidades()
    {
        return $this->belongsToMany(Unidad::class, 'administracion_unidades', 'cuota_id', 'unidad_id')->withPivot('estado', 'valor');
    }

    public function calcularInteres($fecha = null)
    {


        $calculoInteres = 0;

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
                            ["tipo_de_cuota", 'Interes Cuota Administrativa'],
                            ['movimiento', $this->id],
                            ['unidad_id', $this->pivot->unidad_id],
                            ['fecha', '<=', $fecha]
                        ])->sum(DB::raw("valor"));
                } else {
                    //suma de cartera de los interes pagados a esta cuenta
                    $interesesPagados = DB::table("carteras")
                        ->where([
                            ["tipo_de_cuota", 'Interes Cuota Administrativa'],
                            ['movimiento', $this->id],
                            ['unidad_id', $this->pivot->unidad_id],
                            ['fecha', '<=', $fecha]
                        ])->sum(DB::raw("valor"));
                }
                $fechaHoy = date_create($fecha);
                $costo = $this->pivot->valor;

                $pagoSaldo = Cartera::where([
                    ["tipo_de_cuota", 'Cuota Administrativa'],
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
                        // dd([$interes,$interesesPagados]);
                        $costo -= $pago->valor;
                    }
                    $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
                    $interes += ($dias * $datos->tasa_diaria) * $costo;
                }

                $calculoInteres = round(($interes - $interesesPagados) / 100) * 100;
            }
        }
        // dd($calculoInteres);
        if ($calculoInteres <= 0 && $this->calcularValor($fecha) <= 0) {
            $this->pivot->estado = 'Pago';
            $this->pivot->save();
        }

        return $calculoInteres;
    }

    public function calcularValor($fecha = null)
    {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }
        //suma de el valor
        $valorPagado = DB::table("carteras")
            ->where([
                ["tipo_de_cuota", 'Cuota Administrativa'],
                ['unidad_id', $this->pivot->unidad_id],
                ['movimiento', $this->id],
                ['fecha', '<=', $fecha]
            ])->sum(DB::raw("valor"));

        // dd($fecha);

        return $this->pivot->valor - $valorPagado;
    }
}
