<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CertificadoMora extends Model
{
    public function detalles(){
        return $this->hasMany(ConceptoMora::class,'certificado_id');
    }
}
