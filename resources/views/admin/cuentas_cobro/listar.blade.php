@if ($cuentas->count() > 0)
    <form method="GET" action="{{ url('descargarCuentas') }}">
        <input type="hidden" name="data" value="{{ $data }}">
        <button type="submit" class="btn btn-default" type="button"><i class="fa fa-download"></i> Decargar cuentas en PDFs</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Consecutivo</th>
                <th>Fecha</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cuentas as $cuenta)
                <tr class="@if ($cuenta->anulada)
                    bg-red
                @endif">
                    <td>{{ $cuenta->consecutivo }}</td>
                    <td>{{ date('d-m-Y',strtotime($cuenta->fecha)) }}</td>
                    <td>
                        @if (!$cuenta->anulada)
                            <a data-toggle="tooltip" data-placement="top" title="Anular" class="btn btn-default" href="{{ url('anularCuentaCobro',['cuenta'=>$cuenta->id]) }}"><i class="fa fa-ban"></i></a>
                        {{-- @else
                            <a onclick="deshacer({{ $cuenta->id }})" data-toggle="tooltip" data-placement="top" title="Deshacer la anulación" class="btn btn-default" href="#"><i class="fa fa-undo"></i></a> --}}
                        @endif
                        <a data-toggle="tooltip" data-placement="top" title="Ver pdf" target="_blank" class="btn btn-default" href="{{ url('pdfCuentasCobros',['cuenta'=>$cuenta->id]) }}"><i class="fa fa-file-pdf-o"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <h3 class="text-center">No hay cuentas de cobro para mostrar.</h3>
@endif