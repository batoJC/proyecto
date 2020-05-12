<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\FlujoEfectivo;
use App\User;
use App\Conjunto;
use PDF;

class FlujoEfectivoController extends Controller
{

    //listar todo los registros  de flujo de efectivo de 
    //un condominio
    /***************************************************/
    public function index()
    {
        //para la seleccion en el navbar
        session(['section' => 'flujo_efectivo']);

        $saldoActual = $this->saldoActual()['valor'];

        $conjuntos  = Conjunto::find(session('conjunto'));
        $user       = User::where('id_conjunto', session('conjunto'))->get();

        return view('admin.flujo_efectivo.index')
            ->with('saldoActual', $saldoActual)
            ->with('user', $user)
            ->with('conjuntos', $conjuntos);
    }

    public function saldoActual()
    {
        $sumaIngresos = DB::table("flujo_efectivos")
            ->where([
                ["conjunto_id", session('conjunto')],
                ['tipo', 0]
            ])->sum(DB::raw("valor"));

        $sumaEgresos = DB::table("flujo_efectivos")
            ->where([
                ["conjunto_id", session('conjunto')],
                ['tipo', 1]
            ])->sum(DB::raw("valor"));

        $saldoActual = $sumaIngresos - $sumaEgresos; //suma de ingresos - suma de egresos
        return ['valor' => $saldoActual, 'texto' => '$ ' . number_format($saldoActual)];
    }

    //crear registro
    public function add(Request $request)
    {
        $flujo = new FlujoEfectivo();
        $flujo->conjunto_id = session('conjunto');
        $flujo->fecha = $request->fecha;
        $flujo->concepto = $request->concepto;
        $flujo->recibo = $request->recibo;
        $flujo->tipo = $request->tipo;
        $flujo->valor = $request->valor;
        $flujo->save();
        return 1;
    }

    //eliminar registro
    public function delete($id)
    {
        $flujo = FlujoEfectivo::find($id);
        $flujo->delete();
        return 1;
    }

    //consultar registro
    public function show($id)
    {
        $flujo = FlujoEfectivo::find($id);
        return $flujo;
    }

    //editar registro
    public function edit(Request $request)
    {
        $flujo = FlujoEfectivo::find($request->id);
        $flujo->conjunto_id = session('conjunto');
        $flujo->fecha = $request->fecha;
        $flujo->concepto = $request->concepto;
        $flujo->recibo = $request->recibo;
        $flujo->tipo = $request->tipo;
        $flujo->valor = $request->valor;
        $flujo->save();
        return 1;
    }


    //descargar pdf con el registrp
    public function dowload(FlujoEfectivo $flujo)
    {
        $pdf = null;
        $pdf = PDF::loadView('admin.flujo_efectivo.recibo', ['flujo' => $flujo]);
        return $pdf->stream();
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $listaFlujos = FlujoEfectivo::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($listaFlujos)
            ->addColumn('valor', function ($flujo) {
                return '$ ' . number_format($flujo->valor);
            })->addColumn('action', function ($flujo) {
                $salida = '<a data-toggle="tooltip" data-placement="top" title="Editar" 
                                onclick="openModalEdit(' . $flujo->id . ')" class="btn btn-default">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Eliminar" 
                                onclick="eraser(' . $flujo->id . ')" class="btn btn-default">
                                <i class="fa fa-trash"></i>
                            </a>';
                if ($flujo->tipo == 0) {
                    $salida .= '<a data-toggle="tooltip" data-placement="top" title="Descargar recibo de traslado de fondo" target="_blank" href="' . url('descargarIngresoEfectivo', $flujo->id) . '" 
                                class="btn btn-default">
                                <i class="fa fa-download"></i>
                            </a>';
                }
                return $salida;
            })->make(true);
    }
}
