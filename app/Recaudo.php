<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recaudo extends Model
{
    //

    public function propietario(){
    	return $this->belongsTo(User::class,'propietario_id');
    }

    public function conjunto(){
    	return $this->belongsTo(Conjunto::class,'conjunto_id');
    }

    public function detalles(){
        return $this->hasMany(DetalleRecaudo::class,'recaudo_id');
    }

    public function cuentaCobro(){
        return $this->belongsTo(CuentaCobro::class,'cuenta_cobro_id');
    }

    public function reemplazo(){
        return $this->belongsTo(Recaudo::class,'reemplazo_recaudo_id');
    }
}
