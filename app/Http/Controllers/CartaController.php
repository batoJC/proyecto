<?php

namespace App\Http\Controllers;

use PDF;
use QR;
use App\Carta;
use Yajra\Datatables\Datatables;
use App\Conjunto;
use App\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartaController extends Controller
{
    function __construct()
    {
        $this->middleware('admin',['only'=>['store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'cartas']);

        $rol = Auth::user()->id_rol;

        if ($rol == 2) { //administrador
            $conjuntos = Conjunto::find(session('conjunto'));
            $unidades = Unidad::where('conjunto_id', session('conjunto'))->get();

            return view('admin.bienes.cartas')
                ->with('conjuntos', $conjuntos)
                ->with('unidades', $unidades);
                // ->with('cartas', $cartas);
        } elseif ($rol == 3) { //propietario
            $cartas = Carta::where([['conjunto_id', session('conjunto')], ['user_id', Auth::user()->id]])->get();
            $conjuntos = Conjunto::find(session('conjunto'));

            return view('dueno.cartas')
                ->with('conjuntos', $conjuntos)
                ->with('cartas', $cartas);
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
        //

        try {
            $unidad = Unidad::find($request->unidad_id);

            $carta = new Carta();
            $carta->fecha = date('Y-m-d');
            $carta->encabezado = $request->encabezado;
            $carta->cuerpo = $request->cuerpo;
            $carta->user_id = ($request->propietario) ? $request->propietario : $unidad->propietarios()->wherePivot('estado', 'Activo')->first()->id;
            $carta->unidad_id = $request->unidad_id;
            $carta->conjunto_id = $unidad->conjunto_id;
            $carta->save();

            //si es el administrador se debe de retirar todo lo que actualmente
            //esta activo en la unidad
            if (Auth::user()->id_rol == 2 && $request->encabezado == 'Retiro total') {
                $unidad->editar = true;

                //residentes
                foreach ($unidad->residentes as $residente) {
                    $residente->estado = 'Inactivo';
                    $residente->fecha_salida = date('Y-m-d');
                    $residente->save();
                }

                //mascotas
                foreach ($unidad->mascotas as $mascota) {
                    $mascota->estado = 'Inactivo';
                    $mascota->fecha_retiro = date('Y-m-d');
                    $mascota->save();
                }

                //vehiculos
                foreach ($unidad->vehiculos as $vehiculo) {
                    $vehiculo->estado = 'Inactivo';
                    $vehiculo->fecha_retiro = date('Y-m-d');
                    $vehiculo->save();
                }

                //visitantes
                foreach ($unidad->visitantes as $visitante) {
                    $visitante->estado = 'Inactivo';
                    $visitante->fecha_retiro = date('Y-m-d');
                    $visitante->save();
                }

                //empleados
                foreach ($unidad->empleados as $empleado) {
                    $empleado->estado = 'Inactivo';
                    $empleado->fecha_retiro = date('Y-m-d');
                    $empleado->save();
                }
            }

            return array('res' => 1, "msg" => 'Operación realizada correctamente.', 'data' => $carta);
        } catch (\Throwable $th) {
            return array('res' => 0, "msg" => 'Ocurrió un error en el servidor.', 'e' => $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Carta  $carta
     * @return \Illuminate\Http\Response
     */
    public function show(Carta $carta)
    {
        $files = glob(public_path('qrcodes') . '/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file); //elimino el fichero
        }

        $pdf = null;

        $text_qr = "Fecha: " . date('d-m-Y',strtotime($carta->fecha));
        $text_qr .= "\n\r Unidad: " . $carta->unidad->tipo->nombre . ' ' . $carta->unidad->numero_letra;
        $text_qr .= "\n\r Copropiedad: " . Conjunto::find(session('conjunto'))->nombre;

        QR::format('png')->size(180)->margin(10)->generate($text_qr, public_path('qrcodes/qrcode_' . $carta->id . '.png'));
        $pdf = PDF::loadView('admin.PDF.carta', ['carta' => $carta]);
        return $pdf->stream();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Carta  $carta
     * @return \Illuminate\Http\Response
     */
    public function edit(Carta $carta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Carta  $carta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carta $carta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Carta  $carta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carta $carta)
    {
        //
    }

    // para listar por datatables
    // ****************************
    public function datatables(){

        $cartas = Carta::where('conjunto_id', session('conjunto'))->get();


        return Datatables::of($cartas)
            ->addColumn('fecha',function($carta){
                return date('d-m-Y',strtotime($carta->fecha));
            })->addColumn('unidad',function($carta){
                return $carta->unidad->tipo->nombre .' '. $carta->unidad->numero_letra;
            })->addColumn('cuerpo',function($carta){
                return str_limit($carta->cuerpo,80);
            })->addColumn('action', function($carta){
                return '<a data-toggle="tooltip" data-placement="top" title="Ver pdf" class="btn btn-default" onclick="loadDataCarta('.$carta->id.')">
                            <i class="fa fa-eye"></i>
                        </a>';
            })->make(true);
    }

}
