<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gestion Copropietarios</title>

    <link rel="icon" type="image/png" href="{{ asset('imgs/favicon.png') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatable_css.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/master.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <style>
            html,body{
                overflow: hidden;
                height: 100%;
            }

            .main_container{
                height: 100%;
            }

            .left_col{
                height: 100%;
            }

            .scroll-view{
                overflow: auto;
                overflow-x: hidden;
            }

            .scroll-view::-webkit-scrollbar {
                width: 8px;
            }
            .scroll-view::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
                border-radius: 8px;
            }
            .scroll-view::-webkit-scrollbar-thumb {
                border-radius: 8px;
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
            }

            .right_col{
                height: 90vh;
                overflow-x: hidden;
            }


            #cerrar_img{
                font-size: 28px !important;
            }

            .contenido {
                height: 100vh;
                overflow: auto;
            }

            .contenido::-webkit-scrollbar {
                width: 8px;
            }

            .contenido::-webkit-scrollbar-thumb {
                background: #1ABB9C;
            }

            .foto{
                height: 50px;
                cursor: pointer;
            }

            th,td{
                text-align: center;
                font-size: 16px;
            }

            .table{
                width: 100% !important;
            }

        </style>

</head>

<body class="nav-md">
    <div id="show_image">
        <span id="cerrar_img" >X</span>
        <img src="" alt="">
    </div>

    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ url('/') }}" class="site_title">
                            <i class="fa fa-lock"></i>
                            <span>Portero</span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li @if(session('section') == 'home') class="current-page"@endif>
                                    <a href="{{ url('dueno') }}">
                                        <i class="fa fa-home"></i>
                                        Inicio
                                    </a>
                                </li>

                                <li @if(session('section') == 'noticias') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('noticias') }}">
                                            <i class="fa fa-newspaper-o"></i>
                                            Noticias
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'zonas_comunes') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('zonas_comunes') }}">
                                            <i class="fa fa-cube"></i>
                                            Zonas sociales
                                        </a>
                                    @endif
                                </li>
                                <li @if(session('section') == 'unidades') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('unidades_porteria') }}">
                                            <i class="fa fa-braille"></i>
                                            Unidades
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'residentes') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('residentes') }}">
                                            <i class="fa fa-users"></i>
                                            Residentes
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'mascotas') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('mascotas') }}">
                                            <i class="fa fa-paw"></i>
                                            Mascotas
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'vehiculos') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('vehiculos') }}">
                                            <i class="fa fa-automobile"></i>
                                            Vehículos
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'empleados') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('empleados') }}">
                                            <i class="fa fa-users"></i>
                                            Empleados unidad
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'empleados_conjunto') class="current-page"@endif>
                                        @if(Auth::user()->habeas_data == 'Acepto')
                                            <a href="{{ url('empleados_conjunto') }}">
                                                <i class="fa fa-users"></i>
                                                Empleados conjunto
                                            </a>
                                        @endif
                                    </li>

                                <li @if(session('section') == 'visitantes') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('visitantes') }}">
                                            <i class="fa fa-users"></i>
                                            Visitantes
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'documentos') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('documentos') }}">
                                            <i class="fa fa-file-text-o"></i>
                                            Documentos conjunto
                                        </a>
                                    @endif
                                </li>

                                <li @if(session('section') == 'evidencias') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('evidencias') }}">
                                            <i class="fa fa-shield"></i>
                                            Evidencias
                                        </a>
                                    @endif
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Cerrar Sesion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-user"></i>
                                    &nbsp;
                                    {{ Auth::user()->nombre_completo }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            {{-- fin del cuerpo --}}
            {{-- **************************** --}}
            <div class="right_col" role="main">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatable-1.10.12.js') }}"></script>
    <script src="{{ asset('js/datatable_bootstrap.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/all.js') }}"></script>
    <script src="{{ asset('js/mainCustom.js') }}"></script>
    <script src="{{ asset('js/custom_validator.js') }}"></script>
    <script src="{{ asset('build/js/custom.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    @yield('ajax_crud')
</body>
</html>
