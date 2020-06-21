@extends('admin.PDF.plantilla')

@section('style')
    
    
@endsection

@section('contenido')
<h2>{{ $propietario->nombre_completo }} - {{ $propietario->numero_cedula }}</h2>

    @foreach ($propietario->pqr as $p)
<p>
    <b>Tipo: </b>{{ $p->tipo }}<br>
    <b>Fecha: </b>{{ date('d-m-Y',strtotime($p->fecha_solicitud))}}<br>
    <b>Hechos: </b>{{ $p->hechos }}<br>
    <b>Petición: </b>{{ $p->peticion }}<br>
    <b>Estado: </b>{{ $p->estado }}<br>
    <b>Fecha respuesta: </b>{{ date('d-m-Y',strtotime($p->fecha_respuesta))}}<br>
    <b>Respuesta: </b>{{ $p->respuesta }}
</p>
<br>
<hr>
<br>
    @endforeach
@endsection