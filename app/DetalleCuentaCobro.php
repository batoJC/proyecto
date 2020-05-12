<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCuentaCobro extends Model
{
    //
    public $fillable = ['vigencia_inicio','vigencia_fin','referencia','concepto','valor','interes','tipo','cuota_id','unidad_id','cobro_id'];

    public function unidad(){
		return $this->belongsTo('App\Unidad', 'unidad_id');
	}


}