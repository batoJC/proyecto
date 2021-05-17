<?php

namespace App\Http\Controllers;

use App\ArchivoCargaMasiva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Tipo_unidad;
use App\Conjunto;
use App\Division;
use App\User;
use App\Documento;
use App\Mascota;
use App\RegistroFallosCargaUnidades;
use App\Tipo_Documento;
use App\TipoDivision;
use App\TipoDocumento;
use App\Unidad;
use App\Vehiculo;
use App\Visitante;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\TipoMascotas;
use App\Empleado;
use App\Residentes;
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
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar la mascota.');
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
                //TODO :only delete when the status are load or completed
                @unlink(public_path('archivos_masivos/' . $archivoCargaMasiva->ruta));
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

            $excel->sheet("attributos " . $tipoUnidadLabel, function ($sheet) use ($propiedades) {
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

        $archivo = ArchivoCargaMasiva::find($request->id);
        $archivo->email = Auth::user()->email;
        $archivo->save();
        // dd($archivo);
        $process = new ProcessArchivoMasivoUnidades($archivo);
        $process->dispatch($archivo);

        return array('res' => 1, 'msg' => 'Carga masiva Iniciada, cuando esta termine se le notificara con un correo. Recuerde que puede consultar el estado cada vez que lo desee.');
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
