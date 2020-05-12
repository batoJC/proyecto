<?php

namespace App\Http\Controllers;

use App\TipoDivision;
use Illuminate\Http\Request;

class TipoDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // echo "hola";
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
        // echo "hola";
        $tipo = new TipoDivision();
        $tipo->division = mb_strtoupper($request->division, 'UTF-8');
        $tipo->save();
        return $tipo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoDivision  $tipoDivision
     * @return \Illuminate\Http\Response
     */
    public function show(TipoDivision $tipoDivision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoDivision  $tipoDivision
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoDivision $tipoDivision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoDivision  $tipoDivision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoDivision $tipoDivision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoDivision  $tipoDivision
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoDivision $tipoDivision)
    {
        //
    }
}
