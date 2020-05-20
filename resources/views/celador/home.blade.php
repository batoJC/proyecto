@extends('../layouts.app_dashboard_celador')

<style>
 	iframe{
        width: 100%;
        height: 600px;
    }

</style>

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('home') }}">Inicio</a>
				</li>
				  <li>Cartas</li>
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
						<a target="_blanck" href="https://youtu.be/zTEzLnvCXVQ">Priemros pasos</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	{{-- Variables de estado --}}
	{{-- ******************* --}}
	@if(session('status'))
		<div class="alert alert-success-original alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			{!! html_entity_decode(session('status')) !!}	
		</div>
	@endif
	{{-- *************************************** --}}
	{{-- Ventana modal de terminos y condiciones --}}
	{{-- *************************************** --}}
	
	<!-- Modal -->
	<div class="modal fade" id="terminos_condiciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-center" id="myModalLabel">
						Terminos y Condiciones
					</h4>
					@php
						$conjunto = Auth::user()->conjunto;
					@endphp
				</div>
				<div class="modal-body">
					<div class="col-md-5 no-padding logo-background-terminos"></div>
					<div class="col-md-7 textos-terminos">
						<h4 class="text-center terminos-title">
							{{ $conjunto->nombre }}
						</h4>
						{{ $conjunto->reglamento->descripcion }}
						<br>
						<a href="{{ asset('reglamentos/'.$conjunto->reglamento->archivo) }}" target="_blank" class="link">
							Leer Articulo Completo 
							<i class="fa fa-eye"></i>
						</a>
						<br><br>
					</div>
					<form method="POST" id="terminos-form">
						@csrf
						<div class="col-md-12 text-center">
							<input type="hidden" class="hidden" id="terminos_field" name="terminos_field">
							<button type="submit" class="btn btn-success btn-default" id="btn-terminos-acept">
								<i class="fa fa-check"></i>
								Acepto
							</button>
							<button type="submit" class="btn btn-default btn-default" id="btn-terminos-dismi">
								<i class="fa fa-times"></i>
								No acepto
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	{{-- *************************************** --}}
	{{-- *************************************** --}}
	@if(Auth::user()->habeas_data == 'Sin Aceptar')
		<div class="col-md-12">
			<p>Acepta los términos y condiciones para continuar</p>
		</div>
	@elseif(Auth::user()->habeas_data == 'No acepto')
		<div class="col-md-12">
			<h3>Terminos y condiciones no aceptados</h3>
			<p>Los terminos y condiciones o Habeas Data no fueron aceptados, por lo tanto no podrás acceder al sitio, si cambias de opinión te invitamos a contactar con el soporte técnico</p>
		</div>
	@else
	
	@include('celador.modalInfo')
	
	<br><br>
	<table id="cartas-table" class="table table-stripped">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Unidad</th>
				<th>Cuerpo</th>
				<th>Ver pdf</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>   
	@endif
</div>


@endsection
@section('ajax_crud')
	<script>
		var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.cartas.porteria') }}",
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
		var table  = $('#cartas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'cuerpo', name: 'cuerpo'},
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


		// Validacion modal habeas data
		// ****************************
		
		habeas_data = '{{ Auth::user()->habeas_data }}';

		console.log(habeas_data);

		if(habeas_data == 'Sin Aceptar'){
			$('#terminos_condiciones').modal({backdrop: 'static', keyboard: false});
		}

		// Submit de Terminos form
		// ***********************

		$('#btn-terminos-acept').click(function(event) {
			$('#terminos_field').val('Acepto');
		});

		$('#btn-terminos-dismi').click(function(event) {
			$('#terminos_field').val('No acepto');
		});

		$('#terminos-form').on('submit', function(e){
			e.preventDefault();

			$.ajax({
				url: "{{ url('terminos') }}",
				type: 'POST',
				data: $('#terminos-form').serialize(),
				success: function(data){
					location.reload();
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});
	</script>

<script>
	var csrf_token = $('meta[name="csrf-token"]').attr('content');


	function loadDataCarta(id){
		$('#titulo_modal').text('Carta');
		$('.modal-body').html(`<iframe id="frame" title="Pdf de la carta"
			src="{{url('cartas')}}/${id}?_token=${csrf_token}">
		</iframe>`);
		$('.info').modal('show');
	}
</script>

@endsection