<?php

namespace App\Http\Controllers;

use Mail;
use App\Contacto;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['section' => 'contactos']);
        return view('owner.contactos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/contacto');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contacto          = new Contacto();
        $contacto->correo  = $request->correo;
        $contacto->mensaje = $request->mensaje;
        $contacto->save();

        // Envio de correo que alerta la comunicación
        // ******************************************

        $data = array(
            'name' => 'Gestion Copropietarios',
        );

        //TODO: revisar este envio
        Mail::send('emails.contacto', $data, function ($message) {
            $message->from('gestioncopropietario@gmail.com', 'Gestion Copropietarios');
            $message->to('agrupacion2007@gmail.com')->subject('¡Un Nuevo Contacto!');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contacto = Contacto::find($id);
        return $contacto;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect('/contacto');
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
        return redirect('/contacto');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contacto = Contacto::find($id);
        $contacto->delete();
        return 1;
    }


    // para listar por datatables
    // ****************************
    public function datatables()
    {

        $contacto = Contacto::all();


        return Datatables::of($contacto)
            ->addColumn('action', function ($contacto) {
                return '<a data-toggle="tooltip" data-placement="top" title="Mostrar" onclick="showForm(' . $contacto->id . ')" class="btn btn-default">
                    <i class="fa fa-search"></i>
                </a>
                <a data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="functionDelete(' . $contacto->id . ')" class="btn btn-default">
                    <i class="fa fa-trash"></i>
                </a>';
            })->make(true);
    }
}
