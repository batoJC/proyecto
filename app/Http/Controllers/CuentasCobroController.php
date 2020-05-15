<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Consecutivos;
use App\CuentaCobro;
use App\User;
use App\Conjunto;
use App\Cuota_admon;
use Yajra\Datatables\Datatables;
use App\Cuota_extOrd;
use App\DetalleCuentaCobro;
use App\Multa;
use App\Otros_cobros;
use App\saldoInicial;
use App\Unidad;
use ZIP;
use PDF;
use Auth;

class CuentasCobroController extends Controller
{
    //

    public function index()
    {
        session(['section' => 'cuentas']);

        if (Auth::user()->id_rol == 2) {
            $user = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos = Conjunto::find(session('conjunto'));
            $propietarios = User::where([
                ['id_conjunto', session('conjunto')],
                ['id_rol', 3]
            ])->get();
            return view('admin.cuentas_cobro.index')
                ->with('user', $user)
                ->with('propietarios', $propietarios)
                ->with('conjuntos', $conjuntos);
            // ->with('cuentas', $cuentas);
        }
        if (Auth::user()->id_rol == 3) {



            return view('dueno.cuentas_cobro');
        }
    }

    public function indexGenerar()
    {
        session(['section' => 'cuentas']);


        $user = User::where('id_conjunto', session('conjunto'))->get();
        $conjuntos = Conjunto::find(session('conjunto'));
        $consecutivos = Consecutivos::where('conjunto_id', session('conjunto'))->get();

        return view('admin.cuentas_cobro.generar')
            ->with('consecutivos', $consecutivos)
            ->with('user', $user)
            ->with('conjuntos', $conjuntos);
    }

    public function visualizar(Request $request)
    {
        session(['section' => 'cuentas']);
        return view('admin.cuentas_cobro.lista')->with('datos', $this->generarCuentas($request));
    }

    private function generarCuentas(Request $request)
    {
        $consecutivo = Consecutivos::find($request->consecutivo);

        // dd($consecutivo);

        $propietarios = User::where([
            ['id_rol', 3],
            ['id_conjunto', session('conjunto')]
        ])->get();

        $cuotas_por_propiedades = array();
        foreach ($propietarios as $propietario) {
            $data = $propietario->cuentas();
            if (count($data) > 0) {
                $tipo_cobro = 'normal';
                $mayor = 0;

                foreach ($data as $cuenta) {
                    $dias = date_diff(date_create($cuenta['vigencia_inicio']), date_create(date('Y-m-d')))->format('%R%a');
                    if ($dias > $mayor) {
                        $mayor = $dias;
                    }
                }

                if ($mayor >= 90) {
                    $tipo_cobro = 'juridico';
                } else if ($mayor >= 60) {
                    $tipo_cobro = 'pre-juridico';
                }


                $cuotas_por_propiedades[] = array(
                    'consecutivo' => $consecutivo->prefijo . '-' . $consecutivo->numero,
                    'propietario' => $propietario,
                    'cuentas' => $data,
                    'tipo_cobro' => $tipo_cobro
                );
                $consecutivo->numero++;
            }
        }


        $salida  = array(
            'fecha' => date('Y-m-d'),
            'fecha_pronto_pago' => $request->fecha_pronto_pago,
            'descuento' => $request->descuento,
            'cuentas' => $cuotas_por_propiedades,
        );

        return $salida;
    }

    public function cuentaPropietario($consecutivo, $propietario, Request $request)
    {
        $propietario = User::find($propietario);

        $data = $propietario->cuentas();

        $cuenta = null;

        if (count($data) > 0) {
            $tipo_cobro = 'normal';
            $mayor = 0;

            foreach ($data as $cuenta) {
                $dias = date_diff(date_create($cuenta['vigencia_inicio']), date_create(date('Y-m-d')))->format('%R%a');
                if ($dias > $mayor) {
                    $mayor = $dias;
                }
            }

            if ($mayor >= 90) {
                $tipo_cobro = 'juridico';
            } else if ($mayor >= 60) {
                $tipo_cobro = 'pre-juridico';
            }


            $cuenta = array(
                'consecutivo' => $consecutivo,
                'propietario' => $propietario,
                'cuentas' => $data,
                'tipo_cobro' => $tipo_cobro
            );

            $datos = array(
                'fecha' => date('Y-m-d'),
                'fecha_pronto_pago' => $request->fecha_pronto_pago,
                'descuento' => $request->descuento
            );

            return view('admin.cuentas_cobro.cuenta')
                ->with('cuenta', $cuenta)
                ->with('datos', $datos);
        } else {
            return '';
        }
    }

    public function guardar(Request $request)
    {
        $cuentasGeneradas = array();

        try {
            $cuentas = $this->generarCuentas($request);
            $consecutivo = Consecutivos::find($request->consecutivo);

            //crear carpeta
            $nombre_carpeta = time();
            mkdir(public_path() . '/' . $nombre_carpeta);

            foreach ($cuentas['cuentas'] as $cuenta) {
                $auxCuentaCobro = new CuentaCobro();
                $auxCuentaCobro->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
                $auxCuentaCobro->fecha = date('Y-m-d');
                if($request->pronto_pago){
                    $auxCuentaCobro->fecha_pronto_pago = $request->fecha_pronto_pago;
                    $auxCuentaCobro->descuento = $request->descuento;
                }
                $auxCuentaCobro->propietario_id = $cuenta['propietario']->id;
                $auxCuentaCobro->tipo_cobro = $cuenta['tipo_cobro'];
                $auxCuentaCobro->saldo_favor = User::find($cuenta['propietario']->id)->saldo();
                $auxCuentaCobro->conjunto_id = session('conjunto');
                if ($auxCuentaCobro->save()) {
                    foreach ($cuenta['cuentas'] as $detalle) {
                        $auxDetalle = new DetalleCuentaCobro();
                        $detalle['cobro_id'] = $auxCuentaCobro->id;
                        $auxDetalle->fill($detalle);
                        $auxDetalle->save();
                    }

                    $archivo = $this->pdf($auxCuentaCobro);
                    $nombre_archivo = $nombre_carpeta . '/' . str_replace('/', ' ', $auxCuentaCobro->propietario->nombre_completo) . ' - ' . $auxCuentaCobro->consecutivo . ".pdf";
                    file_put_contents(public_path($nombre_archivo), $archivo);

                    $this->enviarEmail($auxCuentaCobro->propietario,$nombre_archivo);
                    // $this->enviarEmail([User::find(6)], $nombre_archivo);

                    $consecutivo->numero++;
                    $consecutivo->save();
                    $cuentasGeneradas[] = $auxCuentaCobro;
                }
            }

            //eliminar caropeta
            $this->eliminarCarpeta($nombre_carpeta);

            return array('res' => 1, 'msg' => 'Cuentas de cobro generadas correctamente.');
        } catch (\Throwable $th) {
            foreach ($cuentasGeneradas as $cuenta) {
                $cuenta->delete();
            }
            return array('res' => 0, 'msg' => 'Ócurrio un error al generar las cuentas de cobro.','e'=>$th);
        }
    }

    public function guardarDescargar(Request $request)
    {
        $cuentas = $this->generarCuentas($request);
        $consecutivo = Consecutivos::find($request->consecutivo);

        //crear carpeta
        $nombre_carpeta = time();
        mkdir(public_path() . '/' . $nombre_carpeta);

        foreach ($cuentas['cuentas'] as $cuenta) {
            $auxCuentaCobro = new CuentaCobro();
            $auxCuentaCobro->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $auxCuentaCobro->fecha = date('Y-m-d');
            if($request->pronto_pago){
                $auxCuentaCobro->fecha_pronto_pago = $request->fecha_pronto_pago;
                $auxCuentaCobro->descuento = $request->descuento;
            }
            $auxCuentaCobro->tipo_cobro = $cuenta['tipo_cobro'];
            $auxCuentaCobro->propietario_id = $cuenta['propietario']->id;
            $auxCuentaCobro->saldo_favor = User::find($cuenta['propietario']->id)->saldo();
            $auxCuentaCobro->conjunto_id = session('conjunto');
            if ($auxCuentaCobro->save()) {
                foreach ($cuenta['cuentas'] as $detalle) {
                    $auxDetalle = new DetalleCuentaCobro();
                    $detalle['cobro_id'] = $auxCuentaCobro->id;
                    $auxDetalle->fill($detalle);
                    $auxDetalle->save();
                }

                $archivo = $this->pdf($auxCuentaCobro);
                $nombre_archivo = $nombre_carpeta . '/' . str_replace('/', ' ', $auxCuentaCobro->propietario->nombre_completo) . ' - ' . $auxCuentaCobro->consecutivo . ".pdf";
                file_put_contents(public_path($nombre_archivo), $archivo);

                $this->enviarEmail($auxCuentaCobro->propietario,$nombre_archivo);
                // $this->enviarEmail([User::find(6)], $nombre_archivo);


                $consecutivo->numero++;
                $consecutivo->save();
            }
        }
        //generar el ZIP y descargamos
        $files = public_path() . '/' . $nombre_carpeta;
        $zip = new ZIP();

        /* Le indicamos en que carpeta queremos que se genere el zip y los comprimimos*/
        $zip->make(public_path('temp/' . $nombre_carpeta . '.zip'))->add($files)->close();

        //eliminar caropeta
        $this->eliminarCarpeta($nombre_carpeta);

        /* Por último, si queremos descarlos, indicaremos la ruta del archiv, su nombre
        y lo descargaremos*/
        return response()->download(public_path('temp/' . $nombre_carpeta . '.zip'));
    }


    private function enviarEmail($propietario, $file)
    {
        $aux = new CorreoController();
        $res = $aux->enviarEmail(Conjunto::find(session('conjunto')), $propietario, 'Cuenta de cobro', '<p>Se ha generado una cuenta de cobro a su nombre. Se adjunta una copia en pdf de la misma.</p>', $file);

        if ($res) {
            return true;
        }
    }


    public function pdf(CuentaCobro $cuenta)
    {
        $pdf = null;
        // validar que sea el administrador o el dueño del recibo
        $usuario = Auth::user();
        if ($usuario->id_rol != 2) {
            if ($cuenta->propietario_id != $usuario->id) {
                die;
            }
        }

        $pdf = PDF::loadView('admin.cuentas_cobro.pdf', [
            'cuenta' => $cuenta
        ]);
        return $pdf->stream();
    }


    private function eliminarCarpeta($nombre)
    {
        foreach (glob(public_path($nombre) . '/*') as $archivo) {
            if (is_dir($archivo)) {
                $data = explode($nombre, $archivo)[1];
                $this->eliminarCarpeta($nombre . $data);
            } else {
                @unlink($archivo);
            }
        }
        rmdir($nombre);
    }


    public function eliminarCuota(Request $request)
    {
        switch ($request->tipo) {
            case 'saldo_inicial':
                $cuota = saldoInicial::find($request->id);
                if ($cuota->delete()) {
                    return array('res' => 1, 'msg' => 'Cuota eliminada correctamente');
                } else {
                    return array('res' => 0, 'msg' => 'No se logró elimnar la cuota');
                }
            case 'administracion':
                $cuota = Cuota_admon::find($request->id);
                if ($cuota->unidades()->detach($request->unidad)) {
                    return array('res' => 1, 'msg' => 'Cuota eliminada correctamente');
                } else {
                    return array('res' => 0, 'msg' => 'No se logró elimnar la cuota');
                }
            case 'extraordinaria':
                $cuota = Cuota_extOrd::find($request->id);
                if ($cuota->unidades()->detach($request->unidad)) {
                    return array('res' => 1, 'msg' => 'Cuota eliminada correctamente');
                } else {
                    return array('res' => 0, 'msg' => 'No se logró elimnar la cuota');
                }
                break;
            case 'otro_cobro':
                $cuota = Otros_cobros::find($request->id);
                if ($cuota->delete()) {
                    return array('res' => 1, 'msg' => 'Cuota eliminada correctamente');
                } else {
                    return array('res' => 0, 'msg' => 'No se logró elimnar la cuota');
                }
            case 'multa':
                $cuota = Multa::find($request->id);
                if ($cuota->delete()) {
                    return array('res' => 1, 'msg' => 'Cuota eliminada correctamente');
                } else {
                    return array('res' => 0, 'msg' => 'No se logró elimnar la cuota');
                }
        }
    }

    public function editarCuota(Request $request)
    {
        $salida = null;
        $cuota = null;

        switch ($request->tipo) {
            case 'saldo_inicial':
                $cuota = saldoInicial::find($request->id);
                $cuota->valor = $request->valor;
                $salida = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => ($cuota->vigencia_fin) ? $cuota->vigencia_fin : '',
                    'referencia' => $cuota->unidad->referencia,
                    'concepto' => $cuota->concepto . ' ' . $cuota->unidad->tipo->nombre . ' ' . $cuota->unidad->numero_letra,
                    'valor' => $cuota->calcularValor(),
                    'interes' => $cuota->calcularInteres(),
                    'tipo' => 'saldo_inicial',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => $cuota->unidad->id,
                );
                break;
            case 'administracion':
                $unidad = Unidad::find($request->unidad);
                $cuota = $unidad->cuotasAdministracion->find($request->id);
                $cuota->pivot->valor = $request->valor;
                $cuota->pivot->save();
                $salida = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => $cuota->vigencia_fin,
                    'referencia' => $unidad->referencia,
                    'concepto' => 'Cuota Administracion ' . $unidad->tipo->nombre . ' ' . $unidad->numero_letra,
                    'valor' => $cuota->calcularValor(),
                    'interes' => $cuota->calcularInteres(),
                    'tipo' => 'administracion',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => $unidad->id,
                );
                break;
            case 'extraordinaria':
                $unidad = Unidad::find($request->unidad);
                $cuota = $unidad->cuotasExtraordinarias->find($request->id);
                $cuota->pivot->valor = $request->valor;
                $cuota->pivot->save();
                $salida = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => $cuota->vigencia_fin,
                    'referencia' => $unidad->referencia,
                    'concepto' => $cuota->concepto . ' ' . $unidad->tipo->nombre . ' ' . $unidad->numero_letra,
                    'valor' => $cuota->calcularValor(),
                    'interes' => $cuota->calcularInteres(),
                    'tipo' => 'extraordinaria',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => $unidad->id,
                );
                break;
            case 'otro_cobro':
                $cuota = Otros_cobros::find($request->id);
                $cuota->valor = $request->valor;
                $salida = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => ($cuota->vigencia_fin) ? $cuota->vigencia_fin : '',
                    'referencia' => $cuota->unidad->referencia,
                    'concepto' => $cuota->concepto . ' ' . $cuota->unidad->tipo->nombre . ' ' . $cuota->unidad->numero_letra,
                    'valor' => $cuota->calcularValor(),
                    'interes' => $cuota->calcularInteres(),
                    'tipo' => 'otro_cobro',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => $cuota->unidad->id,
                );
                break;
            case 'multa':
                $cuota = Multa::find($request->id);
                $cuota->valor = $request->valor;
                $salida = array(
                    'vigencia_inicio' => $cuota->vigencia_inicio,
                    'vigencia_fin' => ($cuota->vigencia_fin) ? $cuota->vigencia_fin : '',
                    'referencia' => '',
                    'concepto' => $cuota->concepto,
                    'valor' => $cuota->calcularValor(),
                    'interes' => $cuota->calcularInteres(),
                    'tipo' => 'multa',
                    'cuota_id' =>  $cuota->id,
                    'unidad_id' => '',
                );
                break;
        }
        if ($cuota->save()) {
            return array('res' => 1, 'cuota' => $salida);
        } else {
            return array('res' => 0);
        }
    }

    public function ultima($propietario)
    {
        $cuenta = CuentaCobro::where([
            ['propietario_id', $propietario],
            ['anulada', false]
        ])->orderBy('id', 'DESC')->first();

        return view('admin.recaudos.cuentas')->with('cuenta', $cuenta);
    }

    public function verDetalle(DetalleCuentaCobro $detalle)
    {
        return $detalle;
    }

    public function detalles(CuentaCobro $cuenta)
    {
        return $cuenta->detalles;
    }

    //crear una cuenta de cobro
    public function crearCuentaCobro(Request $request)
    {
        //ingresa la fecha,tipo de unidad,consecutivo,cuentas de cobro
        $consecutivo = Consecutivos::find($request->consecutivo);
        $cuentaCobro = new CuentaCobro();
        $cuentaCobro->fecha = $request->fecha;
        $cuentaCobro->unidad_id = $request->id_tipo_unidad;
        $cuentaCobro->prefijo = $consecutivo->prefijo;
        $cuentaCobro->numero = $consecutivo->id_consecutivo;
        $cuentaCobro->conjunto_id = session('conjunto');
        $cuentaCobro->save();
        $consecutivo->id_consecutivo++;
        $consecutivo->save();

        $cuotas = explode(')(', $request->cuentas);
        foreach ($cuotas as $value) {
            if ($value != "") {
                $aux = explode(',', $value);
                switch ($aux[1]) {
                    case 2:
                        $cuentaCobro->cuotasOrd()->attach($aux[0]);
                        break;
                    case 3:
                        $cuentaCobro->cuotasExt()->attach($aux[0]);
                        break;
                    case 4:
                        $cuentaCobro->otrosCobros()->attach($aux[0]);
                        break;
                    case 5:
                        $cuentaCobro->multas()->attach($aux[0]);
                        break;
                }
            }
        }

        //enviar el email al dueño
        // $this->enviarEmail($cuentaCobro->id);

        return redirect('listaCuentasCobro');
    }

    public function buscarPorConsecutivo(Request $request)
    {
        // ["upper(replace(consecutivo,' ',''))", strtolower($request->consecutivo)],
        // $cuenta = CuentaCobro::whereRaw("upper(replace(consecutivo,' ','')) = ?", [strtolower(str_replace(' ', '', $request->consecutivo))])
        //     ->where('conjunto_id', session('conjunto'))
        //     ->first();
        $cuenta = CuentaCobro::where('consecutivo',trim(strtoupper($request->consecutivo)))
            ->where('conjunto_id', session('conjunto'))->first();
            // dd($cuenta);
        if ($cuenta) {
            return array('res' => 1, 'id' => $cuenta->id);
        } else {
            return array('res' => 0);
        }
    }

    public function buscarPorPropietario($propietario)
    {
        $cuentas = CuentaCobro::where('propietario_id', $propietario)->get();
        $data = '';
        foreach ($cuentas as $cuenta) {
            $data .= $cuenta->id . ';';
        }

        $data = trim($data, ';');

        return view('admin.cuentas_cobro.listar')
            ->with('cuentas', $cuentas)
            ->with('data', $data);
    }


    public function descargar(Request $request)
    {
        //crear carpeta
        $nombre_carpeta = time();
        mkdir(public_path() . '/' . $nombre_carpeta);


        //colocar todos los pdfs de los egresos en ella
        $cuentas = explode(';', $request->data);

        foreach ($cuentas as $cuenta) {
            $cuenta = CuentaCobro::find($cuenta);
            $archivo = $this->pdf($cuenta);
            file_put_contents(public_path('/' . $nombre_carpeta . '/') . $cuenta->consecutivo . ".pdf", $archivo);
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


    //generar pdf de una cuenta de cobro
    public function generarPDF($cobroId)
    {
        $datos = $this->verDetalle($cobroId);
        $pdf = PDF::loadView('admin.PDF.cuenta_cobro', $datos);
        //enviar el email al dueño
        // $this->enviarEmail($cobroId);
        return $pdf->stream();
    }

    //eliminar una cuenta de cobro generada
    public function eliminar($id)
    {
        $cuenta = CuentaCobro::find($id);
        $cuenta->delete();
    }

   
    // para listar por datatables cuentas de cobro de un propietario
    // ****************************
    public function datatablesDueno()
    {

        $cuentas = CuentaCobro::where('propietario_id', Auth::user()->id)->orderBy('fecha', 'DESC')->orderBy('consecutivo', 'DESC')->get();

        return Datatables::of($cuentas)
            ->addColumn('fecha', function ($cuenta) {
                return date('d-m-Y', strtotime($cuenta->fecha));
            })->addColumn('action', function ($cuenta) {
                return '<a data="' . $cuenta->anulada . '" target="_blank" class="btn btn-default" href="' . url('pdfCuentasCobro', ['cuenta' => $cuenta->id]) . '"><i class="fa fa-file-pdf-o"></i></a>';
            })->make(true);
    }
}
