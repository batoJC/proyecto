@extends('admin.PDF.plantilla')

@section('style')
    <style>
         @page {
            size: A4 portrait;
            margin: 100px 5px 100px 5px !important;
            /* margin: 100px 10px; */
        }

        .red{
            color: red;
        }

        .green{
            color: green;
        }

        table{
            margin-left: -25px;
            margin-right: 60px; 
        	border-collapse: collapse;
        	width: 100%;
        	border-top: 2px solid black;
        	border-bottom: 2px solid black;
        }

        th{
            text-align: center;
        	border-bottom: 2px solid black;
        }

        td{
            border: 1px solid grey;
        }

    </style>
    
@endsection

@section('contenido')

<h2>Flujo de efectivo</h2>
<br>
<table>
    <thead>
        <tr>
            {{-- <th>Tipo</th> --}}
            <th style="width: 90px;">Fecha</th>
            <th style="width: 200px;">Concepto</th>
            <th style="padding: 2px;width:80px">Recibo N°</th>
            <th style="padding: 2px">Entro</th>
            <th style="padding: 2px">C.E.N°</th>
            <th style="padding: 2px">Salio</th>
            <th style="padding: 2px">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @php
            $balance = 0;
        @endphp
        @foreach ($flujo_efectivo as $flujo)
            <tr>
                <td style="width: 60px;">{{ date('d-m-Y',strtotime($flujo->fecha)) }}</td>
                <td>{{ $flujo->concepto }}</td>
                @if (!$flujo->tipo)
                    <td>{{ $flujo->recibo }}</td>
                    <td>${{ number_format($flujo->valor)  }}</td>
                    <td></td>
                    <td></td>
                @else
                    <td></td>
                    <td></td>
                    <td>{{ $flujo->recibo }}</td>
                    <td>${{ number_format($flujo->valor)  }}</td>
                @endif
                @php
                    $valor = ($flujo->tipo)? $flujo->valor * -1 : $flujo->valor;
                    $balance += $valor;
                @endphp
                <td style="width: 120px;" class="@if ($balance >= 0) green @else red @endif">
                    ${{ number_format($balance) }}
                </td>
                
            </tr>
        @endforeach
    </tbody>
</table>

@endsection