<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Empleado;
use App\Tipo_Documento;
use Yajra\Datatables\Datatables;
use App\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
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
        session(['section' => 'empleados']);

        $conjuntos       = Conjunto::find(session('conjunto'));

        $user = Auth::user();

        if ($user->id_rol == 2) {
            $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                ->where([
                    ['unidads.conjunto_id', session('conjunto')],
                    ['atributos_tipo_unidads.nombre', 'lista_empleados']
                ])
                ->select('unidads.*')
                ->get();
            $tipos_documentos = Tipo_Documento::get();

            return view('admin.empleados.index')
                ->with('unidades', $unidades)
                ->with('tipos_documentos', $tipos_documentos)
                ->with('conjuntos', $conjuntos);
        } else if ($user->id_rol == 4) {
            return view('celador.empleados')
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
        //
        try {
            $empleado                    = new Empleado();
            $empleado->nombre            = $request->nombre;
            $empleado->apellido          = $request->apellido;
            $empleado->genero            = $request->genero;
            $empleado->tipo_documento_id = $request->tipo_documento_id;
            $empleado->documento         = $request->documento;
            $empleado->fecha_ingreso     = date('Y-m-d');
            $empleado->unidad_id         = $request->unidad_id;
            $empleado->id_conjunto       = session('conjunto');
            $empleado->save();

            return array('res' => 1, 'msg' => 'Empleado agregado correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el empleado.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
        return $empleado;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empleado $empleado)
    {
        //
        $empleado->nombre            = $request->nombre;
        $empleado->apellido          = $request->apellido;
        $empleado->genero            = $request->genero;
        $empleado->tipo_documento_id = $request->tipo_documento_id;
        $empleado->documento         = $request->documento;
        $empleado->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empleado $empleado)
    {
        //
    }

    public function inactivar(Empleado $empleado)
    {
        $empleado->estado = 'Inactivo';
        $empleado->fecha_retiro = date('Y-m-d');
        $empleado->save();
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $empleados = Empleado::where('id_conjunto', session('conjunto'));
        $usuario = Auth::user();
        if ($usuario->id_rol == 2) {
            $empleados = $empleados->get();
            return Datatables::of($empleados)
                ->addColumn('unidad', function ($empleado) {
                    return $empleado->unidad->tipo->nombre . ' ' . $empleado->unidad->numero_letra;
                })->addColumn('nombre_completo', function ($empleado) {
                    return $empleado->nombre . ' ' . $empleado->apellido;
                })->addColumn('fecha_ingreso', function ($empleado) {
                    return date('d-m-Y', strtotime($empleado->fecha_ingreso));
                })->addColumn('fecha_retiro', function ($empleado) {
                    return ($empleado->fecha_retiro) ? date('d-m-Y', strtotime($empleado->fecha_retiro)) : 'No aplica';
                })->addColumn('action', function ($empleado) {
                    return '<a data-toggle="tooltip" data-placement="top" title="Información de la unidad" class="btn btn-default" 
                            onclick="loadData(' . $empleado->unidad_id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
                })->make(true);
        } else if ($usuario->id_rol == 4) {
            $empleados = $empleados->where('estado', 'Activo')->get();
            return Datatables::of($empleados)
                ->addColumn('unidad', function ($empleado) {
                    return $empleado->unidad->tipo->nombre . ' ' . $empleado->unidad->numero_letra;
                })->addColumn('nombre_completo', function ($empleado) {
                    return $empleado->nombre . ' ' . $empleado->apellido;
                })->addColumn('fecha_ingreso', function ($empleado) {
                    return date('d-m-Y', strtotime($empleado->fecha_ingreso));
                })->addColumn('action', function ($empleado) {
                    return '<a data-toggle="tooltip" data-placement="top" title="Información de la unidad" class="btn btn-default" 
                            onclick="loadData(' . $empleado->unidad_id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
                })->make(true);
        }
    }
}
