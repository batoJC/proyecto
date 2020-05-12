<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encomienda extends Model
{
	protected $table = 'encomientas';

    //******************************

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'id_conjunto');
    }

    public function tipo_unidad(){
    	return $this->belongsTo('App\Tipo_unidad', 'id_tipo_unidad');
    }
}
