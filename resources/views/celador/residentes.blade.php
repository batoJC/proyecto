@extends('../layouts.app_dashboard_celador')

@section('content')  
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('dueno') }}">Inicio</a>
                </li>
                  <li>Residentes</li>
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
                        <a target="_blanck" href="#">Video 1</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('celador.modalInfo')
    
    <br><br>
    <table id="residentes-table" class="table table-stripped">
        <thead>
            <th>Unidad</th>
            <th>Tipo Residente</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Genero</th>
            <th>Tipo de Documento</th>
            <th>Documento</th>
            <th>Fecha de Ingreso</th>
            <th>Acciones</th>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>



@endsection
@section('ajax_crud')
    <script>
        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.residentes.porteria') }}",
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
		var table  = $('#residentes-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'tipo_residente', name: 'tipo_residente'},
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'apellido', name: 'apellido'},
          		{ data: 'genero', name: 'genero'},
          		{ data: 'tipo_documento', name: 'tipo_documento'},
          		{ data: 'documento', name: 'documento'},
          		{ data: 'fecha_ingreso', name: 'fecha_ingreso'},
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