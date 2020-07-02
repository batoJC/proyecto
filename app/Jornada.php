<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    //

    public function empleado(){
        return $this->hasOne(EmpleadosConjunto::class,'id','empleado_conjunto_id');
    }

}
