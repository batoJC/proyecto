<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Unidad;
use App\Visitante;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitanteController extends Controller
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
        //
        session(['section' => 'visitantes']);

        $conjuntos       = Conjunto::find(session('conjunto'));

        $user = Auth::user();

        if ($user->id_rol == 2) {
            $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                ->where([
                    ['unidads.conjunto_id', session('conjunto')],
                    ['atributos_tipo_unidads.nombre', 'lista_visitantes']
                ])
                ->select('unidads.*')
                ->get();
            return view('admin.visitantes.index')
                ->with('unidades', $unidades)
                ->with('conjuntos', $conjuntos);
        } elseif ($user->id_rol == 4) {
            return view('celador.visitantes');
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
        //
        try {
            $visitante                 = new Visitante();
            $visitante->identificacion = $request->identificacion;
            $visitante->nombre         = $request->nombre;
            $visitante->parentesco     = $request->parentesco;
            $visitante->fecha_ingreso  = date('Y-m-d');
            $visitante->unidad_id      = $request->unidad_id;
            $visitante->id_conjunto    = session('conjunto');
            $visitante->save();
            return array('res' => 1, 'msg' => 'Visitante agregado correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el visitante.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Visitante  $visitante
     * @return \Illuminate\Http\Response
     */
    public function show(Visitante $visitante)
    {
        //
        return $visitante;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Visitante  $visitante
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitante $visitante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visitante  $visitante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitante $visitante)
    {
        //
        $visitante->identificacion = $request->identificacion;
        $visitante->nombre         = $request->nombre;
        $visitante->parentesco     = $request->parentesco;
        $visitante->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Visitante  $visitante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitante $visitante)
    {
        //
    }

    public function inactivar(Visitante $visitante)
    {
        $visitante->estado = 'Inactivo';
        $visitante->fecha_retiro = date('Y-m-d');
        $visitante->save();
    }



    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $visitantes = Visitante::where('id_conjunto', session('conjunto'));
        $usuario = Auth::user();

        if ($usuario->id_rol == 2) {
            $visitantes = $visitantes->get();
        } else if ($usuario->id_rol == 4) {
            $visitantes = $visitantes->where('estado', 'Activo')->get();
        }
        return Datatables::of($visitantes)
            ->addColumn('unidad', function ($visitante) {
                return $visitante->unidad->tipo->nombre . ' ' . $visitante->unidad->numero_letra;
            })->addColumn('action', function ($visitante) {
                return '<a data-toggle="tooltip" data-placement="top" title="Infromación de la unidad" class="btn btn-default" 
                            onclick="loadData(' . $visitante->unidad_id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
            })->make(true);
    }
}
