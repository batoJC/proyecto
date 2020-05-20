@extends('../layouts.app_dashboard_dueno')

{{-- Modal para mostrar la información de un documento --}}
<div class="modal fade" id="info_documento" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
					Información del Documento
		        	&nbsp; 
		        </h4>
      		</div>
      		<div class="modal-body" id="body_info_documento">
			</div>
    	</div>
  	</div>
</div>


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('documentos') }}">Documentos conjunto</a>
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
                        <a target="_blanck" href="https://youtu.be/L9lgcOpLlEQ">¿Qué son?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <br><br>
    <table id="documentos-table" class="table table-stripped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Ver</th>
            </tr>
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
                url: "{{ url('api.documentos.dueno') }}",
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
		var table  = $('#documentos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'descripcion', name: 'descripcion'},
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


        function ver(id){
            $.ajax({
                type: "GET",
                url: "{{ url('documentos') }}/"+id,
                data: {
                    '_token' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "HTML"
            }).done(res => {
				$('#info_documento').modal('show');
				$('#body_info_documento').html(res);
				
            });
        }

    </script>
@endsection