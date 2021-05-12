<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoCargaMasiva extends Model
{
  public function tipoUnidad()
  {
    return $this->hasOne(Tipo_unidad::class, 'id', 'tipo_unidad_id');
  }
}
