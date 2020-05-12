@if ($recaudos->count() > 0)
    <form method="GET" action="{{ url('descargarRecaudos') }}">
        <input type="hidden" name="data" value="{{ $data }}">
        <button type="submit" class="btn btn-default" type="button"><i class="fa fa-download"></i> Decargar recaudos en PDFs</button>
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
            @foreach ($recaudos as $recaudo)
                <tr class="@if ($recaudo->anulada)
                    bg-red
                @endif">
                    <td>{{ $recaudo->consecutivo }}</td>
                    <td>{{ date('d-m-Y',strtotime($recaudo->fecha)) }}</td>
                    <td>
                        @if (!$recaudo->anulada)
                            <a data-toggle="tooltip" data-placement="top" title="Anular" class="btn btn-default" href="{{ url('anularRecaudo',['recaudo'=>$recaudo->id]) }}"><i class="fa fa-ban"></i></a>
                        {{-- @else
                            <a onclick="deshacer({{ $recaudo->id }})" data-toggle="tooltip" data-placement="top" title="Deshacer la anulación" class="btn btn-default" href="#"><i class="fa fa-undo"></i></a> --}}
                        @endif
                        <a data-toggle="tooltip" data-placement="top" title="Ver Pdf" target="_blank" class="btn btn-default" href="{{ url('pdfPago',['recaudo'=>$recaudo->id]) }}"><i class="fa fa-file-pdf-o"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@else
    <h3 class="text-center">No hay recaudos para mostrar.</h3>
@endif