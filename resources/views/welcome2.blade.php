<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet"  href="{{ asset('landing_resources/css/main.css')}}">
    <link rel="stylesheet"  href="{{ asset('landing_resources/css/animate.css')}}" />
    <script  src="{{ asset('landing_resources/js/main.js') }}"></script>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>

<body class="container">
    <!-- NAVBAR -->
    <nav id="nav_bar" class="blue-bar navbar">
        <img id="logo" class="logo" src="{{ asset('landing_resources/img/logo_blanco.svg') }}" alt="">
        <ul>
            <li>
                <a href="#section_1">Inicio</a></li>
            <li>
                <a href="#section_2">¿Quienes Somos?</a></li>
            <li>
                <a href="#section_3">Servicios</a></li>
            <li>
                <a href="#section_5">Equipo</a></li>
            <li>
                <a href="#section_6">Contacto</a></li>
            <li><a href="/login" class="btn-blue bg_blue_primary">Ingreso</a></li>
        </ul>
    </nav>

    <!-- FIRST PAGE -->

    <div id="section_1" class="section_1">
        <div class="section_1__banner">
            <h4 class="section_1__title_1">¿Qué esperas para conocernos?</h4>
            <h1 class="section_1__title_2">¡Hola! Somos <span class="text_blue_primary">Gestión Copropietario</span></h1>
        </div>
        <a href="#section_2" class="section_1__action animate__animated animate__bounce animate__repeat-3">
            <svg class="section_1__icon">
              <use xlink:href="{{ asset('landing_resources/img/icons.svg#down-arrow') }}" />
            </svg>
            <h3 style="text-align: center;">ver más</h3>
        </a>
    </div>

    <!-- QUIENES SOMOS -->

    <div id="section_2" class="section_2 visibility-hidden">
        <span class="section_2__circle_1 bg_blue_secondary"></span>
        <span class="section_2__circle_2 bg_blue_primary"></span>
        <div class="section_2__banner">
            <h1 class="section_2__title_1 text_blue_primary">¿Quienes <span class="text_blue_secondary animate__animated animate__backInLeft">Somos?</span></h1><br>
            <ul class="section_2__list">
                <li class="text_blue_primary"><span class="text_blue_secondary">A</span>dministración</li>
                <li class="text_blue_primary"><span class="text_blue_secondary">R</span>esidencial</li>
                <li class="text_blue_primary"><span class="text_blue_secondary">I</span>ntegral</li>
                <li class="text_blue_primary"><span class="text_blue_secondary">E</span>n Propiedad Horizontal</li>
                <li class="text_blue_primary"><span class="text_blue_secondary">S</span>istematizada</li>
            </ul>
            <br>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet ipsa exercitationem asperiores temporibus labore animi quod officiis recusandae itaque. Impedit assumenda totam illo quaerat recusandae amet harum ea. Voluptatibus, ut!</p>
            <br>
            <a href="#section_3" class="btn-blue">Ver más</a>
        </div>
        <img class="section_2__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="">
    </div>

    <!-- NUESTROS SERVICIOS -->

    <div id="section_3" class="section_3 visibility-hidden">
        <div class="section_3__sticker text_blue_primary">Nuestros <span class="text_blue_secondary">Servicios</span></div>
        <div class="section_3__columna_1">
            <div id="card_service_1" class="card_service ">
                <div class="card_service__column_1">
                    <svg class="section_3__icon_1">
                      <use xlink:href="{{ asset('landing_resources/img/person.svg#person') }}" />
                    </svg>
                    <span style="color: black;"></span>
                </div>
                <div class="card_service__column_2">
                    <h2 class="text_blue_secondary">Reportes de tu propiedad</h2>
                    <br>
                    <p style="color: black;">Cada que lo necesites tendrás descargables en PDF o Excel de tu propiedad y lo que necesites.
                    </p>
                </div>
            </div>
            <div id="card_service_2" class="card_service">
                <div class="card_service__column_1">
                    <svg class="section_3__icon_1">
                      <use xlink:href="{{ asset('landing_resources/img/laptop-house-solid.svg#laptop-house-solid') }}" />
                    </svg>
                    <span style="color: black;"></span>
                </div>
                <div class="card_service__column_2">
                    <h2 class="text_blue_secondary">Administra tu Propiedad</h2>
                    <br>
                    <p style="color: black;">Organiza, Programa, Agenda todo lo que incumbe con tu propiedad de manera ágil, rápida y sencilla.
                    </p>
                </div>
            </div>
            <div id="card_service_3" class="card_service">
                <div class="card_service__column_1">
                    <svg class="section_3__icon_1">
                      <use xlink:href="{{ asset('landing_resources/img/comment-solid.svg#comment-solid') }}" />
                    </svg>
                </div>
                <div class="card_service__column_2">
                    <h2 class="text_blue_secondary">Soporte 24/7</h2>
                    <br>
                    <p style="color: black;">¿Tienes problemas? ¿Dudas? Estamos ahí para tí. Estaremos apoyandote en todo lo que necesites.
                    </p>
                </div>
            </div>
        </div>
        <div class="section_3__columna_2">
            <div id="card_service_4" class="card_service">
                <div class="card_service__column_1">
                    <svg class="section_3__icon_1">
                      <use xlink:href="{{ asset('landing_resources/img/file-dollar-solid.svg#file-dollar-solid') }}" />
                    </svg>
                    <span style="color: black;"></span>
                </div>
                <div class="card_service__column_2">
                    <h2 class="text_blue_secondary">Facturación</h2>
                    <br>
                    <p style="color: black;">¿Cansado de los trámites tediosos de las facturas? Nosotros te gestionamos la facturación, rapida y ágilmente.
                    </p>
                </div>
            </div>
            <div id="card_service_5" class="card_service">
                <div class="card_service__column_1">
                    <svg class="section_3__icon_1">
                        <use xlink:href="{{ asset('landing_resources/img/lock-solid.svg#lock-solid') }}" />
                    </svg>
                    <span style="color: black;"></span>
                </div>
                <div class="card_service__column_2">
                    <h2 class="text_blue_secondary">Tus datos están seguros</h2>
                    <br>
                    <p style="color: black;">Tus datos están seguros en nuestros servidores, contamos con copias de seguridad programadas.
                    </p>
                </div>
            </div>
            <div id="card_service_6" class="card_service">
                <div class="card_service__column_1">
                    <svg class="section_3__icon_1">
                        <use xlink:href="{{ asset('landing_resources/img/building-solid.svg#building-solid') }}" />
                    </svg>
                </div>
                <div class="card_service__column_2">
                    <h2 class="text_blue_secondary">Asesorias Legales</h2>
                    <br>
                    <p style="color: black;">¿Tienes dudas de algún tema legal? ¡Claro que sí, nosotros podemos ayudarte! Cualquier tipo de inconveniente legal te aclaramos tus dudas.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- WILLIAM HENAO -->

    <div id="section_4" class="section_4 visibility-hidden">
        <span class="section_4__circle_1 bg_blue_primary"></span>
        <img class="section_4__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="William">
        <div class="section_4__content">
            <h1 class="text_blue_primary">William <br><span class="text_blue_secondary">Henao Gutierrez</span></h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod reiciendis vel, cum quaerat est voluptates pariatur id in qui, necessitatibus, ipsam rem illo unde. Iusto quae iure nostrum tempora quo?</p>
            <br>
            <a href="#section_6" class="btn-blue">Pregúntame</a>
        </div>
    </div>

    <!-- NUESTRO EQUIPO -->

    <div id="section_5" class="section_5">
        <div class="section_5__sticker text_blue_primary">Nuestro <span class="text_blue_secondary">Equipo</span></div>
        <div class="section_5__arrow_left">
            <svg class="section_5__icon_1" onclick="afterPerson();">
                <use xlink:href="{{ asset('landing_resources/img/icons.svg#down-arrow') }}" />
            </svg>
        </div>
        <div class="section_5__content">
            <div id="person_1" class="card">
                <img class="card__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="person">
                <h3 class="text_blue_secondary">William Henao</h3>
                <p>Abogado</p>
            </div>
            <div id="person_2" class="card">
                <img class="card__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="person">
                <h3 class="text_blue_secondary">William Henao</h3>
                <p>Abogado</p>
            </div>
            <div id="person_3" class="card">
                <img class="card__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="person">
                <h3 class="text_blue_secondary">William Henao</h3>
                <p>Abogado</p>
            </div>
            <div id="person_4" class="card">
                <img class="card__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="person">
                <h3 class="text_blue_secondary">William Henao</h3>
                <p>Abogado</p>
            </div>
            <div id="person_5" class="card">
                <img class="card__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="person">
                <h3 class="text_blue_secondary">William Henao</h3>
                <p>Abogado</p>
            </div>
            <div id="person_6" class="card">
                <img class="card__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="person">
                <h3 class="text_blue_secondary">William Henao</h3>
                <p>Abogado</p>
            </div>
            <div id="person_7" class="card">
                <img class="card__image" src="{{ asset('landing_resources/img/person.jpg') }}" alt="person">
                <h3 class="text_blue_secondary">William Henao</h3>
                <p>Abogado</p>
            </div>
        </div>
        <div class="section_5__arrow_right">
            <svg class="section_5__icon_2" onclick="nextPerson();">
                <use xlink:href="{{ asset('landing_resources/img/icons.svg#down-arrow') }}" />
            </svg>
        </div>
    </div>
    </div>
    <div id="section_6" class="section_6 bg_blue_secondary">
        <div style="padding-left: 7em;">
            <br>
            <img src="{{ asset('landing_resources/img/logo_blanco.svg') }}" alt="" class="section_6__logo">
            <br> <br>
        </div>
        <div style="display: flex; width: 100%;">
            <div class="section_6__column_1">
                <h4>Información de Contacto</h4>
                <br>
                <p>+57 3122860436
                    <br> +57 3233534591
                    <br> Cra 23 # 20 - 29 of 306
                    <br> Manizales - Caldas
                    <br> gestioncopropietario@gmail.com
                </p>
            </div>
            <form id="formulario_contacto" class="section_6__column_2">
                @csrf
                <input type="hidden" name="recaptcha" id="recaptcha">
                <h3>Escríbenos</h3>
                <br>
                <div class="section_6__row_1">
                    <div class="row_1__column_1">
                        <label for="">NOMBRE:</label><br>
                        <input type="text" name="nombre" id="nombre" placeholder="Tú nombre">
                    </div>
                    <div class="row_1__column_2">
                        <label for="">EMAIL:</label><br>
                        <input type="email" name="email" id="email" placeholder="correo@gmail.com">
                    </div>
                </div>
                <br>
                <div class="section_6__row_2">
                    <label for="">MENSAJE:</label><br>
                    <textarea name="mensaje" id="mensaje" cols="30" rows="5" placeholder="Escribe tu mensaje."></textarea>
                </div>
                <br>
                <div class="section_6__row_3">
                    <input class="btn-blue bg_blue_primary" type="submit" value="Enviar">
                </div>
            </form>
        </div>
        <p class="footer">
            © 2021 Todos los derechos reservados - Gestioncopropietario.com
        </p>
    </div>
</body>
<script src="https://www.google.com/recaptcha/api.js?render=6Lfnh9MaAAAAAHsQJMxiiOxfPFpfbEtKy7EnocUY"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
</html>