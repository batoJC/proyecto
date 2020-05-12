<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlujoEfectivo extends Model
{
    //

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'conjunto_id');
    }

}
