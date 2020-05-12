<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use App\Conjunto;
use App\Evidencia;
use App\Noticia;
use Auth;
use Illuminate\Http\Request;

class EvidenciaController extends Controller
{

    function __construct()
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'evidencias']);

        switch (Auth::user()->id_rol) {
            case 2:
                $conjuntos = Conjunto::find(session('conjunto'));
                $noticias = Noticia::where('id_conjunto',session('conjunto'))->get();
                return view('admin.evidencias.index')
                    ->with('noticias', $noticias)
                    ->with('conjuntos', $conjuntos);
                break;
            case 3:
                return view('dueno.evidencias.index');
            case 4:
                return view('celador.evidencias.index');

            default:
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
            $evidencia = new Evidencia();
            $evidencia->fecha = $request->fecha;
            $evidencia->noticia_id = $request->noticia;
            $evidencia->contenido = $request->contenido;
            $evidencia->conjunto_id = session('conjunto');
            $files = '';
            $i = 0;
            if ($request->hasFile('fotos')) {
                foreach ($request->fotos as $foto) {
                    $file = time() . $i . '.' . $foto->getClientOriginalExtension();
                    $foto->move(public_path('imgs/private_imgs'), $file);
                    $files .= $file . ';';
                    $i++;
                }
            }
            $evidencia->fotos = trim($files, ';');
            if ($evidencia->save()) {
                return array('res' => 1, 'msg' => 'evidencia registrada correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'Ocurrió un error al registrar la evidencia.');
            }
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar la evidencia.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function show(Evidencia $evidencia)
    {
        return $evidencia;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Evidencia $evidencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evidencia $evidencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evidencia  $evidencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evidencia $evidencia)
    {
        if ($evidencia->delete()) {
            $fotos = explode(';', $evidencia->fotos);
            foreach ($fotos as $foto) {
                @unlink(public_path('imgs/private_imgs') . '/' . $foto);
            }
            return array('res' => 1, 'msg' => 'Evidencia eliminada correctamente.');
        } else {
            return array('res' => 0, 'msg' => 'Ocurrió un error al eliminar la evidencia.');
        }
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $evidencias = Evidencia::where('conjunto_id', session('conjunto'))->get();

        switch (Auth::user()->id_rol) {
            case 2:
                return Datatables::of($evidencias)
                    ->addColumn('fecha', function ($evidencia) {
                        return date('d-m-Y', strtotime($evidencia->fecha));
                    })->addColumn('descripcion', function ($evidencia) {
                        return str_limit($evidencia->contenido, 50);
                    })->addColumn('action', function ($evidencia) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-default" onclick="eliminar(' . $evidencia->id . ')">
                            <i class="fa fa-trash"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Ver evidencia" class="btn btn-default" onclick="ver(' . $evidencia->id . ')">
                            <i class="fa fa-eye"></i>
                        </a>';
                    })->make(true);
                break;
            case 3:
            case 4:
                return Datatables::of($evidencias)
                    ->addColumn('fecha', function ($evidencia) {
                        return date('d-m-Y', strtotime($evidencia->fecha));
                    })->addColumn('descripcion', function ($evidencia) {
                        return str_limit($evidencia->contenido, 50);
                    })->addColumn('action', function ($evidencia) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Ver evidencia" class="btn btn-default" onclick="ver(' . $evidencia->id . ')">
                            <i class="fa fa-eye"></i>
                        </a>';
                    })->make(true);
                break;

            default:
                return [];
        }
    }
}
