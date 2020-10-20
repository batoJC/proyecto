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
                            <div class="col-11 col-md-11"></div>
                            <div class="col-1 col md-1 text-right">
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
                            <h1>Ingreso</h1>
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
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="text" value="{{ old('email') }}" placeholder="Correo Electrónico:" name="email" autocomplete="off">
                                <div>
                                    <input type="password" placeholder="Contraseña:" id="password" name="password" autocomplete="off">
                                    <i title="Mostrar contraseña" onclick="showPass(this);" class="fa fa-eye show_pass"></i>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                           {{ "Olvide mi contraseña" }}
                                       </a>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-info btn-lg">
                                            <i class="fa fa-send"></i>
                                            Enviar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/mainCustom.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
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
    </script>
</body>
</html>