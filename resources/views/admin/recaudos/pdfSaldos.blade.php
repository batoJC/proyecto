@extends('admin.PDF.plantilla')

@section('style')

    <style>
        main{
            margin-left: 0px !important;
            margin-right: 0px !important;
        }

        .borde{
            border-radius: 10px;
            border: 1px solid #333;
            padding: 10px;
            width: 100%;
        }

        .red{
            color: red;
        }

        .text-center{
            text-align: center;
        }

        .text-left{
            text-align: left;
        }

        h1,h4{
            margin: 2px 0px 2px 0px;
            font-weight: 200;
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

        p{
            margin-bottom: 2px;
        }

    </style>
    
@endsection

@section('contenido')


    <h2 class="text-center">Saldos a favor</h2>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saldos as $saldo)
                <tr>
                <td>{{ $saldo['cedula'] }}</td>
                <td>{{ $saldo['nombre'] }}</td>
                <td>$ {{ number_format($saldo['valor']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
