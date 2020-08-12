<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Consecutivos;
use App\Deduccion;
use App\Devengo;
use App\EmpleadosConjunto;
use App\Liquidacion;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Jornada;
use App\Variable;
use PDF;
use ZIP;
use QR;


class LiquidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        //crear liquidacón
        $liquidacion = new Liquidacion();
        try {
            $consecutivo = Consecutivos::find($request->consecutivo);
            $empleado = EmpleadosConjunto::find($request->empleado);
            $conjunto = Conjunto::find(session('conjunto'));
            $jornadas = Jornada::where([
                ['fecha', '>=', $request->fecha_inicio],
                ['fecha', '<=', $request->fecha_fin],
                ['empleado_conjunto_id', $request->empleado]
            ])->get();



            $liquidacion->fecha = date('Y-m-d');
            $liquidacion->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $liquidacion->periodo = date('d/m/Y', strtotime($request->fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($request->fecha_fin));
            $liquidacion->salario = $empleado->salario;
            $liquidacion->subsidio_transporte = Variable::find('subsidio_transporte')->value;
            $liquidacion->dias_transporte = $request->dias_transporte;
            $liquidacion->empleado_conjunto_id = $empleado->id;

            if ($liquidacion->save()) {
                $hora_basico = ($empleado->salario / Variable::find('horas_jornada')->value);

                //Horas
                $devengo = new Devengo();
                $devengo->descripcion = 'Salario';
                $devengo->horas = $jornadas->sum('HOD');
                $devengo->valor = $jornadas->sum('HOD') * $hora_basico;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();
                //Horas
                $incrementar = Variable::find('recargo_ordinario_nocturno')->value + 1;
                $incrementar /= 100;
                $devengo = new Devengo();
                $devengo->descripcion = 'Horas Ordinarias Nocturnas';
                $devengo->horas = $jornadas->sum('HON');
                $devengo->valor = $jornadas->sum('HON') * $hora_basico * $incrementar;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();
                //Horas
                $incrementar = Variable::find('recargo_ordinario_diurno_festivo')->value + 1;
                $incrementar /= 100;
                $devengo = new Devengo();
                $devengo->descripcion = 'Horas Ordinarias Diurnas Festivas';
                $devengo->horas = $jornadas->sum('HODF');
                $devengo->valor = $jornadas->sum('HODF') * $hora_basico * $incrementar;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();
                //Horas
                $incrementar = Variable::find('recargo_ordinario_nocturno_festivo')->value + 1;
                $incrementar /= 100;
                $devengo = new Devengo();
                $devengo->descripcion = 'Horas Ordinarias Nocturnas Festivas';
                $devengo->horas = $jornadas->sum('HONF');
                $devengo->valor = $jornadas->sum('HONF') * $hora_basico * $incrementar;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();
                //Horas
                $incrementar = Variable::find('hora_extra_ordinaria_diurna')->value;
                $devengo = new Devengo();
                $devengo->descripcion = 'Horas Extras Ordinarias Diurnas';
                $devengo->horas = $jornadas->sum('HEDO');
                $devengo->valor = $jornadas->sum('HEDO') * $hora_basico * $incrementar;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();
                //Horas
                $incrementar = Variable::find('hora_extra_ordinaria_nocturna')->value;
                $devengo = new Devengo();
                $devengo->descripcion = 'Horas Extras Ordinarias Nocturnas';
                $devengo->horas = $jornadas->sum('HENO');
                $devengo->valor = $jornadas->sum('HENO') * $hora_basico * $incrementar;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();
                //Horas
                $incrementar = Variable::find('hora_extra_ordinaria_diurna_fesiva')->value;
                $devengo = new Devengo();
                $devengo->descripcion = 'Horas Extras Ordinarias Diurnas Festivas';
                $devengo->horas = $jornadas->sum('HEDF');
                $devengo->valor = $jornadas->sum('HEDF') * $hora_basico * $incrementar;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();
                //Horas
                $incrementar = Variable::find('hora_extra_ordinaria_nocturna_festiva')->value;
                $devengo = new Devengo();
                $devengo->descripcion = 'Horas Extras Ordinarias Nocturnas Festivos';
                $devengo->horas = $jornadas->sum('HENF');
                $devengo->valor = $jornadas->sum('HENF') * $hora_basico * $incrementar;
                $devengo->retencion = true;
                $devengo->liquidacion_id = $liquidacion->id;
                $devengo->save();



                //agregar devengos
                $devengos = json_decode($request->devengos);
                foreach ($devengos as $data) {
                    $devengo = new Devengo();
                    $devengo->descripcion = $data->descripcion;
                    $devengo->horas = null;
                    $devengo->valor = $data->valor;
                    $devengo->retencion = $data->retencion;
                    $devengo->liquidacion_id = $liquidacion->id;
                    $devengo->save();
                }


                //agregar deducciones
                $deducciones = json_decode($request->deducciones);
                foreach ($deducciones as $deduccion) {
                    $aux = new Deduccion();
                    $aux->descripcion = $deduccion->descripcion;
                    $aux->descuento = $deduccion->porcentaje;
                    $aux->valor = $deduccion->valor;
                    $aux->liquidacion_id = $liquidacion->id;
                    $aux->save();
                }
            }

            $consecutivo->numero++;
            $consecutivo->save();

            return ['res' => 1, 'msg' => 'Liquidación guardada correctamente!', 'data' => $liquidacion];
        } catch (\Throwable $th) {
            $liquidacion->delete();
            return ['res' => 0, 'msg' => 'Ocurrió un error al registrar la liquidación'];
        }
    }

    public function prestaciones(Request $request)
    {
        //crear liquidacón
        $liquidacion = new Liquidacion();
        try {
            $consecutivo = Consecutivos::find($request->consecutivo);
            $empleado = EmpleadosConjunto::find($request->empleado);


            $liquidacion->fecha = date('Y-m-d');
            $liquidacion->consecutivo = $consecutivo->prefijo . '-' . $consecutivo->numero;
            $liquidacion->periodo = date('d/m/Y', strtotime($request->fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($request->fecha_fin));
            $liquidacion->salario = $empleado->salario;
            $liquidacion->subsidio_transporte = 0;
            $liquidacion->dias_transporte = 0;
            $liquidacion->empleado_conjunto_id = $empleado->id;
            $liquidacion->tipo = 'prestaciones';

            if ($liquidacion->save()) {
                //agregar devengos
                $devengos = json_decode($request->devengos);
                foreach ($devengos as $data) {
                    $devengo = new Devengo();
                    $devengo->descripcion = $data->descripcion;
                    $devengo->horas = null;
                    $devengo->valor = $data->valor;
                    $devengo->retencion = $data->retencion;
                    $devengo->liquidacion_id = $liquidacion->id;
                    $devengo->save();
                }


                //agregar deducciones
                $deducciones = json_decode($request->deducciones);
                foreach ($deducciones as $deduccion) {
                    $aux = new Deduccion();
                    $aux->descripcion = $deduccion->descripcion;
                    $aux->descuento = $deduccion->porcentaje;
                    $aux->valor = $deduccion->valor;
                    $aux->liquidacion_id = $liquidacion->id;
                    $aux->save();
                }
            }

            $consecutivo->numero++;
            $consecutivo->save();

            return ['res' => 1, 'msg' => 'Liquidación de prestaciones guardada correctamente!', 'data' => $liquidacion];
        } catch (\Throwable $th) {
            $liquidacion->delete();
            return ['res' => 0, 'msg' => 'Ocurrió un error al registrar la liquidación de prestaciones', 'th' => $th];
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function show(Liquidacion $liquidacion)
    {
        if ($liquidacion->empleado->conjunto_id == session('conjunto')) {

            $files = glob(public_path('qrcodes') . '/*');
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file); //elimino el fichero
            }

            $pdf = null;

            $text_qr = "Fecha: " . date('d-m-Y', strtotime($liquidacion->fecha));
            $text_qr .= "\n\r Consecutivo: " . $liquidacion->consecutivo;
            $text_qr .= "\n\r Empleado: " . $liquidacion->empleado->cedula;

            QR::format('png')->size(180)->margin(10)->generate($text_qr, public_path('qrcodes/qrcode_liquidacion_' . $liquidacion->id . '.png'));


            $pdf = PDF::loadView('admin.liquidaciones.pdf', [
                'liquidacion' => $liquidacion
            ]);
            return $pdf->stream();
        } else {
            return ['No tiene permiso para ver esta información'];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Liquidacion $liquidacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Liquidacion $liquidacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Liquidacion  $liquidacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Liquidacion $liquidacion)
    {
        try {
            $liquidacion->delete();
            return ['res' => 1, 'msg' => 'Liquidación eliminada correctamente!'];
        } catch (\Throwable $th) {
            return ['res' => 1, 'msg' => 'No se logro eliminar la liquidación!'];
        }
    }

    public function vistaListar(EmpleadosConjunto $empleado)
    {
        return view('admin.liquidaciones.lista')
            ->with('conjuntos', $empleado->conjunto)
            ->with('empleado', $empleado);
    }


    public function listar($empleado)
    {
        $liquidaciones = Liquidacion::where('empleado_conjunto_id', $empleado);
        return Datatables::of($liquidaciones)
            ->addColumn('fecha', function ($liquidacion) {
                return date('d-m-Y', strtotime($liquidacion->fecha));
            })->addColumn('tipo', function ($liquidacion) {
                if ($liquidacion->tipo == "liquidacion") {
                    return "Liquidación";
                } else {
                    return "Prestaciones";
                }
            })->addColumn('action', function ($liquidacion) {
                $salida = '<a target="_blank" href="' . url('liquidacion', ['liquidacion' => $liquidacion->id]) . '" data-toggle="tooltip" data-placement="top" title="Mostrar" class="btn btn-default">
                                <i class="fa fa-eye"></i>
                            </a>';
                $salida .= '<a data-toggle="tooltip" data-placement="top" title="Eliminar" 
                                onclick="deleteData(' . $liquidacion->id . ')" class="btn btn-default">
                                <i class="fa fa-trash"></i>
                            </a>';
                return $salida;
            })->make(true);
    }

    public function download(EmpleadosConjunto $empleado)
    {
        if (session('conjunto') == $empleado->conjunto_id) {
            $conjunto = Conjunto::find(session('conjunto'));
            $liquidaciones = Liquidacion::where('empleado_conjunto_id', $empleado->id)->orderBy('fecha', 'ASC')->get();
            $carpeta = "exports/{$conjunto->id}";
            $this->eliminarCarpeta(public_path($carpeta));
            @mkdir(public_path($carpeta));
            $carpeta .= "/liquidaciones";
            @mkdir(public_path($carpeta));

            
            foreach ($liquidaciones as $liquidacion) {
                $archivo = $this->show($liquidacion);
                $nombre_archivo = $liquidacion->consecutivo;
                $nombre_archivo = "{$carpeta}/{$nombre_archivo}.pdf";
                file_put_contents(public_path($nombre_archivo), $archivo);
            }

            $zip = new ZIP();
            $files = public_path($carpeta);
            $zip->make(public_path('exports/' . $conjunto->id . '/liquidaciones.zip'))->add($files)->close();

            $pathtoFile = public_path('exports/' . $conjunto->id . '/liquidaciones.zip');
            return response()->download($pathtoFile);
        } else {
            return ['No tiene permiso para ver esta información'];
        }
    }

    private function eliminarCarpeta($nombre)
    {
        foreach (glob($nombre . '/*') as $archivo) {
            if (is_dir($archivo)) {
                $data = explode($nombre, $archivo)[1];
                $this->eliminarCarpeta($nombre . $data);
            } else {
                @unlink($archivo);
            }
        }
        rmdir($nombre);
    }

}
