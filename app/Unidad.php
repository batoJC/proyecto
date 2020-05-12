<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    //
    public function propietarios()
    {
        return $this->belongsToMany(User::class, 'unidads_users', 'unidad_id', 'user_id')->withPivot('fecha_ingreso', 'fecha_retiro', 'estado');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function unidades()
    {
        return $this->hasMany('App\Unidad', 'unidad_id', 'id');
    }

    public function residentes()
    {
        return $this->hasMany('App\Residentes', 'unidad_id', 'id');
    }

    public function mascotas()
    {
        return $this->hasMany('App\Mascota', 'unidad_id', 'id');
    }

    public function vehiculos()
    {
        return $this->hasMany('App\Vehiculo', 'unidad_id', 'id');
    }

    public function visitantes()
    {
        return $this->hasMany('App\Visitante', 'unidad_id', 'id');
    }

    public function empleados()
    {
        return $this->hasMany('App\Empleado', 'unidad_id', 'id');
    }

    public function novedades()
    {
        return $this->hasMany('App\Novedad', 'unidad_id', 'id');
    }

    public function tipo()
    {
        return $this->hasOne(Tipo_unidad::class, 'id', 'tipo_unidad_id');
    }

    public function cuotasExtraordinarias()
    {
        return $this->belongsToMany(Cuota_extOrd::class, 'extraordinaria_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor');
    }

    public function cuotasAdministracion()
    {
        return $this->belongsToMany(Cuota_admon::class, 'administracion_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor');
    }

    public function otrosCobros()
    {
        return $this->hasMany(Otros_cobros::class, 'unidad_id', 'id');
    }

    public function cuotas($fecha = null)
    {
        //buscar las cuotas sin pagar
        $salida = array();

        $saldos = null;
        $administracion = null;
        $extraordinarias = null;
        $otros = null;
        if (!$fecha) {
            $fecha = date('Y-m-d');
            //saldos iniciales
            $saldos = $this->hasMany(saldoInicial::class, 'unidad_id')->where('estado', 'No pago')->where('vigencia_inicio', '<=', $fecha)->get();
            //cuotas administracion
            $administracion = $this->belongsToMany(Cuota_admon::class, 'administracion_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor')->wherePivot('estado', '=', 'No pago')->where('vigencia_inicio', '<=', $fecha)->get();
            //cuotas extraordinarias
            $extraordinarias = $this->belongsToMany('App\Cuota_extOrd', 'extraordinaria_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor')->wherePivot('estado', '=', 'No pago')->where('vigencia_inicio', '<=', $fecha)->get();
            //otros cobros
            $otros = $this->hasMany(Otros_cobros::class, 'unidad_id', 'id')->where([
                ['vigencia_inicio', '<=', $fecha],
                ['estado', 'No pago']
            ])->get();
        } else {
            $saldos = $this->hasMany(saldoInicial::class, 'unidad_id')->where('estado','!=', 'Pronto pago')->where('vigencia_inicio', '<=', $fecha)->get();
            //cuotas administracion
            $administracion = $this->belongsToMany(Cuota_admon::class, 'administracion_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor')->wherePivot('estado', '!=', 'Pronto pago')->where('vigencia_inicio', '<=', $fecha)->get();
            // dd($administracion);
            //cuotas extraordinarias
            $extraordinarias = $this->belongsToMany('App\Cuota_extOrd', 'extraordinaria_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor')->wherePivot('estado', '!=', 'Pronto pago')->where('vigencia_inicio', '<=', $fecha)->get();
            //otros cobros
            $otros = $this->hasMany(Otros_cobros::class, 'unidad_id', 'id')->where([
                ['vigencia_inicio', '<=', $fecha],
                ['estado','!=','Pronto pago']
            ])->get();
        }



        foreach ($saldos as $saldo) {

            $interes = $saldo->calcularInteres($fecha);
            $valor = $saldo->calcularValor($fecha);

            if (($interes + $valor) > 0) {
                $salida[] = array(
                    'vigencia_inicio' => $saldo->vigencia_inicio,
                    'vigencia_fin' => $saldo->vigencia_fin,
                    'referencia' => $this->referencia,
                    'concepto' => mb_strtoupper($saldo->concepto . ' ' . $this->tipo->nombre . ' ' . $this->numero_letra),
                    'valor' => $valor,
                    'interes' => $interes,
                    'tipo' => 'saldo_inicial',
                    'cuota_id' =>  $saldo->id,
                    'unidad_id' => $this->id,
                );
            }
        }


        $mes = array(
            'Jan' => 'ENERO',
            'Feb' => 'FEBRERO',
            'Mar' => 'MARZO',
            'Apr' => 'ABRIL',
            'May' => 'MAYO',
            'Jun' => 'JUNIO',
            'Jul' => 'JULIO',
            'Aug' => 'AGOSTO',
            'Sep' => 'SEPTIEMBRE',
            'Oct' => 'OCTUBRE',
            'Nov' => 'NOVIEMBRE',
            'Dec' => 'DICIEMBRE',
        );

        foreach ($administracion as $cuota) {

            $interes = $cuota->calcularInteres($fecha);
            $valor = $cuota->calcularValor($fecha);

            if (($interes + $valor) > 0) {
                $salida[] = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => $cuota->vigencia_fin,
                    'referencia' => $this->referencia,
                    'concepto' => mb_strtoupper('Cuota Administración ' . $this->tipo->nombre . ' ' . $this->numero_letra . ' ' . $mes[date('M', strtotime($cuota->vigencia_inicio))] . ' ' . date('Y', strtotime($cuota->vigencia_inicio))),
                    'valor' => $valor,
                    'interes' => $interes,
                    'tipo' => 'administracion',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => $this->id,
                );
            }
        }



        foreach ($extraordinarias as $cuota) {

            $interes = $cuota->calcularInteres($fecha);
            $valor = $cuota->calcularValor($fecha);

            if (($interes + $valor) > 0) {
                $salida[] = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => $cuota->vigencia_fin,
                    'referencia' => $this->referencia,
                    'concepto' => mb_strtoupper($cuota->concepto . ' ' . $this->tipo->nombre . ' ' . $this->numero_letra),
                    'valor' => $valor,
                    'interes' => $interes,
                    'tipo' => 'extraordinaria',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => $this->id,
                );
            }
        }


        foreach ($otros as $cuota) {

            $interes = $cuota->calcularInteres($fecha);
            $valor = $cuota->calcularValor($fecha);

            if (($interes + $valor) > 0) {
                $salida[] = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => $cuota->vigencia_fin,
                    'referencia' => $this->referencia,
                    'concepto' => mb_strtoupper($cuota->concepto . ' ' . $this->tipo->nombre . ' ' . $this->numero_letra),
                    'valor' => $valor,
                    'interes' => $interes,
                    'tipo' => 'otro_cobro',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => $this->id,
                );
            }
        }
        return $salida;
    }


    public function interes($fecha = null)
    {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }

        $totalIntereses = 0;

        //cuotas administracion
        $administracion = $this->belongsToMany(Cuota_admon::class, 'administracion_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor')->wherePivot('estado', '=', 'No pago')->where('vigencia_inicio', '<=', $fecha)->get();

        // dd($administracion);

        foreach ($administracion as $cuota) {
            $totalIntereses += $cuota->calcularInteres();
        }

        // dd($totalIntereses);

        //cuotas extraordinarias
        $extraordinarias = $this->belongsToMany('App\Cuota_extOrd', 'extraordinaria_unidades', 'unidad_id', 'cuota_id')->withPivot('estado', 'valor')->wherePivot('estado', '=', 'No pago')->where('vigencia_inicio', '<=', $fecha)->get();
        foreach ($extraordinarias as $cuota) {
            $totalIntereses += $cuota->calcularInteres();
        }

        //otros cobros
        $otros = $this->hasMany(Otros_cobros::class, 'unidad_id', 'id')->where([
            ['vigencia_inicio', '<=', $fecha],
            ['estado', 'No pago']
        ])->get();
        foreach ($otros as $cuota) {
            $totalIntereses += $cuota->calcularInteres();
        }


        return $totalIntereses;
    }
}
