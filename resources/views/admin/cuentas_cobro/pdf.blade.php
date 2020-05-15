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
            margin: 1px 0px 1px 0px;
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

        h3{
            margin: 1px 0px 1px 0px !important;
        }

        p{
            margin-bottom: 2px;
            margin-top: 2px;
        }

    </style>
    
@endsection

@section('contenido')


<div class="row">

    @if ($cuenta->anulada)
        <div>
            <h1 class="red text-center">Anulada</h1>
            <p><b>Fecha: </b>{{ date('d-m-Y',strtotime($cuenta->fecha_anulado)) }}</p>
            <p><b>Motivo: </b>{{$cuenta->motivo}}</p>
            <p><b>Cuenta de reemplazo: </b>{{$cuenta->reemplaza->consecutivo}}</p>
        </div>
    @endif

    <h1 class="text-center titulo">Cuenta  @if ($cuenta['tipo_cobro'] != 'normal')
       en cobro {{$cuenta['tipo_cobro']}}
    @else
        de cobro
    @endif</h1>
    <h4><b>Consecutivo: </b>{{ $cuenta->consecutivo }}</h4>
    <h4><b>Fecha: </b>{{ date('d-m-Y',strtotime($cuenta->fecha)) }}</h4>
    @if ($cuenta->interes() == 0 and $cuenta->fecha_pronto_pago != null)
        <h4><b>Fecha pronto pago: </b>{{ date('d-m-Y',strtotime($cuenta->fecha_pronto_pago ))}}</h4>
    @endif
    <h4><b>Nombre: </b>{{ $cuenta->propietario->nombre_completo }} - {{ $cuenta->propietario->numero_cedula }}</h4>
    <br>
    <h3 class="text-center">Detalles</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Vigencia inicio</th>
                <th>Vigencia fin</th>
                <th>Referencia de pago</th>
                <th>Concepto</th>
                <th>Valor</th>
                <th>Interes</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($cuenta->detalles as $detalle)
                <tr>
                    <td>{{ date('d-m-Y',strtotime($detalle->vigencia_inicio)) }}</td>
                    <td>{{ date('d-m-Y',strtotime($detalle->vigencia_fin)) }}</td>
                    <td>{{ ($detalle->unidad)? $detalle->unidad->referencia : '' }}</td>
                    <td>{{ $detalle->concepto }}</td>
                    <td>$ {{ number_format($detalle->valor) }}</td>
                    <td>$ {{ number_format($detalle->interes) }}</td>
                    <td>$ {{ number_format($detalle->valor+$detalle->interes) }}</td>
                </tr>
                @php
                    $total += $detalle->valor+$detalle->interes;
                @endphp
            @endforeach
        </tbody>
    </table>
    <br>
    <h3 class="text-left"><b>Total a pagar:  </b>$ {{ number_format($total) }}</h3>
    @if ($cuenta->interes() == 0 and $cuenta->fecha_pronto_pago != null)
        <h3 class="text-left"><b>Total a pagar con descuento:  </b>$ {{ number_format($total*(1-($cuenta->descuento/100))) }}</h3>
    @endif
    @if ($cuenta->saldo_favor > 0)
        <h3 class="text-left"><b>Saldo a favor:  </b>$ {{ number_format($cuenta->saldo_favor) }}</h3>
    @endif
    <br>
    <p>Cuentas bancarias del conjunto: </p>
    @foreach ($cuenta->conjunto->cuentas as $cuenta)
        <span>{{ $cuenta->banco }} - {{ $cuenta->nro_cuenta }} - {{ $cuenta->tipo }}</span><br>
    @endforeach
</div>
<br>
<h3>{{ mb_strtoupper(Auth::user()->nombre_completo,'UTF-8') }} - C.c. {{ Auth::user()->numero_cedula }} - Administrador</h3>
@endsection
