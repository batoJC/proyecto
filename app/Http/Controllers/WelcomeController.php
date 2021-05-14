<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Tipo_unidad;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    // Método personalizado para el /welcome
    // *************************************
    //TODO: delete this when the landing are accepted
    public function welcome(){
        $conjunto = Conjunto::all();
        return view('welcome');
    }

    public function welcome2(){
        $conjunto = Conjunto::all();
        return view('welcome2');
    }


    // Select dependiente
    // ******************
    public function fetch(Request $request){
    	$conjunto = $request->get('id_conjunto');

      	$tipos_unidad = Tipo_unidad::where('id_conjunto', $conjunto)->get();
      	return response()->json($tipos_unidad);
    }
}
