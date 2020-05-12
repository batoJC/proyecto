<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\User;
use App\Conjunto;
use App\Cuota_admon;
use App\Cartera;
use App\Tipo_unidad;
use App\Tabla_intereses;
use Yajra\Datatables\Datatables;
use App\Multa;
use App\Ejecucion_presupuestal_individual;
use App\Ejecucion_presupuestal_total;
use App\Unidad;
use Illuminate\Http\Request;

class CuotaAdmonController extends Controller
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
        session(['section' => 'cuota_admon']);

        // dd(Unidad::find(56)->cuotasAdministracion()->orderBy('vigencia_fin','DESC')->first()->pivot->valor);


        if (Auth::user()->id_rol == 2) {

            $conjunto = Conjunto::find(session('conjunto'));
            $presupuestos_egresos = Ejecucion_presupuestal_individual::join('ejecucion_presupuestal_total', 'ejecucion_presupuestal_individual.id_ejecucion_pre_total', '=', 'ejecucion_presupuestal_total.id')
                ->where([
                    ['ejecucion_presupuestal_individual.conjunto_id', session('conjunto')],
                    ['ejecucion_presupuestal_total.tipo', 'egreso'],
                    ['ejecucion_presupuestal_total.vigente', true]
                ])
                ->select('ejecucion_presupuestal_individual.*')
                ->get();

            $presupuestos_ingresos = Ejecucion_presupuestal_individual::join('ejecucion_presupuestal_total', 'ejecucion_presupuestal_individual.id_ejecucion_pre_total', '=', 'ejecucion_presupuestal_total.id')
                ->where([
                    ['ejecucion_presupuestal_individual.conjunto_id', session('conjunto')],
                    ['ejecucion_presupuestal_total.tipo', 'ingreso'],
                    ['ejecucion_presupuestal_total.vigente', true]
                ])
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



            return view('admin.cuota_admon.index')
                ->with('unidades', $unidades)
                ->with('presupuestos_egresos', $presupuestos_egresos)
                ->with('presupuestos_ingresos', $presupuestos_ingresos)
                ->with('conjuntos', $conjunto);
        } elseif (Auth::user()->id_rol == 3) {

            // *****************************************************
            // Validación si el usuario pertenece a varios conjuntos
            // *****************************************************
            if (Auth::user()->id_conjunto != null) {
                $admin       = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
                $apto_user   = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)->first();
                // Validación si se ha creado el apto del cliente
                // **********************************************
                if ($apto_user) {
                    $cuota_admon = Cuota_admon::where('id_tipo_unidad', $apto_user->id)->where('id_conjunto', Auth::user()->id_conjunto)->get();
                    return view('dueno.cuota_admon.index')
                        ->with('admin', $admin)
                        ->with('apto_user', $apto_user)
                        ->with('cuota_admon', $cuota_admon);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
                // **********************************************************************
            } elseif (session('conjunto_user') != null) {

                // Cuando esta nulo el id_conjunto
                // -------------------------------
                $admin         = User::where('id_rol', 2)->where('id_conjunto', session('conjunto_user'))->first();
                $apto_user     = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)->first();
                // Validación si se ha creado el apto del cliente
                // **********************************************
                if ($apto_user) {
                    $cuota_admon   = Cuota_admon::where('id_tipo_unidad', $apto_user->id)->where('id_conjunto', session('conjunto_user'))->get();
                    return view('dueno.cuota_admon.index')
                        ->with('admin', $admin)
                        ->with('apto_user', $apto_user)
                        ->with('cuota_admon', $cuota_admon);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
            }
        } else {
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
        return redirect('/cuota_admon');
    }

    private $auxValorCuotas;


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $request->redondear = ($request->redondear <= 0) ? 1 : $request->redondear;

            //calcular los meses de las cuotas administrativas
            $meses = date_diff(
                date_create($request->vigencia_inicio),
                date_create($request->vigencia_fin)
            )->format('%y') * 12;

            if ($meses == 0) {
                $meses = date_diff(
                    date_create($request->vigencia_inicio),
                    date_create($request->vigencia_fin)
                )->format('%m');
                $meses++;
            }

            $this->auxValorCuotas = array();

            if (in_array('todas', $request->unidades)) {
                $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                    ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                    ->where([
                        ['unidads.conjunto_id', session('conjunto')],
                        ['atributos_tipo_unidads.nombre', 'propietario']
                    ])
                    ->select('unidads.*')
                    ->get();

                // dd($unidades);

                if ($meses == 0) {
                    //se crea la cuota
                    $cuota = new Cuota_admon();
                    $cuota->vigencia_inicio = $request->vigencia_inicio;
                    $cuota->vigencia_fin = $request->vigencia_fin;
                    $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                    $cuota->interes = ($request->interes == null) ? false : true;
                    $cuota->conjunto_id = session('conjunto');
                    $cuota->save();
                    foreach ($unidades as $unidad) {
                        $valor = $this->calcularValorCuota($request, $unidad);
                        if ($valor > 0) {
                            $cuota->unidades()->attach(
                                $unidad,
                                ['valor' => $valor]
                            );
                        }
                    }
                } else {
                    for ($i = 0; $i < $meses; $i++) {
                        $aux_inicio = date("Y-m-d", strtotime($request->vigencia_inicio . "+ " . $i . " month"));
                        $data1 = explode('-', $aux_inicio);
                        $data2 = explode('-', $request->vigencia_fin);
                        $dia_fin = $data2[2];

                        if ($dia_fin > cal_days_in_month(CAL_GREGORIAN, $data1[1], $data1[0])) {
                            $dia_fin = cal_days_in_month(CAL_GREGORIAN, $data1[1], $data1[0]);
                        }

                        // while ($dia_fin > cal_days_in_month(CAL_GREGORIAN, $data1[1], $data1[0])) {
                        //     $dia_fin--;
                        // }

                        $aux_fin = $data1[0] . '-' . $data1[1] . '-' . $dia_fin;

                        $cuota = new Cuota_admon();
                        $cuota->vigencia_inicio = $aux_inicio;
                        $cuota->vigencia_fin = $aux_fin;
                        // $cuota->presupuesto_calcular_id = $request->presupuesto_calcular_id;
                        $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                        $cuota->interes = ($request->interes == null) ? false : true;
                        $cuota->conjunto_id = session('conjunto');
                        $cuota->save();

                        foreach ($unidades as $unidad) {
                            $valor = $this->calcularValorCuota($request, $unidad);

                            // dd($valor);

                            if ($valor > 0) {
                                $cuota->unidades()->attach(
                                    $unidad,
                                    ['valor' => $valor]
                                );
                            }
                        }
                    }
                }
                return array('res' => 1, 'msg' => 'Cuotas de administracion guardadas correctamente.');
            } else {

                if ($meses == 0) {
                    $cuota = new Cuota_admon();
                    $cuota->vigencia_inicio = $request->vigencia_inicio;
                    $cuota->vigencia_fin = $request->vigencia_fin;
                    $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                    $cuota->interes = ($request->interes == null) ? false : true;
                    $cuota->conjunto_id = session('conjunto');
                    $cuota->save();

                    foreach ($request->unidades as $unidad) {
                        if ($unidad != '') {
                            $unidad = Unidad::find($unidad);
                            $valor = $this->calcularValorCuota($request, $unidad);
                            if ($valor > 0) {
                                $cuota->unidades()->attach(
                                    $unidad,
                                    ['valor' => $valor]
                                );
                            }
                        }
                    }
                } else {
                    for ($i = 0; $i <= $meses; $i++) {
                        $aux_inicio = date("Y-m-d", strtotime($request->vigencia_inicio . "+ " . $i . " month"));
                        $data1 = explode('-', $aux_inicio);
                        $data2 = explode('-', $request->vigencia_fin);
                        $dia_fin = $data2[2];

                        if ($dia_fin > cal_days_in_month(CAL_GREGORIAN, $data1[1], $data1[0])) {
                            $dia_fin = cal_days_in_month(CAL_GREGORIAN, $data1[1], $data1[0]);
                        }

                        $aux_fin = $data1[0] . '-' . $data1[1] . '-' . $dia_fin;


                        $cuota = new Cuota_admon();
                        $cuota->vigencia_inicio = $aux_inicio;
                        $cuota->vigencia_fin = $aux_fin;
                        $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                        $cuota->interes = ($request->interes == null) ? false : true;
                        $cuota->conjunto_id = session('conjunto');
                        $cuota->save();

                        foreach ($request->unidades as $unidad) {
                            if ($unidad != '') {
                                $unidadP = Unidad::find($unidad);
                                $cuota->unidades()->attach(
                                    $unidadP,
                                    ['valor' => $this->calcularValorCuota($request, $unidad)]
                                );
                            }
                        }
                    }
                }
            }

            return array('res' => 1, 'msg' => 'Cuotas de administracion guardadas correctamente.');
        } catch (\Throwable $th) {
            // return $th;
            return array('res' => 0, 'msg' => 'Ocurrió un error al realizar el registro.');
        }
    }


    private function calcularValorCuota($request, $unidad)
    {
        $valor = 0;
        switch ($request->tipo_calculo) {
            case 1: //por presupuesto
                // calcular el periodo de las cuotas administrativas
                $presupuesto = Ejecucion_presupuestal_individual::find($request->presupuesto_calcular_id);
                $periodo = date_diff(
                    date_create($presupuesto->ejecucion_presupuestal_total->fecha_inicio),
                    date_create($presupuesto->ejecucion_presupuestal_total->fecha_fin)
                )->format('%y') * 12;

                if ($periodo == 0) {
                    $periodo = date_diff(
                        date_create($presupuesto->ejecucion_presupuestal_total->fecha_inicio),
                        date_create($presupuesto->ejecucion_presupuestal_total->fecha_fin)
                    )->format('%m');
                    $periodo++;
                }

                // dd($periodo);


                $valor = (($unidad->coeficiente / 100) * $presupuesto->total) / $periodo;
                $valor = round($valor / $request->redondear) * $request->redondear;

                return $valor;
            case 2: //por total de gastos

                //obtener presupuestos actuales de ingreso y egreso

                //calcular los ingresos corespondientes a la unidad 
                // por cada mes

                $ingresos = Ejecucion_presupuestal_total::where([
                    ['tipo', 'ingreso'],
                    ['conjunto_id', session('conjunto')],
                    ['vigente', true]
                ])->first();

                $periodo = date_diff(
                    date_create($ingresos->fecha_inicio),
                    date_create($ingresos->fecha_fin)
                )->format('%y') * 12;

                if ($periodo == 0) {
                    $periodo = date_diff(
                        date_create($ingresos->fecha_inicio),
                        date_create($ingresos->fecha_fin)
                    )->format('%m');
                }

                $ingresosMes = (($unidad->coeficiente / 100) * $ingresos->valor_total($request->presupuesto_cargar_id)) / $periodo;
                $ingresosMes = round($ingresosMes / $request->redondear) * $request->redondear;

                //calcular el total de egresos mensuales a pagar por 
                //la unidad teniendo en cuenta que algunos egresos 
                //no deben de ser pagados por algunas unidades
                //y valor faltante lo deben de pagar las que si
                $egresos = Ejecucion_presupuestal_total::where([
                    ['tipo', 'egreso'],
                    ['conjunto_id', session('conjunto')],
                    ['vigente', true]
                ])->first();

                $periodo = date_diff(
                    date_create($egresos->fecha_inicio),
                    date_create($egresos->fecha_fin)
                )->format('%y') * 12;

                if ($periodo == 0) {
                    $periodo = date_diff(
                        date_create($egresos->fecha_inicio),
                        date_create($egresos->fecha_fin)
                    )->format('%m');
                }

                //del de ingreso sacar el total * coeficiente
                //sumar los valores de cada presupuesto de egreso correspondiente
                //teniendo en cuenta las unidades excluidas

                $egresosMes = 0;

                foreach ($egresos->detalles as $egreso) {
                    $cantidadUnidadesExcluidas = $egreso->excluidas->count();
                    if ($cantidadUnidadesExcluidas > 0) {
                        //si no es excluida
                        // dd($egreso->excluidas->find($unidad->id));
                        if (!$egreso->excluidas->find($unidad->id)) {
                            $coeficienteNoExcluidas = 1;
                            foreach ($egreso->excluidas as $unidadExcluida) {
                                $coeficienteNoExcluidas -= $unidadExcluida->coeficiente;
                            }

                            $totalParaCalcular = ($egreso->total) / $coeficienteNoExcluidas;
                            $valorAux = (($unidad->coeficiente / 100) * $totalParaCalcular) / $periodo;
                            $egresosMes += $valorAux;
                        }
                    } else {
                        $valorAux = (($unidad->coeficiente / 100) * $egreso->total) / $periodo;
                        $egresosMes += $valorAux;
                    }
                }


                $egresosMes = round($egresosMes / $request->redondear) * $request->redondear;

                $valor = $egresosMes - $ingresosMes;
                return $valor;
            case 3: //por valor fijo
                $valor = round($request->valor_fijo / $request->redondear) * $request->redondear;
                return $valor;
            case 4: //incremento porcentual
                if (!isset($this->auxValorCuotas[$unidad->id])) {
                    $valorAnterior = $unidad->cuotasAdministracion()->orderBy('vigencia_fin', 'DESC')->first()->pivot->valor;
                    $aumento = ($request->incremento_porcentual / 100) + 1;
                    $valor = $valorAnterior * $aumento;
                    $valor = round($valor / $request->redondear) * $request->redondear;
                    $this->auxValorCuotas[$unidad->id] = $valor;
                } else {
                    $valor = $this->auxValorCuotas[$unidad->id];
                }
                return $valor;
            case 5: //incremento valor fijo
                if (!isset($this->auxValorCuotas[$unidad->id])) {
                    $valorAnterior = $unidad->cuotasAdministracion()->orderBy('vigencia_fin', 'DESC')->first()->pivot->valor;
                    $aumento = $request->incremento_valor_fijo;
                    $valor = $valorAnterior * $aumento;
                    $valor = round($valor / $request->redondear) * $request->redondear;
                    $this->auxValorCuotas[$unidad->id] = $valor;
                } else {
                    $valor = $this->auxValorCuotas[$unidad->id];
                }
                return $valor;
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
        $cuota = Cuota_admon::find($id);
        $unidad = $cuota->unidad;
        return array(
            'cuota' => $cuota,
            'unidad' => array(
                'tipo' => $unidad->tipo->nombre,
                'numero_letra' => $unidad->numero_letra
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
            $cuota = Cuota_admon::find($id);
            $cuota->valor = $request->valor;
            $cuota->vigencia_inicio = $request->vigencia_inicio;
            $cuota->vigencia_fin = $request->vigencia_fin;
            $cuota->presupuesto_calcular_id = $request->presupuesto_calcular_id;
            $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
            $cuota->acta_id = $request->acta_id;
            $cuota->save();
            return array('res' => 1, 'msg' => 'Cuota editada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'No se logró editar la cuota.');
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
            $cuota = Cuota_admon::find($id);
            $cuota->delete();
            return array('res' => 1, 'msg' => 'Cuota eliminada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'No se pudo eliminar la cuota.');
        }
    }


    //muestra las cuentas de cobro de cada tipo de unidad
    // public function verCuentas($unidadId)
    // {
    //     //cuotas administraticas
    //     $aux = array();
    //     $total = 0;
    //     $cuotas = cuota_ord_x_tipo_unidad::where([
    //         ['unidad_id', $unidadId],
    //         ['pago', 0]
    //     ])
    //         ->orWhere([
    //             ['unidad_id', $unidadId],
    //             ['pago', 2]
    //         ])
    //         ->get();
    //     foreach ($cuotas as $cuota) {
    //         $costo = $cuota->cuotaOrd->costo;
    //         $interes = 0;
    //         $interesesPagados = 0;

    //         $fechaAnterior = date_create($cuota->cuotaOrd->fecha_vigencia_inicio);
    //         $fechaHoy = date_create(date('Y-m-d'));
    //         $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');

    //         if ($dias > 0) {
    //             //calcular el interes de la cuenta
    //             //********************************
    //             $datos = Tabla_intereses::where([
    //                 ['fecha_vigencia_inicio', '<=', $cuota->cuotaOrd->fecha_vigencia_fin],
    //                 ['fecha_vigencia_fin', '>=', $cuota->cuotaOrd->fecha_vigencia_fin]
    //             ])->first();
    //             if ($datos != Null) {
    //                 $interesesPagados = DB::table("carteras")
    //                     ->where([
    //                         ["unidad_id", $unidadId],
    //                         ['tipo_de_movimiento', 2],
    //                         ['movimiento', $cuota->cuotaOrd->id]
    //                     ])->sum(DB::raw("interes"));
    //                 $pagoSaldo = Cartera::where([
    //                     ["unidad_id", $unidadId],
    //                     ['tipo_de_movimiento', 2],
    //                     ['movimiento', $cuota->cuotaOrd->id],
    //                     ['capital', '>', 0]
    //                 ])->get();

    //                 $fechaAnterior = date_create($cuota->cuotaOrd->fecha_vigencia_fin);
    //                 $fechaHoy = date_create(date('Y-m-d'));
    //                 $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //                 if ($dias > 0) {
    //                     foreach ($pagoSaldo as $pago) {
    //                         $dias = date_diff($fechaAnterior, date_create($pago->fecha))->format('%R%a');
    //                         $fechaAnterior = date_create($pago->fecha);
    //                         $interes += ($dias * $datos->tasa_diaria) * $costo;
    //                         $costo -= $pago->capital;
    //                     }
    //                     $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //                     $interes += ($dias * $datos->tasa_diaria) * $costo;
    //                 }
    //             }

    //             $interes -= $interesesPagados;
    //             $interes = round($interes, 2);
    //             if (($costo + $interes) > 0) {
    //                 $aux[] = array('id' => $cuota->cuotaOrd->id, 'tipo' => 2, 'fecha' => date('d M Y', strtotime($cuota->cuotaOrd->fecha_vigencia_fin)), 'descripcion' => 'Cuota de administración', 'costo' => $costo, 'interes' => $interes);
    //                 $total += $costo + $interes;
    //             } else {
    //                 $cuota->pago = 1;
    //                 $cuota->save();
    //             }
    //         }
    //     }

    //     //cuotas extraordinarias
    //     $cuotas = cuota_ext_x_tipo_unidad::where([
    //         ['unidad_id', $unidadId],
    //         ['pago', 0]
    //     ])
    //         ->orWhere([
    //             ['unidad_id', $unidadId],
    //             ['pago', 2]
    //         ])
    //         ->get();
    //     foreach ($cuotas as $cuota) {
    //         $costo = $cuota->cuotaExt->costo;
    //         $interes = 0;
    //         $interesesPagados = 0;


    //         //calcular el interes de la cuenta
    //         //********************************
    //         $datos = Tabla_intereses::where([
    //             ['fecha_vigencia_inicio', '<=', $cuota->cuotaExt->fecha_vencimiento],
    //             ['fecha_vigencia_fin', '>=', $cuota->cuotaExt->fecha_vencimiento]
    //         ])->first();

    //         if ($datos != Null) {
    //             $interesesPagados = DB::table("carteras")
    //                 ->where([
    //                     ["unidad_id", $unidadId],
    //                     ['tipo_de_movimiento', 3],
    //                     ['movimiento', $cuota->cuotaExt->id]
    //                 ])->sum(DB::raw("interes"));
    //             $pagoSaldo = Cartera::where([
    //                 ["unidad_id", $unidadId],
    //                 ['tipo_de_movimiento', 3],
    //                 ['movimiento', $cuota->cuotaExt->id],
    //                 ['capital', '>', 0]
    //             ])->get();

    //             $fechaAnterior = date_create($cuota->cuotaExt->fecha_vencimiento);
    //             $fechaHoy = date_create(date('Y-m-d'));
    //             $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //             if ($dias > 0) {
    //                 foreach ($pagoSaldo as $pago) {
    //                     $dias = date_diff($fechaAnterior, date_create($pago->fecha))->format('%R%a');
    //                     $fechaAnterior = date_create($pago->fecha);
    //                     $interes += ($dias * $datos->tasa_diaria) * $costo;
    //                     $costo -= $pago->capital;
    //                 }
    //                 $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //                 $interes += ($dias * $datos->tasa_diaria) * $costo;
    //             }
    //         }


    //         $interes -= $interesesPagados;
    //         $interes = round($interes, 2);
    //         if (($costo + $interes) > 0) {
    //             $aux[] = array('id' => $cuota->cuotaExt->id, 'tipo' => 3, 'fecha' => date('d M Y', strtotime($cuota->cuotaExt->fecha_vencimiento)), 'descripcion' => $cuota->cuotaExt->tipo_cuota_extraordinaria, 'costo' => $costo, 'interes' => $interes);
    //             $total += $costo + $interes;
    //         } else {
    //             $cuota->pago = 1;
    //             $cuota->save();
    //         }
    //     }

    //     //otras cuotas
    //     $cuotas = otros_x_tipo_unidad::where([
    //         ['unidad_id', $unidadId],
    //         ['pago', 0]
    //     ])
    //         ->orWhere([
    //             ['unidad_id', $unidadId],
    //             ['pago', 2]
    //         ])
    //         ->get();
    //     foreach ($cuotas as $cuota) {
    //         $costo = $cuota->cuotaOtros->costo;
    //         $interes = 0;
    //         $interesesPagados = 0;

    //         if ($cuota->cuotaOtros->fecha_vencimiento == null) {
    //             $cuota->cuotaOtros->fecha_vencimiento = "No aplica";
    //         } else {
    //             //calcular el interes de la cuenta
    //             //********************************
    //             $datos = Tabla_intereses::where([
    //                 ['fecha_vigencia_inicio', '<=', $cuota->cuotaOtros->fecha_vencimiento],
    //                 ['fecha_vigencia_fin', '>=', $cuota->cuotaOtros->fecha_vencimiento]
    //             ])->first();
    //             if ($datos != Null) {
    //                 $interesesPagados = DB::table("carteras")
    //                     ->where([
    //                         ["unidad_id", $unidadId],
    //                         ['tipo_de_movimiento', 4],
    //                         ['movimiento', $cuota->cuotaOtros->id]
    //                     ])->sum(DB::raw("interes"));
    //                 $pagoSaldo = Cartera::where([
    //                     ["unidad_id", $unidadId],
    //                     ['tipo_de_movimiento', 4],
    //                     ['movimiento', $cuota->cuotaOtros->id],
    //                     ['capital', '>', 0]
    //                 ])->get();

    //                 $fechaAnterior = date_create($cuota->cuotaOtros->fecha_vencimiento);
    //                 $fechaHoy = date_create(date('Y-m-d'));
    //                 $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //                 if ($dias > 0) {
    //                     foreach ($pagoSaldo as $pago) {
    //                         $dias = date_diff($fechaAnterior, date_create($pago->fecha))->format('%R%a');
    //                         $fechaAnterior = date_create($pago->fecha);
    //                         $interes += ($dias * $datos->tasa_diaria) * $costo;
    //                         $costo -= $pago->capital;
    //                     }
    //                     $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //                     $interes += ($dias * $datos->tasa_diaria) * $costo;
    //                 }
    //             }
    //         }
    //         $interes -= $interesesPagados;
    //         $interes = round($interes, 2);
    //         if (($costo + $interes) > 0) {
    //             $aux[] = array('id' => $cuota->cuotaOtros->id, 'tipo' => 4, 'fecha' => date('d M Y', strtotime($cuota->cuotaOtros->fecha_vencimiento)), 'descripcion' => $cuota->cuotaOtros->descripcion, 'costo' => $costo, 'interes' => $interes);
    //             $total += $costo + $interes;
    //         } else {
    //             $cuota->pago = 1;
    //             $cuota->save();
    //         }
    //     }

    //     //multas
    //     $cuotas = Multa::where([
    //         ['id_tipo_unidad', $unidadId],
    //         ['estado', 0]
    //     ])
    //         ->orWhere([
    //             ['id_tipo_unidad', $unidadId],
    //             ['estado', 2]
    //         ])
    //         ->get();
    //     foreach ($cuotas as $cuota) {
    //         $costo = $cuota->costo;
    //         $interes = 0;
    //         $interesesPagados = 0;


    //         if ($cuota->fecha_vencimiento == null) {
    //             $cuota->fecha_vencimiento = "No aplica";
    //         } else {
    //             //calcular el interes de la cuenta
    //             //********************************
    //             $datos = Tabla_intereses::where([
    //                 ['fecha_vigencia_inicio', '<=', $cuota->fecha_vencimiento],
    //                 ['fecha_vigencia_fin', '>=', $cuota->fecha_vencimiento]
    //             ])->first();
    //             if ($datos != Null) {
    //                 $interesesPagados = DB::table("carteras")
    //                     ->where([
    //                         ["unidad_id", $unidadId],
    //                         ['tipo_de_movimiento', 5],
    //                         ['movimiento', $cuota->id]
    //                     ])->sum(DB::raw("interes"));
    //                 $pagoSaldo = Cartera::where([
    //                     ["unidad_id", $unidadId],
    //                     ['tipo_de_movimiento', 5],
    //                     ['movimiento', $cuota->id],
    //                     ['capital', '>', 0]
    //                 ])->get();

    //                 $fechaAnterior = date_create($cuota->fecha_vencimiento);
    //                 $fechaHoy = date_create(date('Y-m-d'));
    //                 $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //                 if ($dias > 0) {
    //                     foreach ($pagoSaldo as $pago) {
    //                         $dias = date_diff($fechaAnterior, date_create($pago->fecha))->format('%R%a');
    //                         $fechaAnterior = date_create($pago->fecha);
    //                         $interes += ($dias * $datos->tasa_diaria) * $costo;
    //                         $costo -= $pago->capital;
    //                     }
    //                     $dias = date_diff($fechaAnterior, $fechaHoy)->format('%R%a');
    //                     $interes += ($dias * $datos->tasa_diaria) * $costo;
    //                 }
    //             }
    //         }
    //         $interes -= $interesesPagados;
    //         $interes = round($interes, 2);
    //         if (($costo + $interes) > 0) {
    //             $aux[] = array('id' => $cuota->id, 'tipo' => 5, 'fecha' => date('d M Y', strtotime($cuota->fecha_vencimiento)), 'descripcion' => $cuota->descripcion, 'costo' => $costo, 'interes' => $interes);
    //             $total += $costo + $interes;
    //         } else {
    //             $cuota->estado = 1;
    //             $cuota->save();
    //         }
    //     }


    //     $salida = array('cuotas' => $aux, 'total' => $total);
    //     return $salida;
    // }


    public function verDetalle($cuota)
    {
        // dd($cuota);
        $cuotas = DB::table('administracion_unidades')
            // ->join('unidads','administracion_unidades.unidad_id','unidads.id')
            ->where('cuota_id', $cuota)
            ->select('administracion_unidades.*')
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
        return view('admin.cuota_admon.detalle')->with('cuotas', $salida);
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $cuotas = Cuota_admon::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($cuotas)
            ->addColumn('veigencia_inicio', function ($cuota) {
                return date('d-m-Y', strtotime($cuota->vigencia_inicio));
            })->addColumn('vigencia_fin', function ($cuota) {
                return date('d-m-Y', strtotime($cuota->vigencia_fin));
            })->addColumn('interes', function ($cuota) {
                return ($cuota->interes) ? 'Aplica' : 'No aplica';
            })->addColumn('action', function ($cuota) {
                return '<button  data-toggle="tooltip" data-placement="top" 
                                title="Detalles" onclick="detalles(' . $cuota->id . ')" class="btn btn-default">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button  data-toggle="tooltip" data-placement="top" 
                                title="Eliminar" onclick="eliminar(' . $cuota->id . ')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </button>';
            })->make(true);
    }
}
