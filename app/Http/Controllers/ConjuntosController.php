<?php

namespace App\Http\Controllers;

use App\Conjunto;
use App\Reglamento;
use App\Tipo_Conjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\Datatables\Datatables;

class ConjuntosController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'conjuntos']);
        $tipo_conjunto = Tipo_Conjunto::all();
        return view('owner.conjuntos.index')
            ->with('tipo_conjunto', $tipo_conjunto);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('conjuntos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $conjunto                       = new Conjunto();
        $conjunto->nit                  = $request->nit;
        $conjunto->nombre               = $request->nombre;
        $conjunto->correo               = $request->correo;
        $conjunto->ciudad               = $request->ciudad;
        $conjunto->direccion            = $request->direccion;
        $conjunto->barrio               = $request->barrio;
        $conjunto->tel_cel              = $request->tel_cel;
        $conjunto->id_tipo_propiedad    = $request->id_tipo_propiedad;
        $conjunto->save();

        //agregar reglamento
        $reglamento = new Reglamento();
        $reglamento->descripcion = 'Políticas de uso de datos del conjunto.';
        $name = time().'.pdf';
        $origen = asset('docs/tratamiento_de_datos.pdf');
        $destino = asset('reglamentos').'\\'.$name;
        return [$origen,$destino];
        copy($origen, $destino);
        $reglamento->archivo = $name;
        $reglamento->conjunto_id = $conjunto->id;
        $reglamento->save();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $conjunto = Conjunto::find($id);
        return $conjunto;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $conjunto = Conjunto::find($id);
        return $conjunto;
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
        $conjunto                       = Conjunto::find($id);
        $conjunto->nit                  = $request->nit;
        $conjunto->nombre               = $request->nombre;
        $conjunto->correo               = $request->correo;
        $conjunto->ciudad               = $request->ciudad;
        $conjunto->direccion            = $request->direccion;
        $conjunto->barrio               = $request->barrio;
        $conjunto->tel_cel              = $request->tel_cel;
        $conjunto->id_tipo_propiedad    = $request->id_tipo_propiedad;
        $conjunto->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Conjunto::destroy($id);
    }

    // Metodo personalizado para guardar el logo
    // *****************************************

    public function LogoConjuntoStore(Request $request)
    {

        $conjunto = Conjunto::find(session('conjunto'));
        // Imagen
        if ($request->hasFile('foto')) {
            $file = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('/imgs/logos_conjuntos'), $file);

            // Ruta de la img en la BD
            $conjunto->logo = $file;
            $conjunto->save();
        }
        return public_path('imgs/logos_conjuntos');
    }

    // Metodo personalizado para eliminar la Imagen
    // ********************************************

    public function LogoConjuntoDelete(Request $request)
    {

        $conjunto = Conjunto::find($request->id_conjunto);
        // Eliminar imagen
        @unlink(public_path('imgs/logos_conjuntos/' . $conjunto->logo));
        $conjunto->logo = null;
        $conjunto->save();
    }

    public function passwordConjunto()
    {
        $conjunto = Conjunto::find(session('conjunto'));
        return view('admin.changePassword')->with('conjuntos', $conjunto);
    }

    public function changePassword(Request $request)
    {
        try {
            $conjunto = Conjunto::find(session('conjunto'));
            if ($request->password == $request->confirm_password) {
                $conjunto->password = Crypt::encrypt($request->password);
                $conjunto->save();
                return array('res' => 1, 'msg' => 'Contraseña asignada correctamente.');
            }
            return array('res' => 0, 'msg' => 'No se logró asignar la contraseña, por que estas no coinciden.');
        } catch (\Throwable $th) {
            return array('res' => 0, 'msg' => 'Ocurrió un error en el servidor, por favor intentelo más tarde.');
        }
    }

    public function sendEmailPrueba(Request $request)
    {
        $aux = new CorreoController();
        $res = $aux->enviarEmailPrueba($request->correo);
        if ($res) {
            return array('res' => 1, 'msg' => 'Correo enviado correctamente,revise su correo electrónico');
        }
        return array('res' => 0, 'msg' => 'No se logró enviar el correo , compruebe que la configuración de correo sea la correcta.');
    }


    // Custom method for get search
    // ****************************
    public function datatables()
    {
        $conjuntos = Conjunto::all();

        return DataTables::of($conjuntos)
            ->addColumn('tipo_conjunto', function ($conjunto) {
                return $conjunto->tipo_conjunto->tipo;
            })->addColumn('action', function ($conjunto) {
                return '<a wsd onclick="editForm(' . $conjunto->id . ')" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData(' . $conjunto->id . ')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
