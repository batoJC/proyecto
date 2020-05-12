<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use App\User;
use App\Conjunto;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MasivoRequest;
use App\Tipo_Documento;

class UsuariosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'usuarios']);

        if (Auth::user()->id_rol == 1) {
            $conjuntos = Conjunto::all();
            $tipo_documentos = Tipo_Documento::get();
            return view('owner.usuarios.index')
                ->with('conjuntos', $conjuntos)
                ->with('tipo_documentos', $tipo_documentos);
        } elseif (Auth::user()->id_rol == 2) {
            // $user      = User::where([
            //     ['id_conjunto', session('conjunto')],
            //     ['id', '<>', Auth::user()->id]
            // ])->get();
            $conjuntos = Conjunto::where('id', session('conjunto'))->first();
            $tipo_documentos = Tipo_Documento::get();
            return view('admin.usuarios.index')
                // ->with('user', $user)
                ->with('tipo_documentos', $tipo_documentos)
                ->with('conjuntos', $conjuntos);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('usuarios');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->nombre_completo = $request->nombre_completo;
        $user->ocupacion = $request->ocupacion;
        $user->direccion = $request->direccion;
        $user->tipo_documento = $request->tipo_documento;
        $user->numero_cedula = $request->numero_cedula;
        $user->email = $request->email;
        $user->ocupacion = $request->ocupacion;
        $user->password = Hash::make($request->password);

        $user->telefono = $request->telefono;
        $user->celular = $request->celular;
        // *********************************************************
        if (Auth::user()->id_rol == 1) {
            $user->id_rol      = 2;
            $user->fecha_nacimiento        = null;
            $user->genero      = null;
        } elseif (Auth::user()->id_rol == 2) {
            $user->id_rol            = $request->id_rol;
            $user->fecha_nacimiento  = $request->fecha_nacimiento;
            $user->genero            = $request->genero;
        } else {
            return ['res' => 0, 'msg' => 'No tiene permisos para crear usuarios'];
        }
        // Validador de en id_conjunto esta vacio, o sea sean varios
        // *********************************************************
        if ($request->id_conjunto == '') {
            $user->id_conjunto = session('conjunto');
        } else {
            $user->id_conjunto   = $request->id_conjunto;
        }
        $user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return $user;
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
        $id_rol = Auth::user()->id_rol;
        if ($id_rol != 1 and $id_rol != 2) {
            return ['res' => 0, 'msg' => 'No tiene permisos para actualizar usuarios'];
        }

        $user                  = User::find($id);
        $user->nombre_completo = $request->nombre_completo;
        $user->direccion       = $request->direccion;
        $user->ocupacion       = $request->ocupacion;
        $user->tipo_documento     = $request->tipo_documento;
        $user->numero_cedula   = $request->numero_cedula;
        $user->email           = $request->email;
        if ($id_rol == 2) {
            $user->fecha_nacimiento = $request->fecha_nacimiento;
            $user->genero           = $request->genero;
            $user->id_rol           = $request->id_rol;
        }
        $user->telefono        = $request->telefono;
        $user->celular         = $request->celular;
        if ($request->id_conjunto == '') {
            $user->id_conjunto = session('conjunto');
        } else {
            $user->id_conjunto   = $request->id_conjunto;
        }
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id_rol = Auth::user()->id_rol;
        if ($id_rol != 1 and $id_rol != 2) {
            return ['res' => 0, 'msg' => 'No tiene permisos para eliminar usuarios'];
        }
        User::destroy($id);
    }


    // Custom method to change user status
    // ***********************************
    public function disabled($id)
    {
        $id_rol = Auth::user()->id_rol;
        if ($id_rol != 1 and $id_rol != 2) {
            return ['res' => 0, 'msg' => 'No tiene permisos para desactivar usuarios'];
        }
        $user         = User::find($id);
        $user->estado = ($user->estado == 'Activo') ? 'Inactivo' : 'Activo';
        $user->save();
    }

    // Custom method for download .txt 
    // *******************************

    public function download()
    {
        $pathtoFile = public_path() . '/docs/archivobaseusuarios.xlsx';
        return response()->download($pathtoFile);
    }

    // Custom Method para cargar la vista por get
    // ******************************************
    public function usuarios_csv()
    {

        $user      = User::where('id_conjunto', session('conjunto'))->get();
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();
        return view('admin.usuarios.masiva')
            ->with('user', $user)
            ->with('conjuntos', $conjuntos);
    }

    // Custom Method de usuarios cargue masivo el post
    // ***********************************************
    public function usuarios_csv_post(MasivoRequest $request)
    {
        // Validador si llega un archivo
        // *****************************
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();
            // Validador si el arreglo está vacío
            // **********************************
            if (!empty($data) && $data->count()) {
                try {
                    $last_name = '';
                    $last_cc = '';
                    foreach ($data as $key => $value) {
                        $user                    = new User();
                        $user->nombre_completo   = $value->nombre_completo;
                        $user->direccion         = $value->direccion;

                        //verificar que exsita el tipo de documento
                        /*****************************************/
                        $tipo = Tipo_Documento::where(
                            'tipo',
                            mb_strtoupper($value->tipo_de_documento, 'UTF-8')
                        )->first();
                        if (!$tipo) {
                            echo "si" . mb_strtoupper($value->tipo_de_documento, 'UTF-8');
                            $tipo = new Tipo_Documento();
                            $tipo->tipo = mb_strtoupper($value->tipo_de_documento, 'UTF-8');
                            $tipo->save();
                        }

                        $user->tipo_documento       = $tipo->id;
                        $user->numero_cedula     = $value->numero_de_documento;
                        $user->ocupacion     = $value->ocupacion;
                        $user->email             = $value->email;
                        $user->password          = Hash::make($value->password);
                        $user->telefono          = $value->telefono;
                        $user->celular           = $value->celular;
                        $user->id_rol            = ($value->rol == "Porteria") ? 4 : 3;
                        $user->fecha_nacimiento  = $value->fecha_de_nacimiento;
                        $user->genero            = $value->genero;
                        $user->id_conjunto       = session('conjunto');
                        $last_name = $value->nombre_completo;
                        $last_cc = $value->numero_de_documento;
                        $user->save();
                        return redirect('usuarios')
                            ->with('status', 'Se insertó correctamente')
                            ->with('last', "El último registro fue '$last_name' cc: '$last_cc'");
                    }
                } catch (\Throwable $th) {
                    return redirect('usuarios')
                        ->with('error', 'Ocurrió un error al registrar el último registro, verifique que no se encuentre ya registrado.')
                        ->with('last', "El último registro fue '$last_name' cc: '$last_cc'");
                }
            }
        }
    }

    // Terminos y condiciones de todos los users
    // *****************************************

    public function terminos(Request $request)
    {
        $user              = User::find(Auth::user()->id);
        $user->habeas_data = $request->terminos_field;
        $user->save();
    }



    // para listar por datatables
    // ****************************
    public function datatables()
    {
        $user = Auth::user();
        $usuarios = null;
        if ($user->id_rol == 1) {
            $usuarios = User::where('id_rol', 2)->get();
            return Datatables::of($usuarios)
                ->addColumn('tipo_documento', function ($usuario) {
                    return $usuario->tipoDocumento->tipo;
                })->addColumn('conjunto', function ($usuario) {
                    if ($usuario->conjunto == null)
                        return  "No Aplica";
                    else
                        return  $usuario->conjunto->nombre;
                })->addColumn('estado', function ($usuario) {
                   return json_encode([$usuario->estado]);
                })->addColumn('action', function ($usuario) {
                    $salida = '<a data-toggle="tooltip" data-placement="top" title="Ver" onclick="showForm(' . $usuario->id . ')" class="btn btn-default">
                            <i class="fa fa-search"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm(' . $usuario->id . ')" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="ELiminar" onclick="deleteData(' . $usuario->id . ')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
                    if ($usuario->estado == 'Activo') {
                        $salida .= ' <a data-toggle="tooltip" data-placement="top" title="Desactivar" onclick="desativateUser(' . $usuario->id . ')" class="btn btn-default">
                                    <i class="fa fa-user-times"></i>
                                </a>';
                    } else {
                        $salida .= ' <a data-toggle="tooltip" data-placement="top" title="Activar" onclick="desativateUser(' . $usuario->id . ')" class="btn btn-default">
                                    <i class="fa fa-check"></i>
                                </a>';
                    }
                    return $salida;
                })->make(true);
        } elseif ($user->id_rol == 2) {
            $usuarios = User::where([
                ['id_conjunto', session('conjunto')],
                ['id_rol', '<>', 2]
            ])->get();

            return Datatables::of($usuarios)
                ->addColumn('tipo_documento', function ($usuario) {
                    return $usuario->tipoDocumento->tipo;
                })->addColumn('edad', function ($usuario) {
                    if ($usuario->fecha_nacimiento != null) {
                        return date_diff(
                            date_create(date('Y-m-d')),
                            date_create($usuario->fecha_nacimiento)
                        )->format('%y');
                    } else {
                        return 'No aplica.';
                    }
                })->addColumn('rol', function ($usuario) {
                    if ($usuario->id_rol == 3) {
                        return 'Propietario';
                    } elseif ($usuario->id_rol == 4) {
                        return 'Portero';
                    }
                })->addColumn('action', function ($usuario) {
                    $salida = '<a data-toggle="tooltip" data-placement="top" title="Ver" onclick="showForm(' . $usuario->id . ')" class="btn btn-default">
                            <i class="fa fa-search"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm(' . $usuario->id . ')" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="ELiminar" onclick="deleteData(' . $usuario->id . ')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
                    return $salida;
                })->make(true);
        }
    }
}
