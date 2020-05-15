@extends('../layouts.app_dashboard_admin')

@section('title', 'Anular')
<style>
	iframe{
		height: 500px;
		width: 100%;
	}
</style>
@section('content')
<div class="container-fluid">

	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
	  	<li>Anular</li>
	</ul>
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
	{{-- ******************* --}}
	<br><br>
	{{-- @include('admin.cuentas_cobro.form') --}}
	<div class="alert alert-success-original alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">x</span>
		</button>
		<h1 class="text-center">Proceso de anulación</h1>
		<h4>
			-Para el proceso de anulación se carga cada cuenta de cobro generada apartir de la seleccionada para anular o la cuenta de cobro del recaudo a anular.
			<br>
			-Además se carga el recaudo con el cual se pagó dicha cuenta de cobro, recuerde no abandonar esta pestaña mientras se hace este proceso ya que podría generar una inconsistencia al no terminarlo.
			<br>
			{{-- -Para llevar a cabo el proceso debe de crear cada nueva cuenta de cobro y cada nuevo recaudo, si el sistema en un punto encuentra que la cuenta siguiente queda igual a la que se deberia de generar el proceso en este punto. --}}
			<br>
			-Al pulsar en guardar y continuar, primero se evalua que si se haya registrado una nueva cuenta de cobro y un nuevo recaudo, además se guardan estos nuevos y se continua con la cuenta de cobro siguiente, si ya se ha llegado al final el sistema le informará.
		</h4>
	</div>

	<h2 class="red">Cuentas por procesar: <span id="nro_cuentas">{{ count($cuentas) }}</span></h2>
	<div class="container" id="proceso">
	</div>
	<br>
	<hr>
	<div class="row">
		<div class="col-12 text-center">
			<button id="btn" onclick="guardar();" class="btn btn-success"><i class="fa fa-check"></i> Continuar</button>
		</div>
	</div>
	<br>
	<br>
	<br>
</div>
	
	
	@endsection
	@section('ajax_crud')
	<script>
		var cuentas = new FormData();
		var ids = new Array();
		@foreach ($cuentas as $cuenta)
			cuentas.append({{ $cuenta->id }},JSON.stringify({
				'cuenta': @json($cuenta),
				'recaudo' : @json($cuenta->recaudo)
			}));
			ids.push({{$cuenta->id}})
		@endforeach

		$('.select-2').select2();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$(function () {
			loadInfo();
		});

		//variables para el proceso de anulación
		var indice  = 0;


		function loadInfo(){
			if(verificar()){
				$.ajax({
					type: "POST",
					url: "{{ url('loadProceso') }}/"+JSON.parse(cuentas.get(ids[indice])).cuenta.id,
					// url: "{{ url('loadProceso') }}/1",
					data: {
						_token : csrf_token
					},
					dataType: "html",
					success: function (response) {
						$('#proceso').html(response);
					}
				});
			}else{
				btn.disabled = true;
				$('#proceso').html(`<h1>Ya terminaste la anulación de todas las cuentas de cobro y sus respectivos recaudos.</h1>`);
			}
		}

		function guardar(){
			if(verificar()){
				guardarProceso((res)=>{
					if(res){
						indice++;
						nro_cuentas.innerText = ids.length - indice;
						loadInfo();
					}
				});
			}
		}

		function verificar(){
			return (indice < ids.length);
		}

        
	</script>
@endsection
