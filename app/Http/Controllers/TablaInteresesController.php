<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Yajra\Datatables\Datatables;
use App\Conjunto;
use App\Tabla_intereses;
use Illuminate\Http\Request;

class TablaInteresesController extends Controller
{
    function __construct()
    {
        $this->middleware('owner', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'tabla_intereses']);

        if (Auth::user()->id_rol == 1) {
            return view('owner.tabla_intereses.index');

            // Condicional para el admin
            // *************************
        } elseif (Auth::user()->id_rol == 2) {
            $user         = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos    = Conjunto::find(session('conjunto'));
            return view('admin.tabla_intereses.index')
                ->with('user', $user)
                ->with('conjuntos', $conjuntos);

            // Condicional para el user tipo dueño
            // ***********************************
        } elseif (Auth::user()->id_rol == 3) {

            return view('dueno.tabla_intereses.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('tabla_intereses');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (strpos($request->tasa_efectiva_anual, ',')) {
            return 'Error';
        } elseif (strpos($request->tasa_efectiva_anual, '%')) {
            return 'Error_porcentaje';
        } else {
            $intereses                            = new Tabla_intereses();
            $intereses->periodo                   = $request->periodo;
            $intereses->fecha_vigencia_inicio     = $request->fecha_vigencia_inicio;
            $intereses->fecha_vigencia_fin        = $request->fecha_vigencia_fin;
            $intereses->numero_resolucion         = $request->numero_resolucion;
            $intereses->tasa_efectiva_anual       = $request->tasa_efectiva_anual;
            // Calculos para el interés
            // ************************
            $numero_2                             = round($request->tasa_efectiva_anual, 2) * 1.5;

            $intereses->tasa_efectiva_anual_mora  = round($numero_2, 2);
            // ************************
            // Calculo de la tasa
            $numero_hardcore                      = ((pow((($intereses->tasa_efectiva_anual_mora / 100) + 1), 1 / 12) * 12) - 12) * 100;
            $intereses->tasa_mora_nominal_anual   = round($numero_hardcore, 2);

            $intereses->tasa_mora_nominal_mensual = round($intereses->tasa_mora_nominal_anual / 12, 3);

            $intereses->tasa_diaria               = round($intereses->tasa_mora_nominal_mensual / 3000, 10);

            // ************************
            $intereses->save();
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
        $tabla_intereses = Tabla_intereses::find($id);
        return $tabla_intereses;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tabla_intereses = Tabla_intereses::find($id);
        return $tabla_intereses;
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
        if (strpos($request->tasa_efectiva_anual, ',')) {
            return 'Error';
        } elseif (strpos($request->tasa_efectiva_anual, '%')) {
            return 'Error_porcentaje';
        } else {
            $intereses                           = Tabla_intereses::find($id);
            $intereses->periodo                  = $request->periodo;
            $intereses->fecha_vigencia_inicio    = $request->fecha_vigencia_inicio;
            $intereses->fecha_vigencia_fin       = $request->fecha_vigencia_fin;
            $intereses->numero_resolucion        = $request->numero_resolucion;
            $intereses->tasa_efectiva_anual      = $request->tasa_efectiva_anual;
            // Calculos para el interés
            // ************************
            $numero_2 = doubleval($request->tasa_efectiva_anual) * 1.5;
            // ************************
            $intereses->tasa_efectiva_anual_mora = doubleval($numero_2);
            $intereses->save();
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
        Tabla_intereses::destroy($id);
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $intereses = Tabla_intereses::all();
        $respuesta = Datatables::of($intereses)
        ->addColumn('tasa_diaria', function ($interes) {
            return number_format($interes->tasa_diaria, 9);
        });
        switch (Auth::user()->id_rol) {
            case 1:
                $respuesta->addColumn('action', function ($interes) {
                    return '<a data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm(' . $interes->id . ')" class="btn btn-default">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData(' . $interes->id . ')" class="btn btn-default">
                                <i class="fa fa-trash"></i>
                            </a>';
                });
                break;
        }

        return $respuesta->make(true);
    }
}
