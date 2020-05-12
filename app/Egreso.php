<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Egreso extends Model
{
    public function conjunto(){
    	return $this->belongsTo(Conjunto::class,'conjunto_id');
    }


    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor', 'proveedor_id');
    }

    public function detalles()
    {
        return $this->hasMany('App\DetalleEgreso','egreso_id');
    }

    public function valorTotal(){
        // return 300000;
        return DB::table("detalle_egresos")
            ->where("egreso_id", $this->id)
            ->sum(DB::raw("valor"));
    }

}
