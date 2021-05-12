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
                $request->archivo->move(public_path('archivos_masivos/'), $file);
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
        //dd($archivoCargaMasiva);
        try {
            if ($archivoCargaMasiva->ruta != '') {
                // Elimina el archivo
                //TODO :only delete when the status are load or completed
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
        $propiedades = ["Número o letra", "Referencia", "División", "Tipo División","Fecha ingreso"];
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

        if ($archivo != null && $archivo->conjunto_id == session("conjunto")) {
            $path = public_path('archivos_masivos/' . $archivo->ruta);
            $data = Excel::load($path, function ($reader) {
            })->get();


            // Validador si el arreglo está vacío
            // **********************************
            if (!empty($data) && $data->count() > 0) {
                // try {
                $indexLista = array(
                    "lista mascotas" => 0,
                    "lista vehiculos" => 0,
                    "lista residentes" => 0,
                    "lista visitantes" => 0,
                    "lista empleados" => 0
                );

                for ($i = 0; $i < $data[0]->count(); $i++) {
                    //hay que eliminar la unidad y todo lo que se creo si es que
                    //llega a fallar el proceso de acá en adelante
                    $unidad = $this->crearUnidad($i, $data[0][$i], $tipoUnidad, $archivo);
                    if(!$unidad){
                        $archivo->fila = $i;
                        $archivo->save();
                        continue;
                    }

                    $saltarRegistros = $unidad->id == 0;
                    $result = $this->agregarListasPorUnidad($data, $unidad, $indexLista, $saltarRegistros);
                    $indexLista = $result["index"];

                    if ($result["error"]) {
                        $unidad->delete();
                    }
                }

                var_dump($indexLista);

                //when finished and all is good
                return array('res' => 1, 'msg' => 'Carga masiva terminada.');
                // } catch (\Throwable $th) {
                //     return array('res' => 0, 'msg' => 'Error en la carga masiva.');
                // }
            }
        } else {
            return array('res' => 0, 'msg' => 'Ese archivo no existe');
        }
    }

    private function agregarListasPorUnidad($hojasExcel, $unidad, $indexLista, $saltarRegistros)
    {
        $error = false;
        for ($i = 1; $i < $hojasExcel->count(); $i++) {
            // echo ($hojasExcel[$i]->getTitle());
            // $indexLista["lista mascotas"]++;
            $nombreHoja = $hojasExcel[$i]->getTitle();
            switch ($nombreHoja) {
                case  "lista mascotas":
                    $result = $this->crearListaMascotas($indexLista[$nombreHoja], $hojasExcel[$i], $unidad, $saltarRegistros);
                    $saltarRegistros = $result["error"];
                    $indexLista[$nombreHoja] = $result["index"];

                    break;
                case "lista vehiculos":
                    $indexLista[$nombreHoja]++;

                    break;
                case "lista residentes":
                    $indexLista[$nombreHoja]++;

                    break;
                case "lista visitantes":
                    $indexLista[$nombreHoja]++;

                    break;
                case "lista empleados":
                    $indexLista[$nombreHoja]++;

                    break;
            }
        }

        return array("index" => $indexLista, "error" => $error);
    }

    private function crearListaMascotas($index, $data, $unidad, $saltarRegistros)
    {
        while ($index < $data->count()) {
            //si la unidad que  nos dice no es la que vamos agregar decrementamos el
            //indice y terminamos el ciclo
            // var_dump($data);

            if ($this->dataVacia($data[$index])) {
                break;
            }

            if ($unidad->numero_letra != $data[$index]["unidad"]) {
                $index--;
                break;
            }


            //se crea la mascota
            $index++;
        }

        return array("index" => $index, "error" => $saltarRegistros);
    }


    private function dataVacia($data)
    {
        foreach ($data as $value) {
            if ($value != "") {
                return false;
            }
        }
        return true;
    }

    private function agregarRegistroFallos($unRegistro, $descripcion, $idArchivo)
    {
        $registroF = new RegistroFallosCargaUnidades();
        $registroF->registro = $unRegistro;
        $registroF->descripcion_fallo = $descripcion;
        $registroF->archivo_masivo_id = $idArchivo;
        $registroF->save();
    }

    /**
     * metodo para crear una unidad desde el excel
     *
     * @param  \App\Tipo_unidad  $tipoUnidad
     * @return \App\Unidad
     */
    private function crearUnidad($fila, $data, $tipoUnidad, $archivo)
    {
        $fila += 2;

        $k=0;
        for ($i=0; $i < 10000 ; $i++) {
            for ($j=0; $j < 1000 ; $j++) {
                for ($m=0; $m < 10000000 ; $m++) {
                    $k++;
                }
            }
        }

        return null;

        if ($this->dataVacia($data)) {
            $descripcion = "Fila vacía, puede ser el fin del archivo";
            $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);
            return null;
        }

        $unidad = new Unidad();
        $unidad->numero_letra = $data["numero_o_letra"];
        $unidad->referencia = $data["referencia"];
        $unidad->coeficiente = $data["coeficiente"];
        $unidad->observaciones = $data["observaciones"];

        //verificamos que la division existe y el tipo de division existe
        $tipoDivision = TipoDivision::where([
            ['division', mb_strtoupper($data->tipo_division, 'UTF-8')]
        ])->first();

        if (!$tipoDivision) {
            $descripcion = "No se encontro el tipo de división";
            $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);

            return $unidad;
        }

        $division = Division::where([
            ['numero_letra', mb_strtoupper($data['division'], 'UTF-8')],
            ["id_tipo_division", $tipoDivision->id],
            ["id_conjunto", session("conjunto")]
        ])->first();

        if (!$division) {
            $descripcion = "No se encontro el número de division";
            $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);

            return $unidad;
        }

        $unidad->division_id = $division->id;
        $unidad->conjunto_id = session("conjunto");
        $unidad->tipo_unidad_id = $tipoUnidad->id;

        try {
            $unidad->save();
        } catch (\Throwable $th) {
            if(str_contains ($th->getMessage(),"unidad_unica")){
                $descripcion = "Ya existe una unidad con esas propiedades.";
            }else{
                $descripcion = "Error desconocido al crear la unidad.";
            }

            $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);

            return $unidad;
        }


        $coeficiente = false;
        foreach ($tipoUnidad->atributos as $value) {
            if ($value->nombre == "coeficiente") {
                $coeficiente = true;
                break;
            }
        }

        if ($coeficiente) { //si tiene coeficiente debe de tener un propietario
            $propietario = User::where([
                ['numero_cedula',$data->propietario],
                ['id_conjunto', session("conjunto")],
                ["id_rol", 3]
            ])->first();

            if (!$propietario) {
                $descripcion = "No se encontro el documento del propietario";
                $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);
                $unidad->delete();

                return $unidad;
            }

            $propietario->unidades()->attach($unidad, ['fecha_ingreso' => $data['fecha_ingreso']]);
        }
        //TODO: create unidad

        return $unidad;
    }

    private function crearResidentesUnidad($data, $tipoUnidad)
    {
    }
}
