{{-- @extends('admin.PDF.plantilla')

@section('style')
    
    
@endsection

@section('contenido')



@endsection --}}

@extends('admin.PDF.plantilla')

@section('style')
    <style>
        /* @page main {
            size: A4 landscape;
            margin: 2cm;
        }

        .mainPage {
            page: main;
            page-break-after: always;
        } */

        /* h2{
            font-weight: 100 !important;
            font-family: Verdana, Geneva, Tahoma, sans-serif !important;
        } */

    </style>
    
@endsection

@section('contenido')
   <h2 class="text-center">
       {{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}
   </h2>
   <br>

   <h3>Información básica de la unidad</h3>
   <p>
       <b>División: </b> {{ $unidad->division->tipo_division->division }} {{ $unidad->division->numero_letra }} <br>
       <b>Coeficiente(%): </b>{{ $unidad->coeficiente }} <br>
       <b>Referencia de pago: </b>{{ ($unidad->referencia )? $unidad->referencia : 'No aplica' }} <br>
       <b>Observaciones: </b> {{ $unidad->observaciones }}
   </p>
   <br>

   @php
        $var = $unidad->propietarios->where('pivot.estado', 'Activo')->first();

        $nombre_propietario = null;
        $documento_propietario = null;
        $email_propietario = null;
        $direccion_propietario = null;

        if ($var != null) {
            $nombre_propietario = $var['nombre_completo'];
            $documento_propietario = $var['numero_cedula'];
            $email_propietario = $var['email'];
            $direccion_propietario = $var['direccion'];
        }
   @endphp

    <h3>Propietario actual</h3>
    <p>
        <b>Nombre Completo: </b>{{ $nombre_propietario }} <br>
        <b>Documento: </b>{{ $documento_propietario }} <br>
        <b>Correo: </b>{{ $email_propietario }} <br>
        <b>Dirección: </b>{{ $direccion_propietario }}
    </p>
@endsection