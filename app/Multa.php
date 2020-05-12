<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Multa extends Model
{
    protected $table = 'multas';

    // *************************


    public function propietario()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // public function acta(){
    // 	return $this->belongsTo('App\Acta', 'acta_id');
    // }

    public function conjunto()
    {
        return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }

    public function calcularInteres($fecha=null)
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
                if(!$fecha){ //suma de cartera de los interes pagados a esta cuenta
                    $fecha = date('Y-m-d');
                $interesesPagados = DB::table("carteras")
                    ->where([
                        ["tipo_de_cuota", 'Interes Multa'],
                        ['movimiento', $this->id],
                        ['fecha', '<=', $fecha]
                    ])->sum(DB::raw("valor"));
                }else{ //suma de cartera de los interes pagados a esta cuenta
                $interesesPagados = DB::table("carteras")
                    ->where([
                        ["tipo_de_cuota", 'Interes Multa'],
                        ['movimiento', $this->id],
                        ['fecha', '<=', date("Y-m-d",strtotime($fecha."- 0 days"))]
                    ])->sum(DB::raw("valor"));
                }
                $fechaHoy = date_create($fecha);
                $costo = $this->valor;


               

                $pagoSaldo = Cartera::where([
                    ["tipo_de_cuota", 'Multa'],
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

                $calculoInteres = round(($interes - $interesesPagados) / 100) * 100;
            }
        }

        if ($calculoInteres <= 0 && $this->calcularValor() <= 0) {
            $this->estado = 'Pago';
            $this->save();
        }
        return $calculoInteres;
    }

    public function calcularValor($fecha=null)
    {
        if(!$fecha){
            $fecha = date('Y-m-d');
        }else{
            $fecha = date("Y-m-d",strtotime($fecha."- 0 days"));
        }
        //suma de el valor
        $valorPagado = DB::table("carteras")
            ->where([
                ["tipo_de_cuota", 'Multa'],
                ['movimiento', $this->id],
                ['fecha', '<=', $fecha]
            ])->sum(DB::raw("valor"));

        return $this->valor - $valorPagado;
    }
}
