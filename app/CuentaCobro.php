<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CuentaCobro extends Model
{
    //

	public function propietario(){
		return $this->belongsTo('App\User', 'propietario_id');
	}

	public function conjunto(){
		return $this->belongsTo('App\Conjunto', 'conjunto_id');
	}
	
	public function detalles(){
		return $this->hasMany(DetalleCuentaCobro::class,'cobro_id');
	}

	public function reemplaza(){
		return $this->belongsTo(CuentaCobro::class, 'reemplazo_cuenta_id');
	}

	public function interes(){
		return DB::table("detalle_cuenta_cobros")
            ->where("cobro_id", $this->id)
            ->sum(DB::raw("interes"));
	}

	public function valor(){
		return DB::table("detalle_cuenta_cobros")
            ->where("cobro_id", $this->id)
            ->sum(DB::raw("valor"));
	}

	public function recaudo(){
		return $this->hasOne(Recaudo::class,'cuenta_cobro_id')->where('anulada',false);
	}

}
