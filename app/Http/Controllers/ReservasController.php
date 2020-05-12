<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Event;
use App\Conjunto;
use App\Reserva;
use Yajra\Datatables\Datatables;
use App\Zona_Comun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasController extends Controller
{

    function __construct()
    {
        $this->middleware('admin', ['only' => ['aceptar', 'lista']]);
        $this->middleware('dueno', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($zona_comun = null)
    {
        // dd(Auth);
        if (Auth::user()->id_rol == 2) {

            $user         = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos    = Conjunto::find(session('conjunto'));
            $zona_comun   = Zona_Comun::find($zona_comun);
            return view('admin.reservas.index')
                ->with('user', $user)
                ->with('conjuntos', $conjuntos)
                ->with('zona_comun', $zona_comun);
        } elseif (Auth::user()->id_rol == 3) {

            // Validación para identificar el admin del conjunto
            $admin        = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
            $zona_comun   = Zona_Comun::find($zona_comun);
            return view('dueno.reservas.index')
                ->with('admin', $admin)
                ->with('zona_comun', $zona_comun);
        } elseif (Auth::user()->id_rol == 4) {

            session(['section' => 'reservas']);

            $zona_comun   = Zona_Comun::find($zona_comun);
            return view('celador.reservas')
                ->with('zona_comun', $zona_comun);
        }
    }

    public function lista()
    {

        session(['section' => 'lista_reservas']);

        $conjunto = Conjunto::find(session('conjunto'));
        $zonas_comunes = Zona_Comun::where('conjunto_id', session('conjunto'))->get();
        $propietarios = User::where([
            ['id_conjunto', session('conjunto')],
            ['id_rol', 3]
        ])->get();

        return view('admin.reservas.lista')
            ->with('zonas_comunes', $zonas_comunes)
            ->with('propietarios', $propietarios)
            ->with('conjuntos', $conjunto);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return redirect('/reservas');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $reserva = new Reserva();
        try {
            $zona_comun = Zona_Comun::find($request->zona_comun);

            if ($zona_comun) {
                if ($zona_comun->conjunto_id == session('conjunto')) {
                    $reserva->fecha_inicio = $request->fecha_inicio . ' ' . $request->hora_inicio;
                    $reserva->fecha_fin = $request->fecha_fin . ' ' . $request->hora_fin;

                    if (strtotime($reserva->fecha_inicio) >= strtotime($reserva->fecha_fin)) {
                        return ['res' => 0, 'msg' => 'La fecha y hora de fin no pueden ser antes ni igual que la fecha y hora de inicio.'];
                    }

                    $reservas = $zona_comun->reservas()->where([
                        ['fecha_inicio', '>=', $reserva->fecha_inicio],
                        ['fecha_inicio', '<', $reserva->fecha_fin],
                        ['estado', 'aceptada']
                    ])->orWhere([
                        ['fecha_inicio', '<=', $reserva->fecha_inicio],
                        ['fecha_fin', '>=', $reserva->fecha_fin],
                        ['estado', 'aceptada']
                    ])->orWhere([
                        ['fecha_fin', '>', $reserva->fecha_inicio],
                        ['fecha_fin', '<=', $reserva->fecha_fin],
                        ['estado', 'aceptada']
                    ])->count();

                    if ($reservas > 0) {
                        return ['res' => 0, 'msg' => 'Ya existe una reserva aprovada en este horario'];
                    }

                    $reserva->fecha_solicitud = date('Y-m-d');
                    $reserva->motivo = $request->motivo;
                    $reserva->asistentes = $request->asistentes;
                    $reserva->zona_comun_id = $request->zona_comun;

                    $usuario = Auth::user();
                    if ($usuario->id_rol == 2) { //administrador
                        $reserva->propietario_id = $request->propietario;
                        $reserva->estado = 'aceptada';
                        $reserva->save();

                        return ['res' => 1, 'msg' => 'Reserva agregada y aceptada correctamente!'];
                    } else if ($usuario->id_rol == 3) { //propietario
                        $reserva->propietario_id = $usuario->id;
                        $reserva->save();

                        $antes = '';
                        if ($zona_comun->numero > 1) {
                            if ($zona_comun->tipo == 'mes') {
                                $antes = $zona_comun->numero . ' meses';
                            }
                            $antes = $zona_comun->numero . ' ' . $zona_comun->tipo . 's';
                        } else {
                            $antes = $zona_comun->numero . ' ' . $zona_comun->tipo;
                        }

                        return ['res' => 1, 'msg' => 'Reserva agregada correctamente. Recuerde que solo podra rechazar o eliminar su reserva ' . $antes . ' antes a la fecha  que reservó.'];
                    }
                }
            }
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al registrar la reserva.', 'e' => $th];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Reserva $reserva)
    {
        return  view('admin.reservas.reserva')->with('reserva', $reserva);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    public function destroy(Reserva $reserva)
    {
        $usuario = Auth::user();

        if ($reserva->propietario_id == $usuario->id and $reserva->estado == 'pendiente') {

            if (!$this->comprobarEstado($reserva)) {
                return ['res' => 0, 'msg' => 'Se ha pasado la fecha y hora máxima para cancelar la reserva, su reserva quedo aprovada y sera cargada a su cuenta de cobro.'];
            }

            $reserva->delete();
            return ['res' => 1, 'msg' => 'Reserva eliminada correctamente'];
        } else {
            return ['res' => 0, 'msg' => 'No puede eliminar una reserva que no hizo usted'];
        }
    }

    public function all(Zona_Comun $zona_comun, Request $request)
    {

        $date_start = $request->start;
        $date_end = $request->end;

        //comprobar que sea una zona común del conjunto al que pertenece
        if ($zona_comun->conjunto_id == session('conjunto')) {
            $usuario = Auth::user();
            $reservas = null;
            switch ($usuario->id_rol) {
                case 2: //admin
                    $reservas = DB::table('reservas')->join('users', 'reservas.propietario_id', 'users.id')
                        ->where([
                            ['reservas.fecha_inicio', '>=', $date_start],
                            ['reservas.fecha_fin', '<=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id]
                        ])
                        ->orWhere([
                            ['reservas.fecha_fin', '>=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id]
                        ])->orWhere([
                            ['reservas.fecha_inicio', '<=', $date_start],
                            ['reservas.zona_comun_id', $zona_comun->id]
                        ])->select([
                            'reservas.id as id',
                            'users.nombre_completo as title',
                            'reservas.fecha_inicio as start',
                            'reservas.fecha_fin as end',
                            'reservas.estado as estado'
                        ])->get();

                    break;
                case 3: //propietario
                    $reservas = DB::table('reservas')->join('users', 'reservas.propietario_id', 'users.id')
                        ->where([
                            ['reservas.fecha_inicio', '>=', $date_start],
                            ['reservas.fecha_fin', '<=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.estado', 'aceptada']
                        ])
                        ->orWhere([
                            ['reservas.fecha_fin', '>=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.estado', 'aceptada']
                        ])->orWhere([
                            ['reservas.fecha_inicio', '<=', $date_start],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.estado', 'aceptada']
                        ])->orWhere([
                            ['reservas.fecha_inicio', '>=', $date_start],
                            ['reservas.fecha_fin', '<=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.propietario_id', $usuario->id]
                        ])->orWhere([
                            ['reservas.fecha_fin', '>=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.propietario_id', $usuario->id]
                        ])->orWhere([
                            ['reservas.fecha_inicio', '<=', $date_start],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.propietario_id', $usuario->id]
                        ])->select([
                            'reservas.id as id',
                            'users.nombre_completo as title',
                            'reservas.fecha_inicio as start',
                            'reservas.fecha_fin as end',
                            'reservas.estado as estado'
                        ])->get();

                    break;
                case 4: //porteria
                    $reservas = DB::table('reservas')->join('users', 'reservas.propietario_id', 'users.id')
                        ->where([
                            ['reservas.fecha_inicio', '>=', $date_start],
                            ['reservas.fecha_fin', '<=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.estado', 'aceptada']
                        ])
                        ->orWhere([
                            ['reservas.fecha_fin', '>=', $date_end],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.estado', 'aceptada']
                        ])->orWhere([
                            ['reservas.fecha_inicio', '<=', $date_start],
                            ['reservas.zona_comun_id', $zona_comun->id],
                            ['reservas.estado', 'aceptada']
                        ])->select([
                            'reservas.id as id',
                            'users.nombre_completo as title',
                            'reservas.fecha_inicio as start',
                            'reservas.fecha_fin as end',
                            'reservas.estado as estado'
                        ])->get();
                    break;
            }

            for ($i = 0; $i < count($reservas); $i++) {
                $reserva = Reserva::find($reservas[$i]->id);
                if (!$this->comprobarEstado($reserva)) {
                    $reservas[$i]->estado = "aceptada";
                }
            }

            $respuesta = str_replace('"estado":"pendiente"', '"color":"#337ab7","textColor": "#fff"', json_encode($reservas));
            $respuesta = str_replace('"estado":"aceptada"', '"color":"#1ABB9C","textColor": "#fff"', $respuesta);
            $respuesta = str_replace('"estado":"rechazada"', '"color":"#E74C3C","textColor": "#fff"', $respuesta);

            return $respuesta;
        }

        return [];
    }


    public function aceptar(Reserva $reserva)
    {
        try {
            if ($reserva->zona_comun->conjunto->id == session('conjunto')) {
                $reserva->estado = 'aceptada';
                $reserva->save();

                $email = new CorreoController();
                $contenido = "Su reserva ha sido aprobada lo invitamos a que haga uso de ella. <br> 
                                <b>Zona social:</b> {$reserva->zona_comun->nombre} <br>
                                <b>Hora inicio:</b> {$reserva->fecha_inicio} <br>
                                <b>Hora fin:</b> {$reserva->fecha_fin} <br>
                                <b>Motivo:</b> {$reserva->motivo} <br>";
                $email->enviarEmail(Conjunto::find(session('conjunto')), [$reserva->propietario], 'Reserva zona social', $contenido);

                return ['res' => 1, 'msg' => 'Se aceptó la reserva'];
            } else {
                return ['res' => 0, 'msg' => 'No puede afectar una reserva que no sea de su coopropiedad.'];
            }
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al realizar la operación'];
        }
    }

    public function rechazar(Reserva $reserva, Request $request)
    {

        try {
            if ($reserva->zona_comun->conjunto->id == session('conjunto')) {
                $usuario = Auth::user();
                if (($usuario->id_rol == 2)) {
                    $reserva->estado = 'rechazada';
                    $reserva->motivo_rechazo = $request->motivo;
                    $reserva->save();
                    $email = new CorreoController();
                    $contenido = "Su reserva ha sido rechazada<br> 
                                    <b>Motivo anulación:</b> {$reserva->motivo_rechazo} <br>
                                    <b>Zona social:</b> {$reserva->zona_comun->nombre} <br>
                                    <b>Hora inicio:</b> {$reserva->fecha_inicio} <br>
                                    <b>Hora fin:</b> {$reserva->fecha_fin} <br>
                                    <b>Motivo:</b> {$reserva->motivo} <br>";
                    $email->enviarEmail(Conjunto::find(session('conjunto')), [$reserva->propietario], 'Reserva zona social', $contenido);
                } else if ($usuario->id_rol == 3 and $reserva->propietario_id == $usuario->id) {
                    if (!$this->comprobarEstado($reserva)) {
                        return ['res' => 0, 'msg' => 'Se ha pasado la fecha y hora máxima para cancelar la reserva, su reserva quedo aprovada y sera cargada a su cuenta de cobro.'];
                    }

                    $reserva->estado = 'rechazada';
                    $reserva->motivo_rechazo = $request->motivo . ' (Cancela el propietario).';
                    $reserva->save();
                } else {
                    return ['res' => 0, 'msg' => 'No tiene permisos para realizar esta acción'];
                }

                return ['res' => 1, 'msg' => 'Se rechazó la reserva'];
            } else {
                return ['res' => 0, 'msg' => 'No puede afectar una reserva que no sea de su coopropiedad.'];
            }
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al realizar la operación'];
        }
    }

    private function comprobarEstado(Reserva $reserva)
    {
        $fecha_inicio = $reserva->fecha_inicio;
        $zona_comun = $reserva->zona_comun;

        $resto = $zona_comun->numero;
        switch ($zona_comun->tipo) {
            case 'hora':
                $resto .= ' hours';

                break;
            case 'dia':
                $resto .= ' days';

                break;
            case 'mes':
                $resto .= ' months';

                break;

            default:
                $resto = '0 days';
                break;
        }


        //máximo plazo para cancelar reserva
        $fecha = date("d-m-Y H:i", strtotime($fecha_inicio . "- " . $resto));

        if (strtotime(date("d-m-Y H:i")) >= strtotime($fecha)) {
            if ($reserva->estado == 'pendiente')
                return $this->aceptar($reserva)['res'];
            // $reserva->estado = 'aceptada';
            // $reserva->save();
            // return false;
        }
        return true;
    }

    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $reservas = Reserva::join('zonas_comunes', 'zonas_comunes.id', '=', 'reservas.zona_comun_id')
            ->where('zonas_comunes.conjunto_id', session('conjunto'))
            ->select('reservas.*')
            ->get();

        return Datatables::of($reservas)
            ->addColumn('zona_comun', function ($reserva) {
                return $reserva->zona_comun->nombre;
            })->addColumn('propietario', function ($reserva) {
                return $reserva->propietario->nombre_completo;
            })->addColumn('estado', function ($reserva) {
                return mb_strtoupper($reserva->estado);
            })->addColumn('action', function ($reserva) {
                return '<a data-toggle="tooltip" data-placement="top" title="información de la reserva" class="btn btn-default" 
                            onclick="mostrarModalEvento(' . $reserva->id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
            })->make(true);
    }
}
