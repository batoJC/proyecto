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

class ArchivoCargaMasivaController extends Controller
{

    private static $_ATRIBUTOS_POR_LISTA = array(
        "lista_mascotas" => array("código", "nombre", "raza", "fecha nacimiento (MM-DD-AAAA)", "descripcion", "foto (base64)", "unidad", "tipo mascota"),
        "lista_vehiculos" => array("foto vehículo (base64)", "foto tarjeta propiedad cara 1 (base64)", "foto tarjeta propiedad cara 2 (base64)", "Propietario", "tipo", "marca", "color", "placa", "unidad"),
        "lista_residentes" => array("tipo residente", "nombre", "apellido", "profesion", "ocupacion", "direccion", "email", "genero", "documento", "unidad", "tipo documento"),
        "lista_visitantes" => array("documento", "nombre", "parentesco","unidad"),
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

        if ($archivo != null && $archivo->conjunto_id == session("conjunto")) {
            $path = public_path('archivos_masivos/' . $archivo->ruta);
            $data = Excel::load($path, function ($reader) {
            })->get();


            // Validador si el arreglo está vacío
            // **********************************
            if (!empty($data) && $data->count() > 0) {
                // try {
                //falta guardar en la base de datos
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
                    $unidad = $this->crearUnidad($data[0][$i], $tipoUnidad);
                    $unidad->id = 1; //for have a id aunque ese metodo no este implentado

                    $saltarRegistros = $unidad->id == 0;
                    $result = $this->agregarListasPorUnidad($data, $unidad, $indexLista, $saltarRegistros);
                    $indexLista = $result["index"];

                    if ($result["error"]) {
                        $unidad->delete();
                    }
                }

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

    //crear la lista de mascotas de la unidad que llega
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
            //si saltar segistro es falso, se agrega la mascota (unidad valida)
            if (!$saltarRegistros) {
                $mascota = new Mascota();
                $mascota->codigo = $data['codigo'];
                $mascota->nombre = $data['nombre'];
                $mascota->raza = $data['raza'];
                $mascota->fecha_nacimiento = $data['fecha nacimiento (MM-DD-AAAA)'];
                $mascota->descripcion = $data['descripcion'];
                $mascota->foto = $data['foto (base64)'];
                $mascota->unidad_id = $unidad;
                $tipoMascota = TipoMascotas::where( //revisar
                    'tipo',
                    mb_strtoupper($data['tipo mascota'], 'UTF-8')
                )->first();
                dd($tipoMascota);
                if (!$tipoMascota) {
                    $tipoMascota = new TipoMascotas();
                    $tipoMascota->tipo = mb_strtoupper($data['tipo mascota'], 'UTF-8');
                    $tipoMascota->save();
                }
                $mascota->tipo_id = $tipoMascota->id;
                $mascota->id_conjunto = session('conjunto');
                $mascota->save();
            } else {
                $saltarRegistros = true;
            }
            $index++;
        }


        return array("index" => $index, "error" => $saltarRegistros);
    }

    //cargar lista de vehiculos de la unidad
    private function cargarListaVehiculos($index, $data, $unidad, $saltarRegistros)
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
            //si saltar registro es falso, se agrega vehiculo (unidad valida)
            if (!$saltarRegistros) {
                $vehiculo = new Vehiculo();
                $vehiculo->foto_vehiculo = $data['foto vehículo (base64)'];
                $vehiculo->foto_tajeta_1 = $data['foto tarjeta propiedad cara 1 (base64)'];
                $vehiculo->foto_tarjeta_2 = $data['foto tarjeta propiedad cara 2 (base64)'];
                $vehiculo->registra = $data['propietario'];
                $vehiculo->tipo = $data['tipo'];
                $vehiculo->marca = $data['marca'];
                $vehiculo->color = $data['color'];
                $vehiculo->placa = $data['placa'];
                $vehiculo->unidad_id = $unidad;
                $vehiculo->id_conjunto = session('conjunto');
                $vehiculo->save();
            } else {
                $saltarRegistros = true;
            }
            $index++;
        }


        return array("index" => $index, "error" => $saltarRegistros);
    }

    //cargar lista de empleados de la unidad
    private function cargarListaEmpleados($index, $data, $unidad, $saltarRegistros)
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
            //si saltar registro es falso, se agrega empleado (unidad valida)
            if (!$saltarRegistros) {
                $empleado = new Empleado();
                $empleado->nombre = $data['nombre'];
                $empleado->apellido = $data['apellido'];
                $empleado->genero = $data['genero'];
                $empleado->documento = $data['documento'];
                $tipoDocumento = Tipo_Documento::where( //revisar
                    'tipo',
                    mb_strtoupper($data['tipo'], 'UTF-8')
                )->first();
                if (!$tipoDocumento) {
                    $tipoDocumento = new Tipo_Documento();
                    $tipoDocumento->tipo = mb_strtoupper($data['tipo documento'], 'UTF-8');
                    $tipoDocumento->save();
                }
                $empleado->tipo_documento_id = $tipoDocumento->id;
                $empleado->unidad_id = $unidad;
                $empleado->id_conjunto = session('conjunto');
                $empleado->save();
            } else {
                $saltarRegistros = true;
            }
            $index++;
        }


        return array("index" => $index, "error" => $saltarRegistros);
    }

    //cargar lista de visitantes de la unidad
    private function cargarListaVisitantes($index, $data, $unidad, $saltarRegistros)
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

            //si saltar registro es falso, se agrega empleado (unidad valida)
            if (!$saltarRegistros) {
                $visitante = new Visitante();
                $visitante->identificacion = $data['documento'];
                $visitante->nombre = $data['nombre'];
                $visitante->parentesco = $data['parentesco'];
                $visitante->unidad_id = $unidad;
                $visitante->id_conjunto = session('conjunto');
                $visitante->save();
            } else {
                $saltarRegistros = true;
            }
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
    private function crearUnidad($data, $tipoUnidad)
    {
        $unidad = new Unidad();

        //TODO: create unidad

        return $unidad;
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

    private function crearResidentesUnidad($data, $tipoUnidad)
    {
    }
}
