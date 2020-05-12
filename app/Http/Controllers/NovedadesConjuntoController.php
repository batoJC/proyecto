<?php

namespace App\Http\Controllers;

use App\Conjunto;
use Auth;
use Yajra\Datatables\Datatables;
use App\NovedadesConjunto;
use Illuminate\Http\Request;

class NovedadesConjuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'novedades']);

        if(Auth::user()->id_rol == 2){
            $conjuntos = Conjunto::find(session('conjunto'));
            return view('admin.novedades.index')
            ->with('conjuntos',$conjuntos);
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
            $novedad = new NovedadesConjunto();
            $novedad->fecha = $request->fecha;
            $novedad->contenido = $request->contenido;
            $novedad->conjunto_id = session('conjunto');
            $novedad->save();
            if($novedad->save()){
                return array('res' => 1, 'msg' => 'Novedad registrada correctamente.');
            }else{
                return array('res' => 0, 'msg' => 'Ocurrió un error al registrar la novedad.');
            }
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar la novedad.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NovedadesConjunto  $novedadesConjunto
     * @return \Illuminate\Http\Response
     */
    public function show(NovedadesConjunto $novedadesConjunto)
    {
        //
        return $novedadesConjunto;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NovedadesConjunto  $novedadesConjunto
     * @return \Illuminate\Http\Response
     */
    public function edit(NovedadesConjunto $novedadesConjunto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NovedadesConjunto  $novedadesConjunto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NovedadesConjunto $novedadesConjunto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NovedadesConjunto  $novedadesConjunto
     * @return \Illuminate\Http\Response
     */
    public function destroy(NovedadesConjunto $novedadesConjunto)
    {
        if($novedadesConjunto->delete()){
            return array('res' => 1,'msg' => 'Novedad eliminada correctamente.');
        }else{
            return array('res' => 0,'msg' => 'Ocurrió un error al eliminar la novedad.');
        }
    }


    // para listar por datatables
    // ****************************
    public function datatables(){

        $novedades = NovedadesConjunto::where('conjunto_id',session('conjunto'))->get();

        return Datatables::of($novedades)
            ->addColumn('fecha',function($novedad){
                return date('d-m-Y',strtotime($novedad->fecha));
            })->addColumn('descripcion',function($novedad){
                return str_limit($novedad->contenido, 50);
            })->addColumn('action', function($novedad){
                return '<a data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-default" onclick="eliminar('.$novedad->id.')">
                            <i class="fa fa-trash"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Ver novedad" class="btn btn-default" onclick="ver('.$novedad->id.')">
                            <i class="fa fa-eye"></i>
                        </a>';
            })->make(true);
    }

}
