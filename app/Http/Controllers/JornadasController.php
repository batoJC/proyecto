<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\EmpleadosConjunto;
use App\Jornada;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Throw_;

class JornadasController extends Controller
{
    //

    public function index(EmpleadosConjunto $empleado, Request $request)
    {

        if ($empleado->conjunto_id != session('conjunto')) {
            return "No disponible";
        }

        $conjunto = Conjunto::find(session('conjunto'));

        $fecha = date('d-m-Y');
        if ($request->periodo) {
            $fecha = $request->periodo.'-01';
        }

        $month = date('m',strtotime($fecha));
        $year = date('Y',strtotime($fecha));

        $jornadas = Jornada::whereMonth('fecha', date('m', strtotime($fecha)))
            ->whereYear('fecha', date('Y', strtotime($fecha)))
            ->where('empleado_conjunto_id', $empleado->id)
            ->orderBy('fecha','ASC')
            ->get();
        

        return view('admin.jornadas.index')
            ->with('month', $month)
            ->with('year', $year)
            ->with('empleado', $empleado)
            ->with('conjuntos', $conjunto)
            ->with('jornadas', $jornadas);
    }

    public function store(Request $request){

        try {
            $datos = json_decode($request->datos);
            foreach ($datos as $dato) {
                $jornada = new Jornada();
                $jornada->fecha = date('Y.m-d',strtotime($dato->fecha));
                $jornada->entrada = date('H:i',strtotime($dato->entrada));
                $jornada->salida =  date('H:i',strtotime($dato->salida));
                $jornada->HOD = $dato->HOD;
                $jornada->HON = $dato->HON;
                $jornada->HODF = $dato->HODF;
                $jornada->HONF = $dato->HONF;
                $jornada->HEDO = $dato->HEDO;
                $jornada->HENO = $dato->HENO;
                $jornada->HEDF = $dato->HEDF;
                $jornada->HENF = $dato->HENF;
                $jornada->empleado_conjunto_id = $request->empleado;
                $jornada->save();
            }
            session(['status'=>'Jornadas registradas correctamene!']);
            return redirect()->action('JornadasController@index',['empelado'=>$request->empleado,$request]);
        } catch (\Throwable $th) {
            session(['error'=>'Ocurrió un error al registrar las jornadas!']);
            return redirect()->action('JornadasController@index',['empelado'=>$request->empleado,$request]);
        }
    }


    public function delete(Request $request){
        try {
            $jornada = Jornada::find($request->id);
            $jornada->delete();
            return ['res'=>1,'msg'=>'Jornada eliminada correctamente!'];
        } catch (\Throwable $th) {
            return ['res'=>0,'msg'=>'Ocurrió un error al intentar eliminar'];
        }
    }


    public function show(Jornada $jornada){
        if($jornada->empleado->conjunto_id != session('conjunto')){
            return ['res'=>0,'msg'=>'No tienes permiso para ver esto.'];
        }
        return $jornada;
    }


    public function update(Request $request){
        try {
            $jornada = Jornada::find($request->jornada_id);
            if($jornada->empleado->conjunto_id != session('conjunto')){
                session(['error'=>'No tienes permisos para realizar esta acción']);
                return redirect()->action('JornadasController@index',['empelado'=>$request->empleado,$request]);
            }
            $jornada->fecha = $request->fecha;
            $jornada->entrada = $request->entrada;
            $jornada->salida = $request->salida;
            $jornada->HOD = $request->HOD;
            $jornada->HON = $request->HON;
            $jornada->HODF = $request->HODF;
            $jornada->HONF = $request->HONF;
            $jornada->HEDO = $request->HEDO;
            $jornada->HENO = $request->HENO;
            $jornada->HEDF = $request->HEDF;
            $jornada->HENF = $request->HENF;
            $jornada->save();

            session(['status'=>'Se actualizo el registro correctamente!']);
            return redirect()->action('JornadasController@index',['empelado'=>$request->empleado,$request]);

        } catch (\Throwable $th) {
            session(['error'=>'Ocurrión un error al intentar actualizar el registro.']);
            return redirect()->action('JornadasController@index',['empelado'=>$request->empleado,$request]);
        }
    }


}
