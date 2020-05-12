
<br>
<h1 class="text-center">Lista de coincidencias</h1>
<form method="GET" action="{{ url('descargarEgresos') }}">
    <input type="hidden" name="data" value="{{ $data }}">
    <button type="submit" class="btn btn-default" type="button"><i class="fa fa-download"></i> Decargar Comporbantes en PDFs</button>
</form>
<table class="table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Consecutivo</th>
            <th>Factura</th>
            <th>Proveedor</th>
            <th>Valor Total</th>
            <th>Ver</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($egresos as $egreso)
            <tr @if ($egreso->anulado)
                class="bg-red"
            @endif >
                <td>{{ date('d-m-Y',strtotime($egreso->fecha)) }}</td>
                <td>{{ $egreso->prefijo }} {{ $egreso->numero }}</td>
                <td>{{ $egreso->factura }}</td>
                <td>{{ $egreso->proveedor->nombre_completo }}</td>
                <td>$ {{ number_format($egreso->valorTotal()) }}</td>
                <td>
                    <button onclick="verEgreso({{ $egreso->id }})" 
                        @if ($egreso->anulado)
                            class="btn btn-default"
                        @else
                            class="btn btn-primary"
                        @endif><i class="fa fa-eye"></i></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>

$('.table').DataTable({
    language: {
        "processing": "Procesando...",
        "search": "Buscar:",
        "lengthMenu": "Mostrando _MENU_ por página",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 de 0 registros",
        "infoFiltered": "(se han filtrado _MAX_ registros)",
        "infoPostFix": "",
        "loadingRecords": "Cargando...",
        "zeroRecords": "Ningún registro coincide con la búsqueda",
        "emptyTable": "Sin registros",
        "paginate": {
            "first": "Primero",
            "previous": "Anterior",
            "next": "Siguiente",
            "last": "Último"
        },
        "aria": {
            "sortAscending": ": aktywuj, by posortować kolumnę rosnąco",
            "sortDescending": ": aktywuj, by posortować kolumnę malejąco"
        }
    }
});

function verEgreso(id){
    $.ajax({
        type: "GET",
        url: "{{ url('egresos') }}/"+id,
        data: {
            _token : csrf_token
        },
        dataType: "html",
        success: function (response) {
            $('#loadData').html(response);
            $(`#buscar`).modal('hide');

        }
    });
}

</script>
