<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Yajra\Datatables\Datatables;
use App\Multa;
use App\Conjunto;
use App\Ejecucion_presupuestal_individual;
use Illuminate\Http\Request;

class MultasController extends Controller
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
        session(['section' => 'multas']);

        if (Auth::user()->id_rol == 2) {

            $conjunto = Conjunto::find(session('conjunto'));
            $presupuestos_ingresos = Ejecucion_presupuestal_individual::join('ejecucion_presupuestal_total', 'ejecucion_presupuestal_individual.id_ejecucion_pre_total', '=', 'ejecucion_presupuestal_total.id')
                ->where([
                    ['ejecucion_presupuestal_individual.conjunto_id', session('conjunto')],
                    ['ejecucion_presupuestal_total.tipo', 'ingreso'],
                    ['ejecucion_presupuestal_total.vigente', true]
                ])
                ->select('ejecucion_presupuestal_individual.*')
                ->get();

            $propietarios     = User::where([
                ['id_rol', '=', 3],
                ['id_conjunto', '=', session('conjunto')]
            ])->get();

            // $actas = array();


            return view('admin.multas.index')
                ->with('conjuntos', $conjunto)
                //    ->with('actas', $actas)
                ->with('presupuestos_ingresos', $presupuestos_ingresos)
                ->with('propietarios', $propietarios);
        } else if (Auth::user()->id_rol == 3) {
            return view('dueno.multas.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('multas');
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
            foreach ($request->propietarios as $propietario) {

                $cuota = new Multa();
                $cuota->valor = $request->valor;
                $cuota->vigencia_inicio = $request->vigencia_inicio;
                $cuota->vigencia_fin = $request->vigencia_fin;
                $cuota->concepto = $request->concepto;
                $cuota->descripcion = $request->descripcion;
                $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
                if ($request->hasFile('archivo')) {
                    $file = time() . '.' . $request->archivo->getClientOriginalExtension();
                    $request->archivo->move(public_path('document'), $file);

                    // Ruta de la img
                    $cuota->archivo = $file;
                } else {
                    $cuota->archivo = null;
                }
                $cuota->user_id = $propietario;
                $cuota->interes = ($request->interes) ? 1 : 0;
                $cuota->conjunto_id = session('conjunto');
                $cuota->save();
            }


            return array('res' => 1, 'msg' => 'Multas  guardadas correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al realizar el registro.');
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
        $multa  = Multa::find($id);
        // $unidad = $multa->unidad;
        return $multa;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $multas = Multa::find($id);
        return $multas;
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
        try {
            $cuota = Multa::find($id);
            $cuota->valor = $request->valor;
            $cuota->vigencia_inicio = $request->vigencia_inicio;
            $cuota->descripcion = $request->descripcion;
            $cuota->vigencia_fin = $request->vigencia_fin;
            $cuota->presupuesto_cargar_id = $request->presupuesto_cargar_id;
            $cuota->interes = ($request->interes) ? 1 : 0;
            $cuota->save();
            return array('res' => 1, 'msg' => 'Multa  modificada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al modificar el registro.');
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
        try {
            $multa = Multa::find($id);
            if ($multa->delete()) {
                if($multa->archivo){
                    @unlink(public_path('document/' . $multa->archivo));
                }
            }
            return array('res' => 1, 'msg' => 'Multa  eliminada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al eliminar el registro.');
        }
    }


    public function download(Multa $multa)
    {
        $pathtoFile = public_path() . '/document/' . $multa->archivo;
        return response()->download($pathtoFile);
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {
        $usuario = Auth::user();

        switch ($usuario->id_rol) {
            case 2:
                $multas = Multa::where('conjunto_id', session('conjunto'))->get();

                return Datatables::of($multas)
                    ->addColumn('propietario', function ($multa) {
                        return $multa->propietario->nombre_completo;
                    })->addColumn('valor', function ($multa) {
                        return '$ ' . number_format($multa->valor);
                    })->addColumn('vigencia_inicio', function ($multa) {
                        return date('d-m-Y', strtotime($multa->vigencia_inicio));
                    })->addColumn('vigencia_fin', function ($multa) {
                        return ($multa->vigencia_fin) ? date('d-m-Y', strtotime($multa->vigencia_fin)) : 'No aplica';
                    })->addColumn('action', function ($multa) {
                        $salida = '<a data-toggle="tooltip" data-placement="top" title="Consultar" onclick="consultar(' . $multa->id . ')" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="eliminar(' . $multa->id . ')" class="btn btn-default">
                                    <i class="fa fa-trash"></i>
                                </a>';
                        if ($multa->archivo) {
                            $salida .= '<a download data-toggle="tooltip" href="' . url('downloadMultaFile', ['multa' => $multa->id]) . '" data-placement="top" title="Descargar archivo" class="btn btn-default">
                                        <i class="fa fa-download"></i>
                                    </a>';
                        }
                        return $salida;
                    })->make(true);
                break;
            case 3:
                $multas = Multa::where([['conjunto_id', session('conjunto')], ['user_id', Auth::user()->id]])->get();
                return Datatables::of($multas)
                    ->addColumn('propietario', function ($multa) {
                        return $multa->propietario->nombre_completo;
                    })->addColumn('valor', function ($multa) {
                        return '$ ' . number_format($multa->valor);
                    })->addColumn('vigencia_inicio', function ($multa) {
                        return date('d-m-Y', strtotime($multa->vigencia_inicio));
                    })->addColumn('vigencia_fin', function ($multa) {
                        return ($multa->vigencia_fin) ? date('d-m-Y', strtotime($multa->vigencia_fin)) : 'No aplica';
                    })->addColumn('action', function ($multa) {
                        $salida = '<a data-toggle="tooltip" data-placement="top" title="Mostrar" 
                                    onclick="consultar(' . $multa->id . ')" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </a>';
                        if ($multa->archivo) {
                            $salida .= '<a download data-toggle="tooltip" href="' . url('downloadMultaFilePropietario', ['multa' => $multa->id]) . '" data-placement="top" title="Descargar archivo" class="btn btn-default">
                                    <i class="fa fa-download"></i>
                                </a>';
                        }
                        return $salida;
                    })->make(true);

            default:
                return [];
        }
    }
}
