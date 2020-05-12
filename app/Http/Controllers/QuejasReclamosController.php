<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Conjunto;
use Yajra\Datatables\Datatables;
use App\Proveedor;
use App\QuejasReclamos;
use Illuminate\Http\Request;
use ZIP;

class QuejasReclamosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        session(['section' => 'quejas_reclamos']);

        if (Auth::user()->id_rol == 2) {
            $user            = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos       = Conjunto::find(session('conjunto'));
            $proveedores     = Proveedor::get();
            return view('admin.quejas_reclamos.index')
                ->with('user', $user)
                ->with('conjuntos', $conjuntos)
                // ->with('quejas_reclamos', $quejas_reclamos)
                ->with('proveedores', $proveedores);
        } elseif (Auth::user()->id_rol == 3) {

            return view('dueno.quejas_reclamos.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/quejas_reclamos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quejas_reclamos              = new QuejasReclamos();
        $quejas_reclamos->tipo        = $request->tipo;
        $quejas_reclamos->peticion      = $request->peticion;
        $quejas_reclamos->hechos = $request->hechos;
        $quejas_reclamos->estado      = 'Pendiente';
        $quejas_reclamos->id_user     = Auth::user()->id;
        if (Auth::user()->id_conjunto != null) {
            $quejas_reclamos->id_conjunto = Auth::user()->id_conjunto;
        } elseif (session('conjunto_user') != null) {
            $quejas_reclamos->id_conjunto = session('conjunto_user');
        }
        // Validación para la fecha
        // *****************************************************************************
        $quejas_reclamos->fecha_solicitud  = date('Y-m-d');
        // $quejas_reclamos->fecha_solicitud  = date('').'-'.date('m').'-'.date('d');
        // $fecha                             = date('Y-m-j');
        // $nuevafecha                        = strtotime ( '+15 day' , strtotime ( $fecha ) ) ;
        // $nuevafecha                        = date ('Y-m-j' , $nuevafecha);
        // *****************************************************************************
        $quejas_reclamos->dias_restantes   = 15;

        if ($request->hasFile('archivo')) {
            $file = time() . '.' . $request->archivo->getClientOriginalExtension();
            $request->archivo->move(public_path('quejas'), $file);
            // Ruta de la img
            $quejas_reclamos->archivo = $file;
        }

        $quejas_reclamos->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quejas_reclamos = QuejasReclamos::find($id);
        return $quejas_reclamos;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quejas_reclamos = QuejasReclamos::find($id);
        return $quejas_reclamos;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quejas_reclamos              = QuejasReclamos::find($id);
        $quejas_reclamos->tipo        = $request->tipo;
        $quejas_reclamos->peticion      = $request->peticion;
        $quejas_reclamos->hechos = $request->hechos;
        $quejas_reclamos->id_user     = Auth::user()->id;
        if (Auth::user()->id_conjunto != null) {
            $quejas_reclamos->id_conjunto = Auth::user()->id_conjunto;
        } elseif (session('conjunto_user') != null) {
            $quejas_reclamos->id_conjunto = session('conjunto_user');
        }

        if ($request->hasFile('archivo')) {
            if ($quejas_reclamos->archivo != '') {
                @unlink(public_path('quejas') . '/' . $quejas_reclamos->archivo);
            }
            $file = time() . '.' . $request->archivo->getClientOriginalExtension();
            $request->archivo->move(public_path('quejas'), $file);
            // Ruta de la img
            $quejas_reclamos->archivo = $file;
        }

        $quejas_reclamos->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $peticion = QuejasReclamos::find($id);
        if ($peticion->estado == "Pendiente") {
            if ($peticion->archivo != '') {
                @unlink(public_path('quejas') . '/' . $peticion->archivo);
            }
            $peticion->delete();
            return array('res' => 1, "msg" => "La petición o reclamo fue eliminada correctamente.");
        } else {
            return array('res' => 0, "msg" => "La petición no puede ser eliminada ya que el administrador tomo medidas sobre la misma");
        }
    }

    // Cambiar de estado
    // *****************

    public function cambiar_estado(Request $request, $id)
    {
        $quejas_reclamos               = QuejasReclamos::find($id);
        $quejas_reclamos->estado       = $request->estado;
        $quejas_reclamos->tipo = $request->tipo;
        $quejas_reclamos->dias_restantes = $request->dias_restantes;
        if ($request->respuesta != '') {
            $quejas_reclamos->fecha_respuesta = date('Y-m-d');
            $quejas_reclamos->respuesta = $request->respuesta;
            $quejas_reclamos->id_proveedor = $request->id_proveedor;
        }

        $quejas_reclamos->save();
    }

    // Respuesta de la peticion
    // ************************

    public function respuesta($id)
    {
        // $respuestas = Respuesta_peticion::find($id);
        // return $respuestas;
        $peticion = QuejasReclamos::find($id);
        $salida = array(
            'fecha_respuesta' => $peticion->fecha_respuesta,
            'proveedor' => ($peticion->proveedor) ? $peticion->proveedor->nombre_completo . " - " . $peticion->proveedor->celular : 'No aplica',
            'respuesta' => $peticion->respuesta
        );
        return $salida;
    }


    public function prueba()
    {
        // mkdir(public_path().'/'.time());
        @unlink(public_path('/1578444936'));
        return;

        // return "2";
        // $files = glob(public_path('imgs/*'));
        $files = public_path('imgs/*');

        $zip = new ZIP();

        /* Le indicamos en que carpeta queremos que se genere el zip y los comprimimos*/
        $zip->make(public_path('zip/ejemplo.zip'))->add($files)->close();

        /* Por último, si queremos descarlos, indicaremos la ruta del archiv, su nombre
        y lo descargaremos*/
        return response()->download(public_path('zip/ejemplo.zip'));
    }


    public function eliminarCarpeta($nombre)
    {
        // echo public_path($nombre);
        echo $nombre;
        echo '<br>';

        foreach (glob(public_path($nombre) . '\\*') as $archivo) {
            if (is_dir($archivo)) {
                $data = explode($nombre, $archivo)[1];
                $this->eliminarCarpeta($nombre . $data);
            } else {
                @unlink($archivo);
                echo "Eliminar archivo" . $archivo;
                echo '<br>';
            }
        }

        rmdir($nombre);
        echo 'Eliminar carpeta: ' . $nombre;
        echo '<br>';
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $usuario = Auth::user();
        if ($usuario->id_rol == 2) {
            $quejas_reclamos = QuejasReclamos::where('id_conjunto', session('conjunto'))->get();

            // dd($quejas_reclamos);

            return Datatables::of($quejas_reclamos)
                ->addColumn('peticion', function ($queja) {
                    return str_limit($queja->peticion, 30);
                })->addColumn('hechos', function ($queja) {
                    return str_limit($queja->hechos, 30);
                })->addColumn('nombre_completo', function ($queja) {
                    return $queja->user->nombre_completo;
                })->addColumn('fecha_solicitud', function ($queja) {
                    return date('d-m-Y', strtotime($queja->fecha_solicitud));
                })->addColumn('dias_restantes', function ($queja) {
                    if ($queja->dias_restantes > 10) {
                        return json_encode(['class' => 'bg-verde', 'dias' => $queja->dias_restantes]);
                    } elseif ($queja->dias_restantes > 5) {
                        return json_encode(['class' => 'bg-amarillo', 'dias' => $queja->dias_restantes]);
                    } elseif ($queja->dias_restantes > 0) {
                        return json_encode(['class' => 'bg-naranja', 'dias' => $queja->dias_restantes]);
                    } else {
                        return json_encode(['class' => 'bg-rojo', 'dias' => $queja->dias_restantes]);
                    }
                })->addColumn('fecha_respuesta', function ($queja) {
                    return ($queja->fecha_respuesta) ? date('d-m-Y', strtotime($queja->fecha_respuesta)) : "No aplica";
                })->addColumn(
                    'estado',
                    function ($queja) {
                        if ($queja->estado == 'Pendiente') {
                            return  json_encode(['class' => 'td-pendiente', 'estado' => $queja->estado]);
                        } elseif ($queja->estado == 'En proceso') {
                            return  json_encode(['class' => 'td-proceso', 'estado' => $queja->estado]);
                        } elseif ($queja->estado == 'Resuelto') {
                            return  json_encode(['class' => 'td-resuelto', 'estado' => $queja->estado]);
                        } elseif ($queja->estado == 'Cerrado') {
                            return json_encode(['class' => 'td-cerrado', 'estado' => $queja->estado]);
                        }
                    }

                )->addColumn('action', function ($queja) {
                    $salida = '<a data-toggle="tooltip" data-placement="top" title="Ver" onclick="showForm(' . $queja->id . ')" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm(' . $queja->id . ')" class="btn btn-default">
                                    <i class="fa fa-pencil"></i>
                                </a>';
                    if ($queja->archivo != '') {
                        $salida .= '<a data-toggle="tooltip" data-placement="top" title="Mostrar archivo" target="_blank" href="' . asset('quejas/' . $queja->archivo) . '" class="btn btn-default">
                                        <i class="fa fa-eye"></i>
                                    </a>';
                    }
                    return $salida;
                })->make(true);
        } else if ($usuario->id_rol == 3) {
            $quejas_reclamos = QuejasReclamos::where('id_user', Auth::user()->id)->get();
            return Datatables::of($quejas_reclamos)
                ->addColumn('peticion', function ($queja_reclamo) {
                    return str_limit($queja_reclamo->peticion, 30);
                })->addColumn('hechos', function ($queja_reclamo) {
                    return str_limit($queja_reclamo->hechos, 30);
                })->addColumn('fecha_solicitud', function ($queja_reclamo) {
                    return date('d M Y', strtotime($queja_reclamo->fecha_solicitud));
                })->addColumn('action', function ($queja_reclamo) {
                    $salida =  '<a data-toggle="tooltip" data-placement="top" title="Mostrar" onclick="showForm(' . $queja_reclamo->id . ')" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </a>';
                    if ($queja_reclamo->archivo != '') {
                        $salida .= '<a data-toggle="tooltip" data-placement="top" title="Ver archivo" target="_blank" href="' . asset('quejas/' . $queja_reclamo->archivo) . '" 
                                        class="btn btn-default">
                                        <i class="fa fa-eye"></i>
                                    </a>';
                    }
                    if ($queja_reclamo->estado == 'Pendiente') {
                        $salida .= '<a data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm(' . $queja_reclamo->id . ')" class="btn btn-default">
								<i class="fa fa-pencil"></i>
							</a>
							<a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData(' . $queja_reclamo->id . ')" class="btn btn-default">
								<i class="fa fa-trash"></i>
							</a>';
                    } else {
                        $salida .= '<a data-toggle="tooltip" data-placement="top" title="Ver respuesta" onclick="answerData(' . $queja_reclamo->id . ')" class="btn btn-default">
								Ver Respuesta
							</a>';
                    }
                    return $salida;
                })->make(true);
        }
    }
}
