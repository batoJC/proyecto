<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Conjunto;
use App\Tipo_ejecucion_pre;
use App\Ejecucion_presupuestal_total;
use App\Ejecucion_presupuestal_individual;
use Yajra\Datatables\Datatables;
use App\Unidad;
use Illuminate\Http\Request;
use Excel;

class EjecucionPreIndividual extends Controller
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
        session(['section' => 'ejecucion_pre_individual']);


        if (Auth::user()->id_rol == 2) {
            $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();
            $user              = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos         = Conjunto::find(session('conjunto'));
            $ejecucionPreTotal = Ejecucion_presupuestal_total::where([
                ['conjunto_id', session('conjunto')],
                ['vigente', true]
            ])->get();
            $tipoEjecucionPre  = Tipo_ejecucion_pre::where('conjunto_id', session('conjunto'))->get();
            // Validador de si el consecutivo viene vacío
            // ******************************************
            if (count($tipoEjecucionPre) > 0) {
                return view('admin.ejecucion_presupuestal_individual.index')
                    ->with('user', $user)
                    ->with('unidades', $unidades)
                    ->with('conjuntos', $conjuntos)
                    ->with('ejecucionPreTotal', $ejecucionPreTotal)
                    ->with('tipoEjecucionPre', $tipoEjecucionPre);
            } else {
                return redirect('tipo_ejecucion_pre')->with('status', 'Por favor defina un tipo de Ejecución Presupuestal...');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('ejecucion_pre_individual');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Creacion de la ejecucion presupuestal individual
        // ************************************************
        $ejecucionPreIndividual                         = new Ejecucion_presupuestal_individual();
        try {
            $ejecucionPreIndividual->total       = $request->total;
            // soportes
            $files = null;
            if ($request->soportes) {
                $i = 0;
                foreach ($request->soportes as $soporte) {
                    $file = time() . $i . '.' . $soporte->getClientOriginalExtension();
                    $soporte->move(public_path('docs/'), $file);
                    $files .= $file . ';';
                    $i++;
                }
                $files = substr($files, 0, strlen($files) - 1);
            }
            $ejecucionPreIndividual->soportes = $files;
            $ejecucionPreIndividual->id_tipo_ejecucion      = $request->id_tipo_ejecucion;
            $ejecucionPreIndividual->id_ejecucion_pre_total = $request->id_ejecucion_pre_total;
            $ejecucionPreIndividual->conjunto_id            = session('conjunto');
            if ($ejecucionPreIndividual->save()) {
                if ($request->unidades) {
                    foreach ($request->unidades as $key) {
                        $ejecucionPreIndividual->excluidas()->attach(Unidad::find($key));
                    }
                }
                return array('res' => 1, 'msg' => 'Presupuesto agregado correctamente.');
            }
        } catch (\Throwable $th) {
            $ejecucionPreIndividual->delete();
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar el presupuesto.');
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
        $ejecucionPreIndividual = Ejecucion_presupuestal_individual::find($id);
        return $ejecucionPreIndividual;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ejecucionPreIndividual = Ejecucion_presupuestal_individual::find($id);
        return $ejecucionPreIndividual;
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
        // Consulta la ejecucion presupuestal total
        // ****************************************
        $ejecucionPreTotal = Ejecucion_presupuestal_total::find($request->id_ejecucion_pre_total);
        $calculo_total     = $request->porcentaje_total / 100 * $ejecucionPreTotal->valor_total;

        // Creacion de la ejecucion presupuestal individual
        // ************************************************
        $ejecucionPreIndividual                         = Ejecucion_presupuestal_individual::find($id);
        // $ejecucionPreIndividual->porcentaje_total       = $request->porcentaje_total;
        // $ejecucionPreIndividual->porcentaje_ejecutado   = $request->porcentaje_ejecutado;
        // $ejecucionPreIndividual->total                  = $calculo_total;
        // $ejecucionPreIndividual->fecha_inicio           = $request->fecha_inicio;
        // $ejecucionPreIndividual->fecha_fin              = $request->fecha_fin;
        $ejecucionPreIndividual->tipo                   = $request->tipo;
        $ejecucionPreIndividual->id_tipo_ejecucion      = $request->id_tipo_ejecucion;
        $ejecucionPreIndividual->id_ejecucion_pre_total = $request->id_ejecucion_pre_total;
        $ejecucionPreIndividual->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $presupuesto = Ejecucion_presupuestal_individual::find($id);
        $archivos = explode(';', $presupuesto->soportes);
        foreach ($archivos as $archivo) {
            @unlink(public_path('docs/') . $archivo);
        }
        Ejecucion_presupuestal_individual::destroy($id);
    }

    public function calcularCuotaAdministracion()
    {
        $presupuestoIngresos = Ejecucion_presupuestal_total::where([
            ['conjunto_id', session('conjunto')],
            ['vigente', true],
            ['tipo', 'ingreso']
        ])->first();


        $presupuestoEgresos = Ejecucion_presupuestal_total::where([
            ['conjunto_id', session('conjunto')],
            ['vigente', true],
            ['tipo', 'egreso']
        ])->first();

        $presupuestoIngresos = ($presupuestoIngresos == null) ? 0 : $presupuestoIngresos->valor_total();
        $presupuestoEgresos = ($presupuestoEgresos == null) ? 0 : $presupuestoEgresos->valor_total();

        return $presupuestoEgresos - $presupuestoIngresos;
    }

    public function excluidas($id)
    {
        $presupuesto = Ejecucion_presupuestal_individual::find($id);
        return view('admin.ejecucion_presupuestal_individual.excluidas')->with('unidades', $presupuesto->excluidas);
    }

    public function cargarCSV(Request $request)
    {
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();
            // Validador si el arreglo está vacío
            // **********************************
            $contador = 0;
            if (!empty($data) && $data->count()) {
                try {
                    foreach ($data as $key => $value) {
                        $presupuesto = new Ejecucion_presupuestal_individual();

                        //verificar que exista el tipo de presupuesto
                        /*****************************************/
                        $tipo = Tipo_ejecucion_pre::where(
                            'tipo',
                            mb_strtoupper($value->tipo, 'UTF-8')
                        )->first();
                        if (!$tipo) {
                            $tipo = new Tipo_ejecucion_pre();
                            $tipo->tipo = mb_strtoupper($value->tipo, 'UTF-8');
                            $tipo->conjunto_id = session('conjunto');
                            $tipo->save();
                        }

                        $presupuesto->total = $value->total;
                        $presupuesto->id_tipo_ejecucion = $tipo->id;
                        $presupuesto->id_ejecucion_pre_total = $request->id;
                        $presupuesto->conjunto_id = session('conjunto');
                        $presupuesto->save();
                        $contador++;
                    }
                    return array('res' => 1, 'msg' => 'Todos los presupuestos insertados correctamente.');
                } catch (\Throwable $th) {
                    return array('res' => 0, 'msg' => 'Ocurrió un error al realizar la carga masiva.
                    Se cargaron ' . $contador . ' registros.');
                }
            }
        }
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $ejecucionPreIndividual = Ejecucion_presupuestal_individual::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($ejecucionPreIndividual)
            ->addColumn('tipo_t', function ($individual) {
                return $individual->ejecucion_presupuestal_total->tipo;
            })->addColumn('tipo_n', function ($individual) {
                return $individual->Tipo_ejecucion_pre->tipo;
            })->addColumn('porcentaje', function ($individual) {
                return $individual->porcentaje_total().' %';
            })->addColumn('ejecutado', function ($individual) {
                return $individual->porcentaje_ejecutado();
            })->addColumn('total', function ($individual) {
                return '$ ' . number_format($individual->total);
            })->addColumn('periodo', function ($individual) {
                return date('d-m-Y', strtotime($individual->ejecucion_presupuestal_total->fecha_inicio)) . ' a ' . date('d-m-Y', strtotime($individual->ejecucion_presupuestal_total->fecha_fin));
            })->addColumn('action', function ($individual) {
                return '<a data-toggle="tooltip" data-placement="top" 
                            title="Unidades excluidas" onclick="unidades('. $individual->id.')" class="btn btn-default">
                            <i class="fa fa-building"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" 
                            title="Lista de archivos de soporte"  onclick="soportes('.$individual->id.')" class="btn btn-default">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="eliminar"  
                            onclick="deleteData('.$individual->id.')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
