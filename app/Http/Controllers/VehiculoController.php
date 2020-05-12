<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Unidad;
use App\Vehiculo;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehiculoController extends Controller
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
        //
        session(['section' => 'vehiculos']);

        $conjuntos       = Conjunto::find(session('conjunto'));

        $user = Auth::user();

        if ($user->id_rol == 2) {
            $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                ->where([
                    ['unidads.conjunto_id', session('conjunto')],
                    ['atributos_tipo_unidads.nombre', 'lista_vehiculos']
                ])
                ->select('unidads.*')
                ->get();

            return view('admin.vehiculos.index')
                ->with('unidades', $unidades)
                ->with('conjuntos', $conjuntos);
        } elseif ($user->id_rol == 4) {
            return view('celador.vehiculos')
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
            //
            $vehiculo              = new Vehiculo();
            $vehiculo->tipo        = $request->tipo;
            $vehiculo->marca       = $request->marca;
            $vehiculo->color       = $request->color;
            $vehiculo->placa       = $request->placa;
            $vehiculo->registra       = $request->registra;
            $vehiculo->fecha_ingreso       = date('Y-m-d');
            $vehiculo->id_conjunto = session('conjunto');
            $vehiculo->carta_ingreso_id      = $request->carta_ingreso_id;
            $vehiculo->unidad_id   = $request->unidad_id;
            $vehiculo->foto_vehiculo = '';
            $vehiculo->foto_tarjeta_1 = '';
            $vehiculo->foto_tarjeta_2 = '';

            // Imagen 1 vehiculo
            if ($request->file('foto1')) {
                $file1 = $request->placa . 'v.' . $request->foto1->getClientOriginalExtension();
                $request->foto1->move(public_path('imgs/private_imgs'), $file1);
                // Ruta de la img
                $vehiculo->foto_vehiculo = $file1;
            }

            // Imagen 2 tarjeta cara 1
            if ($request->file('foto2')) {
                $file2 = $request->placa . 't1.' . $request->foto2->getClientOriginalExtension();
                $request->foto2->move(public_path('imgs/private_imgs'), $file2);
                // Ruta de la img
                $vehiculo->foto_tarjeta_1 = $file2;
            }

            // Imagen 3 tarjeta cara 2
            if ($request->file('foto3')) {
                $file3 = $request->placa . 't2v.' . $request->foto2->getClientOriginalExtension();
                $request->foto3->move(public_path('imgs/private_imgs'), $file3);
                // Ruta de la img
                $vehiculo->foto_tarjeta_2 = $file3;
            }


            $vehiculo->save();

            return array('res' => 1, 'msg' => 'Vehícilo agregado correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el vehículo.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(Vehiculo $vehiculo)
    {
        //
        return $vehiculo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehiculo $vehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        //
        $vehiculo->tipo        = $request->tipo;
        $vehiculo->marca       = $request->marca;
        $vehiculo->color       = $request->color;
        $vehiculo->placa       = $request->placa;
        $vehiculo->registra       = $request->registra;


        // Imagen 1 vehiculo
        if ($request->file('foto1')) {
            $rutaPasada = public_path('imgs/private_imgs/' . $vehiculo->foto_vehiculo);

            $file1 = $request->placa . 'v.' . $request->foto1->getClientOriginalExtension();
            $request->foto1->move(public_path('imgs/private_imgs'), $file1);
            // Ruta de la img
            $vehiculo->foto_vehiculo = $file1;
            unlink($rutaPasada);
        }

        // Imagen 2 tarjeta cara 1
        if ($request->file('foto2')) {
            $rutaPasada = public_path('imgs/private_imgs/' . $vehiculo->foto_tarjeta_1);

            $file2 = $request->placa . 't1.' . $request->foto2->getClientOriginalExtension();
            $request->foto2->move(public_path('imgs/private_imgs'), $file2);
            // Ruta de la img
            $vehiculo->foto_tarjeta_1 = $file2;
            unlink($rutaPasada);
        }

        // Imagen 3 tarjeta cara 2
        if ($request->file('foto3')) {
            $rutaPasada = public_path('imgs/private_imgs/' . $vehiculo->foto_tarjeta_2);

            $file2 = $request->placa . 't2.' . $request->foto2->getClientOriginalExtension();
            $request->foto2->move(public_path('imgs/private_imgs'), $file2);
            // Ruta de la img
            $vehiculo->foto_tarjeta_2 = $file2;
            unlink($rutaPasada);
        }


        $vehiculo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehiculo $vehiculo)
    {
        //
    }

    public function inactivar(Vehiculo $vehiculo, Request $request)
    {
        $vehiculo->carta_retiro_id = $request->carta_retiro_id;
        $vehiculo->estado = 'Inactivo';
        $vehiculo->fecha_retiro = date('Y-m-d');
        $vehiculo->save();
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $vehiculos = Vehiculo::where('id_conjunto', session('conjunto'));
        $usuario = Auth::user();

        if ($usuario->id_rol == 2) {
            $vehiculos = $vehiculos->get();
        } else if ($usuario->id_rol == 4) {
            $vehiculos = $vehiculos->where('estado', 'Activo')->get();
        }

        return Datatables::of($vehiculos)
            ->addColumn('unidad', function ($vehiculo) {
                return $vehiculo->unidad->tipo->nombre . ' ' . $vehiculo->unidad->numero_letra;
            })->addColumn('foto_vehiculo', function ($vehiculo) {
                return json_encode([$vehiculo->foto_vehiculo]);
            })->addColumn('foto_tarjeta_1', function ($vehiculo) {
                return json_encode([$vehiculo->foto_tarjeta_1]);
            })->addColumn('foto_tarjeta_2', function ($vehiculo) {
                return json_encode([$vehiculo->foto_tarjeta_2]);
            })->addColumn('action', function ($vehiculo) {
                return '<a data-toggle="tooltip" data-placement="top" title="Información de la unidad" class="btn btn-default" onclick="loadData(' . $vehiculo->unidad_id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
            })->make(true);
    }
}
