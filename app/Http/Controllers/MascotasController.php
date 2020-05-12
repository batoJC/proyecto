<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Mascota;
use App\Conjunto;
use Yajra\Datatables\Datatables;
use App\Tipo_unidad;
use App\TipoMascotas;
use App\Unidad;
use Illuminate\Http\Request;

class MascotasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'mascotas']);

        $conjuntos = Conjunto::find(session('conjunto'));
        $user = Auth::user();

        if ($user->id_rol == 2) {
            $tipos_mascotas = TipoMascotas::get();
            $unidades = Unidad::join('tipo_unidad', 'unidads.tipo_unidad_id', '=', 'tipo_unidad.id')
                ->join('atributos_tipo_unidads', 'tipo_unidad.id', '=', 'atributos_tipo_unidads.tipo_unidad_id')
                ->where([
                    ['unidads.conjunto_id', session('conjunto')],
                    ['atributos_tipo_unidads.nombre', 'lista_mascotas']
                ])
                ->select('unidads.*')
                ->get();

            return view('admin.mascotas.index')
                ->with('unidades', $unidades)
                ->with('tipos_mascotas', $tipos_mascotas)
                ->with('conjuntos', $conjuntos);
        } elseif ($user->id_rol == 3) {

            if ($user->id_conjunto != null) {
                // Validación para identificar el admin del conjunto
                $admin       = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
                $aptoCliente = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)->first();
                // Validación si se ha creado el apto del cliente
                if ($aptoCliente) {
                    return view('dueno.mascotas.index')
                        ->with('admin', $admin)
                        ->with('aptoCliente', $aptoCliente);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
                // ****************************************
            } elseif (session('conjunto_user') != null) {
                // Cuando esta nulo el id_conjunto
                // -------------------------------
                // Validación para identificar el admin del conjunto
                $admin       = User::where('id_rol', 2)->where('id_conjunto', session('conjunto_user'))->first();
                $mascotas    = Mascota::where('id_dueno', Auth::user()->id)->get();
                $aptoCliente = Tipo_unidad::where('id_dueno_apto', Auth::user()->id)
                    ->where('id_conjunto', session('conjunto_user'))
                    ->first();
                // Validación si se ha creado el apto del cliente
                if ($aptoCliente) {
                    return view('dueno.mascotas.index')
                        ->with('admin', $admin)
                        ->with('mascotas', $mascotas)
                        ->with('aptoCliente', $aptoCliente);
                } else {
                    session(['section' => 'home']);
                    return redirect('/dueno')->with('status', 'No se ha registrado ningún apartamento a su nombre');
                }
            }

            // ****************************************
        } elseif ($user->id_rol == 4) {
            return view('celador.mascotas')
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
        return redirect('/mascotas');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $mascotas                 = new Mascota();
            $mascotas->codigo           = $request->codigo;
            $mascotas->nombre         = $request->nombre;
            $mascotas->raza    = $request->raza;
            $mascotas->fecha_nacimiento    = $request->fecha_nacimiento;
            $mascotas->fecha_ingreso    = date('Y-m-d');
            $mascotas->descripcion    = $request->descripcion;
            $mascotas->tipo_id    = $request->tipo_id;
            $mascotas->carta_ingreso_id      = $request->carta_ingreso_id;
            $mascotas->unidad_id    = $request->unidad_id;
            $mascotas->id_conjunto    = session('conjunto');
            // Imagen
            if ($request->file('foto')) {
                $file = time() . '.' . $request->foto->getClientOriginalExtension();
                $request->foto->move(public_path('imgs/private_imgs'), $file);
                // Ruta de la img
                $mascotas->foto           = $file;
            } else {
                $mascotas->foto           = 'default_img.jpg';
            }
            $mascotas->save();

            return array('res' => 1, 'msg' => 'Mascota agregada correctamente.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error al registrar la mascota.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mascota $mascota)
    {
        return $mascota;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mascotas   = Mascota::find($id);
        return $mascotas;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mascota $mascota)
    {
        $mascota->codigo           = $request->codigo;
        $mascota->nombre         = $request->nombre;
        $mascota->raza    = $request->raza;
        $mascota->fecha_nacimiento    = $request->fecha_nacimiento;
        $mascota->descripcion    = $request->descripcion;
        $mascota->tipo_id    = $request->tipo_id;
        // Imagen
        if ($request->file('foto')) {
            $rutaPasada = public_path('imgs/private_imgs/' . $mascota->foto);

            $file = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('imgs/private_imgs'), $file);
            // Ruta de la img
            $mascota->foto           = $file;
            unlink($rutaPasada);
        }
        $mascota->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Consulta el archivo
        $mascotas = Mascota::find($id);
        if ($mascotas->foto != 'default_img.jpg') {
            // Elimina el archivo
            unlink(public_path('imgs/private_imgs/' . $mascotas->foto));
        }
        // Borrando el registro
        Mascota::destroy($id);
    }

    public function inactivar(Mascota $mascota, Request $request)
    {
        $mascota->carta_retiro_id = $request->carta_retiro_id;
        $mascota->estado = 'Inactivo';
        $mascota->fecha_retiro = date('Y-m-d');
        $mascota->save();
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $mascotas = Mascota::where('id_conjunto', session('conjunto'));
        $usuario = Auth::user();
        if ($usuario->id_rol == 2) {
            $mascotas = $mascotas->get();
        } else if ($usuario->id_rol == 4) {
            $mascotas = $mascotas->where('estado', 'Activo')->get();
        }
        return Datatables::of($mascotas)
            ->addColumn('unidad', function ($mascota) {
                return $mascota->unidad->tipo->nombre . ' ' . $mascota->unidad->numero_letra;
            })->addColumn('foto', function ($mascota) {
                return json_encode([$mascota->foto]);
            })->addColumn('tipo', function ($mascota) {
                return $mascota->tipo->tipo;
            })->addColumn('action', function ($mascota) {
                return '<a data-toggle="tooltip" data-placement="top" title="Información de la unidad" class="btn btn-default" onclick="loadData(' . $mascota->unidad_id . ')">
                            <i class="fa fa-search"></i>
                        </a>';
            })->make(true);
    }
}
