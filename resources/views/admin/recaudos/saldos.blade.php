@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('admin') }}">Inicio</a>
                </li>
                  <li>Saldos a favor</li>
            </ul>
        </div>
        <div class="col-1 col md-1 text-right">
            <div class="btn-group">
                <i  data-placement="left" 
                    title="Ayuda" 
                    data-toggle="dropdown" 
                    type="button" 
                    aria-expanded="false"
                    class="fa blue fa-question-circle-o ayuda">
                </i>
                <ul role="menu" class="dropdown-menu pull-right">
                    <li>
                        <a target="_blanck" href="https://youtu.be/c_SinPOSftw">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <a download class="btn btn-default" href="{{ url('saldosFavorPdf') }}"><i class="fa fa-download"></i>  Descargar en pdf</a>
    <div class="container-fluid">
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
    </div>
</div>

	

@endsection
@section('ajax_crud')
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


    </script>
@endsection