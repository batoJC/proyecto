<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Yajra\Datatables\Datatables;
class Conjunto extends Model
{
  // ************************************************
  protected $table = 'conjuntos';

  public function tipo_conjunto()
  {
    return $this->belongsTo('App\Tipo_Conjunto', 'id_tipo_propiedad');
  }

  public function procesos()
  {
    return $this->hasMany('App\Tipo_unidad');
  }

  public function cuentas(){
    return $this->hasMany(CuentaBancaria::class,'conjunto_id');
  }


  public function reglamento(){
    return $this->hasOne(Reglamento::class,'conjunto_id');
  }

}
