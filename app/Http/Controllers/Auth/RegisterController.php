<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre_completo' => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:6|confirmed',
            'edad'            => 'required|numeric',
            'genero'          => 'required|string',
            'tipo_cuenta'     => 'nullable',
            'numero_cuenta'   => 'nullable',
            'telefono'        => 'nullable',
            'celular'         => 'required|numeric',
            'id_rol'          => 'required|string|min:1',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'nombre_completo' => $data['nombre_completo'],
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'edad'            => $data['edad'],
            'genero'          => $data['genero'],
            'tipo_cuenta'     => $data['tipo_cuenta'],
            'numero_cuenta'   => Hash::make($data['numero_cuenta']),
            'telefono'        => $data['telefono'],
            'celular'         => $data['celular'],
            'estado'          => $data['estado'],
            'id_rol'          => $data['id_rol'],
        ]);
    }
}
