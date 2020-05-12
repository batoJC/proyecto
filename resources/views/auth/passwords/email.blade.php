<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ingreso | Gestion Copropietarios</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-styles.css') }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="first-content-login">
                <div class="after-before-login">
                    <div class="col-lg-11 div-cuadro-dentro">
                        <div class="col-lg-6 col-md-6 div-background-login">
                            {{-- División del recuadro --}}
                            <div class="after-before-login-inside">
                                <div class="col-lg-8 login-logo"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-10 div-background-form">
                            <br><br>
                            <h1>Restaurar Contraseña</h1>
                            {{-- División de los errores --}}
                            @if(count($errors)  > 0)
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="button">x</span></button>
                                    @foreach($errors->all() as $er)
                                        <ul>
                                            <li>{{ $er }}</li>
                                        </ul>
                                    @endforeach
                                </div>
                            @endif
                            {{-- Division de alertas positivas --}}
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <input type="text" value="{{ old('email') }}" placeholder="Correo electrónico: " name="email" autocomplete="off">
                                <button type="submit" class="btn btn-info btn-lg">
                                    <i class="fa fa-send"></i>
                                    Enviar enlace de Restauracion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
