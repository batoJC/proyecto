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

    </style>
    
@endsection

@section('contenido')
<h3 class="text-center">{{ $encabezado }}</h3>
<h2 class="text-center"><b>Certifica</b></h2>
<h3 class="text-center">{{ $cuerpo }}</h3>
<br>
{{-- tabla --}}
<table>
    <thead>
        <tr>
            <th>Concepto</th>
            <th>Valor</th>
            <th>Fecha vencimiento</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['cuentas'] as $cuenta)
            <tr>
                <td>{{ $cuenta['concepto'] }}</td>
                <td>{{ $cuenta['valor'] }}</td>
                <td>{{ $cuenta['fecha_vencimiento'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2">Total: </td>
            <td>{{ $data['total'] }}</td>
        </tr>
    </tbody>
</table>

<br>
<h3><b>Total: </b>{{ $total }}</h3>
<h3><b>Deudores directos: </b>{{ $deudores }}</h3>
<h3><b>Personas solidarias: </b>{{ $personas_solidarias }}</h3>
<br>
<h3 class="text-center">{{ $pie_pagina }}</h3>

@endsection