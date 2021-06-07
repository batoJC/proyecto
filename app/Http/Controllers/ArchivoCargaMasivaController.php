<?php

namespace App\Http\Controllers;

use App\ArchivoCargaMasiva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Tipo_unidad;
use App\Conjunto;
use App\User;
use Illuminate\Support\Facades\Auth;
use Excel;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessArchivoMasivoUnidades;


class ArchivoCargaMasivaController extends Controller
{

    private static $_ATRIBUTOS_POR_LISTA = array(
        "lista_mascotas" => array("código", "nombre", "raza", "fecha nacimiento", "fecha ingreso", "descripcion", "unidad", "tipo mascota"),
        "lista_vehiculos" => array("Propietario", "fecha ingreso", "tipo", "marca", "color", "placa", "unidad"),
        "lista_residentes" => array("tipo residente", "nombre", "apellido", "fecha ingreso", "profesion", "ocupacion", "direccion", "email", "genero", "documento", "unidad", "tipo documento"),
        "lista_visitantes" => array("documento", "nombre", "parentesco", "unidad", "fecha ingreso",),
        "lista_empleados" => array("nombre", "apellido", "genero", "documento", "unidad", "tipo documento", "fecha ingreso"),
        "lista_unidades" => null

    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \App\Tipo  $tipo
     */

    public function index(Tipo_unidad $tipo)
    {
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();

        return view('admin.tipo_unidad.carga_masiva.index')
            ->with('tipo_unidad', $tipo)
            ->with('conjuntos', $conjuntos);
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
        try {
            $archivoCargaMasiva = new ArchivoCargaMasiva();
            $archivoCargaMasiva->nombre_archivo = $request->nombre_archivo;
            $archivoCargaMasiva->estado = 'subido';
            $archivoCargaMasiva->procesados = 0;
            $archivoCargaMasiva->fallos = 0;
            $archivoCargaMasiva->tipo_unidad_id = $request->tipo_unidad;
            $archivoCargaMasiva->conjunto_id = session('conjunto');
            $archivoCargaMasiva->indice_unidad = 0;
            $archivoCargaMasiva->indice_residentes = 0;
            $archivoCargaMasiva->indice_mascotas = 0;
            $archivoCargaMasiva->indice_vehiculos = 0;
            $archivoCargaMasiva->indice_empleados = 0;
            $archivoCargaMasiva->indice_visitantes = 0;
            $archivoCargaMasiva->usuario_id = Auth::user()->id;

            if ($request->file('archivo')) {
                $file = $request->conjunto_id . time() . '.' . $request->archivo->getClientOriginalExtension();
                $request->archivo->move(public_path('archivos_masivos/'), $file);
                //ruta archivo
                $archivoCargaMasiva->ruta = $file;
                $archivoCargaMasiva->save();
                return array('res' => 1, 'msg' => 'Archivo subido correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'Error, por favor selecciona un archivo');
            }
        } catch (\Trowable $th) {
            Log::channel('slack')->critical("Guardar archivo carga masiva: Ocurrio el siguiente error:
                Error: {$th->getMessage()}");
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el archivo masivo.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function show(ArchivoCargaMasiva $archivoCargaMasiva)
    {
        return $archivoCargaMasiva;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function edit(ArchivoCargaMasiva $archivoCargaMasiva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArchivoCargaMasiva $archivoCargaMasiva)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ArchivoCargaMasiva  $archivoCargaMasiva
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArchivoCargaMasiva $archivoCargaMasiva)
    {
        try {
            if ($archivoCargaMasiva->ruta != '') {
                // Elimina el archivo
                if ($archivoCargaMasiva->estado == 'subido' || $archivoCargaMasiva->estado == 'terminado') {
                    @unlink(public_path('archivos_masivos/' . $archivoCargaMasiva->ruta));
                } else {
                    return ['res' => 0, 'msg' => 'No se puede eliminar un archivo mientras está siendo procesado'];
                }
            }
            $archivoCargaMasiva->delete();
            return ['res' => 1, 'msg' => 'Registro eliminado correctamente'];
        } catch (\Throwable $th) {
            $error = substr($th->getMessage(), 0, 90);
            Log::channel('slack')->critical("Error inesperado. Ocurrió un error al tratar de eliminar el registro en carga masiva de unidades
                \n Error: {$error}");
            Log::channel('daily')->critical("Error inesperado. Ocurrio un error al tratar de eliminar el registro en carga masiva de unidades
                \n Error: {$th->getMessage()}");
            return ['res' => 0, 'msg' => 'Ocurrió un error al tratar de eliminar el registro'];
        }
    }

    // para listar por datatables
    // ****************************
    public function datatables($tipo)
    {
        switch (Auth::user()->id_rol) {
            case 2:

                $archivos = ArchivoCargaMasiva::where([
                    ['tipo_unidad_id', $tipo],
                    ['conjunto_id', session('conjunto')]
                ])->get();
                return Datatables::of($archivos)
                    ->addColumn('nombre', function ($archivo) {
                        return $archivo->nombre_archivo;
                    })->addColumn('ultimoRegistro', function ($archivo) {
                        return $archivo->indice_unidad;
                    })->addColumn('fallos', function ($archivo) {
                        return $archivo->fallos;
                    })->addColumn('procesados', function ($archivo) {
                        return $archivo->procesados;
                    })->addColumn('estado', function ($archivo) {
                        return $archivo->estado;
                    })->addColumn('action', function ($archivo) {
                        $boton = '';
                        if ($archivo->estado == 'terminado') {
                            $boton = 'disabled';
                        }
                        return '<a data-toggle="tooltip" data-placement="top" id="process"
                                    title="Procesar el archivo" class="btn btn-default ' . $boton . '"
                                    onclick="runUpload(' . $archivo->id . ')">
                                    <i class="fa fa-play"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" id="seeResults"
                                    title="Ver resultados del proceso" class="btn btn-default" "
                                    onclick="showForm(' . $archivo->id . ')">
                                    <i class="fa fa-list-ol"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" id="delete"
                                    title="Eliminar este archivo" class="btn btn-default" onclick="deleteData(' . $archivo->id . ')">
                                    <i class="fa fa-trash"></i>
                                </a>
                                ';
                    })->make(true);

            default:
                return [];
        }
    }

    // Generar un excel de acuerdo al tipo de unidad y los datos requeridos
    // para una carga masiva
    public function downloadExcel(Tipo_unidad $tipoUnidad)
    {
        $listas = [];
        $propiedades = ["Número o letra", "Referencia", "División", "Tipo División", "Fecha ingreso propietario"];
        $aux = $tipoUnidad->atributos;
        foreach ($aux as $value) {
            if (str_contains($value->nombre, "lista")) {
                $listas[] = $value->nombre;
                continue;
            }

            $propiedades[] = $value->nombre;
        }

        $tipoUnidadLabel = strtolower($tipoUnidad->nombre);

        Excel::create('plantilla ' . ucfirst($tipoUnidadLabel), function ($excel) use ($tipoUnidadLabel, $propiedades, $listas) {

            $excel->sheet("atributos " . $tipoUnidadLabel, function ($sheet) use ($propiedades) {
                $sheet->fromArray($propiedades, NULL, 'A1');
            });

            foreach ($listas as $key => $value) {
                $encabezados_hoja = $this::$_ATRIBUTOS_POR_LISTA[$value];
                if (!$encabezados_hoja) {
                    continue;
                }
                $excel->sheet(str_replace("_", " ", $value), function ($sheet) use ($encabezados_hoja) {
                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($encabezados_hoja, NULL, 'A1');
                });
            }
        })->download('xlsx');
    }

    // Custom Method para cargar la vista por get
    // ******************************************
    public function unidades_csv()
    {
        //modificar todo
        $user      = User::where('id_conjunto', session('conjunto'))->get();
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();
        return view('admin.usuarios.masiva')
            ->with('user', $user)
            ->with('conjuntos', $conjuntos);
    }

    public function unidades_csv_post(Request $request)
    {
        // Validador si llega un archivo
        // *****************************
        if ($request != null) {
            //validamos que el archivo que se suba sea del conjunto o conjuntos del usuario loggeado(admin)
            $archivo = ArchivoCargaMasiva::find($request->id);
            if ($archivo->conjunto_id == Auth::user()->id_conjunto) {
                if($archivo->estado != "subido"){
                    return array('res' => 0, 'msg' => "No se puede procesar la carga masiva,
                    el proceso del archivo esta en el estado: {$archivo->estado}");
                }

                $archivo->estado = 'en progreso';
                $archivo->usuario_id = Auth::user()->id;
                $archivo->save();
                $process = new ProcessArchivoMasivoUnidades($archivo);
                $process->dispatch($archivo)->onQueue('low');

                return array('res' => 1, 'msg' => 'Carga masiva Iniciada, cuando esta termine se le notificara con un correo. Recuerde que puede consultar el estado cada vez que lo desee.');
            } else {
                return array('res' => 0, 'msg' => 'No se puede procesar la carga masiva, el usuario y el conjunto administrado no coinciden');
            }
        } else {
            return array('res' => 0, 'msg' => 'No se puede iniciar la carga masiva, no se subió ningún archivo.');
        }
    }

    public function showErrors(ArchivoCargaMasiva $archivo)
    {
        $aux = $archivo->errores;
        $errores = array();
        foreach ($aux as $key => $value) {
            $errores[] = array('error' => $value->descripcion_fallo, 'registro' => $value->registro);
        }

        return array('unidades_procesadas' => $archivo->procesados, 'unidades_fallidas' => $archivo->fallos, 'errores' => $errores);
    }
}
