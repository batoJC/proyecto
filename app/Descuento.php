<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    //
    public function conjunto(){
        return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }

    public function unidad(){
        return $this->belongsTo('App\Unidad', 'unidad_id');
    }

    public function propietario(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
