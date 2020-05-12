<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    //

    public function unidad(){
        return $this->belongsTo(Unidad::class,'unidad_id');
    }

}
