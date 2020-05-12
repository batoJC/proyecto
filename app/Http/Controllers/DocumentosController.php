<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Correo;
use Auth;
use Yajra\Datatables\Datatables;
use App\Documento;
use App\User;
use Illuminate\Http\Request;

class DocumentosController extends Controller
{

    function __construct()
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
        session(['section' => 'documentos']);

        //
        switch (Auth::user()->id_rol) {
            case 2:
                $conjunto = Conjunto::find(session('conjunto'));
                $documentos = Documento::where('conjunto_id', session('conjunto'))->get();
                return view('admin.documentos.index')->with('conjuntos', $conjunto)->with('documentos', $documentos);
            case 3: //propietario
                $documentos = Documento::where([
                    ['conjunto_id', session('conjunto')],
                    ['propietario', true]
                ])->get();
                return view('dueno.documentos')->with('documentos', $documentos);
            case 4: //porteria
                return view('celador.documentos');

            default:
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
        try {
            $documento = new Documento();
            $documento->nombre = $request->nombre;
            $documento->descripcion = $request->descripcion;
            $documento->porteria = ($request->porteria) ? true : false;
            $documento->propietario = ($request->propietario) ? true : false;
            $documento->conjunto_id = session('conjunto');
            $files = '';
            $i = 0;
            if ($request->hasFile('archivos')) {
                foreach ($request->archivos as $archivo) {
                    $file = time() . $i . '.' . $archivo->getClientOriginalExtension();
                    $archivo->move(public_path('document'), $file);
                    $files .= $file . ';';
                    $i++;
                }
            }
            $documento->archivos = trim($files, ';');
            if ($documento->save()) {
                if($documento->propietario){
                    $correo = new CorreoController();
                    $propietarios = User::where([
                        ['id_conjunto',session('conjunto')],
                        ['id_rol',3]
                    ])->get();
                    $descripcion = str_limit($documento->descripcion,200);
                    $contenido = "Se ha cargado un nuevo documento en el sistema.<br>
                    <b>Nombre: </b> {$descripcion} <br>
                    <b>Descripción: </b> {$documento->descripcion}";
                    $correo->enviarEmail(Conjunto::find(session('conjunto')),$propietarios,'Nuevo documento',$contenido);
                }
                return array('res' => 1, 'msg' => 'Documento registrado correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el documento.');
            }
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el documento.', 'e' => $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
        return view('admin.documentos.documento')->with('documento', $documento);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {
        if ($documento->delete()) {
            $archivos = explode(';', $documento->archivos);
            foreach ($archivos as $archivo) {
                @unlink(public_path('document') . '/' . $archivo);
            }
            return array('res' => 1, 'msg' => 'Documento eliminado correctamente.');
        } else {
            return array('res' => 0, 'msg' => 'Ocurrió un error al eliminar el documento.');
        }
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {
        switch (Auth::user()->id_rol) {
            case 2:
                $documentos = Documento::where('conjunto_id', session('conjunto'))->get();
                return Datatables::of($documentos)
                    ->addColumn('descripcion', function ($documento) {
                        return str_limit($documento->descripcion, 50);
                    })->addColumn('porteria', function ($documento) {
                        return ($documento->porteria) ? 'Si' : 'No';
                    })->addColumn('propietario', function ($documento) {
                        return ($documento->propietario) ? 'Si' : 'No';
                    })->addColumn('action', function ($documento) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-default" 
                                    onclick="eliminar(' . $documento->id . ')">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Ver documento" class="btn btn-default" 
                                    onclick="ver(' . $documento->id . ')">
                                    <i class="fa fa-eye"></i>
                                </a>';
                    })->make(true);
            case 3: //propietario
                $documentos = Documento::where([
                    ['conjunto_id', session('conjunto')],
                    ['propietario', true]
                ])->get();
                return Datatables::of($documentos)
                    ->addColumn('descripcion', function ($documento) {
                        return str_limit($documento->descripcion, 50);
                    })->addColumn('action', function ($documento) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Ver documento" class="btn btn-default" onclick="ver(' . $documento->id . ')">
                                <i class="fa fa-eye"></i>
                            </a>';
                    })->make(true);
            case 4: //porteria
                $documentos = Documento::where([
                    ['conjunto_id', session('conjunto')],
                    ['porteria', true]
                ])->get();
                return Datatables::of($documentos)
                    ->addColumn('descripcion', function ($documento) {
                        return str_limit($documento->descripcion, 50);
                    })->addColumn('action', function ($documento) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Ver documento" class="btn btn-default" onclick="ver(' . $documento->id . ')">
                                    <i class="fa fa-eye"></i>
                                </a>';
                    })->make(true);

            default:
                return [];
        }
    }
}
