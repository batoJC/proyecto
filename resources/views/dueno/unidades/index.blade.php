@extends('../layouts.app_dashboard_dueno')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('home') }}">Inicio</a>
                </li>
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
                        <a target="_blanck" href="https://youtu.be/ibN7kcqX0Q8">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <h1 class="text-center">
        Listado de mis unidades
    </h1>
    
    @include('dueno.unidades.modalInfo')
    
    
    <div class="container-fluid">
        <br><br>
        <table id="unidades-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Unidad</th>
                    <th>Coeficiente (%)</th>
                    <th>División</th>
                    <th>Observaciones</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>        
    </div>
</div>

	



@endsection
@section('ajax_crud')
    <script>
        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.unidades.dueno') }}",
                data: data,
                dataType: "json",
                success: function (response) {
                    callback(
                        response
                    );
                    $('[data-toggle="tooltip"]').tooltip({
                        container: 'body'
                    });
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#unidades-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'coeficiente', name: 'coeficiente'},
          		{ data: 'division', name: 'division'},
          		{ data: 'observaciones', name: 'observaciones'},
          		{ data: 'action', name: 'action', orderable: false, searchable: false},
          	],
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