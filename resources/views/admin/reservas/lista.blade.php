@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ url('home') }}">Inicio</a>
				</li>
				  <li>Reservas</li>
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
						<a target="_blanck" href="https://youtu.be/XMmajMGGHn0">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	@include('admin.reservas.form')
	<button onclick="showForm();" class="btn btn-success"><i class="fa fa-plus"></i> Agregar reserva</button>
	
	<br><br>
	<table id="reservas-table" class="table table-stripped">
		<thead>
			<th>Fecha Solicitud</th>
			<th>Zona social</th>
			<th>Fecha inicio con hora</th>
			<th>Fecha fin con hora</th>
			<th>Propietario</th>
			<th>Estado</th>
			<th>Ver</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>


	
    
@endsection
@section('ajax_crud')
	<script>
		$(document).ready(function () {
			$('.select-2').select2({
				dropdownParent: $('#formReserva'),
			});
		});

		var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.reservas') }}",
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
		var table  = $('#reservas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha_solicitud', name: 'fecha_solicitud'},
          		{ data: 'zona_comun', name: 'zona_comun'},
          		{ data: 'fecha_inicio', name: 'fecha_inicio'},
          		{ data: 'fecha_fin', name: 'fecha_fin'},
          		{ data: 'propietario', name: 'propietario'},
          		{ data: 'estado', name: 'estado'},
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


	    var _token = $('meta[name="csrf-token"]').attr('content');

        function actualizarPantalla(){
            table.ajax.reload();
        }

		function mostrarModalEvento(id){
            $.ajax({
                type: "GET",
                url: "{{ url('reservas') }}/"+id,
                data: {
                    _token
                },
                dataType: "html",
                success: function (response) {
                    $('#infoReserva').modal('show');
                    $('#info_reserva').html(response);
                }
            });
        }

		//agregar una reserva
		function showForm(){
			$('#modal_reserva').modal('show');
		}

		function registrarReserva(){
		if(verificarFormulario('formReserva',8)){
			$.ajax({
				type: "POST",
				url: "{{url('reservas')}}",
				data: new FormData(formReserva),
				contentType : false,
				processData: false,
				dataType: "json",
				success: function (response) {
					if(response.res){
						swal('logrado!',response.msg,'success').then(res=>{
							table.ajax.reload();
							$('#modal_reserva').modal('hide');
						});
					}else{
						swal('Error!',response.msg,'error');
					}
				}
			}).fail(res=>{
				swal('Error!','Ocurrió un error en el servidor!','error');
			});
		}

		return false;
	}

    </script>
@endsection