<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Ejecucion_presupuestal_individual;
use App\saldoInicial;
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

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $saldos = saldoInicial::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($saldos)
            ->addColumn('vigencia_inicio',function($saldo){
                return date('d-m-Y',strtotime($saldo->vigencia_inicio));
            })->addColumn('vigencia_fin',function($saldo){
                return date('d-m-Y',strtotime($saldo->vigencia_fin));
            })->addColumn('unidad',function($saldo){
                return $saldo->unidad->tipo->nombre .' '. $saldo->unidad->numero_letra;
            })->addColumn('valor',function($saldo){
                return '$ '.number_format($saldo->valor);
            })->addColumn('interes',function($saldo){
                return ($saldo->interes)? 'Aplica' : 'No aplica';
            })->addColumn('action', function ($saldo) {
                return '<button onclick="deleteData('.$saldo->id.')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </button>';
            })->make(true);
    }
}
