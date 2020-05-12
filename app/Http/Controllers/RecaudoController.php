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
use Yajra\Datatables\Datatables;
use QR;
use App\Multa;
use App\Otros_cobros;
use App\Recaudo;
use App\saldoInicial;
use App\Unidad;
use App\User;
use ZIP;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecaudoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        session(['section' => 'recaudos']);

        $usuario = Auth::user();
        if ($usuario->id_rol == 2) { //administrador

            $conjuntos = Conjunto::find(session('conjunto'));
            $consecutivos = Consecutivos::where('conjunto_id', session('conjunto'))->get();
            $propietarios = User::where([
                ['id_conjunto', session('conjunto')],
                ['id_rol', 3]
            ])->get();

            $fecha = CuentaCobro::where([
                ['conjunto_id', session('conjunto')],
                ['anulada', false]
            ])->orderBy('id', 'DESC')->first();

            if ($fecha) {
                $fecha = $fecha->fecha;
            } else {
                return view('admin.recaudos.index')
                    ->with('error', 'fecha')
                    ->with('conjuntos', $conjuntos);
            }

            $bancos = CuentaBancaria::where('conjunto_id',session('conjunto'))->get();

            return view('admin.recaudos.index')
                ->with('error', 'ninguno')
                ->with('bancos', $bancos)
                ->with('fecha', $fecha)
                ->with('consecutivos', $consecutivos)
                ->with('propietarios', $propietarios)
                ->with('conjuntos', $conjuntos);
        } elseif ($usuario->id_rol == 3) { //Dueño del recibo


            return view('dueno.recaudos');
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
        $recaudo = new Recaudo();
        $detalles_cartera = array();

        $cuenta = CuentaCobro::find($request->cuenta);

        if ($cuenta) {
            if ($cuenta->recaudo) {
                return array('res' => 0, 'msg' => 'Ya existe un recaudo para esta cuenta de cobro.');
            }
        } else {
            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.');
        }

        try {

            $fecha = CuentaCobro::where([
                ['conjunto_id', session('conjunto')],
                ['anulada', false]
            ])->orderBy('id', 'DESC')->first()->fecha;

            if ((date_diff(date_create($fecha), date_create($request->fecha))->format('%R%a') < 0)) {
                return array('res' => 0, 'msg' => 'La fecha seleccionada es anterior a la última generación de cuentas de cobro.');
            }

            $recaudo->fecha = $request->fecha;

            //consultar consecutivo
            //---------------------
            $consecutivo = Consecutivos::find($request->consecutivo);

            $recaudo->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $recaudo->tipo_de_pago = $request->tipo_de_pago;
            $recaudo->propietario_id = $request->propietario;
            $recaudo->saldo_favor = 0;
            $recaudo->valor = $request->valor;
            $recaudo->banco = ($request->banco == '')? null : $request->banco;
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
                        $cartera->fecha = date('Y-m-d');
                        $cartera->valor = $pago->valor;
                        $cartera->tipo_de_pago = $request->tipo_de_pago;
                        $cartera->tipo_de_cuota = $detalle->tipo_de_cuota;
                        $cartera->movimiento = $detalle_cuentas_cobro->cuota_id;
                        $cartera->user_register_id = Auth::user()->id;
                        $cartera->user_id = $request->propietario;
                        $cartera->unidad_id = $detalle_cuentas_cobro->unidad_id;
                        $cartera->presupuesto_individual_id = $presupuesto_id;
                        $cartera->save();
                        $detalles_cartera[] = $cartera;
                    }
                }
                $consecutivo->numero++;
                $consecutivo->save();
                $recaudo->save();
                $recaudo->saldo_favor = User::find($request->propietario)->saldo();
                $recaudo->save();


                $archivo = $this->pdf($recaudo);
                $nombre_archivo = 'temp/' . str_replace('/', ' ', $recaudo->propietario->nombre_completo) . ' - ' . $recaudo->consecutivo . ".pdf";
                file_put_contents(public_path($nombre_archivo), $archivo);

                $this->enviarEmail($recaudo->propietario,$nombre_archivo);
                // $this->enviarEmail([User::find(6)], $nombre_archivo);
                @unlink($nombre_archivo);

                return array('res' => 1, 'msg' => 'Recaudo guardado correctamente.', 'pago' => $recaudo);
            }
        } catch (\Throwable $th) {
            $recaudo->delete();
            foreach ($detalles_cartera as $detalle) {
                $detalle->delete();
            }

            // return $th;

            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.');
        }
    }

    public function saveProntoPago(Request $request)
    {
        //
        $recaudo = new Recaudo();
        $detalles_cartera = array();
        $cuotas = array();

        $cuenta = CuentaCobro::find($request->cuenta);

        if ($cuenta) {
            if ($cuenta->recaudo) {
                return array('res' => 0, 'msg' => 'Ya existe un recaudo para esta cuenta de cobro.');
            }
        } else {
            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.');
        }

        try {

            $fecha = CuentaCobro::where([
                ['conjunto_id', session('conjunto')],
                ['anulada', false]
            ])->orderBy('id', 'DESC')->first()->fecha;

            if ((date_diff(date_create($fecha), date_create($request->fecha))->format('%R%a') < 0)) {
                return array('res' => 0, 'msg' => 'La fecha seleccionada es anterior a la última generación de cuentas de cobro.');
            }

            $recaudo->fecha = $request->fecha;

            //consultar consecutivo
            //---------------------
            $consecutivo = Consecutivos::find($request->consecutivo);

            $recaudo->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $recaudo->tipo_de_pago = $request->tipo_de_pago;
            $recaudo->propietario_id = $request->propietario;
            $recaudo->saldo_favor = 0;
            $recaudo->valor = $request->valor;
            $recaudo->banco = ($request->banco == '')? null : $request->banco;
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
                        $cartera->fecha = date('Y-m-d');
                        $cartera->valor = $detalle->valor;
                        $cartera->tipo_de_pago = $request->tipo_de_pago;
                        $cartera->tipo_de_cuota = $detalle->tipo_de_cuota;
                        $cartera->movimiento = $detalle_cuenta->cuota_id;
                        $cartera->user_register_id = Auth::user()->id;
                        $cartera->user_id = $request->propietario;
                        $cartera->unidad_id = $detalle_cuenta->unidad_id;
                        $cartera->presupuesto_individual_id = $presupuesto_id;
                        $cartera->save();
                        $detalles_cartera[] = $cartera;
                    }
                }

                $consecutivo->numero++;
                $consecutivo->save();
                $recaudo->saldo_favor = User::find($request->propietario)->saldo();
                $recaudo->save();

                $archivo = $this->pdf($recaudo);
                $nombre_archivo = 'temp/' . str_replace('/', ' ', $recaudo->propietario->nombre_completo) . ' - ' . $recaudo->consecutivo . ".pdf";
                file_put_contents(public_path($nombre_archivo), $archivo);

                // $this->enviarEmail($auxCuentaCobro->propietario,$nombre_archivo);
                $this->enviarEmail([User::find(6)], $nombre_archivo);
                @unlink($nombre_archivo);

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

            // return $th;

            return array('res' => 0, 'msg' => 'Ócurrio un error al guardar el recaudo.', 'e' => $th);
        }
    }

    private function enviarEmail($propietario, $file)
    {
        $aux = new CorreoController();
        $res = $aux->enviarEmail(Conjunto::find(session('conjunto')), $propietario, 'Recibo de pago', '<p>Se ha generado un recibo de pago a su nombre. Se adjunta una copia en pdf de mismo.</p>', $file);

        if ($res) {
            return true;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recaudo  $recaudo
     * @return \Illuminate\Http\Response
     */
    public function show(Recaudo $recaudo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recaudo  $recaudo
     * @return \Illuminate\Http\Response
     */
    public function edit(Recaudo $recaudo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recaudo  $recaudo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recaudo $recaudo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recaudo  $recaudo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recaudo $recaudo)
    {
        //
    }

    public function pdf(Recaudo $recaudo)
    {
        $pdf = null;
        $files = glob(public_path('qrcodes') . '/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file); 
        }


        $text_qr = $recaudo->conjunto->nombre."\n\r";
        $text_qr .= $recaudo->propietario->nombre_completo .' - '. $recaudo->propietario->numero_cedula."\n\r";
        $text_qr .= $recaudo->consecutivo."\n\r";
        $text_qr .= date('d-m-Y', strtotime($recaudo->fecha))."\n \r";
        $text_qr .= '$ '.number_format($recaudo->valor)."\n\r";
        $text_qr .= $recaudo->tipo_de_pago.' '.$recaudo->banco;

        QR::format('png')->size(140)->margin(2)->generate($text_qr, public_path('qrcodes/qrcoderecaudo_' . $recaudo->id . '.png'));

        // validar que sea el administrador o el dueño del recibo
        $usuario = Auth::user();
        if ($usuario->id_rol != 2) {
            if ($recaudo->propietario_id != $usuario->id) {
                die;
            }
        }

        $pdf = PDF::loadView('admin.recaudos.pdf', [
            'recaudo' => $recaudo
        ]);
        return $pdf->stream();
    }

    public function pdfC($consecutivo)
    {
        $recaudo  = Recaudo::where([['conjunto_id', session('conjunto')], ['consecutivo', $consecutivo]])->first();

        // validar que sea el administrador o el dueño del recibo
        $usuario = Auth::user();
        if ($usuario->id_rol != 2) {
            if ($recaudo->propietario_id != $usuario->id) {
                die;
            }
        }

        $pdf = null;
        $pdf = PDF::loadView('admin.recaudos.pdf', [
            'recaudo' => $recaudo
        ]);
        return $pdf->stream();
    }


    public function saldosFavor()
    {
        session(['section' => 'saldosFavor']);

        $saldos = array();
        $conjuntos = Conjunto::find(session('conjunto'));
        $propietarios = User::where([
            ['id_conjunto', session('conjunto')],
            ['id_rol', 3]
        ])->get();
        foreach ($propietarios as $propietario) {
            $valor = $propietario->saldo();
            if ($valor > 0) {
                $saldos[] = array(
                    'cedula' => $propietario->numero_cedula,
                    'nombre' => $propietario->nombre_completo,
                    'valor' => $propietario->saldo()
                );
            }
        }
        return view('admin.recaudos.saldos')->with('saldos', $saldos)->with('conjuntos', $conjuntos);
    }

    public function saldosFavorPdf()
    {
        session(['section' => 'saldosFavor']);

        $saldos = array();
        $conjuntos = Conjunto::find(session('conjunto'));
        $propietarios = User::where([
            ['id_conjunto', session('conjunto')],
            ['id_rol', 3]
        ])->get();
        foreach ($propietarios as $propietario) {
            $valor = $propietario->saldo();
            if ($valor > 0) {
                $saldos[] = array(
                    'cedula' => $propietario->numero_cedula,
                    'nombre' => $propietario->nombre_completo,
                    'valor' => $propietario->saldo()
                );
            }
        }

        $pdf = null;
        $pdf = PDF::loadView('admin.recaudos.pdfSaldos', [
            'saldos' => $saldos
        ]);
        return $pdf->stream();
    }

    public function listarRecaudos()
    {
        session(['section' => 'recaudos']);

        $conjuntos = Conjunto::find(session('conjunto'));
        $propietarios = User::where([
            ['id_conjunto', session('conjunto')],
            ['id_rol', 3]
        ])->get();

        return view('admin.recaudos.listar')->with('propietarios', $propietarios)
            ->with('conjuntos', $conjuntos);
    }


    public function consultarRecaudos(Request $request)
    {
        $recaudos = array();

        if ($request->tipo == 'fechas') {
            $recaudos = Recaudo::where([
                ['fecha', '>=', $request->fecha_inicio],
                ['fecha', '<=', $request->fecha_fin],
                ['conjunto_id', session('conjunto')]
            ])->get();
        } else {
            $recaudos = Recaudo::where([
                ['propietario_id', $request->propietario],
                ['conjunto_id', session('conjunto')]
            ])->get();
        }

        $data = '';
        foreach ($recaudos as $recaudo) {
            $data .= $recaudo->id . ';';
        }
        $data = trim($data, ';');


        return view('admin.recaudos.lista')->with('recaudos', $recaudos)->with('data', $data);
    }

    public function descargar(Request $request)
    {
        //crear carpeta
        $nombre_carpeta = time();
        mkdir(public_path() . '/' . $nombre_carpeta);


        //colocar todos los pdfs de los egresos en ella
        $recaudos = explode(';', $request->data);

        foreach ($recaudos as $recaudo) {
            $recaudo = Recaudo::find($recaudo);
            $archivo = $this->pdf($recaudo);
            file_put_contents(public_path('/' . $nombre_carpeta . '/') . $recaudo->consecutivo . ".pdf", $archivo);
        }

        //generar el ZIP y descargamos
        $files = public_path() . '\\' . $nombre_carpeta;
        $zip = new ZIP();

        /* Le indicamos en que carpeta queremos que se genere el zip y los comprimimos*/
        $zip->make(public_path('temp/' . $nombre_carpeta . '.zip'))->add($files)->close();

        //eliminar caropeta
        $this->eliminarCarpeta($nombre_carpeta);

        /* Por último, si queremos descarlos, indicaremos la ruta del archiv, su nombre
        y lo descargaremos*/
        return response()->download(public_path('temp/' . $nombre_carpeta . '.zip'));
    }

    private function eliminarCarpeta($nombre)
    {
        foreach (glob(public_path($nombre) . '\\*') as $archivo) {
            if (is_dir($archivo)) {
                $data = explode($nombre, $archivo)[1];
                $this->eliminarCarpeta($nombre . $data);
            } else {
                @unlink($archivo);
            }
        }
        rmdir($nombre);
    }



    // para listar por datatables recaudos de un propietario
    // ****************************
    public function datatablesDueno()
    {

        $recibos = Recaudo::where([
            ['conjunto_id', session('conjunto')],
            ['propietario_id', Auth::user()->id]
        ])->orderBy('fecha', 'DESC')->orderBy('consecutivo', 'DESC')->get();

        return Datatables::of($recibos)
            ->addColumn('fecha', function ($recibo) {
                return date('d-m-Y', strtotime($recibo->fecha));
            })->addColumn('action', function ($recibo) {
                return '<a data="' . $recibo->anulada . '" target="_blank" class="btn btn-default" href="' . url('pdfRecaudo', ['recaudo' => $recibo->id]) . '"><i class="fa fa-file-pdf-o"></i></a>';
            })->make(true);
    }
}
