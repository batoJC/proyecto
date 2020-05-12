<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo_unidad extends Model
{
    // ************************************************
    protected $table = 'tipo_unidad';

    public function atributos(){
    	return $this->hasMany('App\AtributosTipoUnidad', 'tipo_unidad_id');
    }

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }
}
