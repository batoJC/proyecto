<?php

namespace App\Http\Controllers;

use App\Conjunto;
use Yajra\Datatables\Datatables;
use App\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
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
        session(['section' => 'inventario']);

        $conjunto = Conjunto::find(session('conjunto'));

        return view('admin.inventario_general.index')
            ->with('conjuntos', $conjunto);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nuevo_articulo = new Inventario();

        try {
            $nuevo_articulo->nombre = $request->nombre;
            $nuevo_articulo->ubicacion = $request->ubicacion;
            $nuevo_articulo->descripcion = $request->descripcion;
            $nuevo_articulo->condicion = $request->condicion;
            $nuevo_articulo->valor = $request->valor;
            $nuevo_articulo->garantia = ($request->garantia) ? true : false;
            $nuevo_articulo->valido_hasta = $request->valido_hasta;
            $nuevo_articulo->fecha_compra = $request->fecha_compra;
            $nuevo_articulo->fabricante = $request->fabricante;
            $nuevo_articulo->estilo = $request->estilo;
            $nuevo_articulo->numero_serie = $request->numero_serie;
            $nuevo_articulo->observaciones = $request->observaciones;
            $nuevo_articulo->conjunto_id = session('conjunto');

            $files = '';
            $i = 0;
            if ($request->hasFile('fotos')) {
                foreach ($request->fotos as $foto) {
                    $file = time() . $i . '.' . $foto->getClientOriginalExtension();
                    $foto->move(public_path('imgs/private_imgs'), $file);
                    $files .= $file . ';';
                    $i++;
                }
            }
            $nuevo_articulo->foto = trim($files, ';');
            $nuevo_articulo->save();

            return ['res' => 1, 'msg' => 'Se ha registrado el artículo corectamente.'];
        } catch (\Throwable $th) {
            $nuevo_articulo->delete();
            return array('res' => 0, 'msg' => 'Ocurió un error al registrar el artículo en el inventario', 'e' => $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
        return view('admin.inventario_general.articulo')->with('articulo', $inventario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        $fotos = explode(';', $inventario->foto);
        foreach ($fotos as $foto) {
            @unlink(public_path('imgs/private_imgs') . '/' . $foto);
        }
        $inventario->delete();

        return ['res' => 1, 'msg' => 'Artículo eliminado del inventario correctamente.'];
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $inventarios = Inventario::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($inventarios)
            ->addColumn('descripcion',function($inventario){
                return ($inventario->descripcion != null)? str_limit($inventario->descripcion, 40): 'No aplica';
            })->addColumn('valor',function($inventario){
                return '$ '.number_format($inventario->valor);
            })->addColumn('action', function ($inventario) {
                return '<a data-toggle="tooltip" data-placement="top" title="Mostrar" onclick="showArticulo('.$inventario->id.')" class="btn btn-default">
                            <i class="fa fa-search"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData('.$inventario->id.')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
