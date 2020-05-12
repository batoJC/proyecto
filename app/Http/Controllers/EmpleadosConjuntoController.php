<?php

namespace App\Http\Controllers;

use App\Conjunto;
use Yajra\Datatables\Datatables;
use App\EmpleadosConjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadosConjuntoController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'empleados_conunto']);

        $conjuntos       = Conjunto::find(session('conjunto'));

        $user = Auth::user();

        if ($user->id_rol == 2) {


            return view('admin.empleados_conjunto.index')
                ->with('conjuntos', $conjuntos);
        } else if ($user->id_rol == 4) {
            return view('celador.empleados_conjunto')
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
            $empleado = new EmpleadosConjunto();
            $empleado->fecha_ingreso = $request->fecha_ingreso;
            $empleado->nombre_completo = $request->nombre_completo;
            $empleado->cedula = $request->cedula;
            $empleado->direccion = $request->direccion;
            $empleado->cargo = $request->cargo;
            if ($request->hasFile('foto')) {
                // if ($empleado->foto != ''){
                //     @unlink(public_path('imgs/private_imgs').'/'.$empleado->foto);
                // }
                $file = time() . '.' . $request->foto->getClientOriginalExtension();
                $request->foto->move(public_path('imgs/private_imgs'), $file);
                // Ruta de la img
                $empleado->foto = $file;
            }
            $empleado->conjunto_id = session('conjunto');
            $empleado->save();

            return array('res' => 1, 'msg' => 'Empleado agregado correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el empleado.', 'e' => $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmpleadosConjunto  $empleadosConjunto
     * @return \Illuminate\Http\Response
     */
    public function show(EmpleadosConjunto $empleadosConjunto)
    {
        //
        return $empleadosConjunto;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmpleadosConjunto  $empleadosConjunto
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpleadosConjunto $empleadosConjunto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmpleadosConjunto  $empleadosConjunto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpleadosConjunto $empleadosConjunto)
    {
        try {
            $empleadosConjunto->nombre_completo = $request->nombre_completo;
            $empleadosConjunto->cedula = $request->cedula;
            $empleadosConjunto->direccion = $request->direccion;
            $empleadosConjunto->cargo = $request->cargo;
            if ($request->hasFile('foto')) {
                if ($empleadosConjunto->foto != '') {
                    @unlink(public_path('imgs/private_imgs') . '/' . $empleadosConjunto->archivo);
                }
                $file = time() . '.' . $request->foto->getClientOriginalExtension();
                $request->foto->move(public_path('imgs/private_imgs'), $file);
                // Ruta de la img
                $empleadosConjunto->foto = $file;
            }
            $empleadosConjunto->save();

            return array('res' => 1, 'msg' => 'Empleado editado correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al editar el empleado.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmpleadosConjunto  $empleadosConjunto
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpleadosConjunto $empleadosConjunto)
    {
        try {
            if ($empleadosConjunto->foto != '') {
                @unlink(public_path('imgs/private_imgs') . '/' . $empleadosConjunto->foto);
            }
            $empleadosConjunto->delete();
            return array('res' => 1, "msg" => "El empleado fue eliminado correctamente.");
        } catch (\Throwable $th) {
            return array('res' => 0, "msg" => "No se logro eliminar el empleado.");
        }
    }

    public function inactivar(EmpleadosConjunto $empleado, Request $request)
    {
        try {
            $empleado->estado = 'Inactivo';
            $empleado->fecha_retiro = $request->fecha_retiro;
            $empleado->save();

            return ['res' => 1, 'msg' => 'Empleado retirado correctamente'];
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al intentar retirar el empleado.'];
        }
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $empleados = EmpleadosConjunto::where('conjunto_id', session('conjunto'))->get();
        $usuario = Auth::user();

        if ($usuario->id_rol == 2) {
            return Datatables::of($empleados)
                ->addColumn('fecha_ingreso', function ($empleado) {
                    return date('d-m-Y', strtotime($empleado->fecha_ingreso));
                })->addColumn('fecha_retiro', function ($empleado) {
                    return ($empleado->fecha_retiro) ? date('d-m-Y', strtotime($empleado->fecha_retiro)) : 'No aplica';
                })->addColumn('foto', function ($empleado) {
                    return json_encode([$empleado->foto]);
                })->addColumn('action', function ($empleado) {
                    $salida = '<a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-default" 
                                onclick="showEdit(' . $empleado->id . ')">
                                <i class="fa fa-pencil"></i>
                            </a>';
                    if ($empleado->estado == 'Activo') {
                        $salida .= ' <a data-toggle="tooltip" data-placement="top" title="Retirar" class="btn btn-default" 
                                        onclick="retirar(' . $empleado->id . ')">
                                        <i class="fa fa-times"></i>
                                    </a>';
                    }
                    $salida .= '<a data-toggle="tooltip" data-placement="top" title="Eliminar" 
                                onclick="deleteData(' . $empleado->id . ')" class="btn btn-default">
                                <i class="fa fa-trash"></i>
                            </a>';
                    return $salida;
                })->make(true);
        } else if ($usuario->id_rol == 4) {
            return Datatables::of($empleados)
                ->addColumn('foto', function ($empleado) {
                    return json_encode([$empleado->foto]);
                })->make(true);
        }
    }
}
