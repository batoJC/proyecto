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
    <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/switchery.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
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
            height: 4px;;
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
            height: 90vh;
            overflow: auto;
        }

        .contenido::-webkit-scrollbar {
            width: 8px;
            height: 4px;;
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
    <div id="loading">
        <h3>Procesando...</h3>
        <img src="{{ asset('imgs/loading.gif') }}" alt="">
    </div>

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
                            <i class="fa fa-university"></i>
                            <span>{{ Auth::user()->nombre_completo }}
                            </span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li @if(session('section') == 'home') class="current-page"@endif>
                                    <a href="{{ url('admin') }}">
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
                                <li @if(session('section') == 'quejas_reclamos') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('quejas_reclamos') }}">
                                            <i class="fa fa-commenting-o"></i>
                                            PQR
                                        </a>
                                    @endif
                                </li>
                                <li @if(session('section') == 'novedades') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('novedadesConjunto') }}">
                                            <i class="fa fa-list-alt"></i>
                                            Novedades
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
                                {{-- <li @if(session('section') == 'notas') class="current-page"@endif>
                                    @if(Auth::user()->habeas_data == 'Acepto')
                                        <a href="{{ url('notas') }}">
                                            <i class="fa fa-history"></i>
                                            Historico de Mensajes
                                        </a>
                                    @endif
                                </li> --}}
                                <style>
                                    .font-14{
                                        font-size: 13px !important;
                                    }

                                    .font-12{
                                        font-size: 12px !important;
                                    }


                                </style>
                                <li>
                                    <a class="font-14"><i class="fa fa-home"></i> Módulo Administrativo<span class="fa fa-chevron-down cambio"></span></a>
                                    <ul class="nav child_menu">

                                        <li @if(session('section') == 'divisiones') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('divisiones') }}">
                                                    <i class="fa fa-object-ungroup"></i>
                                                    Divisiones
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'tipo_unidad') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('tipo_unidad') }}">
                                                    <i class="fa fa-building-o"></i>
                                                    Tipo de Unidad
                                                </a>
                                            @endif
                                        </li>

                                        <li @if(session('section') == 'usuarios') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('usuarios') }}">
                                                    <i class="fa fa-user"></i>
                                                    Usuarios
                                                </a>
                                            @endif
                                        </li>

                                        <li @if(session('section') == 'unidades') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('unidades') }}">
                                                    <i class="fa fa-braille"></i>
                                                    Administrar Unidad
                                                </a>
                                            @endif
                                        </li>

                                        <li @if(session('section') == 'cartas') class="current-page"@endif>
                                                @if(Auth::user()->habeas_data == 'Acepto')
                                                    <a href="{{ url('cartas') }}">
                                                        <i class="fa fa-exchange"></i>
                                                        Ingreso y retiro de bienes
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
                                                        Empleados Conjunto
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

                                        <li @if(session('section') == 'proveedores') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('proveedores') }}">
                                                    <i class="fa fa-handshake-o"></i>
                                                    Proveedores
                                                </a>
                                            @endif
                                        </li>

                                        <li @if(session('section') == 'mantenimientos') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('mantenimientos') }}">
                                                    <i class="fa fa-bell"></i>
                                                    Mantenimientos
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

                                        <li @if(session('section') == 'lista_reservas') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('listaReservas') }}">
                                                    <i class="fa fa-calendar"></i>
                                                    Reservas zonas sociales
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'inventario') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('inventario') }}">
                                                    <i class="fa fa-barcode"></i>
                                                    Inventario General
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </li>


                                <!-- FINANZAS -->
                                <li>
                                    <a><i class="fa fa-calculator"></i> Módulo Financiero <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">

                                        <li @if(session('section') == 'ejecucion_pre_total') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('ejecucion_pre_total') }}">
                                                    <i class="fa fa-plus-square-o"></i>
                                                    Presupuesto Total
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'tipo_ejecucion_pre') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('tipo_ejecucion_pre') }}">
                                                    <i class="fa fa-square-o"></i>
                                                    Tipos de Ejecución Presupuestal
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'ejecucion_pre_individual') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('ejecucion_pre_individual') }}">
                                                    <i class="fa fa-minus-square-o"></i>
                                                    Ejecución Presupuestal Individual
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'consecutivos') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('consecutivos') }}">
                                                    <i class="fa fa-sort-numeric-asc"></i>
                                                    Consecutivos
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'cuota_admon') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('cuota_admon') }}">
                                                    <i class="fa fa-bookmark"></i>
                                                    Definicion de Cuotas Administrativas
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'cuota_ext_ord') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('cuota_ext_ord') }}">
                                                    <i class="fa fa-usd"></i>
                                                    Cuota Extraordinaria
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'otros_cobros') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('otros_cobros') }}">
                                                    <i class="fa fa-eur"></i>
                                                    Otros Cobros
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'multas') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('multas') }}">
                                                    <i class="fa fa-legal"></i>
                                                    Multas
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'cuentas') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a class="font-14"><i class="fa fa-calculator"></i> Cuentas de cobro <span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li>
                                                        <a href="{{ url('generarCuentaCobro') }}">Generar</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('listarCuentaCobro') }}">listar</a>
                                                    </li>
                                                </ul>
                                            @endif
                                        </li>
                                        {{-- <li @if(session('section') == 'carteras') class="current-page"@endif>
                                            <a><i class="fa fa-home"></i> Carteras <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li>
                                                    <a href="carteraUnidad">Cartera de Unidad</a>
                                                </li>
                                                <li>
                                                    <a href="exportarCartera">Exportar por Consecutivo</a>
                                                </li>
                                            </ul>
                                        </li> --}}
                                        <li @if(session('section') == 'carteras') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('carteras') }}">
                                                    <i class="fa fa-book"></i>
                                                    Carteras
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'saldosFavor') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('saldosFavor') }}">
                                                    <i class="fa fa-usd"></i>
                                                    Saldos a favor
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'paz_salvo') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('paz_salvo') }}">
                                                    <i class="fa fa-sort-alpha-asc"></i>
                                                    Paz y saldo
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'en_mora') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('en_mora') }}">
                                                    <i class="fa fa-sort-alpha-asc"></i>
                                                    En mora
                                                </a>
                                            @endif
                                        </li>

                                        {{-- <li @if(session('section') == 'gestion_cobros') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('gestion_cobros') }}">
                                                    <i class="fa fa-shopping-bag"></i>
                                                    Gestión de Recaudo
                                                </a>
                                            @endif
                                        </li> --}}
                                        {{-- <li @if(session('section') == 'recaudos') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('recaudos') }}">
                                                    <i class="fa fa-shopping-bag"></i>
                                                    Gestión de Recaudo
                                                </a>
                                            @endif
                                        </li> --}}
                                        <li @if(session('section') == 'recaudos') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a class="font-12"><i class="fa fa-shopping-bag"></i> Gestión de Recaudo <span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li>
                                                        <a href="{{ url('recaudos') }}">Agregar</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('listarRecaudo') }}">listar</a>
                                                    </li>
                                                </ul>
                                            @endif
                                        </li>

                                        {{-- <li @if(session('section') == 'conceptos_retencion') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('conceptos_retencion') }}">
                                                    <i class="fa fa-area-chart"></i>
                                                    Conceptos Retención
                                                </a>
                                            @endif
                                        </li> --}}
                                        <li @if(session('section') == 'egresos') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('egresos') }}">
                                                    <i class="fa fa-credit-card"></i>
                                                    Egresos
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'flujo_efectivo') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('flujo_efectivo') }}">
                                                    <i class="fa fa-pie-chart"></i>
                                                    Flujo Efectivo
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'descuentos') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('descuentos') }}">
                                                    <i class="fa fa-balance-scale"></i>
                                                    Descuentos
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'saldos_iniciales') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('saldos_iniciales') }}">
                                                    <i class="fa fa-balance-scale"></i>
                                                    Saldos iniciales
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'tabla_intereses') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('tabla_intereses') }}">
                                                    <i class="fa fa-balance-scale"></i>
                                                    Tasas de Interés
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                {{-- Documentos --}}
                                @if(Auth::user()->habeas_data == 'Acepto')
                                    <li @if(session('section') == 'documentos') class="current-page"@endif>
                                        <a href="{{ url('documentos') }}">
                                            <i class="fa fa-file-text-o"></i> Módulo Documental
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->habeas_data == 'Acepto')
                                <li @if(session('section') == 'exportar') class="current-page"@endif>
                                    <a href="{{ url('exportar') }}">
                                        <i class="fa fa-download"></i> Exportar
                                    </a>
                                </li>
                            @endif
                                {{-- <li>
                                    <a><i class="fa fa-file-text-o"></i> Modulo Documental <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">  --}}
                                        {{-- <li @if(session('section') == 'reglamento') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('reglamento') }}">
                                                    <i class="fa fa-gavel"></i>
                                                    Reglamento del Conjunto
                                                </a>
                                            @endif
                                        </li>
                                        <li @if(session('section') == 'actas') class="current-page"@endif>
                                            @if(Auth::user()->habeas_data == 'Acepto')
                                                <a href="{{ url('actas') }}">
                                                    <i class="fa fa-folder-open-o"></i>
                                                    Actas del Consejo
                                                </a>
                                            @endif
                                        </li> --}}
                                    {{-- </ul>
                                </li> --}}

                                </ul>

                            </li>

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
                            {{-- <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-user"></i>
                                    &nbsp;
                                    {{ Auth::user()->nombre_completo }}
                                </a>
                            </li> --}}
                            <li>
                                <a href="#" style="color: #ff0000e3 !important" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-university"></i>
                                    &nbsp;
                                    {{ $conjuntos->nombre }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            {{-- Cuerpo del dashboard --}}
            {{-- ******************** --}}
            <div class="right_col" role="main">
                <div class="row contenido">
                    <div class="col-lx-12">
                        {{-- Boton mensajes con el dueño --}}
                        {{-- <button class="btn btn-default" id="btn-message">
                            <i class="fa fa-comments-o fa-2x"></i>
                        </button>
                        <div class="col-lg-2" id="div-message">
                            <button class="btn btn-default pull-right" id="btn-message-close">
                                <i class="fa fa-times"></i>
                            </button>
                            <h4>Mensajeria con los dueños</h4>
                            <form action="{{ url('notas') }}" method="post">
                                @csrf
                                <select name="id_receptor" class="form-control-notes field-5-chat">
                                    <option value="default">Seleccione el usuario...</option>
                                    @foreach($user as $usr)
                                        <option value="{{ $usr->id }}">{{ $usr->nombre_completo }}</option>
                                    @endforeach
                                </select>
                                <textarea name="mensaje" class="form-control-notes field-1-chat" cols="30" rows="5" placeholder="Su mensaje..."></textarea>
                                <button type="button" class="btn btn-success btn-block" id="send_form_chat">
                                    <i class="fa fa-send"></i>
                                    &nbsp; Enviar
                                </button>
                            </form>
                        </div> --}}
                            {{-- Gif de carga --}}
                            <div id="gif-loading" class="text-center">
                                <img src="{{ asset('imgs/gestioncopropietarios_color.gif') }}" alt="Gif">
                            </div>
                            {{-- Contenido extendido --}}
                            @yield('content')
                    </div>
                </div>
            </div>
            {{-- fin del cuerpo --}}
            {{-- **************************** --}}
        </div>
    </div>
    {{-- <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script> --}}
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
    <script src="{{ asset('js/switchery.min.js') }}"></script>
    {{-- <script src="../vendors/switchery/dist/switchery.min.js"></script> --}}
    @yield('calendar')
    @yield('ajax_crud')
</body>
</html>
