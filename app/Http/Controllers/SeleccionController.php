<?php

namespace App\Http\Controllers;

use App\Conjunto;
use Illuminate\Http\Request;

class SeleccionController extends Controller
{  
    // Admin seleccionando
    // *******************
    public function seleccion($id){

    	$conjunto = Conjunto::find($id);

    	if($conjunto){
    		// Session para traer toda la info del conjunto
    		session(['conjunto' => $conjunto->id]);
            // Session para la barra lateral
            session(['section' => 'home']);
            return redirect('/admin');
        }
    }

    // User seleccionando
    // *******************

    public function seleccion_user($id){
        $conjunto = Conjunto::find($id);

        if($conjunto){
            // Session para traer toda la info del conjunto
            session(['conjunto_user' => $conjunto->id]);
    		// Session para la barra lateral
            session(['section' => 'home']);
            return redirect('/dueno');
        }
    }
}
