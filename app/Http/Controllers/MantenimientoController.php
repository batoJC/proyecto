<?php

namespace App\Http\Controllers;

use App\Conjunto;
use Yajra\Datatables\Datatables;
use Auth;
use App\Mantenimiento;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'mantenimientos']);

        if (Auth::user()->id_rol == 2) {
            $conjuntos = Conjunto::find(session('conjunto'));
            return view('admin.mantenimientos.index')
                ->with('conjuntos', $conjuntos);
        }
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
        try {
            $mantenimiento                    = new Mantenimiento();
            $mantenimiento->fecha            = $request->fecha;
            $mantenimiento->descripcion          = $request->descripcion;
            $mantenimiento->conjunto_id       = session('conjunto');
            if ($mantenimiento->save()) {
                return array('res' => 1, 'msg' => 'Mantenimiento agregado correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el mantenimiento.');
            }
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el mantenimiento.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Mantenimiento $mantenimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Mantenimiento $mantenimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mantenimiento $mantenimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mantenimiento $mantenimiento)
    {
        if ($mantenimiento->delete()) {
            if ($mantenimiento->archivo) {
                @unlink(public_path('document/' . $mantenimiento->archivo));
            }
            return array('res' => 1, 'msg' => 'Mantenimiento eliminado correctamente.');
        } else {
            return array('res' => 0, 'msg' => 'Ocurrió un error al eliminar el mantenimiento.');
        }
    }

    /**
     * @param \App\Mantenimiento
     */
    public function realizado(Mantenimiento $mantenimiento, Request $request)
    {
        // return $mantenimiento;
        $mantenimiento->realizado = true;
        if ($request->hasFile('archivo')) {
            $file = time() . '.' . $request->archivo->getClientOriginalExtension();
            $request->archivo->move(public_path('document'), $file);
            $mantenimiento->archivo = $file;
        }
        if ($mantenimiento->save()) {
            return array('res' => 1, 'msg' => 'Mantenimiento actualizado correctamente.');
        } else {
            return array('res' => 0, 'msg' => 'Ocurrió un error al realizar la actualización del mantenimiento.');
        }
    }

    public function download(Mantenimiento $mantenimiento)
    {
        $pathtoFile = public_path() . '/document/' . $mantenimiento->archivo;
        return response()->download($pathtoFile);
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $mantenimientos = Mantenimiento::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($mantenimientos)
            ->addColumn('fecha', function ($mantenimiento) {
                return date('d-m-Y', strtotime($mantenimiento->fecha));
            })->addColumn('descripcion', function ($mantenimiento) {
                return str_limit($mantenimiento->descripcion, 50);
            })->addColumn('estado', function ($mantenimiento) {
                return ($mantenimiento->realizado) ? 'Realizado' : 'Por realizar';
            })->addColumn('action', function ($mantenimiento) {
                $salida = '<a data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-default" 
                                onclick="eliminar(' . $mantenimiento->id . ')">
                            <i class="fa fa-trash"></i>
                        </a>';
                if (!$mantenimiento->realizado) {
                    $salida .= '<a data-toggle="tooltip" data-placement="top" title="Marcar mantenimiento como realizado" class="btn btn-default" 
                                    onclick="realizado(' . $mantenimiento->id . ')">
                                <i class="fa fa-check"></i>
                            </a>';
                } else {
                    if ($mantenimiento->archivo) {
                        $salida .= '<a download data-toggle="tooltip" href="' . url('downloadMantenimiento', ['mantenimiento' => $mantenimiento->id]) . '" data-placement="top" title="Descargar archivo" class="btn btn-default">
                                            <i class="fa fa-download"></i>
                                        </a>';
                    }
                }
                return $salida;
            })->make(true);
    }
}
