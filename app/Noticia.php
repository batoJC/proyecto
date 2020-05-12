<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    // ************************************************
    protected $table = 'noticias';

    public function user(){
    	return $this->belongsTo('App\User','id_user');
    }

    public function evidencia(){
        return $this->hasOne(Evidencia::class,'noticia_id');
    }

}
