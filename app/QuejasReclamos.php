<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuejasReclamos extends Model
{
    protected $table = 'quejas_reclamos';

    // **********************************

    public function user(){
    	return $this->belongsTo('App\User', 'id_user');
    }

    public function proveedor(){
    	return $this->belongsTo('App\Proveedor', 'proveedor_id');
    }
}
