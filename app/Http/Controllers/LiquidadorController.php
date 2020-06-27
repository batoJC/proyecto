<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\EmpleadosConjunto;
use App\Feriados;
use Illuminate\Http\Request;

class LiquidadorController extends Controller
{
    //

    public function index(EmpleadosConjunto $empleado){
        // Feriados::isFeriado(date('Y-m-d'));
        $conjunto = Conjunto::find(session('conjunto'));
        return view('admin.liquidador.index')->with('empleado',$empleado)->with('conjuntos',$conjunto);
    }




}
