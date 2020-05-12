<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    public function unidad(){
        return $this->belongsTo(Unidad::class);
    }
}
