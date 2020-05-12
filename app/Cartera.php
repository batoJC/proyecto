<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Otros_cobros;
use App\Cuota_Ord;
use App\Cuota_extOrd;
use App\Multa;

class Cartera extends Model
{
    protected $table = 'carteras';

    //
    public function unidad(){
    	return $this->belongsTo(Unidad::class, 'unidad_id');
	}
	
	public function propietario(){
    	return $this->belongsTo('App\User', 'user_id');
	}
	
	public function usuario(){
    	return $this->belongsTo('App\User', 'user_register_id');
    }

}
