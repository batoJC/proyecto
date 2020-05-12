<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
	protected $table = 'mascotas';

    //******************************

    public function user(){
    	return $this->belongsTo('App\User', 'id_dueno');
    }

    public function unidad(){
    	return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function tipo(){
    	return $this->belongsTo('App\TipoMascotas', 'tipo_id');
    }
}
