<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información sobre liquidador</title>
    {{-- <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"> --}}
    <link rel="icon" type="image/png" href="{{ asset('imgs/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-styles.css') }}">
</head>
<style>
    *{
        font-family: 'Conv_Comfortaa-Bold' !important;
    }

    html,body{
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0px;
        background: #80808000;
    }

    .resaltar{
        color: #1ABB9C;
    }

    p{
        font-size: 15px !important;
        text-align: justify;
    }

    .main{
        background: #80808030;
        width: 100%;
        height: 100vh;
        display: flex;
        align-content: center;
        padding-top: 10%;
    }

    .image-main{
        margin-top: -15vh;
    }

    .calculo{
        padding-left: 10px;
        position: relative;
        height: 130px;
        overflow: auto;
    }

    .t-valor{
        left: 40px;
        top: 25px;
        position: absolute;
        margin: 0px;
        display: inline-block;
    }

    .v-valor{
        position: absolute;
        top: 28px;
        left: 210px;
    }

    .t-hora{
        position: absolute;
        margin: 0px;
        top: 52px;
        left: 40px;
    }

    .v-hora{
        top: 52px;
        position: absolute;
        left: 220px;
    }

    .t-resultado{
        position: absolute;
        margin: 0px;
        display: inline-block;
        top: 25px;
        left: 325px;
        height: 60px;
        width: 131px;
        text-align: center;
    }

    .v-resultado{
        color: #1ABB9C;
        position: absolute;
        top: 38px;
        left: 465px;
    }

    .equals{
        position: relative;
        top: 40px;
        left: 300px;
    }

    .p-r-70{
        padding-right: 70px;
    }

    .p-l-70{
        padding-left: 70px;
    }

    .mostrar{
        display: none;
    }

    
    @media (max-width: 992px) {
        .p-l-70{
            padding-left: 10px;
        }
        .p-r-70{
            padding-right: 10px;
        }

        .mostrar{
            display: block;
        }

        .esconder{
            display: none;
        }

    }

</style>
<body>
    <div class="main">
    <div class="container-fluid" style="width: 100% !important">
            <div class="row">
                <div class="col-0 col-md-1"></div>
                <div class="col-12 col-md-5">
                    <br>
                    <h1  class="text-center">Información sobre el liquidador</h1 >
                    <br>
                    <p>Aquí encontraras una descripción de la forma en que se hacen los calculos para el liquidador de nómina con todos los detalles sobre cada jornada, espero sea de mucha claridad para usted.</p>
                    <p>
                        Jornada máxima legal mensual <span class="resaltar">(horas)</span>: {{ $datos['horas_jornada'] }}
                    </p>
                </div>
                <div class="col-md-6 text-center hidden-xs">
                    <img class="image-main" src="{{ asset('imgs/calculator.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="container">
        <br><br>
        <h1 class="text-center">
            Jornada ordinaria <span class="resaltar">(Hasta {{ $datos['jornada_ordinaria'] }} horas diarias)</span>
        </h1>
        <br><br><br>

        {{-- HORA ORDINARIA DIURNA --}}
        <div class="row">
            <div class="col-12 col-md-6 p-r-70 visible-xs-12">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">O</span>rdinaria <span class="resaltar">D</span>iurna
                </h2>
                <br>
                <p>Estas horas corresponden a la jornada ordinaria o jornada máxima legal <span class="resaltar"> (de {{ $datos['horas_jornada'] }} al mes)</span> del trabajador y que se labora entre las {{ date('h:i A',strtotime($datos['inicio_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} de los días ordinarios <span class="resaltar">(no festivos ni dominicales)</span>.</p>
                <p>El valor de la hora ordinaria diurna <span class="resaltar"> (o corriente)</span>, se obtiene dividiendo el sueldo básico mensual <span class="resaltar">(${{ number_format($datos['salario']) }})</span> entre el total de horas correspondientes a la jornada máxima legal <span> ({{ $datos['horas_jornada'] }} al mes)</span>.</p>
            </div>
            <div class="col-12 col-md-6">
                <br>
                <br>
                <p class="text-center">Tiempo ordinario diurno</p>
                <input class="OD" type="text" value="" name="range" />
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Básico mensual: </p>
                        <span class="resaltar v-valor" style="border-bottom: 2px solid black;">${{ number_format($datos['salario'],2) }}</span>
                        <p class="t-hora">Jornada máxima: </p>
                        <span class="resaltar v-hora">{{ $datos['horas_jornada'] }}</span>
                        <span class="equals">=</span>
                        <p class="t-resultado">Hora ordinaria diurna:</p>
                        <span class="resaltar v-resultado">@php
                            $hora_ordinaria = round($datos['salario']/$datos['horas_jornada'],2);
                            echo '$'.number_format($hora_ordinaria,2);
                        @endphp</span>
                   </div>
                </div>
            </div>
        </div>
        <br><br>

        {{-- HORA ORDINARIA NOCTURNA --}}
        <div class="row">
            <div class="col-12 col-md-6 p-l-70 mostrar">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna
                </h2>
                <br>
                <p>Horas comprendidas en la jornada ordinaria o máxima legal <span class="resaltar">(de {{ $datos['horas_jornada'] }} al mes)</span>  del trabajador y que se laboran entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} de los días ordinarios <span class="resaltar">(no festivos ni dominicales)</span> . Se liquida un recargo del <span class="resaltar">{{ $datos['recargo_ordinario_nocturno'] }}% </span> sobre la hora ordinaria diurna, por el solo hecho de ser trabajo nocturno.</p>
                <p>Para liquidar el valor del recargo nocturno de una hora, se toma el valor de la hora ordinaria y se multiplica por el {{ $datos['recargo_ordinario_nocturno'] }}% <span class="resaltar">(esto es solo el recargo nocturno; el valor de la hora ordinaria se liquida en las {{ $datos['horas_jornada'] }} horas)</span>.</p>
            </div>
            <div class="col-12 col-md-6 uno">
                <br>
                <br>
                <p class="text-center">Tiempo ordinario nocturno</p>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-1" type="text" value="" name="range" />
                </div>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-2" type="text" value="" name="range" />
                </div>
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Hora ordinaria: </p>
                        <span class="resaltar v-valor">${{ number_format($hora_ordinaria,2) }}</span>
                        <p class="t-hora">Tarifa: </p>
                        <span class="resaltar v-hora">{{ $datos['recargo_ordinario_nocturno']/100 }} </span>
                        <span class="equals">=</span>
                        <p class="t-resultado">recargo ordinario nocturno:</p>
                        <span class="resaltar v-resultado">${{ number_format($hora_ordinaria*($datos['recargo_ordinario_nocturno']/100),2) }}</span>
                   </div>
                </div>
            </div>
            <div class="col-12 col-md-6 p-l-70 esconder">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna
                </h2>
                <br>
                <p>Horas comprendidas en la jornada ordinaria o máxima legal <span class="resaltar">(de {{ $datos['horas_jornada'] }} al mes)</span>  del trabajador y que se laboran entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} de los días ordinarios <span class="resaltar">(no festivos ni dominicales)</span> . Se liquida un recargo del <span class="resaltar">{{ $datos['recargo_ordinario_nocturno'] }}% </span> sobre la hora ordinaria diurna, por el solo hecho de ser trabajo nocturno.</p>
                <p>Para liquidar el valor del recargo nocturno de una hora, se toma el valor de la hora ordinaria y se multiplica por el {{ $datos['recargo_ordinario_nocturno'] }}% <span class="resaltar">(esto es solo el recargo nocturno; el valor de la hora ordinaria se liquida en las {{ $datos['horas_jornada'] }} horas)</span>.</p>
            </div>
        </div>
        <br><br>

        {{-- HORA ORDINARIA DIURNA FESTIVA--}}
        <div class="row">
            <div class="col-12 col-md-6 p-r-70">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">O</span>rdinaria <span class="resaltar">D</span>iurna <span class="resaltar">F</span>estiva
                </h2>
                <br>
                <p>Estas horas corresponden a la jornada ordinaria o jornada máxima legal <span class="resaltar">(de {{ $datos['horas_jornada'] }} al mes)</span> del trabajador y que se labora entre las {{ date('h:i A',strtotime($datos['inicio_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} de los días festivos o dominicales.</p>
                <p>Para liquidar el valor del recargo diurno dominical de una hora, se toma el valor de la hora ordinaria y se multiplica por <span class="resaltar">{{ $datos['recargo_ordinario_diurno_festivo'] }}%</span>  (esto es solo el recargo diurno dominical; el valor de la hora ordinaria se liquida en las {{ $datos['horas_jornada'] }} horas)</p>
            </div>
            <div class="col-12 col-md-6">
                <br>
                <br>
                <p class="text-center">Tiempo ordinario diurno festivo</p>
                <input class="OD" type="text" value="" name="range" />
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Hora ordinaria: </p>
                        <span class="resaltar v-valor">${{ number_format($hora_ordinaria,2) }}</span>
                        <p class="t-hora">Tarifa: </p>
                        <span class="resaltar v-hora">{{ $datos['recargo_ordinario_diurno_festivo']/100 }} </span>
                        <span class="equals">=</span>
                        <p class="t-resultado">Recargo ordinario diurno festivo:</p>
                        <span class="resaltar v-resultado">${{ number_format($hora_ordinaria*($datos['recargo_ordinario_diurno_festivo']/100),2) }}</span>
                   </div>
                </div>
            </div>
        </div>
        <br><br>

        {{-- HORA ORDINARIA NOCTURNA FESTIVA--}}
        <div class="row">
            <div class="col-12 col-md-6 p-l-70 mostrar">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna <span class="resaltar">F</span>estivo
                </h2>
                <br>
                <p>Estas horas corresponden a la jornada ordinaria o jornada máxima legal <span class="resaltar">(de {{ $datos['horas_jornada'] }} al mes)</span> del trabajador y que se labora entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} de los días festivos o dominicales.</p>
                <p>Para liquidar el valor del recargo nocturno dominical de una hora, se toma el valor de la hora ordinaria y se multiplica por <span class="resaltar">{{ $datos['recargo_ordinario_nocturno_festivo'] }}% </span>(esto es solo recargo nocturno dominical; el valor de la hora ordinaria se liquida en las {{ $datos['horas_jornada'] }} horas).</p>
            </div>
            <div class="col-12 col-md-6">
                <br>
                <br>
                <p class="text-center">Tiempo ordinario nocturno festivo</p>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-1" type="text" value="" name="range" />
                </div>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-2" type="text" value="" name="range" />
                </div>
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Hora ordinaria: </p>
                        <span class="resaltar v-valor">${{ number_format($hora_ordinaria,2) }}</span>
                        <p class="t-hora">Tarifa: </p>
                        <span class="resaltar v-hora">{{ $datos['recargo_ordinario_nocturno_festivo']/100 }} </span>
                        <span class="equals">=</span>
                        <p class="t-resultado">Recargo ordinario nocturno festivo:</p>
                        <span class="resaltar v-resultado">${{ number_format($hora_ordinaria*($datos['recargo_ordinario_nocturno_festivo']/100),2) }}</span>
                   </div>
                </div>
            </div>
            <div class="col-12 col-md-6 p-l-70 esconder">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna <span class="resaltar">F</span>estivo
                </h2>
                <br>
                <p>Estas horas corresponden a la jornada ordinaria o jornada máxima legal <span class="resaltar">(de {{ $datos['horas_jornada'] }} al mes)</span> del trabajador y que se labora entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} de los días festivos o dominicales.</p>
                <p>Para liquidar el valor del recargo nocturno dominical de una hora, se toma el valor de la hora ordinaria y se multiplica por <span class="resaltar">{{ $datos['recargo_ordinario_nocturno_festivo'] }}% </span>(esto es solo recargo nocturno dominical; el valor de la hora ordinaria se liquida en las {{ $datos['horas_jornada'] }} horas).</p>
            </div>
        </div>
        <br><br><br><br>

        <h1 class="text-center">
            Jornada extraordinaria <span class="resaltar">(Horas extras)</span>
        </h1>
        <br><br><br>

        {{-- HORA EXTRA ORDINARIA DIURNA --}}
        <div class="row">
            <div class="col-12 col-md-6 p-r-70">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">E</span>xtra <span class="resaltar">O</span>rdinaria <span class="resaltar">D</span>iurna
                </h2>
                <br>
                <p>Estas horas corresponden al tiempo trabajado adicionalmente a la jornada ordinaria del trabajador <span class="resaltar">(después de completar las {{ $datos['jornada_ordinaria'] }} horas de la jornada diaria)</span>, llevada a cabo entre las {{ date('h:i A',strtotime($datos['inicio_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} en días ordinarios <span class="resaltar">(no festivos ni dominicales)</span>.</p>
                <p>Se toma el valor de la hora ordinaria y se multiplica por el <span class="resaltar">{{ $datos['hora_extra_ordinaria_diurna']*100 }}%</span>, reconociendo así que el tiempo trabajado es adicional <span class="resaltar">(extra)</span> diurno.</p>
            </div>
            <div class="col-12 col-md-6">
                <br>
                <br>
                <p class="text-center">Tiempo adicional diurno</p>
                <input class="OD" type="text" value="" name="range" />
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Hora ordinaria: </p>
                        <span class="resaltar v-valor">${{ number_format($hora_ordinaria,2) }}</span>
                        <p class="t-hora">Tarifa: </p>
                   <span class="resaltar v-hora">{{ $datos['hora_extra_ordinaria_diurna'] }} </span>
                        <span class="equals">=</span>
                        <p class="t-resultado">Hora extra diurna ordinaria:</p>
                        <span class="resaltar v-resultado">${{ number_format($datos['hora_extra_ordinaria_diurna']*$hora_ordinaria,2) }}</span>
                   </div>
                </div>
            </div>
        </div>
        <br><br>

        {{-- HORA EXTRA ORDINARIA NOCTURNA --}}
        <div class="row">
            <div class="col-12 col-md-6 p-l-70 mostrar">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">E</span>xtra <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna
                </h2>
                <br>
                <p>Estas horas corresponden al tiempo trabajado adicionalmente a la jornada ordinaria del trabajador <span class="resaltar">(después de completar las {{ $datos['jornada_ordinaria'] }} horas de la jornada diaria)</span>, llevada a cabo entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }}, en días ordinarios <span class="resaltar">(no festivos ni dominicales)</span>.</p>
                <p>Se toma el valor de la hora ordinaria y se multiplica por el <span class="resaltar">{{ $datos['hora_extra_ordinaria_nocturna'] }}% </span>, reconociendo así que el tiempo trabajado es adicional <span class="resaltar">(extra)</span> nocturno.</p>
            </div>
            <div class="col-12 col-md-6">
                <br>
                <br>
                <p class="text-center">Tiempo adicional nocturno</p>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-1" type="text" value="" name="range" />
                </div>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-2" type="text" value="" name="range" />
                </div>
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Hora ordinaria: </p>
                        <span class="resaltar v-valor">${{ number_format($hora_ordinaria,2) }}</span>
                        <p class="t-hora">Tarifa: </p>
                        <span class="resaltar v-hora">{{ $datos['hora_extra_ordinaria_nocturna'] }}</span>
                        <span class="equals">=</span>
                        <p class="t-resultado">Hora extra nocturna ordinaria:</p>
                        <span class="resaltar v-resultado">${{ number_format($datos['hora_extra_ordinaria_nocturna']*$hora_ordinaria,2) }}</span>
                   </div>
                </div>
            </div>
            <div class="col-12 col-md-6 p-l-70 esconder">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">E</span>xtra <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna
                </h2>
                <br>
                <p>Estas horas corresponden al tiempo trabajado adicionalmente a la jornada ordinaria del trabajador <span class="resaltar">(después de completar las {{ $datos['jornada_ordinaria'] }} horas de la jornada diaria)</span>, llevada a cabo entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }}, en días ordinarios <span class="resaltar">(no festivos ni dominicales)</span>.</p>
                <p>Se toma el valor de la hora ordinaria y se multiplica por el <span class="resaltar">{{ $datos['hora_extra_ordinaria_nocturna'] }}% </span>, reconociendo así que el tiempo trabajado es adicional <span class="resaltar">(extra)</span> nocturno.</p>
            </div>
        </div>
        <br><br>

        {{-- HORA EXTRA ORDINARIA DIURNA FESTIVA--}}
        <div class="row">
            <div class="col-12 col-md-6 p-r-70">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">E</span>xtra <span class="resaltar">O</span>rdinaria <span class="resaltar">D</span>iurna <span class="resaltar">F</span>estiva
                </h2>
                <br>
                <p>Estas horas corresponden al tiempo trabajado adicionalmente a la jornada ordinaria del trabajador <span class="resaltar">(después de completar las {{ $datos['jornada_ordinaria'] }} horas de la jornada diaria)</span>, laboradas entre las {{ date('h:i A',strtotime($datos['inicio_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }} en días festivos o dominicales</p>
                <p>Se toma el valor de la hora ordinaria y se multiplica por el <span class="resaltar">{{ $datos['hora_extra_ordinaria_diurna_fesiva'] }}% </span>, reconociendo así que el tiempo trabajado además de ser adicional <span class="resaltar">(extra)</span> diurno, es festivo.</p>
            </div>
            <div class="col-12 col-md-6">
                <br>
                <br>
                <p class="text-center">Tiempo adicional diurno festivo</p>
                <input class="OD" type="text" value="" name="range" />
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Hora ordinaria: </p>
                        <span class="resaltar v-valor">${{ number_format($hora_ordinaria,2) }}</span>
                        <p class="t-hora">Tarifa: </p>
                        <span class="resaltar v-hora">{{ $datos['hora_extra_ordinaria_diurna_fesiva'] }}</span>
                        <span class="equals">=</span>
                        <p class="t-resultado">Hora extra diurna festiva:</p>
                        <span class="resaltar v-resultado">${{ number_format($datos['hora_extra_ordinaria_diurna_fesiva']*$hora_ordinaria,2) }}</span>
                   </div>
                </div>
            </div>
        </div>
        <br><br>

        {{-- HORA EXTRA ORDINARIA NOCTURNA FESTIVA--}}
        <div class="row">
            <div class="col-12 col-md-6 p-l-70 mostrar">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">E</span>xtra <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna <span class="resaltar">F</span>estivo
                </h2>
                <br>
                <p>Corresponde al tiempo trabajado adicionalmente a la jornada ordinaria del trabajador <span class="resaltar">(después de completar las {{ $datos['jornada_ordinaria'] }} horas de la jornada diaria)</span>, laborado entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }}, en días festivos o dominicales</p>
                <p>Se toma el valor de la hora ordinaria y se multiplica por el <span class="resaltar">{{ $datos['hora_extra_ordinaria_nocturna']*100 }}% </span>, reconociendo así que el tiempo trabajado además de ser adicional <span class="resaltar">(extra)</span> nocturno, es festivo.</p>
            </div>
            <div class="col-12 col-md-6">
                <br>
                <br>
                <p class="text-center">Tiempo adicional nocturno festivo</p>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-1" type="text" value="" name="range" />
                </div>
                <div class="col-xs-6 col-md-6">
                    <input class="ON-2" type="text" value="" name="range" />
                </div>
                <br>
                <p>Ejemplo: <br></p>
                <div class="col-12">
                   <div class="calculo">
                        <p class="t-valor">Hora ordinaria: </p>
                        <span class="resaltar v-valor">${{ number_format($hora_ordinaria,2) }}</span>
                        <p class="t-hora">Tarifa: </p>
                        <span class="resaltar v-hora">{{ $datos['hora_extra_ordinaria_nocturna'] }}</span>
                        <span class="equals">=</span>
                        <p class="t-resultado">Hora ordinaria nocturna festiva:</p>
                        <span class="resaltar v-resultado">${{ number_format($hora_ordinaria*$datos['hora_extra_ordinaria_nocturna_festiva'],2) }}</span>
                   </div>
                </div>
            </div>
            <div class="col-12 col-md-6 p-l-70 esconder">
                <h2>
                    <span class="resaltar">H</span>ora <span class="resaltar">E</span>xtra <span class="resaltar">O</span>rdinaria <span class="resaltar">N</span>octurna <span class="resaltar">F</span>estivo
                </h2>
                <br>
                <p>Corresponde al tiempo trabajado adicionalmente a la jornada ordinaria del trabajador <span class="resaltar">(después de completar las {{ $datos['jornada_ordinaria'] }} horas de la jornada diaria)</span>, laborado entre las {{ date('h:i A',strtotime($datos['final_jornada'])) }} y las {{ date('h:i A',strtotime($datos['final_jornada'])) }}, en días festivos o dominicales</p>
                <p>Se toma el valor de la hora ordinaria y se multiplica por el <span class="resaltar">{{ $datos['hora_extra_ordinaria_nocturna']*100 }}% </span>, reconociendo así que el tiempo trabajado además de ser adicional <span class="resaltar">(extra)</span> nocturno, es festivo.</p>
            </div>
        </div>
        <br><br>
    </div>
    <br>
    <br>
    <br>
</body>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    {{-- <script src="../vendors/ion.rangeSlider/js/ion.rangeSlider.min.js"></script> --}}
    <script src="{{ asset('js/ion.rangeSlider.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.OD').ionRangeSlider({
                skin: "round",
                type: "int",
                grid: true,
                min: 0,
                max: 24,
                from: {{ date('H',strtotime($datos['inicio_jornada'])) }},
                to: {{ date('H',strtotime($datos['final_jornada'])) }},
                to_fixed: true,
                from_fixed : true
            });
            $('.ON-1').ionRangeSlider({
                skin: "round",
                type: "int",
                grid: true,
                min: 0,
                max: 12,
                from: 0,
                to: {{ date('H',strtotime($datos['inicio_jornada'])) }},
                to_fixed: true,
                from_fixed : true
            });
            $('.ON-2').ionRangeSlider({
                skin: "round",
                type: "int",
                grid: true,
                min: 12,
                max: 24,
                from: {{ date('H',strtotime($datos['final_jornada'])) }},
                to: 24,
                to_fixed: true,
                from_fixed : true
            });
        });
    </script>
</html> 