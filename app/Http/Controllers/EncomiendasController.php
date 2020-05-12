<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Encomienda;
use Illuminate\Http\Request;

class EncomiendasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->id_rol == 3){
            session(['section' => 'encomientas']);
            // Cuando esta nulo el id_conjunto
            // -------------------------------
            if(Auth::user()->id_conjunto != null){
                // Validación para identificar el admin del conjunto
                $admin       = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
            } elseif(session('conjunto_user') != null){
                // Validación para identificar el admin del conjunto
                $admin       = User::where('id_rol', 2)->where('id_conjunto', session('conjunto_user'))->first();
            }
            
            $encomiendas = Encomienda::all();
            return view('dueno.encomiendas.index')
                   ->with('admin', $admin)
                   ->with('encomiendas', $encomiendas);
                   
        // ********************************************
        } else {
            return view('errors.404');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/encomientas');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validación para que solo los no logueados
        // *****************************************
        if(!isset(Auth::user()->id)){
            $encomienda                 = new Encomienda();
            $encomienda->titulo         = $request->titulo;
            $encomienda->descripcion    = $request->descripcion;
            $encomienda->id_conjunto    = $request->id_conjunto;
            $encomienda->id_tipo_unidad = $request->id_tipo_unidad;
            $encomienda->save();
        } else {
            return 'logueado';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/encomientas');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect('/encomientas');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return redirect('/encomientas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('/encomientas');
    }
}
