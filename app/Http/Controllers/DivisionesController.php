<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Conjunto;
use App\Division;
use App\TipoDivision;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class DivisionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'divisiones']);

        $user      = User::where('id_conjunto', session('conjunto'))->get();
        $tipos_divisiones = TipoDivision::get();
        $conjuntos = Conjunto::find(session('conjunto'));
        return view('admin.divisiones.index')
               ->with('user', $user)
               ->with('tipos_divisiones', $tipos_divisiones)
               ->with('conjuntos', $conjuntos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/divisiones');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $division                = new Division();
        $division->id_tipo_division = $request->id_tipo_division;
        $division->numero_letra  = mb_strtoupper($request->numero_letra, 'UTF-8');
        $division->id_conjunto   = session('conjunto');
        $division->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $division = Division::find($id);
        return $division;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect('/divisiones');
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
        $division               = Division::find($id);
        $division->id_tipo_division = $request->id_tipo_division;
        $division->numero_letra  = mb_strtoupper($request->numero_letra, 'UTF-8');
        $division->id_conjunto   = session('conjunto');
        $division->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Division::destroy($id);
    }

    // para listar por datatables
    // ****************************
    public function datatables(){
        $divisiones = Division::where('id_conjunto', session('conjunto'))->get();

        return Datatables::of($divisiones)
            ->addColumn('tipo_division',function($division){
                return $division->tipo_division->division;
            })->addColumn('action', function($division){
                return '<a  data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm('.$division->id.')" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a  data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData('.$division->id.')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
