<?php

namespace App\Http\Controllers;

use App\ArchivoCargaMasiva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Tipo_unidad;
use App\Conjunto;
use App\Division;
use App\Documento;
use App\RegistroFallosCargaUnidades;
use App\TipoDivision;
use App\Unidad;
use Illuminate\Support\Facades\Auth;
use Excel;
use Excel\Concerns\WithMultipleSheets;

class ArchivoCargaMasivaController extends Controller
{

    private static $_ATRIBUTOS_POR_LISTA = array(
        "lista_mascotas" => array("código", "nombre", "raza", "fecha naciemiento (MM-DD-AAAA)", "descripcion", "foto (base64)", "unidad", "tipo mascota"),
        "lista_vehiculos" => array("foto vehículo (base64)", "foto tarjeta propiedad cara 1 (base64)", "foto tarjeta propiedad cara 2 (base64)", "Propietario", "tipo", "marca", "color", "placa", "unidad"),
        "lista_residentes" => array("tipo residente", "nombre", "apellido", "profesion", "ocupacion", "direccion", "email", "genero", "documento", "unidad", "tipo documento"),
        "lista_visitantes" => array("dicumento", "nombre", "parentesco, unidad"),
        "lista_empleados" => array("nombre", "apellido", "genero", "documento", "unidad", "tipo documento"),
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
            $archivoCargaMasiva->fila = 0;
            $archivoCargaMasiva->procesados = 0;
            $archivoCargaMasiva->fallos = 0;
            $archivoCargaMasiva->tipo_unidad_id = $request->tipo_unidad;
            $archivoCargaMasiva->conjunto_id = session('conjunto');
            if ($request->file('archivo')) {
                $file = time() . '.' . $request->archivo->getClientOriginalExtension();
                $request->archivo->move(\public_path('archivos_masivos/'), $file);
                //ruta archivo
                $archivoCargaMasiva->ruta = $file;

                //dd($archivoCargaMasiva);

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
        //
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
        //dd($archivoCargaMasiva);        
        try {
            if ($archivoCargaMasiva->ruta != '') {
                // Elimina el archivo
                @unlink(public_path('archivos_masivos/' . $archivoCargaMasiva->ruta));
            }
            $archivoCargaMasiva->delete();
            return ['res' => 1, 'msg' => 'Registro eliminado correctamente'];
        } catch (\Throwable $th) {
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
                        return $archivo->fila;
                    })->addColumn('fallos', function ($archivo) {
                        return $archivo->fallos;
                    })->addColumn('procesados', function ($archivo) {
                        return $archivo->procesados;
                    })->addColumn('estado', function ($archivo) {
                        return $archivo->estado;
                    })->addColumn('action', function ($archivo) {
                        return '<a data-toggle="tooltip" data-placement="top"
                                    title="Procesar el archivo" class="btn btn-default"
                                    onclick="runUpload(' . $archivo->id . ')">
                                    <i class="fa fa-play"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top"
                                    title="Ver resultados del proceso" href="" class="btn btn-default">
                                    <i class="fa fa-list-ol"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top"
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
        $propiedades = ["Número o letra", "Referencia", "división", "Tipo Division"];
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
    {    // Validador si llega un archivo
        // *****************************

        $archivo = ArchivoCargaMasiva::find($request->id);
        $tipoUnidad = $archivo->tipoUnidad;


        if ($archivo != null) {
            $path = 'public/archivos_masivos/' . $archivo->ruta;
            $data = Excel::load($path, function ($reader) {
            })->get();
            // Validador si el arreglo está vacío
            // **********************************   

            if (!empty($data) && $data->count() > 0) {
                try {

                    foreach($data as $key=>$value){                        
                        echo($key.' - '. $value);
                        echo('</br>');
                    }

                    $this->crearUnidad($data[0],$tipoUnidad);
                    // $this->crearResidentesUnidad();


                    $listas = [];
                    $propiedades = ["numero_letra" => "Número o letra", "referencia" => "Referencia", "división_id" => "division", "tipo_unidad_id" => "tipo_division"];
                    $aux = $tipoUnidad->atributos;
                    foreach ($aux as $value) {
                        if (str_contains($value->nombre, "lista")) {
                            $listas[] = $value->nombre;
                            continue;
                        }

                        $propiedades[] = $value->nombre;
                    }



                    // return redirect('cargaMasivaUnidad/{id}')
                    //     ->with('status', 'Se insertó correctamente')
                    //     ->with('last', "El último registro fue '$last_name' cc: '$last_cc'");
                } catch (\Throwable $th) {
                    // return redirect('usuarios')
                    //     ->with('error', 'Ocurrió un error al registrar el último registro, verifique que no se encuentre ya registrado.')
                    //     ->with('last', "El último registro fue '$last_name' cc: '$last_cc'");
                }
            }
        } else {
            //swal('Error!', 'Ocurrió un error en el servidor, no se ingresó archivo', 'error');
        }
    }

    private function agregarRegistroFallos($unRegistro, $descripcion, $idArchivo)
    {

        $registroF = new RegistroFallosCargaUnidades();
        $registroF->registro = $unRegistro;
        $registroF->descripcion_fallo = $descripcion;
        $registroF->archivo_masivo_id = $idArchivo;
        $registroF->save();
    }

    //metodo para crear una unidad desde el excel
    private function crearUnidad($data, $tipoUnidad)
    {

        // foreach ($data[0] as $key => $value) {
        //     foreach ($value as $key1 => $value1) {
        //         echo ($key1 . " - " . $value1);
        //         echo ('\n');
        //     }
        //     echo ('procesando' . $i);
        //     $unidad                    = new Unidad();
        //     $unidad->numero_letra   = $value->numero_o_letra;
        //     echo ($key . '-' . $value);
        //     dd($unidad);
        //     $unidad['referencia']        = $value['referencia'];
        //     $unidad['coeficiente'] = $value['coeficiente'];
        //     $unidad['observaciones'] = $value['observaciones'];


        //     //verificar que exsita el propietario
        //     /*****************************************/
        //     $usuario = User::where(
        //         'id',
        //         mb_strtoupper($value->propietario, 'UTF-8')
        //     )->first();
        //     if (!$usuario) {
        //         //fallo
        //         $descripcion = "No se encontro el documento del propietario";
        //         $this->agregarRegistroFallos($key, $descripcion, $archivo->id);
        //         continue;
        //     }
        //     //verificamos que la division existe y el tipo de division existe
        //     $tipoDivision = TipoDivision::where(
        //         'division',
        //         mb_strtoupper($value->tipo_division, 'UTF-8')
        //     )->first();

        //     if (!$tipoDivision) {
        //         $descripcion = "No se encontro el número de division";
        //         $this->agregarRegistroFallos($key, $descripcion, $archivo->id);
        //         continue;
        //     }
        //     $division = Division::where(
        //         [
        //             [
        //                 'numero_letra',
        //                 mb_strtoupper($value['numero o letra'], 'UTF-8')
        //             ], [
        //                 "id_tipo_division", $tipoDivision->id
        //             ]
        //         ]
        //     )->first();

        //     if (!$division) {
        //         $descripcion = "No se encontro el número de division";
        //         $this->agregarRegistroFallos($key, $descripcion, $archivo->id);
        //         continue;
        //     }

        //     $unidad->$tipoDivision->id = $value['division'];


        //     $usuario->unidades()->attach($unidad, ['fecha_ingreso' => date('Y-m-d')]);
        // }
    }

    private function crearResidentesUnidad($data, $tipoUnidad){

    }
}
