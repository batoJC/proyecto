@extends('admin.PDF.plantilla')

@section('style')
    <style>
        h3{
            text-align: left !important;
        }

        .text-center{
            text-align: center !important;
        }

        table{
            border-collapse: collapse;
        }

        th,td{
            margin: 0px !important;
            border: 1px solid black;
            font-size: 13px;
            text-align: center;
        }

        h4{
            margin-top: 2px;
        }

    </style>
    
@endsection

@section('contenido')
<h2 class="text-center"><b>Certifica</b></h2>
<br>
<h3 class="text-justify">{{ $cuerpo }}</h3>
<br>
<h3>Se expide de acuerdo con los datos informados por Contabilidad.</h3>
<br>
<h3>{{ $fecha }}</h3>

<br>
<br>
<br>
<br>
@php
    $usuario = Auth::user();
@endphp
@for ($i = 0; $i < strlen($usuario->nombre_completo)*1.3; $i++)
{{'_'}}@endfor
<h3>{{ mb_strtoupper($usuario->nombre_completo,'UTF-8') }}</h3>
<h3>C.c. {{ $usuario->numero_cedula }}</h3>
<h4>Administrador</h4>


@endsection