<?php

namespace App\Http\Controllers;

use App\Cartera;
use App\Conjunto;
use Yajra\Datatables\Datatables;
use App\Descuento;
use App\Unidad;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DescuentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'descuentos']);
        if (Auth::user()->id_rol == 2) {
            $conjuntos = Conjunto::where('id', session('conjunto'))->first();
            $propietarios     = User::where([
                ['id_rol', '=', 3],
                ['id_conjunto', '=', session('conjunto')]
            ])->get();
            return view('admin.descuentos.index')
                ->with('conjuntos', $conjuntos)
                ->with('propietarios', $propietarios);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valor = $this->calcularValor($request)['valor'];
        $descuento = new Descuento();
        $movimientos = array();

        try {
            $descuento->fecha = $request->fecha;
            $descuento->valor = $valor;
            $descuento->user_id = $request->user_id;
            $descuento->unidad_id = $request->unidad_id;
            $descuento->conjunto_id = session('conjunto');
            if ($descuento->save()) {
                $cuentas = array();
                if ($request->unidad_id) {
                    $cuentas = Unidad::find($request->unidad_id)->cuotas();
                } else {
                    $cuentas = User::find($request->user_id)->cuentas();
                }

                $porcentaje = strstr($request->valor, '%', true);
                if ($porcentaje) {
                    foreach ($cuentas as $cuenta) {
                        $totalInteres = $cuenta['interes'] * ($porcentaje / 100);
                        if ($totalInteres > 0) {
                            $cartera = new Cartera();
                            $cartera->prefijo = 'Descuento';
                            $cartera->numero = $descuento->id;
                            $cartera->fecha = $descuento->fecha;
                            $cartera->valor = $totalInteres;
                            $cartera->tipo_de_pago = 'Descuento Intereses';

                            switch ($cuenta['tipo']) {
                                case 'administracion':
                                    $cartera->tipo_de_cuota = 'Interes Cuota Administrativa';
                                    break;
                                case 'extraordinaria':
                                    $cartera->tipo_de_cuota = 'Interes Cuota Extraordinaria';
                                    break;
                                case 'otro_cobro':
                                    $cartera->tipo_de_cuota = 'Interes Otro Cobro';
                                    break;
                                case 'multa':
                                    $cartera->tipo_de_cuota = 'Interes Multa';
                                    break;
                            }

                            $cartera->movimiento = $cuenta['cuota_id'];
                            $cartera->user_register_id = Auth::user()->id;
                            $cartera->user_id = $descuento->user_id;
                            $cartera->unidad_id = $descuento->unidad_id;
                            $cartera->save();
                            $movimientos[] = $cartera;
                        }
                    }
                } else {
                    foreach ($cuentas as $cuenta) {
                        if ($valor > 0) {
                            $totalInteres = ($valor >= $cuenta['interes']) ? $cuenta['interes'] : $valor;
                            $valor -= $totalInteres;
                            if ($totalInteres > 0) {
                                $cartera = new Cartera();
                                $cartera->prefijo = 'Descuento';
                                $cartera->numero = $descuento->id;
                                $cartera->fecha = $descuento->fecha;
                                $cartera->valor = $totalInteres;
                                $cartera->tipo_de_pago = 'Descuento Intereses';

                                switch ($cuenta['tipo']) {
                                    case 'administracion':
                                        $cartera->tipo_de_cuota = 'Interes Cuota Administrativa';
                                        break;
                                    case 'extraordinaria':
                                        $cartera->tipo_de_cuota = 'Interes Cuota Extraordinaria';
                                        break;
                                    case 'otro_cobro':
                                        $cartera->tipo_de_cuota = 'Interes Otro Cobro';
                                        break;
                                    case 'multa':
                                        $cartera->tipo_de_cuota = 'Interes Multa';
                                        break;
                                }

                                $cartera->movimiento = $cuenta['cuota_id'];
                                $cartera->user_register_id = Auth::user()->id;
                                $cartera->user_id = $descuento->user_id;
                                $cartera->unidad_id = $descuento->unidad_id;
                                $cartera->save();
                                $movimientos[] = $cartera;
                            }
                        } else {
                            break;
                        }
                    }
                }
                return array('res' => 1, 'msg' => 'Descuento guardado correctamente.');
            } else {
                return array('res' => 0, 'msg' => 'No se logró guardar el descuento.');
            }
        } catch (\Throwable $th) {

            $descuento->delete();
            foreach ($movimientos as $movimiento) {
                $movimiento->delete();
            }
            return array('res' => 0, 'msg' => 'No se logró guardar el descuento.');
        }
    }

    public function calcularValor(Request $request)
    {
        $valor = 0;
        $porcentaje = strstr($request->valor, '%', true);
        if (!$porcentaje) {
            $valor = $request->valor;
        } else {
            if ($request->unidad_id) {
                $valor = ($porcentaje / 100) * Unidad::find($request->unidad_id)->interes();
            } else {
                $valor = ($porcentaje / 100) * User::find($request->user_id)->interes();
            }
        }

        // dd($valor);

        return array('valor' => round($valor / 100) * 100, 'propietario' => User::find($request->user_id)->nombre_completo);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function show(Descuento $descuento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function edit(Descuento $descuento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descuento $descuento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function destroy($descuento)
    {
        $descuento = Descuento::find($descuento);
        if ($descuento->delete()) {
            return array('res' => 1, 'msg' => 'Descuento eliminado correctamente.');
        } else {
            return array('res' => 0, 'msg' => 'No se logró eliminar el descuento.');
        }
    }

    public function unidades($propietario)
    {
        $unidades = User::find($propietario)->unidades;
        $salida = array();
        foreach ($unidades as $unidad) {
            $nombre = $unidad->tipo->nombre . ' ' . $unidad->numero_letra;
            if ($unidad->pivot->estado == 'Activo') {
                $nombre .= ' - Actualmente Dueño';
            } else {
                $nombre .= ' - Dueño Antiguo';
            }
            $salida[] = array('id' => $unidad->id, 'nombre' => $nombre);
        }
        return $salida;
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $descuentos = Descuento::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($descuentos)
            ->addColumn('fecha',function($descuento){
                return date('d-m-Y',strtotime($descuento->fecha));
            })->addColumn('unidad',function($descuento){
                return ($descuento->unidad) ? $descuento->unidad->tipo->nombre.' '.$descuento->unidad->numero_letra : 'No Aplica';
            })->addColumn('propietario',function($descuento){
                return $descuento->propietario->nombre_completo;
            })->addColumn('valor',function($descuento){
                return '$ '.number_format($descuento->valor);
            })->addColumn('action', function ($descuento) {
                return '<a  data-toggle="tooltip" data-placement="top" title="Eliminar" 
                            onclick="deleteData(' . $descuento->id . ')" 
                            class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
