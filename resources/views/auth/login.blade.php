<!DOCTYPE html>
<html lang="e">
<head>
    <meta charset="UTF-8">
    <title>Ingreso | Gestion Copropietarios</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/master.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index-styles.css') }}">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <style>
        .ayuda{
            font-size: 55px !important;
            color: #2aafe5;
            margin-top: 12px;
        }

        .div-background-form div h1{
            color: white !important;
            font-size: 65px;
            margin-top: 0;
            font-weight: 900;
        }

        .show_pass{
            color:white;
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

        @media only screen and (max-width: 1000px) {
            .row .col-xs-11 {
                width: 85% !important;
            }
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="first-content-login">
                <div class="after-before-login">
                    <div class="col-lg-11 div-cuadro-dentro">
                        <div class="col-lg-3 col-md-3 col-sm-0 col-xs-0"></div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-8 div-background-form">
                            <div class="row">
                                <div class="col-xs-11">
                                    <h1>Ingreso</h1>
                                </div>
                                <div class="col-xs-1 text-right">
                                    <div class="btn-group">
                                        <i  data-placement="left"
                                            title="Ayuda"
                                            data-toggle="dropdown"
                                            type="button"
                                            aria-expanded="false"
                                            class="fa blue fa-question-circle-o ayuda">
                                        </i>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li>
                                                <a target="_blanck" href="https://youtu.be/JgTPeQ7qJOQ">¿Cómo iniciar sesión?</a>
                                                <a target="_blanck" href="https://youtu.be/j3t-rDq2bK0">¿Cómo recuperar contraseña?</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
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
                            @if(isset($captcha_errors) && count($captcha_errors)  > 0)
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="button">x</span></button>
                                    @foreach($captcha_errors as $er)
                                        <ul>
                                            <li>{{ $er }}</li>
                                        </ul>
                                    @endforeach
                                </div>
                            @endif
                            <form id="form_login" method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="hidden" name="recaptcha" id="recaptcha">
                                <input type="text" value="{{ old('email') }}" placeholder="Correo Electrónico:" name="email" autocomplete="off">
                                <div>
                                <input type="password" placeholder="Contraseña:" id="password" name="password" autocomplete="off">
                                    <i title="Mostrar contraseña" onclick="showPass(this);" class="fa fa-eye show_pass"></i>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <a class="btn btn-link text-blue" href="{{ route('password.request') }}">
                                           {{ "Olvide mi contraseña" }}
                                       </a>
                                    </div>
                                    <div class="col-12 text-left">
                                        <button class="btn-submit" type="submit" class="btn btn-info btn-lg">
                                            <i class="fa fa-send"></i>
                                            Entrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-0 col-xs-0"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/mainCustom.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6Lfnh9MaAAAAAHsQJMxiiOxfPFpfbEtKy7EnocUY"></script>
    <script>
        function showPass(e){
            if(password.type == 'password'){
                $(e).removeClass('fa-eye');
                $(e).addClass('fa-eye-slash');
                $(e).attr('title','Ocultar contraseña');
                password.type = 'text';
            }else{
                $(e).addClass('fa-eye');
                $(e).removeClass('fa-eye-slash');
                $(e).attr('title','Mostrar contraseña');
                password.type = 'password';
            }
        }

        document.querySelector("#form_login").addEventListener("submit", function(event) {
            event.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('6Lfnh9MaAAAAAHsQJMxiiOxfPFpfbEtKy7EnocUY', { action: 'submit' }).then(function(token) {
                    if (token) {
                        document.getElementById('recaptcha').value = token;
                        event.returnValue = true;
                        document.querySelector("#form_login").submit();
                    }
                });
            });
        }, false);

    </script>
</body>
</html>