<style>
    td{
        font-size: 13px;
    }
</style>
<table class="table">
    <thead>
        <tr>
            <th>Consecutivo</th>
            <th>Fecha</th>
            <th>Pagó</th>
            <th>Unidad</th>
            <th>Propietario</th>
            <th>Usuario Registró</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detalles as $detalle)
            <tr>
                <td>
                    <a target="_blanck" href="{{ url('pdfPagoC',['consecutivo'=>$detalle->prefijo.'-'.$detalle->numero]) }}">
                        <i class="fa fa-eye"></i>
                        {{ $detalle->prefijo }}-{{ $detalle->numero }}
                    </a>
                </td>
                <td>{{ date('d-m-Y',strtotime($detalle->fecha)) }}</td>
                <td>$ {{ number_format($detalle->valor) }}</td>
                <td>{{ ($detalle->unidad)? $detalle->unidad->tipo->nombre.' '.$detalle->unidad->numero_letra : 'No Aplica' }}</td>
                <td>{{ ($detalle->propietario) ? $detalle->propietario->nombre_completo : 'No Aplica'}}</td>
                <td>{{ $detalle->usuario->nombre_completo }}</td>
            </tr>
        @endforeach
    </tbody>
</table>