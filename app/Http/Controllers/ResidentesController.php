<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Conjunto;
use App\Residentes;
use App\Tipo_Documento;
use Yajra\Datatables\Datatables;
use App\Tipo_unidad;
use App\Unidad;
use Illuminate\Http\Request;
use PDF;

class ResidentesController extends Controller
{
    function __construct()
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     *return \Illuminate\Http\Response
     */
    public function index()
    {


        session(['section' => 'residentes']);

        $user = User::where('id_conjunto', session('conjunto'))->get();
        $conjuntos = Conjunto::find(session('conjunto'));
        $tipos_documentos = Tipo_Documento::get();
        $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
            ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
            ->where([
                ['unidads.conjunto_id', session('conjunto')],
                ['atributos_tipo_unidads.nombre', 'lista_residentes']
            ])
            ->select('unidads.*')
            ->get();

        $user = Auth::user();

        if ($user->id_rol == 2) {

            return view('admin.residentes.index')
                ->with('user', $user)
                ->with('unidades', $unidades)
                ->with('tipos_documentos', $tipos_documentos)
                ->with('conjuntos', $conjuntos);
        } elseif ($user->id_rol == 3) {

            if ($user->id_conjunto != null) {
                // Validación para identificar el admin del conjunto
                $admin        = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
                $tipo_unidad  = Tipo_unidad::where('id_conjunto', Auth::user()->id_conjunto)
                    ->where('id_dueno_apto', Auth::user()->id)
                    ->first();

                // Validación si se ha creado el apto del cliente
                if ($tipo_unidad) {
                    $residentes   = Residentes::where('id_tipo_unidad', $tipo_unidad->id)->get();
                    return view('dueno.residentes.index')
                        ->with('admin', $admin)
                        ->with('tipo_unidad', $tipo_unidad)
                        ->with('residentes', $residentes);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
                // ****************************************
            } elseif (session('conjunto_user') != null) {
                // Cuando esta nulo el id_conjunto
                // -------------------------------
                // Validación para identificar el admin del conjunto
                $admin       = User::where('id_rol', 2)->where('id_conjunto', session('conjunto_user'))->first();
                $tipo_unidad = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)
                    ->where('id_conjunto', session('conjunto_user'))
                    ->first();

                // Validación si se ha creado el apto del cliente
                if ($tipo_unidad) {
                    $residentes   = Residentes::where('id_tipo_unidad', $tipo_unidad->id)->get();
                    return view('dueno.residentes.index')
                        ->with('admin', $admin)
                        ->with('tipo_unidad', $tipo_unidad)
                        ->with('residentes', $residentes);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
            }

            // ****************************************
        } elseif ($user->id_rol == 4) {
            return view('celador.residentes')
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
        return redirect('/residentes');
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
            $residentes = new Residentes();
            $residentes->tipo_residente = $request->tipo_residente;
            $residentes->nombre = $request->nombre;
            $residentes->apellido = $request->apellido;
            $residentes->ocupacion = $request->ocupacion;
            $residentes->profesion = $request->profesion;
            $residentes->direccion = $request->direccion;
            $residentes->email = $request->email;
            $residentes->fecha_nacimiento = $request->fecha_nacimiento;
            $residentes->genero = $request->genero;
            $residentes->tipo_documento_id = $request->tipo_documento_id;
            $residentes->documento = $request->documento;
            $residentes->carta_ingreso_id = $request->carta_ingreso_id;
            $residentes->fecha_ingreso = ($request->fecha_ingreso) ? $request->fecha_ingreso : date('Y-m-d');
            $residentes->unidad_id = $request->unidad_id;

            // session('conjunto') inicializada solo por el admin del conjunto
            // ***************************************************************
            if (session('conjunto') != null) {
                $residentes->id_conjunto    = session('conjunto');
            } else {
                // Validación por si el user pertenece a varios conjuntos
                // ******************************************************
                if (Auth::user()->id_conjunto != null) {
                    $residentes->id_conjunto    = Auth::user()->id_conjunto;
                } else {
                    $residentes->id_conjunto = session('conjunto_user');
                }
            }
            // ******************************************************
            $residentes->save();

            return array('res' => 1, "msg" => "Residente agregado correctamente.");
        } catch (\Throwable $th) {
            return array('res' => 0, "msg" => "Ocurrió un error al guardar en el servidor.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $residentes = Residentes::find($id);
        return $residentes;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $residentes = Residentes::find($id);
        return $residentes;
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
        $residentes                 = Residentes::find($id);
        $residentes->tipo_residente = $request->tipo_residente;
        $residentes->nombre = $request->nombre;
        $residentes->apellido = $request->apellido;
        $residentes->ocupacion = $request->ocupacion;
        $residentes->profesion = $request->profesion;
        $residentes->direccion = $request->direccion;
        $residentes->email = $request->email;
        $residentes->fecha_nacimiento = $request->fecha_nacimiento;
        $residentes->genero = $request->genero;
        $residentes->tipo_documento_id = $request->tipo_documento_id;
        $residentes->documento = $request->documento;
        $residentes->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Residentes::destroy($id);
    }

    public function inactivar(Residentes $residente, Request $request)
    {
        $residente->estado = 'Inactivo';
        $residente->carta_retiro_id = $request->carta_retiro_id;
        $residente->fecha_salida = date('Y-m-d');
        $residente->save();
    }

    public function download(Request $request)
    {

        $fecha_inicio = date("Y-m-d", strtotime(date('Y-m-d') . "- " . ($request->edad_fin) . " year"));
        $fecha_fin = date("Y-m-d", strtotime(date('Y-m-d') . "- " . ($request->edad_inicio) . " year"));

        $residentes = Residentes::where([
            ['fecha_nacimiento', '>=', $fecha_inicio],
            ['fecha_nacimiento', '<=', $fecha_fin],
            ['estado', 'Activo'],
            ['id_conjunto', session('conjunto')]
        ])->get();

        $pdf = null;
        $pdf = PDF::loadView('admin.residentes.lista', [
            'edad_inicio' => $request->edad_inicio,
            'edad_fin' => $request->edad_fin,
            'residentes' => $residentes
        ])->setPaper('letter', 'landscape');
        return $pdf->stream();
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $residentes = Residentes::where('id_conjunto', session('conjunto'));
        $usuario = Auth::user();
        if ($usuario->id_rol == 2) {
            $residentes = $residentes->get();

            return Datatables::of($residentes)
                ->addColumn('tipo_documento', function ($residente) {
                    return $residente->tipo_documento->tipo;
                })->addColumn('unidad', function ($residente) {
                    return $residente->unidad->tipo->nombre . ' ' . $residente->unidad->numero_letra;
                })->addColumn('fecha_ingreso', function ($residente) {
                    return date('d-m-Y', strtotime($residente->fecha_ingreso));
                })->addColumn('fecha_salida', function ($residente) {
                    if ($residente->fecha_salida != null) {
                        return date('d-m-Y', strtotime($residente->fecha_salida));
                    } else {
                        return 'No Aplica';
                    }
                })->addColumn('action', function ($residente) {
                    return '<a data-toggle="tooltip" data-placement="top" title="Información de la unidad" class="btn btn-default" onclick="loadData(' . $residente->unidad_id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
                })->make(true);
        } else if ($usuario->id_rol == 4) {
            $residente = $residentes->where('estado', 'Activo')->get();
            return Datatables::of($residentes)
                ->addColumn('unidad', function ($residente) {
                    return $residente->unidad->tipo->nombre.' '.$residente->unidad->numero_letra;
                })
                ->addColumn('tipo_documento', function ($residente) {
                    return $residente->tipo_documento->tipo;
                })->addColumn('fecha_ingreso', function ($residente) {
                    return date('d-m-Y', strtotime($residente->fecha_ingreso));
                })->addColumn('action', function ($residente) {
                    return '<a data-toggle="tooltip" data-placement="top" title="Información de la unidad" class="btn btn-default" onclick="loadData(' . $residente->unidad_id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
                })->make(true);
        }
    }
}
