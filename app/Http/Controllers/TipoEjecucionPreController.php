<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Yajra\Datatables\Datatables;
use App\Conjunto;
use App\Tipo_ejecucion_pre;
use Illuminate\Http\Request;

class TipoEjecucionPreController extends Controller
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
        session(['section' => 'tipo_ejecucion_pre']);


        if (Auth::user()->id_rol == 2) {
            $user         = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos    = Conjunto::find(session('conjunto'));
            return view('admin.tipo_ejecucion.index')
                ->with('user', $user)
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
        return redirect('tipo_ejecucion_pre');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo_ejecucion_pre              = new Tipo_ejecucion_pre();
        $tipo_ejecucion_pre->tipo        = mb_strtoupper($request->tipo, 'UTF-8');
        $tipo_ejecucion_pre->descripcion = $request->descripcion;
        $tipo_ejecucion_pre->conjunto_id = session('conjunto');
        $tipo_ejecucion_pre->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipo_ejecucion_pre  = Tipo_ejecucion_pre::find($id);
        return $tipo_ejecucion_pre;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo_ejecucion_pre  = Tipo_ejecucion_pre::find($id);
        return $tipo_ejecucion_pre;
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
        $tipo_ejecucion_pre              = Tipo_ejecucion_pre::find($id);
        $tipo_ejecucion_pre->tipo        = mb_strtoupper($request->tipo);
        $tipo_ejecucion_pre->descripcion = $request->descripcion;
        $tipo_ejecucion_pre->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tipo_ejecucion_pre::destroy($id);
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $tipo_ejecucion_pre = Tipo_ejecucion_pre::where('conjunto_id', session('conjunto'))->get();

        return Datatables::of($tipo_ejecucion_pre)
            ->addCOlumn('descripcion', function ($tipo) {
                return ($tipo->descripcion != null) ? str_limit($tipo->descripcion, 30) : 'No aplica';
            })->addColumn('action', function ($tipo) {
                return '<a data-toggle="tooltip" data-placement="top" 
                            title="Editar" onclick="editForm('.$tipo->id.')" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" 
                            title="Eliminar" onclick="deleteData('.$tipo->id.')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
