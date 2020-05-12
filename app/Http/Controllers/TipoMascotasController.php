<?php

namespace App\Http\Controllers;

use App\TipoMascotas;
use Illuminate\Http\Request;

class TipoMascotasController extends Controller
{
    function __construct()
    {
        $this->middleware('admin',['only'=>['store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $tipo = new TipoMascotas();
            $tipo->tipo = strtoupper($request->tipo);
            $tipo->save();
            return $tipo;
        } catch (\Throwable $th) {
            return null;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoMascotas  $tipoMascotas
     * @return \Illuminate\Http\Response
     */
    public function show(TipoMascotas $tipoMascotas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoMascotas  $tipoMascotas
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoMascotas $tipoMascotas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoMascotas  $tipoMascotas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoMascotas $tipoMascotas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoMascotas  $tipoMascotas
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoMascotas $tipoMascotas)
    {
        //
    }
}
