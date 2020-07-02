<?php

namespace App\Http\Controllers;

use App\Carta;
use Auth;
use DB;
use App\User;
use App\Noticia;
use App\Conjunto;
use App\CuentaBancaria;
use App\QuejasReclamos;
use App\Reglamento;
use App\Variable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //redireccionar automaticamente a su panel de control
        $rol = Auth::user()->id_rol;
        switch ($rol) {
            case 1:
                return $this->owner();
            case 2:
                return $this->admin();
            case 3:
                return $this->dueno();
            case 4:
                return $this->porteria();
            default:
                redirect('/');
                break;
        }
    }

    // Dueño del desarrollo
    // ********************
    public function owner()
    {
        session(['section' => 'home']);
        $variablesLiquidador = Variable::where('modulo','liquidacion')->get();
        return view('owner.home')->with('variablesLiquidador',$variablesLiquidador);
    }

    // Admin Conjunto
    // **************
    public function admin()
    {

        session(['section' => 'home']);

        $active_user = User::where('id_rol', 2)->where('id', Auth::user()->id)->where('estado', 'Activo')->first();
        // Validador si el usuario esta desactivado
        // ****************************************
        if ($active_user) {

            // Validación de si el usuario es admin de varios conjuntos
            // ********************************************************
            if (Auth::user()->id_conjunto == null) {

                // Validación de si el conjunto está inicializado (la session)
                // ***********************************************************
                if (!session('conjunto')) {
                    $conjuntos = Conjunto::all();
                    return view('admin.seleccionar_conjunto')
                        ->with('conjuntos', $conjuntos);
                } else {
                    $user                = User::where('id_conjunto', session('conjunto'))->get();
                    $conjuntos           = Conjunto::find(session('conjunto'));
                    // Obtener la fecha actual
                    // ***********************
                    $fecha_actual        = idate('Y') . '-' . idate('m') . '-' . idate('d');
                    // ***********************
                    $quejas_reclamos_pen = QuejasReclamos::where('id_conjunto', session('conjunto'))
                        ->where('estado', 'Pendiente')
                        ->get();
                    $quejas_reclamos_res = QuejasReclamos::where('id_conjunto', session('conjunto'))
                        ->where('estado', 'Resuelto')
                        ->get();
                    $quejas_reclamos_pro = QuejasReclamos::where('id_conjunto', session('conjunto'))
                        ->where('estado', 'En proceso')
                        ->get();
                    $quejas_reclamos_cer = QuejasReclamos::where('id_conjunto', session('conjunto'))
                        ->where('estado', 'Cerrado')
                        ->get();
                    // Consulta para las peticiones vencidas
                    // *************************************
                    $quejas_reclamos_ven = QuejasReclamos::where('id_conjunto', session('conjunto'))
                        ->where('estado', 'Pendiente')
                        ->where('dias_restantes', '<=', 6)
                        ->get();
                    // *************************************
                    $quejas_reclamos_all = QuejasReclamos::where('id_conjunto', session('conjunto'))->get();

                    $cuentas_bancarias = CuentaBancaria::where('conjunto_id', session('conjunto'))->get();


                    $sumaIngresos = DB::table("flujo_efectivos")
                        ->where([
                            ["conjunto_id", session('conjunto')],
                            ['tipo', 0]
                        ])->sum(DB::raw("valor"));

                    $sumaEgresos = DB::table("flujo_efectivos")
                        ->where([
                            ["conjunto_id", session('conjunto')],
                            ['tipo', 1]
                        ])->sum(DB::raw("valor"));

                    $saldoActual = $sumaIngresos - $sumaEgresos;

                    return view('admin.home')
                        ->with('user', $user)
                        ->with('conjuntos', $conjuntos)
                        ->with('saldoActual', $saldoActual)
                        ->with('cuentas_bancarias', $cuentas_bancarias)
                        ->with('quejas_reclamos_pen', $quejas_reclamos_pen)
                        ->with('quejas_reclamos_res', $quejas_reclamos_res)
                        ->with('quejas_reclamos_pro', $quejas_reclamos_pro)
                        ->with('quejas_reclamos_cer', $quejas_reclamos_cer)
                        ->with('quejas_reclamos_ven', $quejas_reclamos_ven)
                        ->with('quejas_reclamos_all', $quejas_reclamos_all);
                }
                // ************************************************************
            } else {
                // Declaracion de la variable de sesion aqui
                // *****************************************
                $conjunto = Conjunto::find(Auth::user()->id_conjunto);
                session(['conjunto' => $conjunto->id]);
                // ------------------------------------

                $cuentas_bancarias = CuentaBancaria::where('conjunto_id', session('conjunto'))->get();
                $reglamento = Reglamento::where('conjunto_id', null)->first();


                $user                = User::where('id_conjunto', session('conjunto'))->get();
                $conjuntos           = Conjunto::find(session('conjunto'));
                // Obtener la fecha actual
                // ***********************
                $fecha_actual        = idate('Y') . '-' . idate('m') . '-' . idate('d');
                // ***********************
                $quejas_reclamos_pen = QuejasReclamos::where('id_conjunto', session('conjunto'))
                    ->where('estado', 'Pendiente')
                    ->get();
                $quejas_reclamos_res = QuejasReclamos::where('id_conjunto', session('conjunto'))
                    ->where('estado', 'Resuelto')
                    ->get();
                $quejas_reclamos_pro = QuejasReclamos::where('id_conjunto', session('conjunto'))
                    ->where('estado', 'En proceso')
                    ->get();
                $quejas_reclamos_cer = QuejasReclamos::where('id_conjunto', session('conjunto'))
                    ->where('estado', 'Cerrado')
                    ->get();
                // Consulta para las peticiones vencidas
                // *************************************
                $quejas_reclamos_ven = QuejasReclamos::where('id_conjunto', session('conjunto'))
                    ->where('estado', 'Pendiente')
                    ->where('dias_restantes', '<=', 6)
                    ->get();
                // *************************************
                $quejas_reclamos_all = QuejasReclamos::where('id_conjunto', session('conjunto'))->get();

                $sumaIngresos = DB::table("flujo_efectivos")
                    ->where([
                        ["conjunto_id", session('conjunto')],
                        ['tipo', 0]
                    ])->sum(DB::raw("valor"));

                $sumaEgresos = DB::table("flujo_efectivos")
                    ->where([
                        ["conjunto_id", session('conjunto')],
                        ['tipo', 1]
                    ])->sum(DB::raw("valor"));

                $saldoActual = $sumaIngresos - $sumaEgresos;

                return view('admin.home')
                    ->with('user', $user)
                    ->with('conjuntos', $conjuntos)
                    ->with('reglamento', $reglamento)
                    ->with('cuentas_bancarias', $cuentas_bancarias)
                    ->with('saldoActual', $saldoActual)
                    ->with('quejas_reclamos_pen', $quejas_reclamos_pen)
                    ->with('quejas_reclamos_res', $quejas_reclamos_res)
                    ->with('quejas_reclamos_pro', $quejas_reclamos_pro)
                    ->with('quejas_reclamos_cer', $quejas_reclamos_cer)
                    ->with('quejas_reclamos_ven', $quejas_reclamos_ven)
                    ->with('quejas_reclamos_all', $quejas_reclamos_all);
            }
            // ************************************************************
        } else {
            return view('errors.no-active');
        }
    }

    // Dueño apto
    // **********
    public function dueno()
    {

        session(['section' => 'home']);

        if (Auth::user()->id_conjunto != null) {
            // Validación para identificar el admin del conjunto
            session(['conjunto' => Auth::user()->id_conjunto]);
            $admin    = User::where('id_rol', 3)->where('id_conjunto', Auth::user()->id_conjunto)->first();
            $noticias = Noticia::where('id_conjunto', $admin->id_conjunto)
                ->take(20)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('dueno.home')
                ->with('admin', $admin)
                ->with('noticias', $noticias);
            // ****************************************
        } elseif (session('conjunto_user') != null) {
            // Cuando esta nulo el id_conjunto 
            // -------------------------------
            // Validación para identificar el admin del conjunto
            $admin    = User::where('id_rol', 2)->where('id_conjunto', session('conjunto_user'))->first();
            $noticias = Noticia::where('id_conjunto', session('conjunto_user'))
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('dueno.home')
                ->with('admin', $admin)
                ->with('noticias', $noticias);
        } else {
            $conjuntos = Conjunto::all();
            return view('dueno.selecion')
                ->with('conjuntos', $conjuntos);
        }
    }

    // Proveedor
    // *********
    public function proveedor()
    {
        session(['section' => 'home']);

        $quejas_reclamos = QuejasReclamos::where('id_proveedor', Auth::user()->id)
            ->where('estado', 'En proceso')->get();
        return view('proveedor.home')
            ->with('quejas_reclamos', $quejas_reclamos);
    }

    // porteria
    // *********
    public function porteria()
    {
        session(['section' => 'home']);

        // $quejas_reclamos = QuejasReclamos::where('id_proveedor', Auth::user()->id)
        //     ->where('estado', 'En proceso')->get();
        // return view('celador.home')
        //     ->with('quejas_reclamos', $quejas_reclamos);

        session(['conjunto' => Auth::user()->id_conjunto]);
        $cartas = Carta::where('conjunto_id', session('conjunto'))->orderBy('fecha','DESC')->get();

        return view('celador.home')
            ->with('cartas', $cartas);
    }

    // Empleado 
    // ********

    // public function empleado()
    // {
    //     session(['section' => 'home']);

    //     return view('empleado.home');
    // }
}
