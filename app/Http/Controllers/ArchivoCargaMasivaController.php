<?php

namespace App\Http\Controllers;

use App\ArchivoCargaMasiva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Tipo_unidad;
use App\Conjunto;
use Illuminate\Support\Facades\Auth;


class ArchivoCargaMasivaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \App\Tipo  $tipo
     */

    public function index(Tipo_unidad $tipo)
    {
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();

        return view('admin.tipo_unidad.carga_masiva.index')
            ->with('tipo_unidad', $tipo)
            ->with('conjuntos', $conjuntos);
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
            $archivoCargaMasiva = new ArchivoCargaMasiva();
            $archivoCargaMasiva->nombre_archivo = $request->nombre_archivo;
            $archivoCargaMasiva->estado = 'subido';
            $archivoCargaMasiva->fila=0;
            $archivoCargaMasiva->procesados=0;
            $archivoCargaMasiva->fallos=0;
            $archivoCargaMasiva->tipo_unidad_id = $request->tipo_unidad;
            $archivoCargaMasiva->conjunto_id = session('conjunto');
            if ($request->file('archivo')) {
                $file = time() . '.' . $request->archivo->getClientOriginalExtension();
                $request->archivo->move(\public_path('archivos_masivos/'), $file);
                //ruta archivo
                $archivoCargaMasiva->ruta = $file;

                //dd($archivoCargaMasiva);

                $archivoCargaMasiva->save();
                return array('res' => 1, 'msg' => 'Archivo subido correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'Error, por favor selecciona un archivo');
            }
        } catch (\Trowable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar la mascota.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function show(ArchivoCargaMasiva $archivoCargaMasiva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function edit(ArchivoCargaMasiva $archivoCargaMasiva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArchivoCargaMasiva $archivoCargaMasiva)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArchivoCargaMasiva $archivoCargaMasiva)
    {
        //dd($archivoCargaMasiva);        
        try {            
            if ($archivoCargaMasiva->ruta != '') {
                // Elimina el archivo
                @unlink(public_path('archivos_masivos/' . $archivoCargaMasiva->ruta));
            }
            $archivoCargaMasiva->delete();
            return ['res' => 1, 'msg' => 'Registro eliminado correctamente'];
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al tratar de eliminar el registro'];
        }
    }

    // para listar por datatables
    // ****************************
    public function datatables($tipo)
    {
        switch (Auth::user()->id_rol) {
            case 2:
                $archivos = ArchivoCargaMasiva::where([
                    ['tipo_unidad_id', $tipo],
                    ['conjunto_id', session('conjunto')]
                ])->get();

                return Datatables::of($archivos)
                    ->addColumn('nombre', function ($archivo) {
                        return $archivo->nombre_archivo;
                    })->addColumn('ultimoRegistro', function ($archivo) {
                        return $archivo->fila;
                    })->addColumn('fallos', function ($archivo) {
                        return $archivo->fallos;
                    })->addColumn('procesados', function ($archivo) {
                        return $archivo->procesados;
                    })->addColumn('estado', function ($archivo) {
                        return $archivo->estado;
                    })->addColumn('action', function ($archivo) {
                        return '<a data-toggle="tooltip" data-placement="top"
                                    title="Procesar el archivo" class="btn btn-default"
                                    onclick="loadData(' . $archivo->id . ')">
                                    <i class="fa fa-play"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top"
                                    title="Ver resultados del proceso" href="" class="btn btn-default">
                                    <i class="fa fa-list-ol"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top"
                                    title="Eliminar este archivo" class="btn btn-default" onclick="deleteData(' . $archivo->id . ')">
                                    <i class="fa fa-trash"></i>
                                </a>
                                ';
                    })->make(true);

            default:
                return [];
        }
    }
}
