<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    public function conjunto(){
    	return $this->belongsTo(Conjunto::class, 'conjunto_id');
    }

}
