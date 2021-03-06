<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoCargaMasiva extends Model
{
  public function tipoUnidad()
  {
    return $this->hasOne(Tipo_unidad::class, 'id', 'tipo_unidad_id');
  }

  public function conjunto()
  {
    return $this->hasOne(Conjunto::class, 'id', 'conjunto_id');
  }

  public function usuario()
  {
    return $this->hasOne(User::class, 'id', 'usuario_id');
  }

  public function errores()
  {
    return $this->hasMany(RegistroFallosCargaUnidades::class, 'archivo_masivo_id', 'id');
  }
}
