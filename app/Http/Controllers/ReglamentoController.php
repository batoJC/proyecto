<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Reglamento;
use Illuminate\Http\Request;

class ReglamentoController extends Controller
{
    //

    public function show()
    {
        $reglamento = null;
        if(Auth::user()->id_rol == 1){
            $reglamento = Reglamento::where('conjunto_id', null)->first();
        }else{
            $reglamento = Reglamento::where('conjunto_id', session('conjunto'))->first();
        }
        return [$reglamento];
    }


    public function store(Request $request)
    {
        try {
            $reglamento = new Reglamento();
            $reglamento->descripcion = $request->descripcion;
            if ($request->hasFile('archivo_agregar')) {
                $file = time() . '.' . $request->archivo_agregar->getClientOriginalExtension();
                $request->archivo_agregar->move(public_path('reglamentos'), $file);
                $reglamento->archivo = $file;
            }

            $usuario = Auth::user();
            $reglamento->conjunto_id = $usuario->id_conjunto;

            $reglamento->save();
            return ['res' => 1, 'msg' => 'Reglamento guardado correctamente'];
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al guardar el reglamento','e'=>$th];
        }
    }


    public function update(Reglamento $reglamento, Request $request)
    {
        if($reglamento->conjunto_id != session('conjunto') && Auth::user()->id_rol != 1){
            return ['res'=>0,'msg'=>'No tiene permiso para esta acción'];
        }
        try {
            $reglamento->descripcion = $request->descripcion;
            if ($request->hasFile('archivo_editar')) {
                $archivoAnterior = $reglamento->archivo;
                $file = time() . '.' . $request->archivo_editar->getClientOriginalExtension();
                $request->archivo_editar->move(public_path('reglamentos'), $file);
                $reglamento->archivo = $file;
                @unlink(public_path('reglamentos') . '/' . $archivoAnterior);
            }
            $reglamento->save();
            return ['res' => 1, 'msg' => 'Reglamento editado correctamente'];
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al editar el reglamento'];
        }
    }
}
