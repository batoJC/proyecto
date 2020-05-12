<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Yajra\Datatables\Datatables;
use App\Conjunto;
use App\Consecutivos;
use Illuminate\Http\Request;

class ConsecutivosController extends Controller
{
    function __construct()
    {
        $this->middleware('admin',['only'=>['store','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'consecutivos']);
        
        if(Auth::user()->id_rol == 2){

            $user             = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos        = Conjunto::find(session('conjunto'));
            return view('admin.consecutivos.index')
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
        return redirect('consecutivos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $consecutivos                   = new Consecutivos();
        $consecutivos->numero           = $request->numero;
        $consecutivos->prefijo          = $request->prefijo;
        $consecutivos->conjunto_id      = session('conjunto');
        $consecutivos->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consecutivos = Consecutivos::find($id);
        return $consecutivos;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $consecutivos = Consecutivos::find($id);
        return $consecutivos;
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Consecutivos::destroy($id);
    }

    // para listar por datatables
    // ****************************
    public function datatables(){

            $consecutivos     = Consecutivos::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($consecutivos)
            ->addColumn('action', function($consecutivo){
                return '<a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData('.$consecutivo->id.')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }

}
