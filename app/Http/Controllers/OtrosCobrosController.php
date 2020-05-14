<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Conjunto;
use App\Tipo_unidad;
use App\Otros_cobros;
use App\Ejecucion_presupuestal_individual;
use Yajra\Datatables\Datatables;
use App\Unidad;
use Illuminate\Http\Request;

class OtrosCobrosController extends Controller
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
        session(['section' => 'otros_cobros']);

        if (Auth::user()->id_rol == 2) {

            $conjunto = Conjunto::find(session('conjunto'));
            $presupuestos_ingresos = Ejecucion_presupuestal_individual::join(
                'ejecucion_presupuestal_total',
                'ejecucion_presupuestal_individual.id_ejecucion_pre_total',
                '=',
                'ejecucion_presupuestal_total.id'
            )
                ->where('ejecucion_presupuestal_total.tipo', 'ingreso')
                ->where('ejecucion_presupuestal_total.conjunto_id', session('conjunto'))
                ->where('ejecucion_presupuestal_total.vigente', true)
                ->select('ejecucion_presupuestal_individual.*')
                ->get();
            $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                ->where([
                    ['unidads.conjunto_id', session('conjunto')],
                    ['atributos_tipo_unidads.nombre', 'propietario']
                ])
                ->select('unidads.*')
                ->get();



            return view('admin.otros_cobros.index')
                ->with('conjuntos', $conjunto)
                ->with('presupuestos_ingresos', $presupuestos_ingresos)
                ->with('unidades', $unidades);
        } else if (Auth::user()->id_rol == 3) {

            // *****************************************************
            // Validación si el usuario pertenece a varios conjuntos
            // *****************************************************
            if (Auth::user()->id_conjunto != null) {
                $admin        = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
                $apto_user    = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)->first();

                // Validación si se ha creado el apto del cliente
                // **********************************************
                if ($apto_user) {
                    $otros_cobros = Otros_cobros::where('id_tipo_unidad', $apto_user->id)->where('id_conjunto', Auth::user()->id_conjunto)->get();
                    return view('dueno.otros_cobros.index')
                        ->with('admin', $admin)
                        ->with('apto_user', $apto_user)
                        ->with('otros_cobros', $otros_cobros);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
                // **********************************************************************
            } elseif (session('conjunto_user') != null) {

                // Cuando esta nulo el id_conjunto
                // -------------------------------
                $admin        = User::where('id_rol', 2)->where('id_conjunto', session('conjunto_user'))->first();
                $apto_user    = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)->first();

                // Validación si se ha creado el apto del cliente
                // **********************************************
                if ($apto_user) {
                    $otros_cobros = Otros_cobros::where('id_tipo_unidad', $apto_user->id)->where('id_conjunto', session('conjunto_user'))->get();
                    return view('dueno.otros_cobros.index')
                        ->with('admin', $admin)
                        ->with('apto_user', $apto_user)
                        ->with('otros_cobros', $otros_cobros);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
                // **********************************************************************
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/otros_cobros');
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

            if (in_array('todas', $request->unidades)) {
                $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                    ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                    ->where([
                        ['unidads.conjunto_id', session('conjunto')],
                        ['atributos_tipo_unidads.nombre', 'propietario']
                    ])
                    ->select('unidads.*')
                    ->get();

                foreach ($unidades as $unidad) {
                    $cuota = new Otros_cobros();
                    $cuota->valor = $request->valor;
                    $cuota->vigencia_inicio = $request->vigencia_inicio;
                    $cuota->descripcion = $request->descripcion;
                    $cuota->concepto = $request->concepto;
                    $cuota->vigencia_fin = $request->vigencia_fin;
                    $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                    $cuota->unidad_id = $unidad->id;
                    $cuota->interes = ($request->interes) ? 1 : 0;
                    $cuota->conjunto_id = session('conjunto');
                    $cuota->save();
                }

                return array('res' => 1, 'msg' => 'Cuotas guardadas correctamente.');
            } else {
                foreach ($request->unidades as $unidad) {
                    if ($unidad != '') {
                        $cuota = new Otros_cobros();
                        $cuota->valor = $request->valor;
                        $cuota->vigencia_inicio = $request->vigencia_inicio;
                        $cuota->vigencia_fin = $request->vigencia_fin;
                        $cuota->descripcion = $request->descripcion;
                        $cuota->concepto = $request->concepto;
                        $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                        $cuota->unidad_id = $unidad;
                        $cuota->interes = ($request->interes) ? 1 : 0;
                        $cuota->conjunto_id = session('conjunto');
                        $cuota->save();
                    }
                }
            }


            return array('res' => 1, 'msg' => 'Cuotas  guardadas correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al realizar el registro.');
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
        $otros_cobros  = Otros_cobros::find($id);
        return $otros_cobros;
        // $unidad = $otros_cobros->unidad;
        // return array('cuota' => $otros_cobros, 'unidad' => $unidad->tipo->nombre.' '.$unidad->numero_letra);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $otros_cobros  = Otros_cobros::find($id);
        return $otros_cobros;
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
        // try{
        //     $cuota = Otros_cobros::find($id);
        //     $cuota->valor = $request->valor;
        //     $cuota->vigencia_inicio = $request->vigencia_inicio;
        //     $cuota->descripcion = $request->descripcion;
        //     $cuota->vigencia_fin = $request->vigencia_fin;
        //     $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
        //     $cuota->interes = ($request->interes) ? 1 : 0;
        //     $cuota->save();
        //     return array('res' => 1, 'msg' => 'Cuota  modificada correctamente.');
        // } catch (\Throwable $th) {
        //     return array('res' => 0, 'msg' => 'Ocurrió un error al modificar el registro.');
        // }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Otros_cobros::destroy($id);
            return array('res' => 1, 'msg' => 'Cuota  eliminada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al eliminar el registro.');
        }
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $cuotas = Otros_cobros::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($cuotas)
            ->addColumn('unidad', function ($cuota) {
                return $cuota->unidad->tipo->nombre . ' ' . $cuota->unidad->numero_letra;
            })->addColumn('valor', function ($cuota) {
                return '$ ' . number_format($cuota->valor);
            })->addColumn('vigencia_inicio', function ($cuota) {
                return date('d-m-Y', strtotime($cuota->vigencia_inicio));
            })->addColumn('vigencia_fin', function ($cuota) {
                return ($cuota->vigencia_fin) ? date('d-m-Y', strtotime($cuota->vigencia_fin)) : 'No aplica';
            })->addColumn('action', function ($cuota) {
                return ' <a  data-toggle="tooltip" data-placement="top" title="Consultar" onclick="consultar(' . $cuota->id . ')" class="btn btn-default">
                            <i class="fa fa-search"></i>
                        </a>
                        <a  data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="eliminar(' . $cuota->id . ')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
