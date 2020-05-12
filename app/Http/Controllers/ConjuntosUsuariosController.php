<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\User;
use App\Conjunto;
use App\ConjuntosUsuarios;
use Illuminate\Http\Request;

class ConjuntosUsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conjuntos_usuarios = ConjuntosUsuarios::all();
        $conjuntos          = Conjunto::all();
        $users              = User::where('estado', 'Activo')
                              ->where('habeas_data', 'Acepto')
                              ->where('id_rol', 2)
                              ->where('id_conjunto', null)
                              ->get();

        return view('owner.conjuntosUsuarios.index')
               ->with('conjuntos_usuarios', $conjuntos_usuarios)
               ->with('conjuntos', $conjuntos)
               ->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('conjuntos_usuarios');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $conjuntos_usuarios_consult      = ConjuntosUsuarios::where('id_user', $request->id_user)
                                           ->where('id_conjunto', $request->id_conjunto)
                                           ->first();
        if($conjuntos_usuarios_consult != null){
            return 'Error';
        } else {
            $conjuntos_usuarios              = new ConjuntosUsuarios();
            $conjuntos_usuarios->id_user     = $request->id_user;
            $conjuntos_usuarios->id_conjunto = $request->id_conjunto;
            $conjuntos_usuarios->save();
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
        return redirect('conjuntos_usuarios');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect('conjuntos_usuarios');
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
        return redirect('conjuntos_usuarios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ConjuntosUsuarios::destroy($id);
    }

    // Metodo personalizado para mostrar todos los conjuntos de un user
    // ****************************************************************
    public function mostrar_todos($id){
        $conjuntos_usuarios  = DB::table('conjuntos')
                               ->join('conjuntos_a_usuarios', 'conjuntos_a_usuarios.id_conjunto', '=', 'conjuntos.id')
                               ->select('nombre')
                               ->where('id_user', $id)
                               ->get();

        return $conjuntos_usuarios;
    }
}
