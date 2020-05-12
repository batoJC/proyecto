<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona_Comun extends Model
{
    // ************************************************
    protected $table = 'zonas_comunes';

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }

    public function reservas(){
        return $this->hasMany(Reserva::class,'zona_comun_id','id');
    }

}
