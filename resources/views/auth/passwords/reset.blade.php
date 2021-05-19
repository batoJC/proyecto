<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ingreso | Gestion Copropietario</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-styles.css') }}">
    <style>
        .div-background-form h1{
            color: white !important;
            font-size: 55px;
            margin-top: 0;
            font-weight: 900;
        }

        input{
            color:white !important;
            border-bottom: 2px solid white !important;
            font-weight: bold;
        }

        input::placeholder{
            color:white !important;
        }

        .grecaptcha-badge{
            z-index: 10000;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="first-content-login">
                <div class="after-before-login">
                    <div class="col-lg-11 div-cuadro-dentro">
                        <div class="col-lg-6 col-md-6 col-sm-10 div-background-form">
                            <h1>Cambio de Contraseña</h1>
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
                            <form id="form_reset_password" method="POST" action="{{ route('password.request') }}">
                                @csrf
                                <input type="hidden" name="recaptcha" id="recaptcha">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="text" value="{{ old('email') }}" placeholder="Correo Electrónico:" name="email" autocomplete="off">
                                <input type="password" placeholder="Nueva contraseña:" name="password" autocomplete="off">
                                <input type="password" placeholder="Confirme la nueva contraseña:" name="password_confirmation" autocomplete="off">
                                <button type="submit" class="btn btn-info btn-lg btn-submit">
                                    <i class="fa fa-send"></i>
                                    Restablecer o cambiar contraseña
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
    <script src="https://www.google.com/recaptcha/api.js?render=6Lfnh9MaAAAAAHsQJMxiiOxfPFpfbEtKy7EnocUY"></script>
    <script>
         document.querySelector("#form_reset_password").addEventListener("submit", function(event) {
            event.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('6Lfnh9MaAAAAAHsQJMxiiOxfPFpfbEtKy7EnocUY', { action: 'submit' }).then(function(token) {
                    if (token) {
                        document.getElementById('recaptcha').value = token;
                        event.returnValue = true;
                        document.querySelector("#form_reset_password").submit();
                    }
                });
            });
        }, false);
    </script>
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
                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
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
