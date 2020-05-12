<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Yajra\Datatables\Datatables;
use App\Conjunto;
use App\Ejecucion_presupuestal_individual;
use App\Ejecucion_presupuestal_total;
use Illuminate\Http\Request;

class EjecucionPreTotalController extends Controller
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
        session(['section' => 'ejecucion_pre_total']);



        if (Auth::user()->id_rol == 2) {
            $user         = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos    = Conjunto::find(session('conjunto'));
            return view('admin.ejecucion_presupuestal_total.index')
                ->with('user', $user)
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
        return redirect('ejecucion_pre_total');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ejecucion_pre_total               = new Ejecucion_presupuestal_total();
        // $ejecucion_pre_total->valor_total  = $request->valor_total;
        $ejecucion_pre_total->fecha_inicio = $request->fecha_inicio;
        $ejecucion_pre_total->fecha_fin    = $request->fecha_fin;
        $ejecucion_pre_total->tipo         = $request->tipo;
        // Imagen
        // if($request->hasFile('archivo')){
        //     $file = time().'.'.$request->archivo->getClientOriginalExtension();
        //     $request->archivo->move(public_path('docs/'), $file);
        //     // Ruta de la img 
        //     $ejecucion_pre_total->archivo = $file;
        // } else {
        //     $ejecucion_pre_total->archivo = null;
        // }
        // *********************************************************************
        $ejecucion_pre_total->conjunto_id  = session('conjunto');
        Ejecucion_presupuestal_total::where([
            ['conjunto_id', session('conjunto')],
            ['tipo', $request->tipo]
        ])->update(['vigente' => false]);
        $ejecucion_pre_total->vigente = true;
        $ejecucion_pre_total->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ejecucion_pre_total = Ejecucion_presupuestal_total::find($id);
        return $ejecucion_pre_total;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ejecucion_pre_total = Ejecucion_presupuestal_total::find($id);
        return $ejecucion_pre_total;
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
        // $ejecucion_pre_total               = Ejecucion_presupuestal_total::find($id);
        // $ejecucion_pre_total->valor_total  = $request->valor_total;
        // $ejecucion_pre_total->fecha_inicio = $request->fecha_inicio;
        // $ejecucion_pre_total->fecha_fin    = $request->fecha_fin;
        // $ejecucion_pre_total->tipo         = $request->tipo;

        // // Imagen
        // if($request->hasFile('archivo')){
        //     $file = time().'.'.$request->archivo->getClientOriginalExtension();
        //     $request->archivo->move(public_path('docs/'), $file);
        //     // Ruta de la img 
        //     $ejecucion_pre_total->archivo = $file;
        // }
        // // *************************************************************************
        // $ejecucion_pre_total->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // ubicar el archivo 
        $ejecucion_pre_total = Ejecucion_presupuestal_total::find($id);

        // Borrar registro
        Ejecucion_presupuestal_total::destroy($id);
    }

    public function detalle($id)
    {
        $ejecucionPreIndividual = Ejecucion_presupuestal_individual::where('id_ejecucion_pre_total', $id)->get();
        return view('admin.ejecucion_presupuestal_total.detalle')->with('ejecucionPreIndividual', $ejecucionPreIndividual);
    }

    public function download()
    {
        $pathtoFile = public_path() . '/docs/basepresupuesto.xlsm';
        return response()->download($pathtoFile);
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $ejecucion_pre_total = Ejecucion_presupuestal_total::where('conjunto_id', session('conjunto'))->orderBy('vigente', true)->get();


        return Datatables::of($ejecucion_pre_total)
            ->addColumn('vigencia_inicio', function ($ejecucion) {
                return date('d-m-Y', strtotime($ejecucion->fecha_inicio));
            })->addColumn('vigencia_fin', function ($ejecucion) {
                return date('d-m-Y', strtotime($ejecucion->fecha_fin));
            })->addColumn('valor_total', function ($ejecucion) {
                return '$ ' . number_format($ejecucion->valor_total());
            })->addColumn('total_ejecutado', function ($ejecucion) {
                $porcentaje = round(($ejecucion->total_ejecutado() * 100) / (($ejecucion->valor_total() == 0) ? 1 : $ejecucion->valor_total()), 2);
                return '$ ' . number_format($ejecucion->total_ejecutado()).'('.$porcentaje.'%)';
            })
            ->addColumn('action', function ($ejecucion) {
                return '<input onchange="cargarCSV('.$ejecucion->id.');" style="display:none;" type="file" name="'.$ejecucion->id.'" id="csv'.$ejecucion->id.'">
                <label data-toggle="tooltip" data-placement="top" title="Cargar desde archivo csv" for="csv'.$ejecucion->id.'" class="btn btn-default">
                    <i class="fa fa-upload"></i>
                </label>
                <a data-toggle="tooltip" data-placement="top" title="Detalles del presupuesto" onclick="detalles('.$ejecucion->id.')" class="btn btn-default">
                    <i class="fa fa-eye"></i>
                </a>
                <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData('.$ejecucion->id.')" class="btn btn-default">
                    <i class="fa fa-trash"></i>
                </a>';
            })->make(true);
    }
}
