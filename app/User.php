<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_completo', 'tipo_cedula', 'numero_cedula', 'email', 'password', 'edad', 'genero', 'tipo_cuenta', 'numero_cuenta', 'telefono', 'celular', 'estado', 'id_rol',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'numero_cuenta',
    ];

    // ************************************************
    protected $table = 'users';

    // Relaciones !!
    // *************
    public function rol()
    {
        return $this->belongsTo('App\Rol', 'id_rol');
    }

    public function conjunto()
    {
        return $this->belongsTo('App\Conjunto', 'id_conjunto');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo('App\Tipo_Documento', 'tipo_documento');
    }

    public function unidades()
    {
        return $this->belongsToMany(Unidad::class, 'unidads_users', 'user_id', 'unidad_id')->withPivot('estado');
    }

    public function cuentas($fecha = null)
    {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }

        $salida = array();
        //cuotas de las unidades
        $unidades = $this->belongsToMany(Unidad::class, 'unidads_users', 'user_id', 'unidad_id')->wherePivot('estado', '=', 'Activo')->withPivot('estado')->get();
        if ($unidades->count() > 0) {
            foreach ($unidades as $unidad) {
                $data = $unidad->cuotas($fecha);
                foreach ($data as $e) {
                    $salida[] = $e;
                }
            }
        }

        // dd($unidades);

        $multas = null;
        if ($fecha == date('Y-m-d')) {
            $multas = $this->hasMany(Multa::class, 'user_id', 'id')->where([
                ['vigencia_inicio', '<=', $fecha],
                ['estado', 'No pago']
            ])->get();
        } else {
            $multas = $this->hasMany(Multa::class, 'user_id', 'id')->where([
                ['vigencia_inicio', '<=', $fecha]
            ])->get();
        }


        foreach ($multas as $multa) {

            $interes = $multa->calcularInteres($fecha);
            $valor = $multa->calcularValor($fecha);

            if (($interes + $valor) > 0) {
                $salida[] = array(
                    'vigencia_inicio' => $multa->vigencia_inicio,
                    'vigencia_fin' => $multa->vigencia_fin,
                    'referencia' => null,
                    'concepto' => mb_strtoupper($multa->concepto),
                    'valor' => $valor,
                    'interes' => $interes,
                    'tipo' => 'multa',
                    'cuota_id' =>  $multa->id,
                    'unidad_id' => null,
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

        //cuotas de las unidades
        $unidades = $this->belongsToMany(Unidad::class, 'unidads_users', 'user_id', 'unidad_id')->wherePivot('estado', '=', 'Activo')->withPivot('estado')->get();


        if ($unidades->count() > 0) {
            foreach ($unidades as $unidad) {
                $totalIntereses += $unidad->interes($fecha);
            }
        }

        $multas = null;
        if ($fecha == date('Y-m-d')) {
            $multas = $this->hasMany(Multa::class, 'user_id', 'id')->where([
                ['vigencia_inicio', '<=', $fecha],
                ['estado', 'No pago']
            ])->get();
        } else {
            $multas = $this->hasMany(Multa::class, 'user_id', 'id')->where([
                ['vigencia_inicio', '<=', $fecha],
                ['estado', '!=', 'Pronto pago']
            ])->get();
        }

        foreach ($multas as $multa) {
            $totalIntereses += $multa->calcularInteres($fecha);
        }

        return $totalIntereses;
    }

    public function saldo($fecha = null)
    {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }

        $sumaRecaudo = DB::table("recaudos")
            ->where([
                ['propietario_id', $this->id],
                ['fecha', '<=', $fecha],
                ['anulada', false]
            ])->sum(DB::raw("valor"));
        $sumaCartera = DB::table("carteras")
            ->where([
                ['user_id', $this->id],
                ['fecha', '<=', $fecha]
            ])->sum(DB::raw("valor"));

        return $sumaRecaudo - $sumaCartera;
    }

    public function pqr(){
        return $this->hasMany(QuejasReclamos::class,'id_user');
    }
}
