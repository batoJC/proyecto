@extends('admin.PDF.plantilla')

@section('style')
    <style>
         table{
        	border-collapse: collapse;
        	width: 100%;
        	border-top: 2px solid black;
        	border-bottom: 2px solid black;
        }

        th{
            text-align: center;
        	border: 2px solid black;
        }

        td{
            border: 1px solid grey;
            text-align: center;
        }

    </style>
    
@endsection

@section('contenido')
<h2>Deudas</h2>
<br>
<table>
    <thead>
        <tr>
            <th>Identificación</th>
            <th>Nombre</th>
            <th>Unidad</th>
            <th>Capital</th>
            <th>Interés</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cuentas as $cuenta)
            <tr>
                <td>{{ $cuenta['id'] }}</td>
                <td>{{ $cuenta['propietario'] }}</td>
                <td>{{ $cuenta['unidad'] }}</td>
                <td>${{ number_format($cuenta['capital']) }}</td>
                <td>${{ number_format($cuenta['interes']) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


@endsection