<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carta extends Model
{
    //
    public function unidad()
    {
        return $this->belongsTo('App\Unidad', 'unidad_id');
    }

    public function conjunto()
    {
        return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }

    public function residentes_ingreso()
    {
        return $this->hasMany('App\Residentes', 'carta_ingreso_id', 'id');
    }

    public function residentes_retiro()
    {
        return $this->hasMany('App\Residentes', 'carta_retiro_id', 'id');
    }

    public function mascotas_ingreso()
    {
        return $this->hasMany('App\Mascota', 'carta_ingreso_id', 'id');
    }

    public function mascotas_retiro()
    {
        return $this->hasMany('App\Mascota', 'carta_retiro_id', 'id');
    }

    public function vehiculos_ingreso()
    {
        return $this->hasMany('App\Vehiculo', 'carta_ingreso_id', 'id');
    }

    public function vehiculos_retiro()
    {
        return $this->hasMany('App\Vehiculo', 'carta_retiro_id', 'id');
    }
}
