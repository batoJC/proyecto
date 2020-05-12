<?php

namespace App\Http\Controllers;

use App\AtributosTipoUnidad;
use DB;
use Auth;
use App\User;
use App\Division;
use App\Conjunto;
use App\Parqueadero;
use App\Residentes;
use App\Tipo_unidad;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class Tipo_unidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'tipo_unidad']);

        $user         = User::where('id_conjunto', session('conjunto'))->get();
        $conjuntos    = Conjunto::find(session('conjunto'));
        
        $divisiones   = Division::where('id_conjunto', session('conjunto'))->get();
        // $parqueaderos = Parqueadero::where('id_conjunto', session('conjunto'))->get();
        // $residentes   = Residentes::where('id_conjunto', session('conjunto'))->get();

        return view('admin.tipo_unidad.index')
            ->with('user', $user)
            ->with('conjuntos', $conjuntos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/tipo_unidad');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo = new Tipo_unidad();
        $tipo->nombre = mb_strtoupper($request->nombre, 'UTF-8');;
        $tipo->conjunto_id = session('conjunto');
        $tipo->save();

        $atributos = $request->all();
        $i = 0;
        foreach ($atributos as $key => $value) {
            if ($i > 3) {
                $atributo = new AtributosTipoUnidad();
                $atributo->nombre = $key;
                $atributo->tipo_unidad_id = $tipo->id;
                $atributo->save();
            }
            $i++;
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
        $tipo_unidad = Tipo_unidad::find($id);
        return [$tipo_unidad, $tipo_unidad->atributos];
        // return [$tipo_unidad];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo_unidad = Tipo_unidad::find($id);
        return $tipo_unidad;
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
        $tipo = Tipo_unidad::find($id);
        $tipo->nombre = mb_strtoupper($request->nombre, 'UTF-8');
        $tipo->save();

        AtributosTipoUnidad::where('tipo_unidad_id', $id)->delete();

        $atributos = $request->all();
        $i = 0;
        foreach ($atributos as $key => $value) {
            if ($i > 3) {
                $atributo = new AtributosTipoUnidad();
                $atributo->nombre = $key;
                $atributo->tipo_unidad_id = $tipo->id;
                $atributo->save();
            }
            $i++;
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
        Tipo_unidad::destroy($id);
    }

    public function showFormResidentes($id)
    {
        // $residentes   = Residentes::where('id_tipo_unidad', $id)->get();
        $residentes = DB::table('residentes')->select('tipo_residente', 'nombre', 'apellido', 'estado')->where('id_tipo_unidad', $id)->get();
        return $residentes;
    }

    // para listar por datatables
    // ****************************
    public function datatables(){

        $tipos_unidad  = Tipo_unidad::where('conjunto_id', session('conjunto'))->get();

        return Datatables::of($tipos_unidad)
            ->addColumn('action', function($tipo_unidad){
                return '<a data-toggle="tooltip" data-placement="top" 
                                    title="Mostrar" onclick="showForm('.$tipo_unidad->id.')" class="btn btn-default">
                            <i class="fa fa-search"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" 
                                    title="Editar" onclick="editForm('.$tipo_unidad->id.')" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" 
                                    title="Eliminar onclick="deleteData('.$tipo_unidad->id.')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }

}
