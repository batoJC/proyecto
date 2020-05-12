<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //
    public function tipoDocumento(){
        return $this->belongsTo('App\Tipo_Documento', 'tipo_documento');
    }
}
