<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use Yajra\Datatables\Datatables;
use App\User;
use App\Conjunto;
use App\Tipo_unidad;
use App\Cuota_extOrd;
use App\Ejecucion_presupuestal_individual;
use App\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CuotaExtOrdinariaController extends Controller
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
        session(['section' => 'cuota_ext_ord']);

        if (Auth::user()->id_rol == 2) {

            $user             = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos        = Conjunto::find(session('conjunto'));
            $presupuestos_ingresos = Ejecucion_presupuestal_individual::join(
                'ejecucion_presupuestal_total',
                'ejecucion_presupuestal_individual.id_ejecucion_pre_total',
                '=',
                'ejecucion_presupuestal_total.id'
            )->where([
                ['ejecucion_presupuestal_total.tipo', 'ingreso'],
                ['ejecucion_presupuestal_total.conjunto_id', session('conjunto')],
                ['ejecucion_presupuestal_total.vigente', true]
            ])->select('ejecucion_presupuestal_individual.*')
                ->get();
            $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                ->where([
                    ['unidads.conjunto_id', session('conjunto')],
                    ['atributos_tipo_unidads.nombre', 'propietario']
                ])
                ->select('unidads.*')
                ->get();

            return view('admin.cuota_ord.index')
                ->with('user', $user)
                ->with('conjuntos', $conjuntos)
                ->with('presupuestos_ingresos', $presupuestos_ingresos)
                ->with('unidades', $unidades);
            // $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
            //     ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
            //     ->where([
            //         ['unidads.conjunto_id', session('conjunto')],
            //         ['atributos_tipo_unidads.nombre', 'propietario']
            //     ])
            //     ->select('unidads.*')
            //     ->get();
            // $actas = array();

            // $cuotas = Cuota_extOrd::where('conjunto_id', session('conjunto'))->get();


            // return view('admin.cuota_extr_ord.index')
            //     ->with('cuotas', $cuotas)
            //     ->with('actas', $actas)
            //     ->with('unidades', $unidades)
            //     ->with('presupuestos_ingresos', $presupuestos_ingresos)
            //     ->with('conjuntos', $conjunto);
        } else if (Auth::user()->id_rol == 3) {

            // *****************************************************
            // Validación si el usuario pertenece a varios conjuntos
            // *****************************************************
            if (Auth::user()->id_conjunto != null) {
                $admin         = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
                $apto_user     = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)->first();

                // Validación si se ha creado el apto del cliente
                // **********************************************
                if ($apto_user) {
                    $cuota_ext_ord = Cuota_extOrd::where('id_tipo_unidad', $apto_user->id)->where('id_conjunto', Auth::user()->id_conjunto)->get();
                    return view('dueno.cuota_extr_ord.index')
                        ->with('admin', $admin)
                        ->with('apto_user', $apto_user)
                        ->with('cuota_ext_ord', $cuota_ext_ord);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }


                // **********************************************************************
            } elseif (session('conjunto_user') != null) {

                // Cuando esta nulo el id_conjunto
                // -------------------------------
                $admin          = User::where('id_rol', 2)->where('id_conjunto', session('conjunto_user'))->first();
                $apto_user      = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)->first();

                // Validación si se ha creado el apto del cliente
                // **********************************************
                if ($apto_user) {
                    $cuota_ext_ord  = Cuota_extOrd::where('id_tipo_unidad', $apto_user->id)->where('id_conjunto', session('conjunto_user'))->get();
                    return view('dueno.cuota_extr_ord.index')
                        ->with('admin', $admin)
                        ->with('apto_user', $apto_user)
                        ->with('cuota_ext_ord', $cuota_ext_ord);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
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
        return redirect('/cuota_ext_ord');
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
            $request->valor = $request->valor / $request->cuantas;
            //se crea cada cuenta segun los parametros
            for ($i = 0; $i < $request->cuantas; $i++) {
                $vigencia_inicio = date("Y-m-d", strtotime($request->vigencia_inicio . "+ " . ($i * $request->cada_cuanto) . " month"));
                $vigencia_fin = date("Y-m", strtotime($vigencia_inicio)) . '-' . $request->dia_corte;

                $cuota_ext_ord = new Cuota_extOrd();
                $cuota_ext_ord->concepto = $request->concepto;
                $cuota_ext_ord->vigencia_inicio = $vigencia_inicio;
                $cuota_ext_ord->vigencia_fin = $vigencia_fin;
                $cuota_ext_ord->interes = ($request->interes == null) ? false : true;
                $cuota_ext_ord->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                $cuota_ext_ord->conjunto_id = session('conjunto');
                $cuota_ext_ord->save();

                //relacionar la cuota con los aptos seleccionados
                if (in_array('todas', $request->unidades)) { //si se seleccionan todos los aptos
                    $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
                    foreach ($unidades as $unidad) {
                        $valor = 0;
                        if ($request->tipo == 'valor') {
                            $valor = $request->valor;
                        } else {
                            $valor = $request->valor * ($unidad->coeficiente/100);
                        }
                        $cuota_ext_ord->unidades()->attach(
                            $unidad,
                            ['valor' => round($valor/100)*100]
                        );
                    }
                } else if ($request->tipo == 'valor') { //para solo algunos con valor fijo
                    foreach ($request->unidades as $unidad) {
                        if ($unidad != "") {
                            $cuota_ext_ord->unidades()->attach(
                                Unidad::find($unidad),
                                ['valor' => round($request->valor/100)*100]
                            );
                        }
                    }
                } else { //solo algunas con valor del coeficiente
                    $coeficienteTotal = 0;
                    foreach ($request->unidades as $unidad) {
                        if ($unidad != "") {
                            $coeficienteTotal += Unidad::find($unidad)->coeficiente;
                        }
                    }
                    $valorTotal = $request->valor / $coeficienteTotal;

                    foreach ($request->unidades as $unidad) {
                        if ($unidad != "") {
                            $auxUnidad = Unidad::find($unidad);
                            $cuota_ext_ord->unidades()->attach(
                                $auxUnidad,
                                ['valor' => round($valorTotal * $auxUnidad->coeficiente / 100)*100]
                            );
                        }
                    }
                }
            }
            return array('res' => 1, 'msg' => 'Cuotas extraordinarias registradas correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'ocurrió un error al registrar las cuotas extraordinarias.','e'=>$th);
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


        $cuota_ext_ord = Cuota_extOrd::find($id);
        // dd($cuota_ext_ord->unidades);
        $aux =  DB::table('cuota_ext_x_tipo_unidads')
            ->where('cuota_id', $id)
            ->select('unidad_id')
            ->get();

        $unidades = array();
        foreach ($aux as $unidad) {
            $unidades[] = $unidad->unidad_id;
        }


        return array('cuota' => $cuota_ext_ord, 'unidades' => $unidades);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cuota_ext_ord = Cuota_extOrd::find($id);
        return $cuota_ext_ord;
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
        try {
            $cuota_ext_ord = Cuota_extOrd::find($id);
            $cuota_ext_ord->valor = $request->valor;
            $cuota_ext_ord->vigencia_inicio = $request->vigencia_inicio;
            $cuota_ext_ord->vigencia_fin = $request->vigencia_fin;
            $cuota_ext_ord->presupuesto_cargar_id = $request->presupuesto_cargar_id;
            $cuota_ext_ord->conjunto_id = session('conjunto');
            $cuota_ext_ord->acta_id = $request->acta_id;
            $cuota_ext_ord->save();

            //eliminar las relaciones de antes
            DB::table('cuota_ext_x_tipo_unidads')
                ->where('cuota_id', $id)
                ->delete();

            //relacionar la cuota con los aptos seleccionados
            if (in_array('todas', $request->unidades)) { //si se seleccionan todos los aptos
                $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
                foreach ($unidades as $unidad) {
                    $cuota_ext_ord->unidades()->attach($unidad);
                }
            } else { //para solo algunos
                foreach ($request->unidades as $unidad) {
                    if ($unidad != "") {
                        $cuota_ext_ord->unidades()->attach(Unidad::find($unidad));
                    }
                }
            }

            return array('res' => 1, 'msg' => 'Cuota modificada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'No se logró midificar la cuenta.');
        }
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
            Cuota_extOrd::destroy($id);
            return array('res' => 1, 'msg' => 'Cuota eliminada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'No se logró eliminar la cuota.');
        }
    }

    public function verDetalle($cuota)
    {
        // $cuota = Cuota_extOrd::find($cuota);
        // return view('admin.cuota_ord.detalle')->with('cuotas', $cuota->unidades);

        $cuotas = DB::table('extraordinaria_unidades')
            // ->join('unidads','administracion_unidades.unidad_id','unidads.id')
            ->where('cuota_id', $cuota)
            ->select('extraordinaria_unidades.*')
            ->get();
        // dd($cuotas);    
        $salida = array();
        foreach ($cuotas as $cuota) {
            $unidad = Unidad::find($cuota->unidad_id);
            $salida[] = [
                'nombre' => $unidad->tipo->nombre . ' ' . $unidad->numero_letra,
                'estado' => $cuota->estado,
                'valor' => $cuota->valor
            ];
        }
        return view('admin.cuota_ord.detalle')->with('cuotas', $salida);
    }

    public function pdfNoPago($cuota)
    {
        $cuota = Cuota_extOrd::find($cuota);
        $pdf = null;
        $pdf = PDF::loadView('admin.cuota_ord.pdfDetalles', [
            'cuotas' => $cuota->unidades
        ]);
        return $pdf->stream();
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $cuotas = Cuota_extOrd::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($cuotas)
            ->addColumn('veigencia_inicio', function ($cuota) {
                return date('d-m-Y', strtotime($cuota->vigencia_inicio));
            })->addColumn('vigencia_fin', function ($cuota) {
                return date('d-m-Y', strtotime($cuota->vigencia_fin));
            })
            ->addColumn('action', function ($cuota) {
                return ' <button data-toggle="tooltip" data-placement="top" title="Detalles" onclick="detalles(' . $cuota->id . ')" 
                                class="btn btn-default">
                            <i class="fa fa-eye"></i>
                        </button>
                        <a data-toggle="tooltip" data-placement="top" 
                            title="Pdf con personas que deben esta cuota extraordinaria" target="_blank" 
                            href="' . url('detalleCuotaExtraordinariaPdf', ['cuota' => $cuota->id]) . '" 
                            class="btn btn-default">
                            <i class="fa fa-user-times"></i>
                        </a>
                        <button data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="eliminar(' . $cuota->id . ')" 
                                class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </button>';
            })->make(true);
    }
}
