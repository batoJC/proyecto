<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Ejecucion_presupuestal_individual;
use App\saldoInicial;
use Excel;
use Yajra\Datatables\Datatables;
use App\Unidad;
use Illuminate\Http\Request;

class SaldoInicialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'saldos_iniciales']);


        //
        $conjunto = Conjunto::find(session('conjunto'));
        $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
        $presupuestos = Ejecucion_presupuestal_individual::join('ejecucion_presupuestal_total', 'ejecucion_presupuestal_individual.id_ejecucion_pre_total', '=', 'ejecucion_presupuestal_total.id')
            ->where([
                ['ejecucion_presupuestal_individual.conjunto_id', session('conjunto')],
                ['ejecucion_presupuestal_total.tipo', 'ingreso'],
                ['ejecucion_presupuestal_total.vigente', true]
            ])
            ->select('ejecucion_presupuestal_individual.*')
            ->get();
        return view('admin.saldos_iniciales.index')
            ->with('presupuestos', $presupuestos)
            ->with('unidades', $unidades)
            ->with('conjuntos', $conjunto);
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

        if ($request->interes && !($request->vigencia_fin)) {
            return array('res' => 0, 'msg' => 'Si aplica interes debe de seleccionar una vigencia final.');
        }

        try {
            $saldoInicial = new saldoInicial();
            $saldoInicial->vigencia_inicio = $request->vigencia_inicio;
            $saldoInicial->vigencia_fin = ($request->vigencia_fin) ? $request->vigencia_fin : null;
            $saldoInicial->concepto = $request->concepto;
            $saldoInicial->valor = $request->valor;
            $saldoInicial->interes = ($request->interes) ? true : false;
            $saldoInicial->presupuesto_cargar_id = $request->presupuesto;
            $saldoInicial->unidad_id = $request->unidad;
            $saldoInicial->conjunto_id = session('conjunto');
            $saldoInicial->save();

            return array('res' => 1, 'msg' => 'Saldo inicial guardado correctamente!');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el saldo  inicial.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\saldoInicial  $saldoInicial
     * @return \Illuminate\Http\Response
     */
    public function show(saldoInicial $saldoInicial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\saldoInicial  $saldoInicial
     * @return \Illuminate\Http\Response
     */
    public function edit(saldoInicial $saldoInicial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\saldoInicial  $saldoInicial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, saldoInicial $saldoInicial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\saldoInicial  $saldoInicial
     * @return \Illuminate\Http\Response
     */
    public function destroy($saldoInicial)
    {
        //
        try {
            $saldoInicial = saldoInicial::find($saldoInicial);
            $saldoInicial->delete();
            return array('res' => 1, 'msg' => 'Saldo inicial eliminado correctamente!');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al eliminar el saldo  inicial.');
        }
    }


    //mostrar vista para cargar saldos iniciales d eforma masiva
    //**********************************************************
    public function viewMasivo()
    {
        $conjunto = Conjunto::find(session('conjunto'));

        return view('admin.saldos_iniciales.masivo')
            ->with('conjuntos', $conjunto);
    }


    //para la carga masiva de saldos iniciales
    //****************************************
    public function masivo(Request $request)
    {
        // Validador si llega un archivo
        // *****************************
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();
            // Validador si el arreglo está vacío
            // **********************************
            if (!empty($data) && $data->count()) {
                try {
                    $unidad = '';
                    $contador = 0;
                    foreach ($data as $key => $value) {

                        $saldo_inicial = new saldoInicial();
                        $saldo_inicial->concepto = $value->concepto;
                        $saldo_inicial->vigencia_inicio = $value->vigencia_inicio;
                        $saldo_inicial->vigencia_fin = $value->vigencia_fin;
                        $saldo_inicial->interes = ($value->interes == 'si');
                        $saldo_inicial->valor = $value->valor;
                        $saldo_inicial->conjunto_id = session('conjunto');

                        //verificar que exista el presupuesto
                        /*****************************************/
                        $ejecucion_individual = Ejecucion_presupuestal_individual::join('tipo_ejecucion_pre', 'ejecucion_presupuestal_individual.id_tipo_ejecucion', 'tipo_ejecucion_pre.id')
                            ->join('ejecucion_presupuestal_total', 'ejecucion_presupuestal_individual.id_ejecucion_pre_total', 'ejecucion_presupuestal_total.id')//TODO
                            ->where([
                                ['ejecucion_presupuestal_total.vigente', true],
                                ['ejecucion_presupuestal_total.tipo', 'ingreso'],
                                ['ejecucion_presupuestal_total.conjunto_id', session('conjunto')],
                                ['tipo_ejecucion_pre.tipo', trim(mb_strtoupper($value->presupuesto, 'UTF-8'))]
                            ])->select('ejecucion_presupuestal_individual.id')->first();

                        // dd($ejecucion_individual);


                        if ($ejecucion_individual) {
                            $saldo_inicial->presupuesto_cargar_id = $ejecucion_individual->id;
                        } else {
                            return redirect('saldos_iniciales')
                                ->with('error', 'El presupuesto individual no existe!')
                                ->with('last', "La última fila insertada fue la $contador, unidad: $unidad");
                        }

                        //verificar que exista la unidad
                        $data = explode('-', $value->unidad);
                        $tipo_unidad = mb_strtoupper($data[0], 'UTF-8');
                        $numero_unidad = $data[1];
                        $unidad = Unidad::where('numero_letra', $numero_unidad)
                            ->where('unidads.conjunto_id', session('conjunto'))
                            ->join('tipo_unidad', 'unidads.tipo_unidad_id', 'tipo_unidad.id')
                            ->where('tipo_unidad.nombre', $tipo_unidad)
                            ->select('unidads.id')
                            ->first();

                        if ($unidad) {
                            $saldo_inicial->unidad_id = $unidad->id;
                        } else {
                            return redirect('saldos_iniciales')
                                ->with('error', 'La unidad no existe!')
                                ->with('last', "La última fila insertada fue la $contador, unidad: $unidad");
                        }


                        if ($saldo_inicial->save()) {
                            $contador++;
                            $unidad = $value->unidad;
                        } else {
                            return redirect('saldos_iniciales')
                                ->with('error', 'Ocurrió un error al guardar!')
                                ->with('last', "La última fila insertada fue la $contador, unidad: $unidad");
                        }
                    }
                    return redirect('saldos_iniciales')
                        ->with('status', 'Se insertó correctamente!')
                        ->with('last', "La última fila insertada fue la $contador, unidad: $unidad");
                } catch (\Throwable $th) {
                    return redirect('saldos_iniciales')
                        ->with('error', 'Ocurrió un error al registrar!')
                        ->with('last', "La última fila insertada fue la $contador, unidad: $unidad");
                }
            } else {
                return redirect('saldos_iniciales')
                    ->with('error', 'El archivo esta vacío!')
                    ->with('last', "No se encontró ningun registro");
            }
        }
    }

    //para descargar el archivo dbase
    public function download()
    {
        $pathtoFile = public_path() . '/docs/basesaldosiniciales.xlsx';
        return response()->download($pathtoFile);
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $saldos = saldoInicial::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($saldos)
            ->addColumn('vigencia_inicio', function ($saldo) {
                return date('d-m-Y', strtotime($saldo->vigencia_inicio));
            })->addColumn('vigencia_fin', function ($saldo) {
                if ($saldo->vigencia_fin) {
                    return date('d-m-Y', strtotime($saldo->vigencia_fin));
                } else {
                    return 'No aplica';
                }
            })->addColumn('unidad', function ($saldo) {
                return $saldo->unidad->tipo->nombre . ' ' . $saldo->unidad->numero_letra;
            })->addColumn('valor', function ($saldo) {
                return '$ ' . number_format($saldo->valor);
            })->addColumn('interes', function ($saldo) {
                return ($saldo->interes) ? 'Aplica' : 'No aplica';
            })->addColumn('action', function ($saldo) {
                return '<button data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData(' . $saldo->id . ')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </button>';
            })->make(true);
    }
}
