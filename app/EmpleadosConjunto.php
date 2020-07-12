<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpleadosConjunto extends Model
{
    //
    public function conjunto(){
        return $this->hasOne(Conjunto::class,'id','conjunto_id');
    }

}
