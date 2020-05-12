<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    //
    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }
}
