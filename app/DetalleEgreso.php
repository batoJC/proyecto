<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEgreso extends Model
{
    //
    public $fillable = ['codigo','concepto','valor','egreso_id','presupuesto_id'];
}
