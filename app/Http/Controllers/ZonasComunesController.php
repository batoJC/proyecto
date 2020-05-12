<?php

namespace App\Http\Controllers;

use Auth;
use Yajra\Datatables\Datatables;
use App\User;
use App\Conjunto;
use App\Zona_Comun;
use Illuminate\Http\Request;

class ZonasComunesController extends Controller
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
        session(['section' => 'zonas_comunes']);

        $usuario = Auth::user();
        $conjuntos    = Conjunto::find(session('conjunto'));

        switch ($usuario->id_rol) {
            case 2: //admin
                return view('admin.zonas.index')
                    ->with('conjuntos', $conjuntos);
                break;
            case 3: //propietarios
                return view('dueno.zonas_comunes')
                    ->with('conjuntos', $conjuntos);
                break;
            case 4: //porteria
                return view('celador.zonas_comunes');
                break;

            default:
                return view('errors.404');
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return redirect('/parqueaderos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zona_comun                 = new Zona_Comun();
        try {
            $zona_comun->nombre         = $request->nombre;
            $zona_comun->valor_uso      = $request->valor_uso;
            $zona_comun->numero      = $request->numero;
            $zona_comun->tipo      = $request->tipo;
            $zona_comun->conjunto_id    = session('conjunto');
            $zona_comun->save();
            return ['res' => 1, 'msg' => 'Se registro la zona social correctamente'];
        } catch (\Throwable $th) {
            $zona_comun->delete();
            return ['res' => 0, 'msg' => 'Ocurrió un error al registrar la zona social', 'e' => $th];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zona_comun = Zona_Comun::find($id);
        return $zona_comun;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $zona_comun = Zona_Comun::find($id);
        return $zona_comun;
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
        $zona_comun = Zona_Comun::find($id);
        try {
            $zona_comun->nombre         = $request->nombre;
            $zona_comun->valor_uso      = $request->valor_uso;
            $zona_comun->numero      = $request->numero;
            $zona_comun->tipo      = $request->tipo;
            $zona_comun->conjunto_id    = session('conjunto');
            $zona_comun->save();
            return ['res' => 1, 'msg' => 'Se actualizó la información de la zona social correctamente'];
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al registrar la zona social'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Zona_Comun::destroy($id);
        return ['res' => 1, 'msg' => 'Se elimino la zona social correctamente'];
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {
        $zonas_comunes = Zona_Comun::where('conjunto_id', session('conjunto'))->get();

        switch (Auth::user()->id_rol) {
            case 2:
                return Datatables::of($zonas_comunes)
                    ->addColumn('valor_uso', function ($zona_comun) {
                        return ($zona_comun->valor_uso != null) ? '$ ' . number_format($zona_comun->valor_uso) : 'No aplica';
                    })->addColumn('antes', function ($zona_comun) {
                        return $zona_comun->numero . ' ' . $zona_comun->tipo;
                    })->addColumn('action', function ($zona_comun) {
                        return '<a class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Ver reservas de esta zona social" 
                                    href="' . url('reservasZonaComun', ['zona_comun' => $zona_comun->id]) . '">
                                    <i class="fa fa-calendar"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Mostrar" onclick="showForm(' . $zona_comun->id . ')" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm(' . $zona_comun->id . ')" class="btn btn-default">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData(' . $zona_comun->id . ')" class="btn btn-default">
                                    <i class="fa fa-trash"></i>
                                </a>';
                    })->make(true);
                break;
            case 3:
            case 4:
                return Datatables::of($zonas_comunes)
                    ->addColumn('antes', function ($zona_comun) {
                        return $zona_comun->numero . ' ' . $zona_comun->tipo;
                    })->addColumn('valor_uso', function ($zona_comun) {
                        return ($zona_comun->valor_uso) ? '$ ' . number_format($zona_comun->valor_uso) : 'No aplica';
                    })->addColumn('action', function ($zona_comun) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Ver reservas de esta zona social" 
                                    href="' . url('reservasZonaComun', ['zona_comun' => $zona_comun->id]) . '" 
                                    class="btn btn-default">
                                    <i class="fa fa-calendar"></i>
                                </a>';
                    })->make(true);

            default:
                return [];
        }
    }
}
