<?php

namespace App\Http\Controllers;

use Mail;
use App\Contacto;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $request->nombre = trim($request->nombre, " \n\r\t\v\0");
        $request->email = trim($request->email, " \n\r\t\v\0");
        $request->mensaje = trim($request->mensaje, " \n\r\t\v\0");

        if ($request->nombre == "") {
            return ["res" => 0, "msg" => "Debes de ingresar un nombre para saber como llamarte cuando nos comuniquemos contigo."];
        }

        if ($request->email == "") {
            return ["res" => 0, "msg" => "Hola " . $request->nombre . ", debes de ingresar un correo para poder contactarnos contigo."];
        }

        if ($request->mensaje == "") {
            return ["res" => 0, "msg" => "Hola " . $request->nombre . ", debes de ingresar un mensaje para saber como podemos ayudarte."];
        }

        try {

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $remoteip = $_SERVER['REMOTE_ADDR'];
            $data = [
                'secret' => config('services.recaptcha.secret'),
                'response' => $request->get('recaptcha'),
                'remoteip' => $remoteip
            ];
            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                ]
            ];
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $resultJson = json_decode($result);
            if ($resultJson->success != true) {
                Log::channel('slack')->warning("Formulario de contacto: No se pudo conectar con google para validar captcha.
                \nEmail: {$request->email}\nNombre: {$request->nombre}");
                return ["res" => 0, "msg" => $request->nombre . ", nuestra conexión con google a fallado por favor vuelve a intentar."];
            }
            if ($resultJson->score < 0.7) {
                Log::channel('slack')->warning("Formulario de contacto: El captcha no pasó el score necesario para que sea válido.
                \nEmail: {$request->email}\nNombre: {$request->nombre}");
                return ["res" => 0, "msg" => $request->nombre . ", detectamos que tu petición no es válida según google, si sigues teniendo problemas escribemos a nuestro correo de contacto."];
            }



            $contacto          = new Contacto();
            $contacto->nombre  = $request->nombre;
            $contacto->email  = $request->email;
            $contacto->mensaje = $request->mensaje;
            $contacto->save();

            // Envio de correo que alerta la comunicación
            // ******************************************
            // $data = array(
            //     'name' => 'Gestion Copropietarios',
            // );
            //TODO: revisar este envio o hacer un envio a slack
            // Mail::send('emails.contacto', $data, function ($message) {
            //     $message->from('gestioncopropietario@gmail.com', 'Gestion Copropietarios');
            //     $message->to('juacagiri@gmail.com')->subject('¡Un Nuevo Contacto!');
            // });

            return ["res" => 1, "msg" => $request->nombre . ", hemos recibido tu mensaje y nos pondremos en contacto contigo lo más rápido posible."];
        } catch (\Throwable $th) {
            Log::channel('slack')->critical("Formulario de contacto: Error inesperado.
                \nEmail: {$request->email}\nNombre: {$request->nombre} \n Error: {$th->getMessage()}");
            return ["res" => 0, "msg" => "Hola " . $request->nombre . ", lo sentimos ocurrió un problema al registrar tu mensaje."];
        }
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
