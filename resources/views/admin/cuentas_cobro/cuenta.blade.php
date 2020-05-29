<div class="row borde">
    <h1 class="text-center">Cuenta @if ($cuenta['tipo_cobro'] != 'normal')
        en cobro {{$cuenta['tipo_cobro']}}
    @else
        de cobro
    @endif</h1>
    <h4><b>Consecutivo: </b>{{ $cuenta['consecutivo'] }}</h4>
    <h4><b>Fecha: </b>{{ date('d-m-Y',strtotime($datos['fecha'])) }}</h4>
    @if ($cuenta['propietario']->interes() == 0 and $datos['fecha_pronto_pago'] != null)
        <h4><b>Fecha pronto pago: </b>{{ date('d-m-Y',strtotime($datos['fecha_pronto_pago'])) }}</h4>
    @endif
    <h4><b>Nombre: </b>{{ $cuenta['propietario']->nombre_completo }} - {{ $cuenta['propietario']->numero_cedula }}</h4>
    <br>
    <h3 class="text-center">Detalles</h3>
    <table class="table">
        <thead>
            <th>Vigencia inicio</th>
            <th>Vigencia fin</th>
            <th>Referencia de pago</th>
            <th>Concepto</th>
            <th>Valor</th>
            <th>Interes</th>
            <th>Total</th>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($cuenta['cuentas'] as $detalle)
                    <tr data-row="{{ $detalle['tipo'].'_'.$detalle['cuota_id'] }}">
                    <td><i onclick="eliminarCuota('{{ $detalle['tipo'] }}','{{ $detalle['cuota_id'] }}','{{ $detalle['unidad_id'] }}','{{ $cuenta['propietario']->id }}')" class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Eliminar esta cuenta"></i>  {{ date('d-m-Y',strtotime($detalle['vigencia_inicio'])) }}</td>
                    <td>{{ ($detalle['vigencia_fin'])? date('d-m-Y',strtotime($detalle['vigencia_fin'])) : '' }}</td>
                    <td>{{ $detalle['referencia'] }}</td>
                    <td>{{ $detalle['concepto'] }}</td>
                    <td>$ {{ number_format($detalle['valor']) }} <i onclick="editarValor('{{ $detalle['tipo'] }}','{{ $detalle['cuota_id'] }}','{{ $detalle['unidad_id'] }}','{{ $cuenta['propietario']->id }}')" class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Editar el valor de esta cuenta"></i></td>
                    <td>$ {{ number_format($detalle['interes']) }}</td>
                    <td>$ {{ number_format($detalle['valor']+$detalle['interes']) }}</td>
                </tr>
                @php
                    $total += $detalle['valor']+$detalle['interes'];
                @endphp
            @endforeach
        </tbody>
    </table>
    <h3><b>Total a pagar:  </b>$ {{ number_format($total) }}</h3>
    @if ($cuenta['propietario']->interes() == 0 and $datos['fecha_pronto_pago'] != null)
        <h3><b>Total a pagar con descuento:  </b>$ {{ number_format($total*(1-($datos['descuento']/100))) }}</h3>
    @endif
    @if ($cuenta['propietario']->saldo()>0)
        <h3><b>Saldo a favor:  </b>$ {{ number_format($cuenta['propietario']->saldo()) }}</h3>
    @endif
</div>