@extends('admin.PDF.plantilla')

@section('style')
    
    
@endsection

@section('contenido')
    <h2>Listado reservas aprobadas</h2>
    <br>

    @foreach ($reservas as $reserva)
        <p> 
            <b>Zona social: </b>{{ $reserva->zona_comun->nombre }}
            <br>
            <b>Fecha solicitud: </b>{{ date('d/m/Y',strtotime($reserva->fecha_solicitud)) }}
            <br>
            <b>Solicitada por: </b>{{ $reserva->propietario->nombre_completo }} - {{ $reserva->propietario->numero_cedula  }}
            <br>
            <b>Motivo: </b>{{ $reserva->motivo }}
            <br>
            <b>Número de asistentes: </b>{{ $reserva->asistentes }}
            <br>
            <b>Fecha y hora de inicio: </b>{{ date('d/m/Y h:i A',strtotime($reserva->fecha_inicio)) }}
            <br>
            <b>Fecha y hora de fin: </b>{{ date('d/m/Y h:i A',strtotime($reserva->fecha_fin)) }}
            <br>
        </p>
        <br>
        <hr>
        <br>
    @endforeach

@endsection