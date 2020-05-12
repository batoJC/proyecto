<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    //
    public function propietario(){
        return $this->hasOne(User::class,'id','propietario_id');
    }

    public function zona_comun(){
        return $this->hasOne(Zona_Comun::class,'id','zona_comun_id');
    }

}
