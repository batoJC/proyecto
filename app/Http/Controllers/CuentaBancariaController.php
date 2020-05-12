<?php

namespace App\Http\Controllers;

use App\CuentaBancaria;
use Illuminate\Http\Request;

class CuentaBancariaController extends Controller
{
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
        try {$cuenta = new CuentaBancaria();
            $cuenta->banco = $request->banco;
            $cuenta->nro_cuenta = $request->nro_cuenta;
            $cuenta->tipo = $request->tipo;
            $cuenta->conjunto_id = session('conjunto');
            if ($cuenta->save()) {
                return array('res' => 1, 'msg' => 'Cuenta bancaria guardada correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'No se logró guardar la cuenta bancaria.');
            }
        } catch (\Throwable $th) {
            return array('res' => 0,'msg' => 'Ocurrió un error al realizar la operación');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CuentaBancaria  $cuentaBancaria
     * @return \Illuminate\Http\Response
     */
    public function show(CuentaBancaria $cuentaBancaria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CuentaBancaria  $cuentaBancaria
     * @return \Illuminate\Http\Response
     */
    public function edit(CuentaBancaria $cuentaBancaria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CuentaBancaria  $cuentaBancaria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CuentaBancaria $cuentaBancaria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CuentaBancaria  $cuentaBancaria
     * @return \Illuminate\Http\Response
     */
    public function destroy($cuentaBancaria)
    {
        try {
            $cuentaBancaria = CuentaBancaria::find($cuentaBancaria);
            if ($cuentaBancaria->delete()) {
                return array('res' => 1, 'msg' => 'Cuenta bancaria eliminada correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'No se logró eliminar la cuenta bancaia.');
            }
        } catch (\Throwable $th) {
            return array('res' => 0,'msg' => 'Ocurrió un error al realizar la operación');
        }
    }
}
