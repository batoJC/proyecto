<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\ArchivoCargaMasiva;
use App\Division;
use App\User;
use App\Mascota;
use App\RegistroFallosCargaUnidades;
use App\Tipo_Documento;
use App\TipoDivision;
use App\Unidad;
use App\Vehiculo;
use App\Conjunto;
use App\Visitante;
use Excel;
use Illuminate\Support\Facades\Crypt;
use App\TipoMascotas;
use App\Empleado;
use App\Residentes;
use App\Http\Controllers\CorreoController;
use Illuminate\Support\Facades\Log;

class ProcessArchivoMasivoUnidades implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const LETRAS_ERROR = 300;

    protected $archivoMasivo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ArchivoCargaMasiva $archivoMasivo)
    {
        $this->archivoMasivo = $archivoMasivo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ArchivoCargaMasiva $archivo)
    {
        try {
            $tipoUnidad = $this->archivoMasivo->tipoUnidad;

            if ($this->archivoMasivo != null) {
                //comprobamos que el archivo no se ha procesado
                if ($this->archivoMasivo->estado != 'terminado') {

                    $path = public_path('archivos_masivos/' . $this->archivoMasivo->ruta);
                    $data = Excel::load($path, function ($reader) {
                    })->get();

                    // Validador si el arreglo está vacío
                    // **********************************
                    if (!empty($data) && $data->count() > 0) {


                        //falta guardar en la base de datos
                        $indexLista = array(
                            "lista mascotas" => $this->archivoMasivo->indice_mascotas,
                            "lista vehiculos" => $this->archivoMasivo->indice_vehiculos,
                            "lista residentes" => $this->archivoMasivo->indice_residentes,
                            "lista visitantes" => $this->archivoMasivo->indice_visitantes,
                            "lista empleados" => $this->archivoMasivo->indice_empleados
                        );

                        $unidadesAgregadas = $this->archivoMasivo->procesados;
                        $unidadesNoAgregadas = $this->archivoMasivo->fallos;

                        $i = $this->archivoMasivo->indice_unidad;
                        for ($i; $i < $data[0]->count(); $i++) {
                            //hay que eliminar la unidad y todo lo que se creo si es que
                            //llega a fallar el proceso de acá en adelante
                            $unidad = $this->crearUnidad($i, $data[0][$i], $tipoUnidad, $this->archivoMasivo);
                            if (!$unidad) {
                                $this->archivoMasivo->indice_unidad = $i;
                                $this->archivoMasivo->save();

                                break;
                            }


                            $saltarRegistros = $unidad->id == 0;
                            $result = $this->agregarListasPorUnidad($data, $unidad, $indexLista, $saltarRegistros, $this->archivoMasivo);
                            $indexLista = $result["index"];

                            if ($result["error"]) {
                                $unidad->delete();
                                $unidadesNoAgregadas++;
                            }

                            $unidadesAgregadas++;
                            $this->archivoMasivo->indice_unidad = $i;
                            $this->archivoMasivo->procesados = $unidadesAgregadas;
                            $this->archivoMasivo->fallos = $unidadesNoAgregadas;
                            $this->archivoMasivo->indice_residentes = $indexLista['lista residentes'];
                            $this->archivoMasivo->indice_mascotas = $indexLista['lista mascotas'];
                            $this->archivoMasivo->indice_empleados = $indexLista['lista empleados'];
                            $this->archivoMasivo->indice_vehiculos = $indexLista['lista vehiculos'];
                            $this->archivoMasivo->indice_visitantes = $indexLista['lista visitantes'];
                            $this->archivoMasivo->save();
                        }
                        $this->archivoMasivo->indice_unidad = $i;
                        $this->archivoMasivo->estado = "terminado";
                        $this->archivoMasivo->procesados = $unidadesAgregadas;
                        $this->archivoMasivo->fallos = $unidadesNoAgregadas;
                        $this->archivoMasivo->save();

                        //when finished and all is good
                        $this->enviarEmailRespuesta("Carga masiva terminada.");
                    }
                } else {
                    $this->enviarEmailRespuesta("El archivo ya fue procesado");
                }
            } else {
                $this->enviarEmailRespuesta("El archivo no existe");
            }
        } catch (\Throwable $th) {
            $this->archivoMasivo->estado = "subido";
            $this->archivoMasivo->save();
            $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
            Log::channel('slack')->critical("Ocurrió un error al realizar la carga masiva:
            Email: {$this->archivoMasivo->usuario->email}
            Archivo: {$this->archivoMasivo->nombre_archivo}
            Error: {$error}
            Conjunto: {$this->archivoMasivo->conjunto->nombre}");
            Log::channel('daily')->critical("Ocurrió un error al realizar la carga masiva:
             Email: {$this->archivoMasivo->usuario->email}
            Archivo: {$this->archivoMasivo->nombre_archivo}
            Conjunto: {$this->archivoMasivo->conjunto->nombre}
            Error: {$th->getMessage()}");
            $this->enviarEmailRespuesta("Ocurrió un error al procesar la carga masiva, si el error persiste pongase en contacto con nuestro soporte técnico");
        }
    }

    private function enviarEmailRespuesta($mensaje)
    {
        try {
            $conjunto = new Conjunto();
            $conjunto->nombre = 'Gestión copropietario';
            $conjunto->correo = env("MAIL_USERNAME");
            $conjunto->password = Crypt::encrypt(env("MAIL_PASSWORD"));
            $usuario = $this->archivoMasivo->usuario;
            $correo = new CorreoController();
            $nombreArchivo = $this->archivoMasivo->nombre_archivo;
            $salida = $correo->enviarEmail($conjunto, [$usuario], "Estado carga masiva {$nombreArchivo}", $mensaje);
            if (!$salida) {
                Log::channel('slack')->critical("Error, no se pudo enviar mensaje al terminar el proceso de carga masiva para:
                 Email: {$this->archivoMasivo->usuario->email}
                Archivo: {$this->archivoMasivo->nombre_archivo}
                Conjunto: {$this->archivoMasivo->conjunto->nombre}
                Mensaje: {$mensaje}");
            }
        } catch (\Throwable $th) {
            $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
            Log::channel('slack')->critical("Error, Ocurrió un error en el proceso de carga masiva para:
             Email: {$this->archivoMasivo->usuario->email}
            Archivo: {$this->archivoMasivo->nombre_archivo}
            Conjunto: {$this->archivoMasivo->conjunto->nombre}
            Mensaje: {$mensaje}
            Error: {$error}");
            Log::channel('daily')->critical("Error, Ocurrió un error en el proceso de carga masiva para:
             Email: {$this->archivoMasivo->usuario->email}
            Archivo: {$this->archivoMasivo->nombre_archivo}
            Conjunto: {$this->archivoMasivo->conjunto->nombre}
            Mensaje: {$mensaje}
            Error: {$th->getMessage()}");
        }
    }


    private function agregarListasPorUnidad($hojasExcel, $unidad, $indexLista, $error, $archivo)
    {
        for ($i = 1; $i < $hojasExcel->count(); $i++) {
            $nombreHoja = $hojasExcel[$i]->getTitle();
            switch ($nombreHoja) {
                case  "lista mascotas":
                    $result = $this->crearListaMascotas($indexLista[$nombreHoja], $hojasExcel[$i], $unidad, $error, $archivo);
                    $error = $result["error"];
                    $indexLista[$nombreHoja] = $result["index"];

                    break;
                case "lista vehiculos":
                    $result = $this->crearListaVehiculos($indexLista[$nombreHoja], $hojasExcel[$i], $unidad, $error, $archivo);
                    $error = $result["error"];
                    $indexLista[$nombreHoja] = $result["index"];

                    break;
                case "lista residentes":
                    $result = $this->crearListaResidentes($indexLista[$nombreHoja], $hojasExcel[$i], $unidad, $error, $archivo);
                    $error = $result["error"];
                    $indexLista[$nombreHoja] = $result["index"];

                    break;
                case "lista visitantes":
                    $result = $this->crearListaVisitantes($indexLista[$nombreHoja], $hojasExcel[$i], $unidad, $error, $archivo);
                    $error = $result["error"];
                    $indexLista[$nombreHoja] = $result["index"];

                    break;
                case "lista empleados":
                    $result = $this->crearListaEmpleados($indexLista[$nombreHoja], $hojasExcel[$i], $unidad, $error, $archivo);
                    $error = $result["error"];
                    $indexLista[$nombreHoja] = $result["index"];

                    break;
            }
        }

        return array("index" => $indexLista, "error" => $error);
    }

    //crear la lista de residentes de la unidad
    private function crearListaResidentes($index, $data, $unidad, $saltarRegistros, $archivo)
    {
        while ($index < $data->count()) {
            //si la unidad que  nos dice no es la que vamos agregar decrementamos el
            //indice y terminamos el ciclo

            if ($this->dataVacia($data[$index])) {
                break;
            }

            if ($unidad->numero_letra != $data[$index]["unidad"]) {
                break;
            }
            //si saltar segistro es falso, se agrega la mascota (unidad valida)
            if (!$saltarRegistros) {
                $residente = new Residentes();
                $residente->tipo_residente = $data[$index]['tipo_residente'];
                $residente->nombre = $data[$index]['nombre'];
                $residente->apellido = $data[$index]['apellido'];
                $residente->profesion = $data[$index]['profesion'];
                $residente->ocupacion = $data[$index]['ocupacion'];
                $residente->direccion = $data[$index]['direccion'];
                $residente->email = $data[$index]['email'];
                $residente->fecha_nacimiento = date("Y-m-d", strtotime($data[$index]["fecha_nacimiento"])); //falta excel
                $residente->genero = $data[$index]['genero'];
                $residente->documento = $data[$index]['documento'];
                $residente->fecha_ingreso = date("Y-m-d", strtotime($data[$index]["fecha_ingreso"]));
                $residente->unidad_id = $unidad->id;
                $tipoDocumento = Tipo_Documento::where(
                    'tipo',
                    mb_strtoupper($data[$index]['tipo_documento'], 'UTF-8')
                )->first();
                if (!$tipoDocumento) {
                    $tipoDocumento = new Tipo_Documento();
                    $tipoDocumento->tipo = mb_strtoupper($data[$index]['tipo_documento'], 'UTF-8');
                    try {
                        $tipoDocumento->save();
                    } catch (\Throwable $th) {
                        $descripcion = 'No se pudo agregar el tipo documento al momento de crear el residente en la unidad';
                        $this->agregarRegistroFallos($index, $descripcion, $archivo->id);

                        $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                        Log::channel('slack')->critical("Error, no se pudo agregar el documento al crear el redidente en la unidad, en carga masiva de unidades
                         Error: {$error}");
                        Log::channel('daily')->critical("Error, no se pudo agregar el documento al crear el redidente en la unidad, en carga masiva de unidades
                         Error: {$th->getMessage()}");
                    }
                }
                $residente->tipo_documento_id = $tipoDocumento->id;
                $residente->id_conjunto = $archivo->conjunto_id;
                try {
                    $residente->save();
                } catch (\Throwable $th) {
                    $descripcion = 'No se pudo guardar el residente a la unidad';
                    $this->agregarRegistroFallos($index, $descripcion, $archivo->id);

                    $error = substr($th->getMessage(), 0, 90);
                    Log::channel('slack')->critical("Error, no se puedo agregar el residente a la unidad, en carga masiva de unidades
                         Error: {$error}");
                    Log::channel('daily')->critical("Error, no se puedo agregar el residente a la unidad, en carga masiva de unidades
                         Error: {$th->getMessage()}");
                }
            }
            $index++;
        }


        return array("index" => $index, "error" => $saltarRegistros);
    }

    //crear la lista de mascotas de la unidad que llega
    private function crearListaMascotas($index, $data, $unidad, $saltarRegistros, $archivo)
    {
        while ($index < $data->count()) {
            //si la unidad que  nos dice no es la que vamos agregar decrementamos el
            //indice y terminamos el ciclo

            if ($this->dataVacia($data[$index])) {
                break;
            }

            if ($unidad->numero_letra != $data[$index]["unidad"]) {
                break;
            }
            //si saltar segistro es falso, se agrega la mascota (unidad valida)
            if (!$saltarRegistros) {
                $mascota = new Mascota();
                $mascota->codigo = $data[$index]['codigo'];
                $mascota->nombre = $data[$index]['nombre'];
                $mascota->raza = $data[$index]['raza'];
                $mascota->fecha_nacimiento = date("Y-m-d", strtotime($data[$index]["fecha_nacimiento"]));
                $mascota->descripcion = $data[$index]['descripcion'];
                $mascota->foto = '';
                $mascota->fecha_ingreso = date("Y-m-d", strtotime($data[$index]["fecha_ingreso"]));
                $mascota->unidad_id = $unidad->id;
                $tipoMascota = TipoMascotas::where( //revisar
                    'tipo',
                    mb_strtoupper($data[$index]['tipo_mascota'], 'UTF-8')
                )->first();
                if (!$tipoMascota) {
                    $tipoMascota = new TipoMascotas();
                    $tipoMascota->tipo = mb_strtoupper($data[$index]['tipo_mascota'], 'UTF-8');
                    try {
                        $tipoMascota->save();
                    } catch (\Throwable $th) {
                        $descripcion = 'No se pudo agregar el tipo de mascota, cuando agregamos mascota a la unidad';
                        $this->agregarRegistroFallos($index, $descripcion, $archivo->id);
                        $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                        Log::channel('slack')->critical("Error, No se pudo agregar el tipo de mascota, cuando agregamos mascota a la unidad, en carga masiva de unidades
                             Error: {$error}");
                        Log::channel('daily')->critical("Error, No se pudo agregar el tipo de mascota, cuando agregamos mascota a la unidad,  en carga masiva de unidades
                             Error: {$th->getMessage()}");
                    }
                }
                $mascota->tipo_id = $tipoMascota->id;
                $mascota->id_conjunto = $archivo->conjunto_id;
                try {
                    $mascota->save();
                } catch (\Throwable $th) {
                    $descripcion = 'No se pudo agregar la mascota a la unidad';
                    $this->agregarRegistroFallos($index, $descripcion, $archivo->id);
                    $error = substr($th->getMessage(), 0, 90);
                    Log::channel('slack')->critical("Error, no se pudo agregar la mascota a la unidad, en carga masiva de unidades
                         Error: {$error}");
                    Log::channel('daily')->critical("Error, no se pudo agregar la mascota a la unidad, en carga masiva de unidades
                        \n Error: {$th->getMessage()}");
                }
            }
            $index++;
        }
        return array("index" => $index, "error" => $saltarRegistros);
    }

    //cargar lista de vehiculos de la unidad
    private function crearListaVehiculos($index, $data, $unidad, $saltarRegistros, $archivo)
    {

        while ($index < $data->count()) {
            //si la unidad que  nos dice no es la que vamos agregar decrementamos el
            //indice y terminamos el ciclo

            if ($this->dataVacia($data[$index])) {
                break;
            }

            if ($unidad->numero_letra != $data[$index]["unidad"]) {
                break;
            }
            //si saltar registro es falso, se agrega vehiculo (unidad valida)
            if (!$saltarRegistros) {
                $vehiculo = new Vehiculo();
                $vehiculo->foto_vehiculo = '';
                $vehiculo->foto_tarjeta_1 = '';
                $vehiculo->foto_tarjeta_2 = '';
                $vehiculo->registra = $data[$index]['propietario'];
                $vehiculo->tipo = $data[$index]['tipo'];
                $vehiculo->marca = $data[$index]['marca'];
                $vehiculo->fecha_ingreso = date("Y-m-d", strtotime($data[$index]["fecha_ingreso"]));
                $vehiculo->color = $data[$index]['color'];
                $vehiculo->placa = $data[$index]['placa'];
                $vehiculo->unidad_id = $unidad->id;
                $vehiculo->id_conjunto = $archivo->conjunto_id;
                try {
                    $vehiculo->save();
                } catch (\Throwable $th) {
                    $descripcion = 'No se pudo agregar el vehiculo a la unidad';
                    $this->agregarRegistroFallos($index, $descripcion, $archivo->id);
                    $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                    Log::channel('slack')->critical("Error, no se pudo agregar el vehiculo a la unidad, en carga masiva de unidades
                        \n Error: {$error}");
                    Log::channel('daily')->critical("Error no se pudo agregar el vehiculo a la unidad, en carga masiva de unidades
                        \n Error: {$th->getMessage()}");
                }
            }
            $index++;
        }
        return array("index" => $index, "error" => $saltarRegistros);
    }

    //cargar lista de empleados de la unidad
    private function crearListaEmpleados($index, $data, $unidad, $saltarRegistros, $archivo)
    {

        while ($index < $data->count()) {
            //si la unidad que  nos dice no es la que vamos agregar decrementamos el
            //indice y terminamos el ciclo

            if ($this->dataVacia($data[$index])) {
                break;
            }

            if ($unidad->numero_letra != $data[$index]["unidad"]) {
                break;
            }
            //si saltar registro es falso, se agrega empleado (unidad valida)
            if (!$saltarRegistros) {
                $empleado = new Empleado();
                $empleado->nombre = $data[$index]['nombre'];
                $empleado->apellido = $data[$index]['apellido'];
                $empleado->genero = $data[$index]['genero'];
                $empleado->documento = $data[$index]['documento'];
                $empleado->fecha_ingreso = date("Y-m-d", strtotime($data[$index]["fecha_ingreso"]));
                $tipoDocumento = Tipo_Documento::where(
                    'tipo',
                    mb_strtoupper($data[$index]['tipo_documento'], 'UTF-8')
                )->first();
                if (!$tipoDocumento) {
                    $tipoDocumento = new Tipo_Documento();
                    $tipoDocumento->tipo = mb_strtoupper($data[$index]['tipo_documento'], 'UTF-8');
                    try {
                        $tipoDocumento->save();
                    } catch (\Throwable $th) {
                        $descripcion = 'No se pudo agregar el tipo de documento, cuando se agregaba empleado';
                        $this->agregarRegistroFallos($index, $descripcion, $archivo->id);
                        $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                        Log::channel('slack')->critical("Error, no se pudo agregar el tipo de documento, cuando se agregaba empleado, en carga masiva de unidades
                            \n Error: {$error}");
                        Log::channel('daily')->critical("Error, no se pudo agregar el tipo de documento, cuando se agregaba empleado, en carga masiva de unidades
                            \n Error: {$th->getMessage()}");
                    }
                }

                $empleado->tipo_documento_id = $tipoDocumento->id;
                $empleado->unidad_id = $unidad->id;
                $empleado->id_conjunto = $archivo->conjunto_id;
                try {
                    $empleado->save();
                } catch (\Throwable $th) {
                    $descripcion = 'No se pudo agregar el empleado a la unidad';
                    $this->agregarRegistroFallos($index, $descripcion, $archivo->id);
                    $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                    Log::channel('slack')->critical("Error, No se pudo agregar el empleado a la unidad, en carga masiva de unidades
                        \n Error: {$error}");
                    Log::channel('daily')->critical("Error, No se pudo agregar el empleado a la unidad en carga masiva de unidades
                        \n Error: {$th->getMessage()}");
                }
            }
            $index++;
        }


        return array("index" => $index, "error" => $saltarRegistros);
    }

    //cargar lista de visitantes de la unidad
    private function crearListaVisitantes($index, $data, $unidad, $saltarRegistros, $archivo)
    {

        while ($index < $data->count()) {
            //si la unidad que  nos dice no es la que vamos agregar decrementamos el
            //indice y terminamos el ciclo

            if ($this->dataVacia($data[$index])) {
                break;
            }

            if ($unidad->numero_letra != $data[$index]["unidad"]) {
                break;
            }

            //si saltar registro es falso, se agrega empleado (unidad valida)
            if (!$saltarRegistros) {
                $visitante = new Visitante();
                $visitante->identificacion = $data[$index]['documento'];
                $visitante->nombre = $data[$index]['nombre'];
                $visitante->parentesco = $data[$index]['parentesco'];
                $visitante->unidad_id = $unidad->id;
                $visitante->fecha_ingreso = date("Y-m-d", strtotime($data[$index]["fecha_ingreso"]));
                $visitante->id_conjunto = $archivo->conjunto_id;
                try {
                    $visitante->save();
                } catch (\Throwable $th) {
                    $descripcion = 'No se pudo agregar el visitante a la unidad';
                    $this->agregarRegistroFallos($index, $descripcion, $archivo->id);
                    $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                    Log::channel('slack')->critical("Error, No se pudo agregar el visitante a la unidad, en carga masiva de unidades
                        \n Error: {$error}");
                    Log::channel('daily')->critical("Error, No se pudo agregar el visitante a la unidad en carga masiva de unidades
                        \n Error: {$th->getMessage()}");
                }
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
    private function crearUnidad($fila, $data, $tipoUnidad, $archivo)
    {
        $fila += 2;

        if ($this->dataVacia($data)) {
            $descripcion = "Fila vacía, puede ser el fin del archivo. Fila: " . $fila;
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
            ["id_conjunto", $archivo->conjunto_id]
        ])->first();

        if (!$division) {
            $descripcion = "No se encontro el número de division";
            $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);

            return $unidad;
        }

        $unidad->division_id = $division->id;
        $unidad->conjunto_id = $archivo->conjunto_id;
        $unidad->tipo_unidad_id = $tipoUnidad->id;

        try {
            $unidad->save();
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), "unidad_unica")) {
                $descripcion = "La unidad " . $data["numero_o_letra"] . " Ya existe, con esas propiedades.";
            } else {
                $descripcion = "Error desconocido al crear la unidad.";
                $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                Log::channel('slack')->critical("Error desconocido al crear la unidad en carga masiva de unidades
                    \n Error: {$error}");
                Log::channel('daily')->critical("Error desconocido al crear la unidaden carga masiva de unidades
                    \n Error: {$th->getMessage()}");
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
                ['numero_cedula', $data->propietario],
                ['id_conjunto', $archivo->conjunto_id],
                ["id_rol", 3]
            ])->first();

            if (!$propietario) {
                $descripcion = "No se encontro el documento del propietario";
                $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);
                $unidad->delete();

                return $unidad;
            }

            try {
                $propietario->unidades()->attach($unidad, ['fecha_ingreso' => $data['fecha_ingreso_propietario']]);
            } catch (\Throwable $th) {
                $unidad->delete();
                if (str_contains($th->getMessage(), "Invalid datetime format")) {
                    $descripcion = "Problema con el formato de la fecha de ingreso de la unidad. Unidad: " . $unidad->numero_letra;
                } else {
                    $descripcion = "Error desconocido al asociar el propietario con la unidad. Unidad: " . $unidad->numero_letra;
                }

                $this->agregarRegistroFallos($fila, $descripcion, $archivo->id);

                $error = substr($th->getMessage(), 0, self::LETRAS_ERROR);
                Log::channel('slack')->critical("Error inesperado. Agregando residente en carga masiva de unidades
                \n Error: {$error}");
                Log::channel('daily')->critical("Error inesperado. Agregando residente en carga masiva de unidades
                \n Error: {$th->getMessage()}");

                return $unidad;
            }
        }

        return $unidad;
    }
}
