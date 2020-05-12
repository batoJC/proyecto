@extends('../layouts.app_dashboard_dueno')

@section('title', 'Tabla de Intereses')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			{{-- ************************************** --}}
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('dueno') }}">Inicio</a>
				</li>
				<li>Mi Cartera</li>
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
	{{-- Variable de session para actualización --}}
	{{-- ************************************** --}}
	@if(session('status'))
		<div class="alert alert-error alert-dismissible" role="alert">
			<button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			{!! html_entity_decode(session('status')) !!}
		</div>
	@endif
	<br>
	
	<h3><b>Saldo a Favor:  </b> ${{ number_format($saldo) }}</h3>
	
	
	<style>
		td{
			font-size: 13px;
		}
	</style>
	<table id="detalles-table" class="table">
		<thead>
			<tr>
				<th>Consecutivo</th>
				<th>Fecha</th>
				<th>Pagó</th>
				<th>Unidad</th>
				<th>Propietario</th>
				<th>Registrado por</th>
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
                url: "{{ url('api.micartera.dueno') }}",
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
		var table  = $('#detalles-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'action', name: 'action', orderable: false, searchable: false},
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'valor', name: 'valor'},
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'propietario', name: 'propietario'},
          		{ data: 'usuario', name: 'usuario'},
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
