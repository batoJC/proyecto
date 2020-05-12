@extends('../layouts.app_dashboard_dueno')

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<h3>Noticias</h3>
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
		@php
			$items = 0;
		@endphp
		<div class="row">
			@foreach($noticias as $notic)
	
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<div class="col-lg-12 no-padding news-div">
						<img src="{{ asset('imgs/private_imgs/'.$notic->foto) }}" alt="Photo">
						<h2>{{ $notic->titulo }}</h2>
						<p>{{ str_limit($notic->descripcion, 100) }}</p>
						<p class="autor">
							<i class="fa fa-user-circle"></i>
							{{ $notic->user->nombre_completo }}
							&nbsp;&nbsp;
							<a href="{{ url('noticias/'.$notic->id) }}">
								Leer más &nbsp;
								<i class="fa fa-arrow-right"></i>
							</a>
						</p>
					</div>
				</div>
				@php 
					if($items == 3){
						echo '</div><br><div class="row">';
						$items = -1;
					}else{
	
					}
				@endphp
				@php
					$items++;
				@endphp
			@endforeach
		</div>
		<br><br>
	@endif
</div>


@endsection
@section('ajax_crud')
	<script>
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
@endsection
