<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Division;
use App\Tipo_Documento;
use App\Tipo_unidad;
use App\TipoMascotas;
use Yajra\Datatables\Datatables;
use App\Unidad;
use Illuminate\Support\Facades\Crypt;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnidadController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
        // dd('hola');
        $this->middleware('admin', ['only' => ['store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'unidades']);

        //index para listar los tipos de unidad
        $tipos = Tipo_unidad::where('conjunto_id', session('conjunto'))->get();
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();

        $user = Auth::user();

        // dd($user);

        if ($user->id_rol == 2) {
            return view('admin.unidades.index')
                ->with('conjuntos', $conjuntos)
                ->with('tipos', $tipos);
        } elseif ($user->id_rol == 4) {
            return view('celador.tipo_unidades')
                ->with('conjuntos', $conjuntos)
                ->with('tipos', $tipos);
        } elseif ($user->id_rol == 3) {
            return view('dueno.unidades.index')
                ->with('conjuntos', $conjuntos);
        }

        /*$unidades  = Unidad::join('unidads_users', 'unidads.id', '=', 'unidads_users.unidad_id')
            ->where([
                ['unidads.conjunto_id', session('conjunto')],
                ['unidads_users.user_id', $user->id],
                ['unidads_users.estado','Activo']
            ]) 
            ->select('unidads.*')
            ->get();*/
    }


    public function indexTipo(Tipo_unidad $tipo)
    {
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();

        //atributos para pedir en la vista
        $atributos = [];
        $aux = $tipo->atributos;
        foreach ($aux as $value) {
            $atributos[] = $value->nombre;
        }

        $user = Auth::user();

        if ($user->id_rol == 2) {
            return view('admin.unidades.listarUnidades')
                ->with('tipo', $tipo)
                ->with('atributos', $atributos)
                ->with('conjuntos', $conjuntos);
        } elseif ($user->id_rol == 4) {
            return view('celador.unidades')
                ->with('atributos', $atributos)
                ->with('tipo', $tipo)
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

        //para enviar desde el correo de la app
        $conjunto = new Conjunto();
        $conjunto->nombre = 'Gestión copropietario';
        $conjunto->correo = 'gestioncopropietario@gmail.com';
        $conjunto->password = Crypt::encrypt('gestioncopropietario2019');
        $usuario = new User();
        $usuario->nombre_completo = 'Juan Carlos'.date('d-m-Y H:i:s');
        $usuario->email = 'Juacagiri@gmail.com';
        $correo = new CorreoController();
        $correo->enviarEmail($conjunto,[$usuario],'Mantenimiento programado',$request->all());

        try {
            //code...
            $unidad = new Unidad();
            $unidad->numero_letra = $request->numero_letra;
            $unidad->coeficiente = $request->coeficiente;
            $unidad->referencia = $request->referencia;
            $unidad->division_id = $request->division_id;
            $unidad->conjunto_id = session('conjunto');
            $unidad->observaciones = $request->observaciones;
            $unidad->unidad_id = $request->unidad_id;
            $unidad->tipo_unidad_id = $request->tipo_unidad;
            $unidad->save();

            //si tiene un propietario para facturar
            if ($request->propietario) {
                $propietario = User::find($request->propietario);
                $propietario->unidades()->attach($unidad, ['fecha_ingreso' => date('Y-m-d')]);
            }

            return [
                'res' => 1,
                "msg" => "Unidad agregada correctamente esta el correo",
                "data" => $unidad
            ];
        } catch (\Throwable $th) {
            return ['res' => 0, "msg" => "Ocurrió un problema al guardar la unidad ", "e" => $th];
        }
        // return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function show(Unidad $unidade)
    {
        // $residentes_activos = $unidade->residentes()->where('estado','Activo')->get();

        //atributos para pedir en la vista
        $atributos = [];
        $aux = $unidade->tipo->atributos;
        foreach ($aux as $value) {
            $atributos[] = $value->nombre;
        }

        $var = $unidade->propietarios->where('pivot.estado', 'Activo')->first();

        // dd($var);

        $nombre_propietario = null;
        $documento_propietario = null;
        $email_propietario = null;
        $direccion_propietario = null;

        if ($var != null) {
            $nombre_propietario = $var['nombre_completo'];
            $documento_propietario = $var['numero_cedula'];
            $email_propietario = $var['email'];
            $direccion_propietario = $var['direccion'];
        }

        $conjunto = Conjunto::find(session('conjunto'));

        return view('admin.unidades.mostrarUnidad')
            ->with('conjunto', $conjunto)
            ->with('nombre_propietario', $nombre_propietario)
            ->with('direccion_propietario', $direccion_propietario)
            ->with('documento_propietario', $documento_propietario)
            ->with('email_propietario', $email_propietario)
            ->with('atributos', $atributos)
            ->with('unidad', $unidade);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Unidad $unidade)
    {
        //
        $tipo = $unidade->tipo;
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();

        //divisiones del conjunto
        $divisiones = Division::where('id_conjunto', session('conjunto'))->get();

        //atributos para pedir en la vista
        $atributos = [];
        $aux = $tipo->atributos;
        foreach ($aux as $value) {
            $atributos[] = $value->nombre;
        }

        $tipos_mascotas = null;
        if (in_array('lista_mascotas', $atributos)) {
            $tipos_mascotas = TipoMascotas::get();
        }

        $tipos_documentos = null;
        if (in_array('lista_residentes', $atributos) || in_array('lista_empleados', $atributos)) {
            $tipos_documentos = Tipo_Documento::get();
        }

        $propietarios = null;
        if (in_array('propietario', $atributos)) {
            $propietarios     = User::where([
                ['id_rol', '=', 3],
                ['id_conjunto', '=', session('conjunto')]
            ])->get();
        }

        //lista de unidades que pueden tener unidades asociadas
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->tipo->where('nombre','APARTAMENTO')->get();

        $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
            ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
            ->where([
                ['unidads.conjunto_id', session('conjunto')],
                ['atributos_tipo_unidads.nombre', 'lista_unidades']
            ])
            ->select('unidads.*')
            ->get();


        return view('admin.unidades.editarUnidad')
            ->with('unidad', $unidade)
            ->with('unidades', $unidades)
            ->with('tipos_mascotas', $tipos_mascotas)
            ->with('tipos_documentos', $tipos_documentos)
            ->with('propietarios', $propietarios)
            ->with('divisiones', $divisiones)
            ->with('atributos', $atributos)
            ->with('tipo', $tipo)
            ->with('conjuntos', $conjuntos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidad $unidade)
    {
        //

        // $user = App\User::find(1);
        // $user->roles()->updateExistingPivot($roleId, $attributes);

        try {
            $unidade->numero_letra = $request->numero_letra;
            $unidade->coeficiente = $request->coeficiente;
            $unidade->referencia = $request->referencia;
            $unidade->division_id = $request->division_id;
            $unidade->observaciones = $request->observaciones;
            $unidade->unidad_id = $request->unidad_id;
            $unidade->save();

            $propietario = 0;
            $nuevoPropietario = 0;

            //si tiene un propietario para facturar
            if ($request->propietario) {

                $propietario = $unidade->propietarios->where('pivot.estado', 'Activo')->first();

                if ($request->propietario != $propietario->id) {
                    $propietario->unidades()
                        ->updateExistingPivot(
                            $unidade->id,
                            [
                                'fecha_retiro' => date('Y-m-d'),
                                'estado' => 'Inactivo'
                            ]
                        );
                    $nuevoPropietario = User::find($request->propietario);
                    $nuevoPropietario->unidades()->attach($unidade, ['fecha_ingreso' => date('Y-m-d')]);
                    $unidade->cambio_propietario = true;
                    $unidade->save();

                    $propietario = $nuevoPropietario;
                }
            }

            return [
                'res' => 1,
                "msg" => "Unidad Editada correctamente",
                "data" => $unidade,
                "propietario" => $propietario,
            ];
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidad $unidade)
    {
        try {
            $unidade->delete();
            return ['res' => 1, 'msg' => 'Registro eliminado correctamente'];
        } catch (\Throwable $th) {
            return ['res' => 0, 'msg' => 'Ocurrió un error al tratar de eliminar el registro'];
        }
    }


    /**
     * Metodo para cargar la vista según el tipo de unidad
     * que se desea agregar
     * 
     * @param  \App\Tipo  $tipo
     * 
     */
    public function loadAddForTipo(Tipo_unidad $tipo)
    {
        $conjuntos = Conjunto::where('id', session('conjunto'))->first();

        //divisiones del conjunto
        $divisiones = Division::where('id_conjunto', session('conjunto'))->get();

        //atributos para pedir en la vista
        $atributos = [];
        $aux = $tipo->atributos;
        foreach ($aux as $value) {
            $atributos[] = $value->nombre;
        }

        $tipos_mascotas = null;
        if (in_array('lista_mascotas', $atributos)) {
            $tipos_mascotas = TipoMascotas::get();
        }

        $tipos_documentos = null;
        if (in_array('lista_residentes', $atributos) || in_array('lista_empleados', $atributos)) {
            $tipos_documentos = Tipo_Documento::get();
        }

        $propietarios = null;
        if (in_array('propietario', $atributos)) {
            $propietarios     = User::where([
                ['id_rol', '=', 3],
                ['id_conjunto', '=', session('conjunto')]
            ])->get();
        }

        //lista de unidades que pueden tener unidades asociadas
        // $unidades = Unidad::where('conjunto_id',session('conjunto'))->tipo->where('nombre','APARTAMENTO')->get();

        $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
            ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
            ->where([
                ['unidads.conjunto_id', session('conjunto')],
                ['atributos_tipo_unidads.nombre', 'lista_unidades']
            ])
            ->select('unidads.*')
            ->get();


        return view('admin.unidades.agregarUnidad')
            ->with('unidades', $unidades)
            ->with('tipos_mascotas', $tipos_mascotas)
            ->with('tipos_documentos', $tipos_documentos)
            ->with('propietarios', $propietarios)
            ->with('divisiones', $divisiones)
            ->with('atributos', $atributos)
            ->with('tipo', $tipo)
            ->with('conjuntos', $conjuntos);
    }


    public function datosPdf($tipo, Unidad $unidad)
    {


        $atributos = [];
        $aux = $unidad->tipo->atributos;
        foreach ($aux as $value) {
            $atributos[] = $value->nombre;
        }


        return view('admin.unidades.mostrarUnidadPdf')
            ->with('atributos', $atributos)
            ->with('tipo', $tipo)
            ->with('unidad', $unidad);
    }

    public function interes(Unidad $unidad)
    {
        return array('valor' => $unidad->interes());
    }



    // para listar por datatables
    // ****************************
    public function datatables($tipo = null)
    {



        switch (Auth::user()->id_rol) {
            case 2:
                $unidades = Unidad::where([
                    ['tipo_unidad_id', $tipo],
                    ['conjunto_id', session('conjunto')]
                ])->get();

                return Datatables::of($unidades)
                    ->addColumn('coeficiente', function ($unidad) {
                        return ($unidad->coeficiente) ? $unidad->coeficiente : 'No aplica';
                    })->addColumn('division', function ($unidad) {
                        return $unidad->division->tipo_division->division . ' ' . $unidad->division->numero_letra;
                    })->addColumn('observaciones', function ($unidad) {
                        return ($unidad->observaciones) ? $unidad->observaciones : 'No aplica';
                    })->addColumn('action', function ($unidad) {
                        return '<a data-toggle="tooltip" data-placement="top" 
                                    title="Mostrar la información de la unidad" class="btn btn-default" 
                                    onclick="loadData(' . $unidad->id . ')">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" 
                                    title="Editar la unidad" href="' . route('unidades.edit', ['unidade' => $unidad->id]) . '" class="btn btn-default">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" 
                                    title="Eliminar esta unidad" class="btn btn-default" onclick="deleteData(' . $unidad->id . ')">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" 
                                    title="Ejecutar un retiro total" class="btn btn-default" onclick="retiroTotal(' . $unidad->id . ')">
                                    <i class="fa fa-close"></i>
                                </a>
                                <a data-toggle="tooltip" data-placement="top" 
                                    title="Registrar una novedad" class="btn btn-default" onclick="registrarNovedad(' . $unidad->id . ')">
                                    <i class="fa fa-plus-square"></i>
                                </a>';
                    })->make(true);
            case 3:
                $unidades  = Unidad::join('unidads_users', 'unidads.id', '=', 'unidads_users.unidad_id')
                    ->where([
                        ['unidads_users.user_id', Auth::user()->id],
                        ['unidads_users.estado', 'Activo']
                    ])
                    ->select('unidads.*')
                    ->get();
                return Datatables::of($unidades)
                    ->addColumn('unidad', function ($unidad) {
                        return $unidad->tipo->nombre . ' ' . $unidad->numero_letra;
                    })->addColumn('coeficiente', function ($unidad) {
                        return ($unidad->coeficiente) ? $unidad->coeficiente : 'No aplica';
                    })->addColumn('division', function ($unidad) {
                        return $unidad->division->tipo_division->division . ' ' . $unidad->division->numero_letra;
                    })->addColumn('observaciones', function ($unidad) {
                        return ($unidad->observaciones) ? $unidad->observaciones : 'No aplica';
                    })->addColumn('action', function ($unidad) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Ver unidad" class="btn btn-default" onclick="loadData(' . $unidad->id . ')">
                                    <i class="fa fa-search"></i>
                                </a>';
                    })->make(true);
            case 4:
                $unidades = Unidad::where([
                    ['tipo_unidad_id', $tipo],
                    ['conjunto_id', session('conjunto')]
                ])->get();

                return Datatables::of($unidades)
                    ->addColumn('coeficiente', function ($unidad) {
                        return ($unidad->coeficiente) ? $unidad->coeficiente : 'No aplica';
                    })->addColumn('division', function ($unidad) {
                        return $unidad->division->tipo_division->division . ' ' . $unidad->division->numero_letra;
                    })->addColumn('observaciones', function ($unidad) {
                        return ($unidad->observaciones) ? $unidad->observaciones : 'No aplica';
                    })->addColumn('action', function ($unidad) {
                        return '<a data-toggle="tooltip" data-placement="top" title="Ver unidad" class="btn btn-default" onclick="loadData(' . $unidad->id . ')">
                                    <i class="fa fa-search"></i>
                                </a>';
                    })->make(true);

            default:
                return [];
        }
    }
}
