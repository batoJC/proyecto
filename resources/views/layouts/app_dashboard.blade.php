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
    {{-- <link href="{{ asset('css/owl.carousel.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/owl.theme.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/master.css') }}" rel="stylesheet">
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ url('/owner') }}" class="site_title">
                            <i class="fa fa-globe"></i> 
                            <span>Usuario Raíz</span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li @if(session('section') == 'home') class="current-page"@endif>
                                    <a href="{{ url('owner') }}">
                                        <i class="fa fa-home"></i> 
                                        Inicio
                                    </a>
                                </li>
                                <li @if(session('section') == 'tipo_conjunto') class="current-page"@endif>
                                    <a href="{{ url('tipo_conjunto') }}">
                                        <i class="fa fa-cog"></i> 
                                        Tipo de Conjunto
                                    </a>
                                </li>
                                <li @if(session('section') == 'conjuntos') class="current-page"@endif>
                                    <a href="{{ url('conjuntos') }}">
                                        <i class="fa fa-university"></i> 
                                        Conjuntos
                                    </a>
                                </li>
                                <li @if(session('section') == 'usuarios') class="current-page"@endif>
                                    <a href="{{ url('usuarios') }}">
                                        <i class="fa fa-user"></i> 
                                        Administradores
                                    </a>
                                </li>
                                <li @if(session('section') == 'tabla_intereses') class="current-page"@endif>
                                    <a href="{{ url('tabla_intereses') }}">
                                        <i class="fa fa-balance-scale"></i> 
                                        Tabla de intereses
                                    </a>
                                </li>
                                <li @if(session('section') == 'contactos') class="current-page"@endif>
                                    <a href="{{ url('contactos') }}">
                                        <i class="fa fa-envelope-open-o"></i> 
                                        Contacto
                                    </a>
                                </li>
                                <li @if(session('section') == 'items') class="current-page"@endif>
                                    <a href="{{ url('items') }}">
                                        <i class="fa fa-sitemap"></i> 
                                        Item
                                    </a>
                                </li>
                                {{-- <li @if(session('section') == 'ingresos_oficina') class="current-page"@endif>
                                    <a href="{{ url('ingresos_oficina') }}">
                                        <i class="fa fa-money"></i> 
                                        Ingresos (Dinero)
                                    </a>
                                </li> --}}
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
            <!-- /top navigation -->
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatable-1.10.12.js') }}"></script>
    <script src="{{ asset('js/datatable_bootstrap.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
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
