<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consecutivos extends Model
{
	protected $table = 'consecutivos';

    // *******************************

    public function conjunto(){
    	return $this->belongsTo('App\Conjunto', 'id_conjunto');
    }
}
