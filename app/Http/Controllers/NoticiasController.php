<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use App\User;
use Yajra\Datatables\Datatables;
use App\Noticia;
use App\Conjunto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoticiasController extends Controller
{

    function __construct()
    {
        $this->middleware('admin', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'noticias']);
        $usuario = Auth::user();

        if($usuario->id_rol == 2){
            $user         = User::where('id_conjunto', session('conjunto'))->get();
            $conjuntos    = Conjunto::find(session('conjunto'));
            return view('admin.noticias.index')
                ->with('user', $user)
                ->with('conjuntos', $conjuntos);

        }elseif($usuario->id_rol == 4){
            $noticias = Noticia::where('id_conjunto', session('conjunto'))
                    ->take(20)
                    ->orderBy('created_at', 'desc')
                    ->get();

            return view('celador.noticias.index')
                    ->with('noticias',$noticias);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('noticias');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $noticia              = new Noticia();
        $noticia->titulo      = $request->titulo;
        $noticia->descripcion = $request->descripcion;
        // Imagen
        if ($request->hasFile('foto')) {
            $file = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('imgs/private_imgs'), $file);

            // Ruta de la img
            $noticia->foto       = $file;
        } else {
            $noticia->foto           = 'default_newspapers.png';
        }
        // ******************************
        $noticia->id_user     = $request->id_user;
        $noticia->id_conjunto = session('conjunto');

        $correo = new CorreoController();
        $propietarios = User::where([
            ['id_conjunto',session('conjunto')],
            ['id_rol',3]
        ])->get();
        $noticia->save();
        $descripcion = str_limit($noticia->descripcion, 200);
        $contenido = "Se ha agregado una nueva noticia en la plataforma <br>
            <b>Título: </b> {$noticia->titulo} <br>
            {$descripcion} <br>
            para ver la noticia completa puedes pulsar <a href='".url('noticias')."/{$noticia->id}' >AQUÍ</a>";
        $res = $correo->enviarEmail(Conjunto::find(session('conjunto')),$propietarios,'Nueva noticia',$contenido);
        // return [$res];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $noticia     = Noticia::find($id);
        if (Auth::user()->id_rol == 2) {
            return $noticia;
        } elseif (Auth::user()->id_rol == 3) {
            session(['section' => 'home']);
            // Validación para identificar el admin del conjunto
            $admin       = User::where('id_rol', 2)->where('id_conjunto', Auth::user()->id_conjunto)->first();
            // Validador si la noticia existe
            if ($noticia) {
                return view('dueno.noticias.show')
                    ->with('admin', $admin)
                    ->with('noticia', $noticia);
            } else {
                return view('errors.404');
            }
        }elseif(Auth::user()->id_rol == 4){
            return view('celador.noticias.show')
                    ->with('noticia', $noticia);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $noticia = Noticia::find($id);
        return $noticia;
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
        $noticia              = Noticia::find($id);
        $noticia->titulo      = $request->titulo;
        $noticia->descripcion = $request->descripcion;
        // Imagen
        if ($request->hasFile('foto')) {
            $file = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('imgs/private_imgs'), $file);

            // Ruta de la img
            $noticia->foto       = $file;
        }
        // ******************************
        $noticia->id_user     = $request->id_user;
        $noticia->id_conjunto = session('conjunto');
        $noticia->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Noticia::destroy($id);
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $noticias     = Noticia::where('id_conjunto', session('conjunto'))->get();

        return Datatables::of($noticias)
            ->addColumn('descripcion', function ($noticia) {
                return str_limit($noticia->descripcion, 50);
            })->addColumn('nombre_completo', function ($noticia) {
                return $noticia->user->nombre_completo;
            })->addColumn('created_at', function ($noticia) {
                return date('d-m-Y',strtotime($noticia->created_at));
            })->addColumn('action', function ($noticia) {
                return '<a data-toggle="tooltip" data-placement="top" title="Mostrar" onclick="showForm(' . $noticia->id . ')" class="btn btn-default">
                            <i class="fa fa-search"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Editar" onclick="editForm(' . $noticia->id . ')" class="btn btn-default">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="deleteData(' . $noticia->id . ')" class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </a>';
            })->make(true);
    }
}
