
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

        .qr_img{
            float: right;
        }

    </style>
    
@endsection

@section('contenido')


<div class="row">


    @if ($recaudo->anulada)
        <h3 class="red">ANULADO</h3>
        <h4><b>Fecha anulación: </b>{{ date('d-m-Y',strtotime($recaudo->fecha_anulacion)) }}</h4>
        <h4><b>Motivo anulación: </b>{{ $recaudo->motivo }}</h4>
        <h4><b>Recaudo de reemplazo: </b>{{ ($recaudo->reemplazo)? $recaudo->reemplazo->consecutivo : 'No aplica' }}</h4>
    @endif

    <h1 class="text-center titulo">Recibo de pago</h1>
    <img class="qr_img" src="{{public_path() }}/qrcodes/qrcoderecaudo_{{$recaudo->id}}.png" alt="QR">

    <h4><b>Consecutivo: </b>{{ $recaudo->consecutivo }}</h4>
    <h4><b>Fecha: </b>{{ date('d-m-Y',strtotime($recaudo->fecha)) }}</h4>
    <h4><b>Valor recaudado: </b> $ {{ number_format($recaudo->valor) }}</h4>
    <h4><b>Nombre: </b>{{ $recaudo->propietario->nombre_completo }} - {{ $recaudo->propietario->numero_cedula }}</h4>
    <h4><b>Tipo de pago: </b>{{ $recaudo->tipo_de_pago }} {{ $recaudo->banco }}</h4>
    <br>
    <br>
    <h3 class="text-center">Detalles</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($recaudo->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->concepto() }}</td>
                    <td>$ {{ number_format($detalle->valor) }}</td>
                </tr>
                @php
                    $total += $detalle->valor;
                @endphp
            @endforeach
        </tbody>
    </table>
    <br>
    <h3 class="text-left"><b>Total pagado:  </b>$ {{ number_format($total) }}</h3>
    @if (($recaudo->cuentaCobro->valor() - $total) >= 0)
        <h3 class="text-left"><b>Saldo después de este pago:  </b>$ {{ number_format(($recaudo->cuentaCobro->valor() + $recaudo->cuentaCobro->valor()) - $total) }}</h3>
    @else
        <h3 class="text-left"><b>Saldo a favor después de este pago:  </b>$ {{ number_format($recaudo->saldo_favor) }}</h3>
    @endif
</div>
<br>
<br>
<br>
<br>
@php
    $usuario = Auth::user();
@endphp
@for ($i = 0; $i < strlen($usuario->nombre_completo)*1.5; $i++){{'_'}}@endfor
<h3>{{ mb_strtoupper($usuario->nombre_completo,'UTF-8') }}</h3>
<h3>C.c. {{ $usuario->numero_cedula }}</h3>
<h4>Administrador</h4>

@endsection
