{{-- {{ dd(Auth::user()->id_rol) }} --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Index</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('imgs/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
</head>
{{-- Desactivar los f del teclado y el click derecho --}}
{{-- <body oncontextmenu='return false' onkeydown='return false'> --}}
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="first-content container-fluid" id="home">
                <div class="row">
                    <nav class="navbar navbar-default" role="navigation" id="nav-bar">
                        <!-- Botones para la barra movil -->
                        <!-- *************************** -->
                        <button type="button" class="navbar-toggle btn-responsive-hamburguesa" id="btn-hamburguesa">
                            <i class="fa fa-bars"></i>
                        </button>
                        <button type="button" class="navbar-toggle btn-responsive-hamburguesa" id="btn-hamburguesa-close">
                            <i class="fa fa-times"></i>
                        </button>
                        <!-- Barra movil opciones menu -->
                        <!-- ************************* -->
                        <div id="div-hamburguesa-movil-opciones">
                            <ul>
                                <li class="active">
                                    <a href="#home" class="options-navbar-responsive-active anchor">Inicio</a>
                                </li>
                                <li>
                                    <a href="#quienes_somos" class="options-navbar-responsive anchor">¿Quienes somos?</a>
                                </li>
                                <li>
                                    <a href="#servicios" class="options-navbar-responsive anchor">Servicios</a>
                                </li>
                                <li>
                                    <a href="#equipo" class="options-navbar-responsive anchor">Equipo</a>
                                </li>
                                <li>
                                    <a href="#footer" class="options-navbar-responsive anchor">Contacto</a>
                                </li>
                                {{-- <li>
                                    <a href="{{ url('encomientas') }}" class="options-navbar-responsive anchor">Encomientas</a>
                                </li> --}}
                                <li>
                                    @if (Route::has('login'))
                                        @auth
                                             @if(Auth::user()->id_rol == 1)
                                                <a href="{{ url('/owner') }}" class="options-navbar-responsive">
                                                    Panel Administr..
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @elseif(Auth::user()->id_rol == 2)
                                                <a href="{{ url('/admin') }}" class="options-navbar-responsive anchor">
                                                    Panel Administr..
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @elseif(Auth::user()->id_rol == 3)
                                                <a href="{{ url('/dueno') }}" class="options-navbar-responsive anchor">
                                                    Panel Administr..
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @elseif(Auth::user()->id_rol == 4)
                                                <a href="{{ url('/celador') }}" class="options-navbar-responsive anchor">
                                                    Panel Administr..
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @elseif(Auth::user()->id_rol == 6)
                                                <a href="{{ url('/proveedor') }}" class="options-navbar-responsive anchor">
                                                    Panel Administr..
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @else
                                                <a href="" class="options-navbar-responsive anchor">
                                                    Sin rol
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @endif  
                                        @else
                                            <a href="{{ url('login') }}" class="options-navbar-responsive anchor">Ingreso</a>
                                        @endauth
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <!-- Menu hamburguesa opciones redes sociales -->
                        <!-- **************************************** -->
                        <button type="button" class="navbar-toggle btn-responsive-hamburguesa" id="btn-social">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <a href="#" id="main_logo_responsive"></a>
                        <button type="button" class="navbar-toggle btn-responsive-hamburguesa" id="btn-social-close">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <!-- Barra movil opciones menu -->
                        <!-- ************************* -->
                        <div id="div-hamburguesa-redes" class="col-sm-4 col-xs-12">
                            <ul>
                                <li>
                                    <i class="fa fa-phone"></i>
                                    +57 3216548787
                                </li>
                                <li>
                                    <i class="fa fa-map-marker"></i>
                                    &nbsp; Calle 10 # 20 - 25
                                    <br>
                                    &nbsp; &nbsp;&nbsp; Manizales - Caldas
                                </li>
                                <li>
                                    <i class="fa fa-envelope"></i>
                                    &nbsp;  gestionamos@gmail.com
                                </li>
                            </ul>
                        </div>
                        <!-- Barra escritorio hasta la resolucion 900px width-->
                        <!-- *********************************************** -->
                        <div class="col-lg-3 col-md-2 text-center div-hamburguesa-desktop no-padding">
                            <a href="#" id="main_logo"></a>
                        </div>
                        <div class="col-lg-6 col-md-8 div-hamburguesa-desktop no-padding text-center">
                            <a href="#" class="options-navbar">Inicio</a>
                            <a href="#quienes_somos" class="options-navbar anchor">¿Quienes somos?</a>
                            <a href="#servicios" class="options-navbar anchor">Servicios</a>
                            <a href="#equipo" class="options-navbar">Equipo</a>
                            {{-- <a href="{{ url('encomientas') }}" class="options-navbar">Encomientas</a> --}}
                            <a href="#footer" class="options-navbar">Contacto</a>
                            @if (Route::has('login'))
                                @auth
                                     @if(Auth::user()->id_rol == 1)
                                        <a href="{{ url('/owner') }}" class="options-navbar">
                                            Panel Administrativo &nbsp;
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    @elseif(Auth::user()->id_rol == 2)
                                        <a href="{{ url('/admin') }}" class="options-navbar">
                                            Panel Administrativo &nbsp;
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    @elseif(Auth::user()->id_rol == 3)
                                        <a href="{{ url('/dueno') }}" class="options-navbar">
                                            Panel Administrativo &nbsp;
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    @elseif(Auth::user()->id_rol == 4)
                                        <a href="{{ url('/home') }}" class="options-navbar">
                                            Panel Administr..
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    @elseif(Auth::user()->id_rol == 6)
                                        <a href="{{ url('/proveedor') }}" class="options-navbar">
                                            Panel Administrativo &nbsp;
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    @else
                                        <a href="" class="options-navbar">
                                            Sin rol
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    @endif  
                                @else
                                    <a href="{{ url('login') }}" class="options-navbar">Ingreso</a>
                                @endauth
                            @endif
                        </div>
                        <div class="col-lg-3 col-md-2 text-center div-hamburguesa-desktop no-padding">
                            <a href="#" class="redes-sociales">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="#" class="redes-sociales">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a href="#" class="redes-sociales">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                    </nav>
                    <div class="after-before">
                        <div class="textos-centro">
                            <h5 id="scroll-position-1">¿Qué esperas para conocernos?</h5>
                            <h2>
                                ¡Hola! Somos 
                                <span>
                                    Gestión Copropietarios
                                </span>
                            </h2>
                            <br>
                            <button class="btn-gestion">
                                Read More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="second-content" id="quienes_somos">
                <div class="row reset-styles">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 quienes-section-img"></div>
                    <br>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 quienes-section-textos">
                        <h2 class="text-center">
                            <span class="quienes">
                                &nbsp;¿Quienes
                            </span>
                            <span class="somos">
                                Somos?
                            </span>
                        </h2>
                        <br>
                        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum quas sit autem corporis in impedit repudiandae esse dignissimos. Distinctio nihil dignissimos magnam quibusdam incidunt ullam quaerat praesentium ratione cumque iure. Veritatis sit nostrum ex aut id, tempora magni recusandae soluta adipisci nesciunt consequatur, est, enim error impedit eum sequi. Non commodi necessitatibus quod aliquam tempora rem vero voluptatibus perferendis eligendi fugit.</p>
                        <br>
                        <div class="col-lg-6 col-md-8 col-sm-8 ">
                            <button class="btn-gestion btn-block">
                                Read More
                            </button>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="third-content">
                <div class="row reset-styles">
                    <!-- Galería de iconos -->
                    <!-- ***************** -->
                    <div class="owl-one owl-carousel brands-icons">
                        <div class="item">
                            <div class="conjunto-logo logo-1"></div>
                        </div>
                        <div class="item">
                            <div class="conjunto-logo logo-2"></div>
                        </div>
                        <div class="item">
                            <div class="conjunto-logo logo-3"></div>
                        </div>
                        <div class="item">
                            <div class="conjunto-logo logo-4"></div>
                        </div>
                        <div class="item">
                            <div class="conjunto-logo logo-5"></div>
                        </div>
                    </div>
                    <!-- Galería de imagenes -->
                    <!-- ******************* -->
                    <div class="owl-two owl-carousel brands-imgs">
                        <div class="item">
                            <div class="conjunto-img img-1"></div>
                        </div>
                        <div class="item">
                            <div class="conjunto-img img-2"></div>
                        </div>
                        <div class="item">
                            <div class="conjunto-img img-3"></div>
                        </div>
                        <div class="item">
                            <div class="conjunto-img img-4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fourth-content" id="servicios">
                <div class="row reset-styles">
                    <div class="col-lg-12 text-center">
                        <h2 class="our-services text-center">
                            <span class="nuestros">
                                &nbsp; Nuestros
                            </span>
                            <span class="servicios">
                                Servicios
                            </span>
                            <span class="border-bottom"></span>
                        </h2>
                    </div>
                    <div class="col-lg-12 dad-services-content">
                        <!-- Hijo 1 -->
                        <div class="col-lg-4 col-md-4 col-sm-4 child-services-content">
                            <!-- Imagen de fonto - icon -->
                            <div class="col-lg-4 grandchild-content grandchild-1"></div>
                            <!-- titulo y parrafo -->
                            <div class="col-lg-8 grandchild-content">
                                <h4 class="text-center">Asesorias Legales</h4>
                                <p class="text-justify">
                                    ¿Tienes dudas de algún tema legal? ¡Claro que sí, nosotros podemos ayudarte!
                                    Cualquier tipo de inconveniente legal te aclaramos tus dudas.
                                </p>
                            </div>
                        </div>
                        <!-- Hijo 2 -->
                        <div class="col-lg-4 col-md-4 col-sm-4 child-services-content">
                            <!-- Imagen de fonto - icon -->
                            <div class="col-lg-4 grandchild-content grandchild-2"></div>
                            <!-- titulo y parrafo -->
                            <div class="col-lg-8 grandchild-content">
                                <h4 class="text-center">Administra tu Propiedad</h4>
                                <p class="text-justify">
                                    Organiza, Programa, Agenda todo lo que incumbe con tu propiedad de manera ágil, rápida y sencilla 
                                </p>
                            </div>
                        </div>
                        <!-- Hijo 3 -->
                        <div class="col-lg-4 col-md-4 col-sm-4 child-services-content">
                            <!-- Imagen de fonto - icon -->
                            <div class="col-lg-4 grandchild-content grandchild-3"></div>
                            <!-- titulo y parrafo -->
                            <div class="col-lg-8 grandchild-content">
                                <h4 class="text-center">Soporte 24 / 7</h4>
                                <p class="text-justify">
                                    ¿Tienes problemas? ¿Dudas? Estamos ahí para tí. 
                                    Estaremos apoyandote en todo lo que necesites. 
                                </p>
                            </div>
                        </div>
                        <!-- Hijo 4 -->
                        <div class="col-lg-4 col-md-4 col-sm-4 child-services-content">
                            <!-- Imagen de fonto - icon -->
                            <div class="col-lg-4 grandchild-content grandchild-4"></div>
                            <!-- titulo y parrafo -->
                            <div class="col-lg-8 grandchild-content">
                                <h4 class="text-center">Facturación</h4>
                                <p class="text-justify">
                                    ¿Cansado de los trámites tediosos de las facturas?
                                    Nosotros te gestionamos la facturación, rapida y ágilmente. 
                                </p>
                            </div>
                        </div>
                        <!-- Hijo 5 -->
                        <div class="col-lg-4 col-md-4 col-sm-4 child-services-content">
                            <!-- Imagen de fonto - icon -->
                            <div class="col-lg-4 grandchild-content grandchild-5"></div>
                            <!-- titulo y parrafo -->
                            <div class="col-lg-8 grandchild-content">
                                <h4 class="text-center">Tus datos están seguros</h4>
                                <p class="text-justify">
                                    Tus datos están seguros en nuestros servidores, 
                                    contamos con copias de seguridad programadas.
                                </p>
                            </div>
                        </div>
                        <!-- Hijo 6 -->
                        <div class="col-lg-4 col-md-4 col-sm-4 child-services-content">
                            <!-- Imagen de fonto - icon -->
                            <div class="col-lg-4 grandchild-content grandchild-6"></div>
                            <!-- titulo y parrafo -->
                            <div class="col-lg-8 grandchild-content">
                                <h4 class="text-center">Reportes de tu propiedad</h4>
                                <p class="text-justify">
                                    Cada que lo necesites tendrás descargables en PDF o Excel de
                                    tu propiedad y lo que necesites.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fifth-content">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 stats">
                    <h5 class="text-center">20</h5>
                    <p>Conjuntos Administrados</p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 stats">
                    <h5 class="text-center">280</h5>
                    <p>Empleados</p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 stats stats-hidden">
                    <h5 class="text-center">500</h5>
                    <p>Usuarios satisfechos</p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 stats stats-hidden">
                    <h5 class="text-center">40</h5>
                    <p>Administradores Satisfechos</p>
                </div>
            </div>
            <div class="sixth-content">
                <div class="row reset-styles">
                    <div class="col-lg-6 col-md-6 col-sm-6 william-img"></div>
                    <br>
                    <div class="col-lg-6 col-md-6 col-sm-6 quienes-section-textos">
                        <h2 class="text-center">
                            <span class="quienes">
                                &nbsp;William
                            </span>
                            <span class="somos">
                                Henao
                            </span>
                        </h2>
                        <br>
                        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum quas sit autem corporis in impedit repudiandae esse dignissimos. Distinctio nihil dignissimos magnam quibusdam incidunt ullam quaerat praesentium ratione cumque iure. Veritatis sit nostrum ex aut id, tempora magni recusandae soluta adipisci nesciunt consequatur, est, enim error impedit eum sequi. Non commodi necessitatibus quod aliquam tempora rem vero voluptatibus perferendis eligendi fugit.</p>
                        <br>
                    </div>
                </div>
            </div>
            <div class="seventh-content">
                <div class="col-lg-12">
                    <h5 class="text-center">
                        Mis 
                        <span class="nuestro">
                            Encomientas
                        </span>
                    </h5>
                    <p class="text-center">
                        ¿Llegó una nueva encomienda?<br>
                        Acá podrás organizarla y clasificarla
                    </p>
                    <br>
                    <div class="col-lg-2 col-md-4 col-sm-4 col-centered">
                        <button type="button" class="btn-gestion" id="btn-encomiendas">
                            ¡Entrar!
                        </button>
                    </div>
                </div>
            </div>
            {{-- Ventana modal de las encomiendas --}}
            {{-- ******************************** --}}
            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content modal-padding">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                            </button>
                            <h4 class="text-center">
                                <i class="fa fa-inbox"></i>
                                Encomiendas
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                @csrf {{ method_field('POST') }}
                                {{-- Cada campo --}}
                                <div class="row">
                                    <div class="col-md-4 error-validate-1">
                                        <i class="fa fa-font"></i>
                                        <label class="margin-top">
                                            Titulo
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="titulo" id="titulo" class="form-control field-1" placeholder="Ejemplo: No me gusta la porteria" autocomplete="off">
                                    </div>
                                </div>
                                <br>
                                {{-- Cada campo --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <i class="fa fa-text-width"></i>
                                        <label class="margin-top">
                                            Descripción
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea name="descripcion" id="descripcion" cols="30" rows="5" placeholder="Ejemplo: No me la porteria por que es muy pequeña para que alguien trabaje ahi" class="form-control" autocomplete="off"></textarea>
                                    </div>
                                </div>
                                <br>
                                {{-- Cada campo --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <i class="fa fa-building"></i>
                                        <label class="margin-top">
                                            Conjunto
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="id_conjunto" id="id_conjunto" class="form-control field-3 select-2">
                                            <option value="">Seleccione...</option>
                                            @foreach($conjunto as $conjunt)
                                                <option value="{{ $conjunt->id }}">
                                                    {{ $conjunt->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                {{-- Cada campo --}}
                                <div class="row">
                                    <div class="col-md-4 error-validate-5">
                                        <i class="fa fa-building"></i>
                                        <label class="margin-top">
                                            Tipo Unidad
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="id_tipo_unidad" id="id_tipo_unidad" class="form-control field-5">
                                            <option value="default">Seleccione...</option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                {{-- Cada campo --}}
                                <div class="row">
                                    <div class="col-md-4 error-validate-6">
                                        <i class="fa fa-lock"></i>
                                        <label class="margin-top">
                                            Contraseña
                                        </label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="password" class="form-control field-6" name="password_field" placeholder="Digita la contraseña">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-success" id="send_form">
                                            <i class="fa fa-send"></i>
                                            &nbsp; Enviar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ******************************** --}}
            {{-- ******************************** --}}
            <div class="eighth-content" id="equipo">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="our-team text-center">
                            <span class="nuestro">
                                &nbsp; Nuestro
                            </span>
                            <span class="equipo">
                                Equipo
                            </span>
                            <span class="border-bottom"></span>
                        </h2>
                    </div>
                </div>
                <div class="row reset-styles">
                    <div class="owl-three owl-carousel team-content">
                        <div class="item">
                            <div class="team-img team-1-img"></div>
                            <div class="team-text">
                                <h4 class="text-center">Daniela Ejemplo</h4>
                                <p class="text-center">Comunicadora</p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-img team-2-img"></div>
                            <div class="team-text">
                                <h4 class="text-center">Carlos Ejemplo</h4>
                                <p class="text-center">Abogado</p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-img team-3-img"></div>
                            <div class="team-text">
                                <h4 class="text-center">Maria Ejemplo</h4>
                                <p class="text-center">Diseñadora</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-content" id="footer">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 footer-item text-center">
                        <a href="" id="main_logo_footer"></a>
                        <br><br>
                        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum quas sit autem corporis in impedit repudiandae esse dignissimos. Distinctio nihil dignissimos magnam quibusdam incidunt ullam quaerat praesentium ratione cumque iure. Veritatis sit nostrum ex aut id, tempora magni recusandae soluta adipisci nesciunt consequatur, est, enim error impedit eum sequi.</p>
                        <div class="col-lg-12 col-md-12 text-left">
                            <a href="#" class="redes-sociales">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="#" class="redes-sociales">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a href="#" class="redes-sociales">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 footer-item">
                        <h3 class="text-center">Información</h3>
                        <br>
                        <p class="text-center">
                            <i class="fa fa-phone"></i>
                            +57 3216548787
                        </p>
                        <p class="text-center">
                            <i class="fa fa-map-marker"></i>
                            &nbsp; Calle 10 # 20 - 25
                            <br>
                            &nbsp; &nbsp;&nbsp; Manizales - Caldas
                        </p>
                        <p class="text-center">
                            <i class="fa fa-envelope"></i>
                            &nbsp;  gestioncopropietario@gmail.com
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 footer-item">
                        <h3 class="text-center">Contáctanos</h3>
                        <br>
                        <form action="{{ url('contacto') }}" method="POST" id="contac_form">
                            @csrf
                            <input type="email" name="correo" class="form-control-custom" placeholder="Digite su correo *" autocomplete="off" id="email-field">
                            <br><br>
                            <textarea name="mensaje" class="form-control-custom" rows="6" placeholder="Escriba su mensaje*" autocomplete="off" id="text-field"></textarea>
                            <div class="col-lg-6 col-centered">
                                <button type="button" class="btn-gestion btn-formg btn-block" id="send_form_contacto">
                                    Enviar
                                </button>
                                <br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="{{ asset('js/all.js') }}"></script>
    <script src="{{ asset('js/custom_validator.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        
        // Código hermoso, select dependiente precioso ♥
        // *********************************************
        $('#id_conjunto').on('change', function(e){
            var id_conjunto = e.target.value;
            console.log(id_conjunto);

            $.get('tipo_unidad_get?id_conjunto=' + id_conjunto,function(data) {

              $('#id_tipo_unidad').empty();
              $('#id_tipo_unidad').append('<option value="default">Seleccione...</option>');

              $.each(data, function(fetch, tipos_unidad){
                $('#id_tipo_unidad').append('<option value="'+ tipos_unidad.id +'">'+ tipos_unidad.tipo_unidad + ' - ' +tipos_unidad.numero_letra + '</option>');
              })
            });
        });
    
        // Select 2 
        // ****************
        $('.select-2').select2({
            // Este es el id de la ventana modal #modal-form
            dropdownParent: $('#modal-form')
        });

        // Ajax para el envio del formulario de contacto
        // *********************************************
        $('#contac_form').on('submit', function(e){
            e.preventDefault();

            $.ajax({
                url: "{{ url('contacto') }}",
                type: "POST",
                data: $('#contac_form').serialize(),
                success: function(data){
                    swal("¡Ya nos ha llegado tu mensaje!", "Muy pronto nos pondremos en contacto", "success")
                        .then((value) => {
                        location.reload();
                    });                
                },
                error: function(){
                    swal("Operación cancelada", "Lo sentimos, vuelve a intentarlo", "error");
                }
            });
        });

        // Formulario de las encomiendas
        // *****************************
        $('#btn-encomiendas').click(function(event){
            $('#modal-form').modal('show');
        });

        // Evento submit del form de la ventana modal
        // ******************************************
        $('#modal-form').on('submit', function(e){
            e.preventDefault();

            // Validador si la contraseña está correcta
            // ****************************************
            if($('input[name="password_field"]').val() == 566656224141){
                $.ajax({
                    url: "{{ url('encomientas') }}",
                    type: "POST",
                    data: $('#modal-form form').serialize(),
                    success: function(data){
                        $('#modal-form_estado').modal('hide');
                        swal("Operación Exitosa", "El estado ha ido cambiado", "success")
                            .then((value) => {
                            location.reload();
                        });
                    },
                    error: function(){
                        swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
                    }
                }); 
            } else {
                swal("Contraseña Incorrecta", "La contraseña no coincide, intentalo de nuevo", "error")
                        .then((value) => {
                        location.reload();
                    });
            }
        });
    </script>
</body>
</html>