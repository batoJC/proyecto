<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    // ************************************************
    protected $table = 'divisiones';

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'id_conjunto');
    }

    public function tipo_division(){
    	return $this->belongsTo('App\TipoDivision', 'id_tipo_division');
    }

    
}
