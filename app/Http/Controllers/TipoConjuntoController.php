<?php

namespace App\Http\Controllers;

use App\Tipo_Conjunto;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class TipoConjuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'tipo_conjunto']);
        $tipos = Tipo_Conjunto::get();
        return view('owner.tipos_conjunto.index')->with('tipos', $tipos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('tipo_conjunto');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo_conjunto       = new Tipo_Conjunto();
        $tipo_conjunto->tipo = $request->tipo ;
        $tipo_conjunto->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipo_conjunto = Tipo_Conjunto::find($id);
        return $tipo_conjunto;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo_conjunto = Tipo_Conjunto::find($id);
        return $tipo_conjunto;
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
        $tipo_conjunto       = Tipo_Conjunto::find($id);
        $tipo_conjunto->tipo = $request->tipo;
        $tipo_conjunto->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tipo_Conjunto::destroy($id);
    }

    // Custom method for get search
    // ****************************
    public function reload()
    {

        $tipo_conjunto = Tipo_Conjunto::all();

        return Datatables::of($tipo_conjunto)
            ->addColumn('action', function ($tipo_conjunto) {
                return '<a  data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-default" onclick="editForm(' . $tipo_conjunto->id . ')" class="btn btn-default"><i class="fa fa-pencil"></i></a> ' .
                    '<a  data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-default" onclick="deleteData(' . $tipo_conjunto->id . ')" class="btn btn-default"><i class="fa fa-trash"></i></a>';
            })->make(true);
    }
}
