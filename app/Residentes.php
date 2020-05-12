<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Residentes extends Model
{
    //******************************
    protected $table = 'residentes';

    public function unidad(){
    	return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function tipo_documento(){
    	return $this->belongsTo('App\Tipo_Documento', 'tipo_documento_id');
    }

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'id_conjunto');
    }
}
