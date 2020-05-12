
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

        .qr_img{
            margin-top: -20px;
            float: right;
        }

    </style>
    
@endsection

@section('contenido')

    <div>
        
        @if ($egreso->anulado)
            <h1 class="red text-center">Anulado</h1>
            <h3 class="red">{{ $egreso->detalle }}</h3>
        @endif
        <img class="qr_img" src="{{public_path() }}/qrcodes/qrcodeegreso_{{$egreso->id}}.png" alt="QR">
        <div>
            <div>
                <h4><b>Consecutivo: </b> {{ $egreso->prefijo }} {{ $egreso->numero }}</h4>
                <h4><b>Fecha: </b> {{ date('d-m-Y',strtotime($egreso->fecha)) }}</h4>
                <h4><b>Factura: </b> {{ $egreso->factura }}</h4>
                <h4><b>Proveedor: </b> {{ $egreso->proveedor->nombre_completo }}</h4>
            </div>
        </div>
        <br><br><br>
        <h3 class="text-center">Detalles</h3>
        <table>
            <thead>
                <tr class="text-center">
                    <th>Código</th>
                    <th>Concepto</th> 
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($egreso->detalles as $detalle)
                    <tr class="text-center">
                        <td>{{ $detalle->codigo }}</td>
                        <td>{{ $detalle->concepto }}</td>
                        <td>$ {{ number_format($detalle->valor) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <h3 class="text-left">Valor total: $ {{ number_format($egreso->valorTotal()) }}</h3>
        <br>
    </div>
<br><br><br>
@php
    $usuario = Auth::user();
@endphp
@for ($i = 0; $i < strlen($usuario->nombre_completo)*1.5; $i++){{'_'}}@endfor
<h3>{{ mb_strtoupper($usuario->nombre_completo,'UTF-8') }}</h3>
<h3>C.c. {{ $usuario->numero_cedula }}</h3>
<h4>Administrador</h4>
    
@endsection

