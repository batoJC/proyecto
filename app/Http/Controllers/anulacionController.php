<?php

namespace App\Http\Controllers;

use App\Cartera;
use App\Conjunto;
use App\Consecutivos;
use App\CuentaBancaria;
use App\CuentaCobro;
use App\Cuota_admon;
use App\Cuota_extOrd;
use App\DetalleCuentaCobro;
use App\DetalleRecaudo;
use App\Multa;
use App\Otros_cobros;
use App\Recaudo;
use App\saldoInicial;
use App\Unidad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class anulacionController extends Controller
{
    //

    function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     dd($request);
        //     // return $next($request);
        // });
    }

    public function anularCuentaCobro(CuentaCobro $cuenta)
    {
        $conjuntos = Conjunto::find(session('conjunto'));

        if ($cuenta->conjunto_id != $cuenta->conjunto_id) {
            return view('errors.404');
        }

        $cuentas = CuentaCobro::where([
            ['fecha', '>=', $cuenta->fecha],
            ['propietario_id', $cuenta->propietario_id],
            ['anulada', false],
        ])->get();

        return view('admin.anular.index')
            ->with('cuentas', $cuentas)
            ->with('conjuntos', $conjuntos);
    }

    public function anularRecaudo(Recaudo $recaudo)
    {
        return $this->anularCuentaCobro(CuentaCobro::find($recaudo->cuenta_cobro_id));
    }

    public function loadProceso(CuentaCobro $cuenta)
    {
        // dd($cuenta->propietario);
        if ($cuenta->conjunto_id != $cuenta->conjunto_id) {
            return 'No esta permitido.';
        }
        return view('admin.anular.proceso')->with('cuenta', $cuenta);
    }

    public function newCuenta(CuentaCobro $cuenta)
    {
        $consecutivos = Consecutivos::where('conjunto_id', session('conjunto'))->get();

        return view('admin.anular.change_cuenta')
            ->with('consecutivos', $consecutivos)
            ->with('cuenta', $cuenta);
    }

    public function newRecaudo(Recaudo $recaudo)
    {
        $consecutivos = Consecutivos::where('conjunto_id', session('conjunto'))->get();
        $bancos = CuentaBancaria::where('conjunto_id', session('conjunto'))->get();

        return view('admin.anular.change_recaudo')
            ->with('consecutivos', $consecutivos)
            ->with('bancos', $bancos)
            ->with('recaudo', $recaudo);
    }

    public function addRecaudo(CuentaCobro $cuenta)
    {
        $consecutivos = Consecutivos::where('conjunto_id', session('conjunto'))->get();
        $bancos = CuentaBancaria::where('conjunto_id', session('conjunto'))->get();

        return view('admin.anular.change_recaudo')
            ->with('consecutivos', $consecutivos)
            ->with('bancos', $bancos)
            ->with('recaudo', null)
            ->with('cuenta', $cuenta);
    }

    public function cuenta(Request $request)
    {
        $consecutivo = Consecutivos::find($request->consecutivo);

        $propietario = User::find($request->propietario);
        $cuentas = null;

        $data = $propietario->cuentas($request->fecha);
        if (count($data) >= 0) {
            $tipo_cobro = 'normal';
            $mayor = 0;

            foreach ($data as $cuenta) {
                $dias = date_diff(date_create($cuenta['vigencia_inicio']), date_create($request->fecha))->format('%R%a');
                if ($dias > $mayor) {
                    $mayor = $dias;
                }
            }

            if ($mayor >= 90) {
                $tipo_cobro = 'juridico';
            } else if ($mayor >= 60) {
                $tipo_cobro = 'pre-juridico';
            }


            $cuentas = array(
                'consecutivo' => $consecutivo->prefijo . '-' . $consecutivo->numero,
                'propietario' => $propietario,
                'cuentas' => $data,
                'tipo_cobro' => $tipo_cobro
            );
        }

        $recaudo = Recaudo::where([
            ['anulada',false],
            ['propietario_id',$request->propietario],
            ['fecha', $request->fecha]
        ])->first();

        $datos  = array(
            'fecha' => $request->fecha,
            'fecha_pronto_pago' => $request->fecha_pronto_pago,
            'descuento' => $request->descuento,
        );

        return view('admin.anular.cuenta')->with('datos', $datos)->with('cuenta', $cuentas)->with('recaudo', $recaudo);
    }

    public function recaudo(Recaudo $recaudo, Request $request)
    {
        $cuenta = $recaudo->cuentaCobro;
        if ($cuenta->anulada) {
            $cuenta = $cuenta->reemplaza;
        }

        // $fecha = date("d-m-Y", strtotime($recaudo->fecha . "- 1 days"));
        $fecha = $request->fecha_recaudo;

        return view('admin.anular.recaudo')->with('cuenta', $cuenta)->with('fecha', $fecha)->with('nueva', false);
    }

    public function recaudoAdd(CuentaCobro $cuenta)
    {

        $fecha = date("d-m-Y");
        return view('admin.anular.recaudo')->with('cuenta', $cuenta)->with('fecha', $fecha)->with('nueva', true);
    }


    public function reemplazarCuenta(CuentaCobro $cuentaA, Request $request)
    {

        // return $cuentaA;
        //generar cuenta de cobro
        $consecutivo = Consecutivos::find($request->consecutivo);

        $propietario = User::find($request->propietario);
        $cuentas = null;

        $data = $propietario->cuentas($request->fecha);
        if (count($data) > 0) {
            $tipo_cobro = 'normal';
            $mayor = 0;

            foreach ($data as $cuenta) {
                $dias = date_diff(date_create($cuenta['vigencia_inicio']), date_create($request->fecha))->format('%R%a');
                if ($dias > $mayor) {
                    $mayor = $dias;
                }
            }

            if ($mayor >= 90) {
                $tipo_cobro = 'juridico';
            } else if ($mayor >= 60) {
                $tipo_cobro = 'pre-juridico';
            }


            $cuentas = array(
                'consecutivo' => $consecutivo->prefijo . '-' . $consecutivo->numero,
                'propietario' => $propietario,
                'cuentas' => $data,
                'tipo_cobro' => $tipo_cobro
            );

            $auxCuentaCobro = new CuentaCobro();
            try {
                $auxCuentaCobro->consecutivo = $cuentas['consecutivo'];
                $auxCuentaCobro->fecha = $request->fecha;
                $auxCuentaCobro->fecha_pronto_pago = $request->fecha_pronto_pago;
                $auxCuentaCobro->descuento = $request->descuento;
                $auxCuentaCobro->propietario_id = $cuentas['propietario']->id;
                $auxCuentaCobro->tipo_cobro = $cuentas['tipo_cobro'];
                $auxCuentaCobro->saldo_favor = $propietario->saldo($request->fecha);
                $auxCuentaCobro->conjunto_id = session('conjunto');
                if ($auxCuentaCobro->save()) {
                    $cuentaA->anulada = true;
                    $cuentaA->motivo = $request->motivo;
                    $cuentaA->fecha_anulado = date('Y-m-d');
                    $cuentaA->reemplazo_cuenta_id = $auxCuentaCobro->id;
                    $cuentaA->save();

                    foreach ($cuentas['cuentas'] as $detalle) {
                        $auxDetalle = new DetalleCuentaCobro();
                        $detalle['cobro_id'] = $auxCuentaCobro->id;
                        $auxDetalle->fill($detalle);
                        $auxDetalle->save();
                    }
                    $consecutivo->numero++;
                    $consecutivo->save();
                }

                return array('res' => 1, 'msg' => 'Se anulo y reemplazo la cuenta de cobro correctamente.');
            } catch (\Throwable $th) {
                $auxCuentaCobro->delete();
                $cuentaA->anulada = false;
                $cuentaA->motivo = null;
                $cuentaA->fecha_anulado = null;
                $cuentaA->reemplazo_cuenta_id = null;
                $cuentaA->save();
                return array('res' => 1, 'msg' => 'Ocurrió un error al reemplazar la cuenta de cobro.');
            }
        }
    }

    public function reemplazarRecaudo(Recaudo $recaudoA, Request $request)
    {
        $consecutivo = explode('-', $recaudoA->consecutivo);

        $movimientos_cartera = Cartera::where([
            ['prefijo', $consecutivo[0]],
            ['numero', $consecutivo[1]],
            ['user_id', $recaudoA->propietario_id]
        ])->get();

        //guardar recaudo
        $recaudo = new Recaudo();
        $detalles_cartera = array();

        $cuenta = CuentaCobro::find($request->cuenta);

        if (!$cuenta) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al guardar el recaudo.');
        }

        try {

            $fecha = $cuenta->fecha;

            if ((date_diff(date_create($fecha), date_create($request->fecha))->format('%R%a') < 0)) {
                return array('res' => 0, 'msg' => 'La fecha seleccionada es anterior a la generación de la cuenta de cobro.');
            }

            $recaudo->fecha = $request->fecha;

            //consultar consecutivo
            //---------------------
            $consecutivo = Consecutivos::find($request->consecutivo_recaudo);

            $recaudo->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $recaudo->tipo_de_pago = $request->tipo_de_pago;
            $recaudo->propietario_id = $request->propietario_recaudo;
            $recaudo->banco = ($request->banco == '') ? null : $request->banco;
            $recaudo->saldo_favor = 0;
            $recaudo->valor = $request->valor;
            $recaudo->cuenta_cobro_id = $request->cuenta;
            $recaudo->conjunto_id = session('conjunto');

            if ($recaudo->save()) {

                $aux = explode(';', $request->pagos);
                foreach ($aux as $data) {
                    $pago = json_decode($data);

                    $detalle_cuentas_cobro = DetalleCuentaCobro::find($pago->detalle);

                    $detalle = new DetalleRecaudo();
                    $detalle->concepto = $detalle_cuentas_cobro->concepto;
                    $presupuesto_id = null;

                    if ($pago->tipo == 'valor') {

                        switch ($detalle_cuentas_cobro->tipo) {
                            case 'saldo_inicial':
                                $detalle->tipo_de_cuota = 'Saldo Inicial';
                                $cuota = saldoInicial::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'administracion':
                                $detalle->tipo_de_cuota = 'Cuota Administrativa';
                                $cuota = Cuota_admon::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'extraordinaria':
                                $detalle->tipo_de_cuota = 'Cuota Extraordinaria';
                                $cuota = Cuota_extOrd::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'otro_cobro':
                                $detalle->tipo_de_cuota = 'Otro Cobro';
                                $cuota = Otros_cobros::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'multa':
                                $detalle->tipo_de_cuota = 'Multa';
                                $cuota = Multa::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                        }
                    } else if ($pago->tipo == 'interes') {
                        switch ($detalle_cuentas_cobro->tipo) {
                            case 'saldo_inicial':
                                $detalle->tipo_de_cuota = 'Interes Saldo Inicial';
                                break;
                            case 'administracion':
                                $detalle->tipo_de_cuota = 'Interes Cuota Administrativa';
                                break;
                            case 'extraordinaria':
                                $detalle->tipo_de_cuota = 'Interes Cuota Extraordinaria';
                                break;
                            case 'otro_cobro':
                                $detalle->tipo_de_cuota = 'Interes Otro Cobro';
                                break;
                            case 'multa':
                                $detalle->tipo_de_cuota = 'Interes Multa';
                                break;
                        }
                    }

                    $detalle->cuenta_id = $detalle_cuentas_cobro->cuota_id;
                    $detalle->recaudo_id = $recaudo->id;
                    $detalle->unidad_id = $detalle_cuentas_cobro->unidad_id;
                    $detalle->presupuesto_id = $presupuesto_id;
                    $detalle->valor = $pago->valor;


                    if ($detalle->save()) {
                        $cartera = new Cartera();
                        $cartera->prefijo = $consecutivo->prefijo;
                        $cartera->numero = $consecutivo->numero;
                        $cartera->fecha = $recaudo->fecha;
                        $cartera->valor = $pago->valor;
                        $cartera->tipo_de_pago = $request->tipo_de_pago;
                        $cartera->tipo_de_cuota = $detalle->tipo_de_cuota;
                        $cartera->movimiento = $detalle_cuentas_cobro->cuota_id;
                        $cartera->user_register_id = Auth::user()->id;
                        $cartera->user_id = $request->propietario_recaudo;
                        $cartera->unidad_id = $detalle_cuentas_cobro->unidad_id;
                        $cartera->presupuesto_individual_id = $presupuesto_id;
                        $cartera->save();
                        $detalles_cartera[] = $cartera;
                    }
                }
                $consecutivo->numero++;
                $consecutivo->save();


                //eliminar de la cartera los recaudos
                foreach ($movimientos_cartera as $movimiento) {
                    switch ($movimiento->tipo_de_cuota) {
                        case 'Cuota Administrativa':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasAdministracion->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Cuota Extraordinaria':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasExtraordinarias->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Otro Cobro':
                            $cuota = Otros_cobros::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Multa':
                            $cuota = Multa::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Saldo Inicial':
                            $cuota = saldoInicial::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Interes Cuota Administrativa':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasAdministracion->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Interes Cuota Extraordinaria':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasExtraordinarias->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Interes Otro Cobro':
                            $cuota = Otros_cobros::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Interes Multa':
                            $cuota = Multa::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Interes Saldo Inicial':
                            $cuota = saldoInicial::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                    }
                    $movimiento->delete();
                }

                //anular el recaudo
                $recaudoA->anulada = true;
                $recaudoA->fecha_anulacion = date('Y-m-d');
                $recaudoA->motivo = $request->motivo_recaudo;
                $recaudoA->reemplazo_recaudo_id = $recaudo->id;
                $recaudoA->save();

                $recaudo->saldo_favor = User::find($request->propietario_recaudo)->saldo($recaudo->fecha);
                $recaudo->save();

                return array('res' => 1, 'msg' => 'Recaudo guardado correctamente.', 'pago' => $recaudo);
            }
        } catch (\Throwable $th) {
            $recaudo->delete();
            foreach ($detalles_cartera as $detalle) {
                $detalle->delete();
            }

            //reestalecer el recaudo
            $recaudoA->anulada = false;
            $recaudoA->fecha_anulacion = null;
            $recaudoA->motivo = null;
            $recaudoA->reemplazo_recaudo_id = null;
            $recaudoA->save();

            //agregar a la cartera los recaudos
            foreach ($movimientos_cartera as $movimiento) {
                $movimiento->save();
            }
            return array('res' => 0, 'msg' => 'Ocurrió un error al guardar el recaudo.', 'e' => $th);
        }
    }

    public function add_Recaudo(Request $request)
    {


        //guaradr recaudo
        $recaudo = new Recaudo();
        $detalles_cartera = array();

        $cuenta = CuentaCobro::find($request->cuenta);

        if (!$cuenta) {
            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.');
        }

        try {

            $fecha = $cuenta->fecha;

            if ((date_diff(date_create($fecha), date_create($request->fecha))->format('%R%a') < 0)) {
                return array('res' => 0, 'msg' => 'La fecha seleccionada es anterior a la generación de la cuenta de cobro.');
            }

            $recaudo->fecha = $request->fecha;

            //consultar consecutivo
            //---------------------
            $consecutivo = Consecutivos::find($request->consecutivo_recaudo);

            $recaudo->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $recaudo->tipo_de_pago = $request->tipo_de_pago;
            $recaudo->propietario_id = $request->propietario_recaudo;
            $recaudo->banco = ($request->banco == '') ? null : $request->banco;
            $recaudo->saldo_favor = 0;
            $recaudo->valor = $request->valor;
            $recaudo->cuenta_cobro_id = $request->cuenta;
            $recaudo->conjunto_id = session('conjunto');

            if ($recaudo->save()) {

                $aux = explode(';', $request->pagos);
                foreach ($aux as $data) {
                    $pago = json_decode($data);

                    $detalle_cuentas_cobro = DetalleCuentaCobro::find($pago->detalle);

                    $detalle = new DetalleRecaudo();
                    $detalle->concepto = $detalle_cuentas_cobro->concepto;
                    $presupuesto_id = null;

                    if ($pago->tipo == 'valor') {

                        switch ($detalle_cuentas_cobro->tipo) {
                            case 'saldo_inicial':
                                $detalle->tipo_de_cuota = 'Saldo Inicial';
                                $cuota = saldoInicial::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'administracion':
                                $detalle->tipo_de_cuota = 'Cuota Administrativa';
                                $cuota = Cuota_admon::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'extraordinaria':
                                $detalle->tipo_de_cuota = 'Cuota Extraordinaria';
                                $cuota = Cuota_extOrd::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'otro_cobro':
                                $detalle->tipo_de_cuota = 'Otro Cobro';
                                $cuota = Otros_cobros::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                            case 'multa':
                                $detalle->tipo_de_cuota = 'Multa';
                                $cuota = Multa::find($detalle_cuentas_cobro->cuota_id);
                                $presupuesto_id = $cuota->presupuesto_cargar_id;
                                break;
                        }
                    } else if ($pago->tipo == 'interes') {
                        switch ($detalle_cuentas_cobro->tipo) {
                            case 'saldo_inicial':
                                $detalle->tipo_de_cuota = 'Interes Saldo Inicial';
                                break;
                            case 'administracion':
                                $detalle->tipo_de_cuota = 'Interes Cuota Administrativa';
                                break;
                            case 'extraordinaria':
                                $detalle->tipo_de_cuota = 'Interes Cuota Extraordinaria';
                                break;
                            case 'otro_cobro':
                                $detalle->tipo_de_cuota = 'Interes Otro Cobro';
                                break;
                            case 'multa':
                                $detalle->tipo_de_cuota = 'Interes Multa';
                                break;
                        }
                    }

                    $detalle->cuenta_id = $detalle_cuentas_cobro->cuota_id;
                    $detalle->recaudo_id = $recaudo->id;
                    $detalle->unidad_id = $detalle_cuentas_cobro->unidad_id;
                    $detalle->presupuesto_id = $presupuesto_id;
                    $detalle->valor = $pago->valor;


                    if ($detalle->save()) {
                        $cartera = new Cartera();
                        $cartera->prefijo = $consecutivo->prefijo;
                        $cartera->numero = $consecutivo->numero;
                        $cartera->fecha = $recaudo->fecha;
                        $cartera->valor = $pago->valor;
                        $cartera->tipo_de_pago = $request->tipo_de_pago;
                        $cartera->tipo_de_cuota = $detalle->tipo_de_cuota;
                        $cartera->movimiento = $detalle_cuentas_cobro->cuota_id;
                        $cartera->user_register_id = Auth::user()->id;
                        $cartera->user_id = $request->propietario_recaudo;
                        $cartera->unidad_id = $detalle_cuentas_cobro->unidad_id;
                        $cartera->presupuesto_individual_id = $presupuesto_id;
                        $cartera->save();
                        $detalles_cartera[] = $cartera;
                    }
                }
                $consecutivo->numero++;
                $consecutivo->save();
                $recaudo->saldo_favor = User::find($request->propietario_recaudo)->saldo($recaudo->fecha);
                $recaudo->save();

                return array('res' => 1, 'msg' => 'Recaudo guardado correctamente.', 'pago' => $recaudo);
            }
        } catch (\Throwable $th) {
            $recaudo->delete();
            foreach ($detalles_cartera as $detalle) {
                $detalle->delete();
            }
            //  return $th;   
            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.', 'e' => $th);
        }
    }

    public function anularPagosRecaudo(Recaudo $recaudo)
    {
        $consecutivo = explode('-', $recaudo->consecutivo);

        $movimientos_cartera = Cartera::where([
            ['prefijo', $consecutivo[0]],
            ['numero', $consecutivo[1]],
            ['user_id', $recaudo->propietario_id]
        ])->get();
        try {

            //eliminar de la cartera los recaudos
            foreach ($movimientos_cartera as $movimiento) {
                switch ($movimiento->tipo_de_cuota) {
                    case 'Cuota Administrativa':
                        $unidad = Unidad::find($movimiento->unidad_id);
                        $cuota = $unidad->cuotasAdministracion->find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                        }
                        break;
                    case 'Cuota Extraordinaria':
                        $unidad = Unidad::find($movimiento->unidad_id);
                        $cuota = $unidad->cuotasExtraordinarias->find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                        }
                        break;
                    case 'Otro Cobro':
                        $cuota = Otros_cobros::find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->estado = 'No pago';
                            $cuota->save();
                        }
                        break;
                    case 'Multa':
                        $cuota = Multa::find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->estado = 'No pago';
                            $cuota->save();
                        }
                        break;
                    case 'Saldo Inicial':
                        $cuota = saldoInicial::find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->estado = 'No pago';
                            $cuota->save();
                        }
                        break;
                    case 'Interes Cuota Administrativa':
                        $unidad = Unidad::find($movimiento->unidad_id);
                        $cuota = $unidad->cuotasAdministracion->find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                        }
                        break;
                    case 'Interes Cuota Extraordinaria':
                        $unidad = Unidad::find($movimiento->unidad_id);
                        $cuota = $unidad->cuotasExtraordinarias->find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                        }
                        break;
                    case 'Interes Otro Cobro':
                        $cuota = Otros_cobros::find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->estado = 'No pago';
                            $cuota->save();
                        }
                        break;
                    case 'Interes Multa':
                        $cuota = Multa::find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->estado = 'No pago';
                            $cuota->save();
                        }
                        break;
                    case 'Interes Saldo Inicial':
                        $cuota = saldoInicial::find($movimiento->movimiento);
                        if ($cuota) {
                            $cuota->estado = 'No pago';
                            $cuota->save();
                        }
                        break;
                }
                $movimiento->delete();
            }

            //anular el recaudo
            $recaudo->anulada = true;
            $recaudo->fecha_anulacion = date('Y-m-d');
            $recaudo->save();
            return array('res' => 1, 'msg' => 'Proceso terminado con éxito.');
        } catch (\Throwable $th) {
            //agregar a la cartera los recaudos
            foreach ($movimientos_cartera as $movimiento) {
                $movimiento->save();
            }

            //reestalecer el recaudo
            $recaudo->anulada = false;
            $recaudo->fecha_anulacion = null;
            $recaudo->motivo = null;
            $recaudo->reemplazo_recaudo_id = null;
            $recaudo->save();
            return array('res' => 0, 'msg' => 'Ocurrió un error en el servidor.', 'e' => $th);
        }
    }

    public function restablecerPagosRecaudo(Recaudo $recaudo)
    {

        $detalles_cartera = array();
        try {
            $consecutivo = explode('-', $recaudo->consecutivo);


            foreach ($recaudo->detalles as $detalle) {
                $cartera = new Cartera();
                $cartera->prefijo = $consecutivo[0];
                $cartera->numero = $consecutivo[1];
                $cartera->fecha = $recaudo->fecha;
                $cartera->valor = $detalle->valor;
                $cartera->tipo_de_pago = $recaudo->tipo_de_pago;
                $cartera->tipo_de_cuota = $detalle->tipo_de_cuota;
                $cartera->movimiento = $detalle->cuenta_id;
                $cartera->user_register_id = Auth::user()->id;
                $cartera->user_id = $recaudo->propietario_id;
                $cartera->unidad_id = $detalle->unidad_id;
                $cartera->presupuesto_individual_id = $detalle->presupuesto_id;
                $cartera->save();
                $detalles_cartera[] = $cartera;
            }

            //reestalecer el recaudo
            $recaudo->anulada = false;
            $recaudo->fecha_anulacion = null;
            $recaudo->save();


            return array('res' => 1, 'msg' => 'Proceso terminado con éxito.');
        } catch (\Throwable $th) {
            foreach ($detalles_cartera as $detalle) {
                $detalle->delete();
            }
            return array('res' => 0, 'msg' => 'Ocurrió un error en el servidor.');
        }
    }


    //pagar pronto pago del recaudo que locha
    public function addProntoPago(Request $request)
    {
        //
        $recaudo = new Recaudo();
        $detalles_cartera = array();
        $cuotas = array();

        $cuenta = CuentaCobro::find($request->cuenta);


        try {

            $fecha = $cuenta->fecha;

            if ((date_diff(date_create($fecha), date_create($request->fecha_recaudo))->format('%R%a') < 0)) {
                return array('res' => 0, 'msg' => 'La fecha seleccionada es anterior a la última generación de cuentas de cobro.');
            }

            $recaudo->fecha = $request->fecha_recaudo;

            //consultar consecutivo
            //---------------------
            $consecutivo = Consecutivos::find($request->consecutivo_recaudo);

            $recaudo->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $recaudo->pronto_pago = true;
            $recaudo->tipo_de_pago = $request->tipo_de_pago;
            $recaudo->propietario_id = $request->propietario_recaudo;
            $recaudo->saldo_favor = 0;
            $recaudo->valor = $request->valor;
            $recaudo->banco = ($request->banco == '') ? null : $request->banco;
            $recaudo->cuenta_cobro_id = $request->cuenta;
            $recaudo->conjunto_id = session('conjunto');

            //TODO: pronto pago
            if ($recaudo->save()) {

                foreach ($cuenta->detalles as $detalle_cuenta) {
                    //elegir 'administracion','extraordinaria','otro_cobro','multa','saldo_inicial'
                    $detalle = new DetalleRecaudo();
                    $detalle->concepto = $detalle_cuenta->concepto;
                    $detalle->cuenta_id = $detalle_cuenta->cuota_id;
                    $detalle->recaudo_id = $recaudo->id;
                    $detalle->unidad_id = $detalle_cuenta->unidad_id;
                    $detalle->valor = $detalle_cuenta->valor * (1 - ($cuenta->descuento / 100));
                    $cuota = null;
                    switch ($detalle_cuenta->tipo) {
                        case 'saldo_inicial':
                            $detalle->tipo_de_cuota = 'Saldo Inicial';
                            $cuota = saldoInicial::find($detalle_cuenta->cuota_id);
                            $cuota->estado = 'Pronto pago';
                            $cuota->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'saldo_inicial');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'administracion':
                            $detalle->tipo_de_cuota = 'Cuota Administrativa';
                            $unidad = Unidad::find($detalle_cuenta->unidad_id);
                            $cuota = $unidad->cuotasAdministracion->find($detalle_cuenta->cuota_id);
                            $cuota->pivot->estado = 'Pronto pago';
                            $cuota->pivot->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'administracion');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'extraordinaria':
                            $detalle->tipo_de_cuota = 'Cuota Extraordinaria';
                            $unidad = Unidad::find($detalle_cuenta->unidad_id);
                            $cuota = $unidad->cuotasExtraordinarias->find($detalle_cuenta->cuota_id);
                            $cuota->pivot->estado = 'Pronto pago';
                            $cuota->pivot->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'extraordinaria');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'otro_cobro':
                            $detalle->tipo_de_cuota = 'Otro Cobro';
                            $cuota = Otros_cobros::find($detalle_cuenta->cuota_id);
                            $cuota->estado = 'Pronto pago';
                            $cuota->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'otro_cobro');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'multa':
                            $detalle->tipo_de_cuota = 'Multa';
                            $cuota = Multa::find($detalle_cuenta->cuota_id);
                            $cuota->estado = 'Pronto pago';
                            $cuota->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'multa');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                    }

                    $detalle->presupuesto_id = $presupuesto_id;


                    if ($detalle->save()) {
                        $cartera = new Cartera();
                        $cartera->prefijo = $consecutivo->prefijo;
                        $cartera->numero = $consecutivo->numero;
                        $cartera->fecha = $recaudo->fecha;
                        $cartera->valor = $detalle->valor;
                        $cartera->tipo_de_pago = $request->tipo_de_pago;
                        $cartera->tipo_de_cuota = $detalle->tipo_de_cuota;
                        $cartera->movimiento = $detalle_cuenta->cuota_id;
                        $cartera->user_register_id = Auth::user()->id;
                        $cartera->user_id = $request->propietario_recaudo;
                        $cartera->unidad_id = $detalle_cuenta->unidad_id;
                        $cartera->presupuesto_individual_id = $presupuesto_id;
                        $cartera->save();
                        $detalles_cartera[] = $cartera;
                    }
                }

                $consecutivo->numero++;
                $consecutivo->save();
                $recaudo->save();
                $recaudo->saldo_favor = User::find($request->propietario_recaudo)->saldo($recaudo->fecha);
                $recaudo->save();

                return array('res' => 1, 'msg' => 'Recaudo guardado correctamente.', 'pago' => $recaudo);
            }
        } catch (\Throwable $th) {
            $recaudo->delete();
            foreach ($detalles_cartera as $detalle) {
                $detalle->delete();
            }

            foreach ($cuotas as $cuota) {
                if ($cuota) {
                    if ($cuota['tipo'] == 'administracion' || $cuota['tipo'] == 'extraordinaria') {
                        $cuota->pivot->valor = 'No pago';
                        $cuota->pivot->save();
                    } else {
                        $cuota['cuota']->estado = 'No pago';
                        $cuota['cuota']->save();
                    }
                }
            }

            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.', 'e' => $th);
        }
    }

    public function reemplazarProntoPago(Recaudo $recaudoA, Request $request)
    {
        //

        $consecutivo = explode('-', $recaudoA->consecutivo);

        $movimientos_cartera = Cartera::where([
            ['prefijo', $consecutivo[0]],
            ['numero', $consecutivo[1]],
            ['user_id', $recaudoA->propietario_id]
        ])->get();

        $recaudo = new Recaudo();
        $detalles_cartera = array();
        $cuotas = array();

        $cuenta = CuentaCobro::find($request->cuenta);

        try {

            $fecha = $cuenta->fecha;

            if ((date_diff(date_create($fecha), date_create($request->fecha_recaudo))->format('%R%a') < 0)) {
                return array('res' => 0, 'msg' => 'La fecha seleccionada es anterior a la última generación de cuentas de cobro.');
            }

            $recaudo->fecha = $request->fecha_recaudo;

            //consultar consecutivo
            //---------------------
            $consecutivo = Consecutivos::find($request->consecutivo_recaudo);

            $recaudo->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $recaudo->tipo_de_pago = $request->tipo_de_pago;
            $recaudo->pronto_pago = true;
            $recaudo->propietario_id = $request->propietario_recaudo;
            $recaudo->saldo_favor = 0;
            $recaudo->valor = $request->valor;
            $recaudo->banco = ($request->banco == '') ? null : $request->banco;
            $recaudo->cuenta_cobro_id = $request->cuenta;
            $recaudo->conjunto_id = session('conjunto');

            //TODO: pronto pago
            if ($recaudo->save()) {

                //eliminar de la cartera los recaudos
                foreach ($movimientos_cartera as $movimiento) {
                    switch ($movimiento->tipo_de_cuota) {
                        case 'Cuota Administrativa':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasAdministracion->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Cuota Extraordinaria':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasExtraordinarias->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Otro Cobro':
                            $cuota = Otros_cobros::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Multa':
                            $cuota = Multa::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Saldo Inicial':
                            $cuota = saldoInicial::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Interes Cuota Administrativa':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasAdministracion->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Interes Cuota Extraordinaria':
                            $unidad = Unidad::find($movimiento->unidad_id);
                            $cuota = $unidad->cuotasExtraordinarias->find($movimiento->movimiento);
                            $cuota->pivot->estado = 'No pago';
                            $cuota->pivot->save();
                            break;
                        case 'Interes Otro Cobro':
                            $cuota = Otros_cobros::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Interes Multa':
                            $cuota = Multa::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                        case 'Interes Saldo Inicial':
                            $cuota = saldoInicial::find($movimiento->movimiento);
                            $cuota->estado = 'No pago';
                            $cuota->save();
                            break;
                    }
                    $movimiento->delete();
                }


                foreach ($cuenta->detalles as $detalle_cuenta) {
                    //elegir 'administracion','extraordinaria','otro_cobro','multa','saldo_inicial'
                    $detalle = new DetalleRecaudo();
                    $detalle->concepto = $detalle_cuenta->concepto;
                    $detalle->cuenta_id = $detalle_cuenta->cuota_id;
                    $detalle->recaudo_id = $recaudo->id;
                    $detalle->unidad_id = $detalle_cuenta->unidad_id;
                    $detalle->valor = $detalle_cuenta->valor * (1 - ($cuenta->descuento / 100));
                    $cuota = null;
                    switch ($detalle_cuenta->tipo) {
                        case 'saldo_inicial':
                            $detalle->tipo_de_cuota = 'Saldo Inicial';
                            $cuota = saldoInicial::find($detalle_cuenta->cuota_id);
                            $cuota->estado = 'Pronto pago';
                            $cuota->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'saldo_inicial');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'administracion':
                            $detalle->tipo_de_cuota = 'Cuota Administrativa';
                            $unidad = Unidad::find($detalle_cuenta->unidad_id);
                            $cuota = $unidad->cuotasAdministracion->find($detalle_cuenta->cuota_id);
                            $cuota->pivot->estado = 'Pronto pago';
                            $cuota->pivot->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'administracion');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'extraordinaria':
                            $detalle->tipo_de_cuota = 'Cuota Extraordinaria';
                            $unidad = Unidad::find($detalle_cuenta->unidad_id);
                            $cuota = $unidad->cuotasExtraordinarias->find($detalle_cuenta->cuota_id);
                            $cuota->pivot->estado = 'Pronto pago';
                            $cuota->pivot->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'extraordinaria');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'otro_cobro':
                            $detalle->tipo_de_cuota = 'Otro Cobro';
                            $cuota = Otros_cobros::find($detalle_cuenta->cuota_id);
                            $cuota->estado = 'Pronto pago';
                            $cuota->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'otro_cobro');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                        case 'multa':
                            $detalle->tipo_de_cuota = 'Multa';
                            $cuota = Multa::find($detalle_cuenta->cuota_id);
                            $cuota->estado = 'Pronto pago';
                            $cuota->save();
                            $cuotas[] = array('cuota' => $cuota, 'tipo' => 'multa');
                            $presupuesto_id = $cuota->presupuesto_cargar_id;
                            break;
                    }

                    $detalle->presupuesto_id = $presupuesto_id;


                    if ($detalle->save()) {
                        $cartera = new Cartera();
                        $cartera->prefijo = $consecutivo->prefijo;
                        $cartera->numero = $consecutivo->numero;
                        $cartera->fecha = $recaudo->fecha;
                        $cartera->valor = $detalle->valor;
                        $cartera->tipo_de_pago = $request->tipo_de_pago;
                        $cartera->tipo_de_cuota = $detalle->tipo_de_cuota;
                        $cartera->movimiento = $detalle_cuenta->cuota_id;
                        $cartera->user_register_id = Auth::user()->id;
                        $cartera->user_id = $request->propietario_recaudo;
                        $cartera->unidad_id = $detalle_cuenta->unidad_id;
                        $cartera->presupuesto_individual_id = $presupuesto_id;
                        $cartera->save();
                        $detalles_cartera[] = $cartera;
                    }
                }

                $consecutivo->numero++;
                $consecutivo->save();


                //anular el recaudo
                $recaudoA->anulada = true;
                $recaudoA->fecha_anulacion = date('Y-m-d');
                $recaudoA->motivo = $request->motivo_recaudo;
                $recaudoA->reemplazo_recaudo_id = $recaudo->id;
                $recaudoA->save();

                $recaudo->save();
                $recaudo->saldo_favor = User::find($request->propietario_recaudo)->saldo($recaudo->fecha);
                $recaudo->save();

                return array('res' => 1, 'msg' => 'Recaudo guardado correctamente.', 'pago' => $recaudo);
            }
        } catch (\Throwable $th) {
            $recaudo->delete();
            foreach ($detalles_cartera as $detalle) {
                $detalle->delete();
            }

            foreach ($cuotas as $cuota) {
                if ($cuota) {
                    if ($cuota['tipo'] == 'administracion' || $cuota['tipo'] == 'extraordinaria') {
                        $cuota->pivot->valor = 'No pago';
                        $cuota->pivot->save();
                    } else {
                        $cuota['cuota']->estado = 'No pago';
                        $cuota['cuota']->save();
                    }
                }
            }

            //reestalecer el recaudo
            $recaudoA->anulada = false;
            $recaudoA->fecha_anulacion = null;
            $recaudoA->motivo = null;
            $recaudoA->reemplazo_recaudo_id = null;
            $recaudoA->save();

            //agregar a la cartera los recaudos
            foreach ($movimientos_cartera as $movimiento) {
                $movimiento->save();
            }

            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.', 'e' => $th);
        }
    }

    // //deshacer la anulación de una cuenta de cobro
    // public function deshacerAnulacionCuentaCobro(CuentaCobro $cuenta,Request $request){
    //     return ['oe'];
    // }

    // //deshacer la anulación de un recaudo
    // public function deshacerAnulacionRecaudo(Recaudo $recaudo,Request $request){
    //     return ['oe'];
    // }


}
