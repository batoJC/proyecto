<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConjuntosUsuarios extends Model
{
    // ************************************************
    protected $table = 'conjuntos_a_usuarios';

    public function usuario(){
    	return $this->belongsTo('App\User', 'id_user');
    }

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'id_conjunto');
    }
}
