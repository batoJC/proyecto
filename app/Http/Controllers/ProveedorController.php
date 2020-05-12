<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Proveedor;
use App\Tipo_Documento;
use Yajra\Datatables\Datatables;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        session(['section' => 'proveedores']);

        if (Auth::user()->id_rol == 2) {
            $user       = User::where('id_conjunto', session('conjunto'))->get();
            $tipo_documentos = Tipo_Documento::get();
            $conjuntos   = Conjunto::where('id', session('conjunto'))->first();

            return view('admin.proveedores.index')
                ->with('tipo_documentos', $tipo_documentos)
                ->with('user', $user)
                ->with('conjuntos', $conjuntos);
        } else {
            return view('errors.404');
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
        //
        $proveedor                  = new Proveedor();
        $proveedor->nombre_completo = $request->nombre_completo;
        $proveedor->email = $request->email;
        $proveedor->tipo_documento     = $request->tipo_documento;
        $proveedor->documento   = $request->documento;
        $proveedor->direccion   = $request->direccion;
        $proveedor->telefono        = $request->telefono;
        $proveedor->celular         = $request->celular;
        $proveedor->conjunto_id = session('conjunto');
        $proveedor->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedore)
    {
        //
        return $proveedore;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedore)
    {
        //
        return $proveedore;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedore)
    {
        //
        $proveedore->nombre_completo = $request->nombre_completo;
        $proveedore->email = $request->email;
        $proveedore->tipo_documento     = $request->tipo_documento;
        $proveedore->documento   = $request->documento;
        $proveedore->direccion   = $request->direccion;
        $proveedore->telefono        = $request->telefono;
        $proveedore->celular         = $request->celular;
        $proveedore->conjunto_id = session('conjunto');
        $proveedore->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedore)
    {
        //
        $proveedore->delete();
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $proveedores = Proveedor::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($proveedores)
            ->addColumn('tipo_documento', function ($proveedor) {
                return  $proveedor->tipoDocumento->tipo;
            })->addColumn('telefono', function ($proveedor) {
                if ($proveedor->telefono == null || $proveedor->telefono == '') {
                    return 'No aplica';
                } else {
                    return $proveedor->telefono;
                }
            })->addColumn('action', function ($proveedor) {

                return ' <a data-toggle="tooltip" data-placement="top" title="Mostrar" class="btn btn-default" onclick="showForm('.$proveedor->id.')">
                            <i class="fa fa-search"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-default" onclick="editForm('.$proveedor->id.')">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-default" onclick="deleteData('.$proveedor->id.')">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
