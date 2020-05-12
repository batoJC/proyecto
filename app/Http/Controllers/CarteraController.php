<?php

namespace App\Http\Controllers;

use Auth;
use PDF;
use DB;
use App\Acta;
use App\User;
use App\Conjunto;
use App\Tipo_unidad;
use App\Cartera;
use Yajra\Datatables\Datatables;
use App\Consecutivos;
use App\Unidad;
use Illuminate\Http\Request;

class CarteraController extends Controller
{
    //
    public function index()
    {
        session(['section' => 'carteras']);
        //listar unidades y propietarios
        $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
        $propietarios = User::where('id_conjunto', session('conjunto'))->get();
        $user       = User::where('id_conjunto', session('conjunto'))->get();
        $conjuntos  = Conjunto::find(session('conjunto'));
        // $actas      = Acta::where('id_conjunto', session('conjunto'))->get();
        return view('admin.carteras.index')
            ->with('user', $user)
            ->with('unidades', $unidades)
            ->with('propietarios', $propietarios)
            ->with('conjuntos', $conjuntos);
        //    ->with('actas', $actas);
    }

    public function exportar()
    {
        session(['section' => 'carteras']);
        $user       = User::where('id_conjunto', session('conjunto'))->get();
        $conjuntos  = Conjunto::find(session('conjunto'));
        $consecutivos = Consecutivos::where('id_conjunto', session('conjunto'))->get();
        $actas      = Acta::where('id_conjunto', session('conjunto'))->get();
        return view('admin.carteras.exportar')
            ->with('user', $user)
            ->with('consecutivos', $consecutivos)
            ->with('conjuntos', $conjuntos)
            ->with('actas', $actas);
    }


    public function detalleCartera($unidadId)
    {
        $carteras = Cartera::where('unidad_id', $unidadId)->get();
        $salida = array();
        foreach ($carteras as $detalle) {
            $movimiento = "";
            switch ($detalle->tipo_de_movimiento) { //movimiento
                case 1:
                    $movimiento = "Consignación  de dinero";
                    $detalle->capital *= -1;
                    break;
                case 2:
                    $movimiento = "Cuota de Administración";
                    break;
                case 3:
                    $movimiento = $detalle->infoMovimiento()->tipo_cuota_extraordinaria;
                    break;
                case 4:
                    $movimiento = $detalle->infoMovimiento()->descripcion;
                    break;
                case 5:
                    $movimiento = $detalle->infoMovimiento()->descripcion;
                    break;
            }

            $aux = array(
                'id' => $detalle->id,
                'cuenta' => $detalle->prefijo . $detalle->numero,
                'user' => $detalle->user->nombre_completo,
                'fecha' => $detalle->fecha,
                'movimiento' => $movimiento,
                'capital' => $detalle->capital,
                'interes' => $detalle->interes
            );
            $salida[] = $aux;
        }
        return $salida;
    }

    public function detallePorCuenta($prefijo, $numero)
    {
        $carteras = Cartera::where([
            ['prefijo', $prefijo],
            ['numero', $numero]
        ])->get();
        $salida = array();
        foreach ($carteras as $detalle) {
            $movimiento = "";
            switch ($detalle->tipo_de_movimiento) { //movimiento
                case 1:
                    $movimiento = "Consignación  de dinero";
                    $detalle->capital *= -1;
                    break;
                case 2:
                    $movimiento = "Cuota de Administración";
                    break;
                case 3:
                    $movimiento = $detalle->infoMovimiento()->tipo_cuota_extraordinaria;
                    break;
                case 4:
                    $movimiento = $detalle->infoMovimiento()->descripcion;
                    break;
                case 5:
                    $movimiento = $detalle->infoMovimiento()->descripcion;
                    break;
            }

            $aux = array(
                'id' => $detalle->id,
                'cuenta' => $detalle->prefijo . $detalle->numero,
                'fecha' => $detalle->fecha,
                'user' => $detalle->user->nombre_completo,
                'movimiento' => $movimiento,
                'capital' => number_format($detalle->capital),
                'interes' => number_format($detalle->interes)
            );
            $salida[] = $aux;
        }
        return $salida;
    }

    public function generarPDF($prefijo, $numero)
    {
        $datos = array();
        $datos['cuentas'] = $this->detallePorCuenta($prefijo, $numero);
        $cartera = Cartera::where([
            ['prefijo', $prefijo],
            ['numero', $numero]
        ])->first();
        $datos['fecha'] = $cartera->fecha;
        $datos['cuenta'] = $prefijo . $numero;
        $datos['unidad'] = $cartera->unidad;
        $pdf = PDF::loadView('admin.PDF.comprobante', $datos);
        return $pdf->stream();
    }

    //eliminar un movimiento registrado en cartera
    public function eliminar($id)
    {
        $operacion = Cartera::find($id);
        $unidadId = $operacion->unidad->id;
        $tipo = $operacion->tipo_de_movimiento;
        $cuota = $operacion->infoMovimiento();
        $operacion->delete();
        switch ($tipo) {
            case 2:
                $cuota = cuota_ord_x_tipo_unidad::where([
                    ['unidad_id', $unidadId],
                    ['cuota_id', $cuota->id]
                ])
                    ->first();
                $cuota->pago = 0;
                $cuota->save();
            case 3:
                $cuota = cuota_ext_x_tipo_unidad::where([
                    ['unidad_id', $unidadId],
                    ['cuota_id', $cuota->id]
                ])
                    ->first();
                $cuota->pago = 0;
                $cuota->save();
            case 4:
                $cuota = otros_x_tipo_unidad::where([
                    ['unidad_id', $unidadId],
                    ['cuota_id', $cuota->id]
                ])
                    ->first();
                $cuota->pago = 0;
                $cuota->save();
            case 5:
                $cuota->estado = 0;
                $cuota->save();
        }
        return 1;
    }


    public function verMiCartera()
    {   
        session(['section' => 'miCartera']);


        $propietario = Auth::user();

        $saldo = $propietario->saldo();

        return view('dueno.cartera')
            ->with('saldo', $saldo);
    }

    public function miCarteraDatatable()
    {
        $propietario = Auth::user();

        $detalles = Cartera::where([['user_id', $propietario->id]])->orderBy('fecha', 'DESC')->get();
        return Datatables::of($detalles)
            ->addColumn('fecha',function($detalle){
                return date('d-m-Y',strtotime($detalle->fecha));
            })->addColumn('valor',function($detalle){
                return '$ '.number_format($detalle->valor);
            })->addColumn('unidad',function($detalle){
                return ($detalle->unidad)? $detalle->unidad->tipo->nombre.' '.$detalle->unidad->numero_letra : 'No Aplica';
            })->addColumn('propietario',function($detalle){
                return ($detalle->propietario) ? $detalle->propietario->nombre_completo : 'No Aplica';
            })->addColumn('usuario',function($detalle){
                return $detalle->usuario->nombre_completo;
            })->addColumn('action', function ($detalle) {
                return '<a target="_blanck" 
                            href="' . url('pdfRecaudoC', ['consecutivo' => $detalle->prefijo . '-' . $detalle->numero]) . '">
                            <i class="fa fa-eye"></i>
                            '.$detalle->prefijo.' '.$detalle->numero.
                        '</a>';
            })->make(true);
    }

    public function consultarCartera(Request $request)
    {
        $detalles = array();
        if ($request->unidad_id && $request->user_id) {
            $detalles = Cartera::where([
                ['user_id', $request->user_id],
                ['unidad_id', $request->unidad_id]
            ])->get();
        } else if ($request->unidad_id) {
            $detalles = Cartera::where('unidad_id', $request->unidad_id)->get();
        } else if ($request->user_id) {
            $detalles = Cartera::where('user_id', $request->user_id)->get();
        }
        return view('admin.carteras.detalles')->with('detalles', $detalles);
    }
}
