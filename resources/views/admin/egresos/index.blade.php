@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('home') }}">Inicio</a>
				</li>
				  <li>Egresos</li>
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
						<a target="_blanck" href="https://youtu.be/xhnoIVjqVrs">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 text-center">
			<a class="btn btn-success" onclick="cargarAgregar()">
				<i class="fa fa-plus"></i>
				Agregar egreso
			</a>
			<a class="btn btn-default" onclick="abrirModal('buscar')">
				<i class="fa fa-search"></i>
				Buscar egreso
			</a>
			<a class="btn btn-primary" onclick="abrirModal('listar')">
				<i class="fa fa-list-ol"></i>
				Listar egresos
			</a>
		</div>
	</div>
	
	<div id="loadData">
	
	</div>
</div>


	

@include('admin.egresos.forms')
@endsection
@section('ajax_crud')
	<script>

		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$('#imgSalida').hide();
		$('.btn-speacial').hide();

		$('.btn-logotype-brand').click(function(event) {
			
			$('.upload').click();

			$(function() {
				$('#file-input').change(function(e) {
					addImage(e); 
				});

			    function addImage(e){
			    	var file = e.target.files[0],
			   		imageType = /image.*/;
			    
			    	// if (!file.type.match(imageType))
			    	// return;
			  
			    	var reader = new FileReader();
			    	reader.onload = fileOnload;
			    	reader.readAsDataURL(file);
			    }
			  
			    function fileOnload(e) {
			    	var result=e.target.result;
			    	$('#imgSalida').attr("src", result);
			    	$('#imgSalida').fadeIn(600);
			    	$('.btn-speacial').fadeIn(600);
			    	$('.btn-logotype-brand').fadeOut(200);
			    }
			});
		});

		$('.btn-speacial').click(function(event){
			$('#imgSalida').fadeOut(300);
			$('.btn-logotype-brand').fadeIn(200);
			$('.btn-speacial').hide();
			$('#file-input').val('');
		});

		function abrirModal(nombre){
			$(`#${nombre}`).modal('show');
            // $(`#${nombre}Form`)[0].reset();
		}


		function cargarAgregar(){
			$.ajax({
				type: "POST",
				url: "{{ url('agregarEgresos') }}",
				data: {
					_token : csrf_token
				},
				dataType: "html",
				success: function (response) {
					$('#loadData').html(response);
				}
			});
		}


		function buscar(){
			$.ajax({
				type: "POST",
				url: "{{ url('buscarEgreso') }}",
				data: {
					_token : csrf_token,
					'prefijo' : prefijo.value,
					'numero' : numero.value
				},
				dataType: "html",
				success: function (response) {
					$('#loadData').html(response);
					$(`#buscar`).modal('hide');

				}
			});
		}

		function listar(){
			$.ajax({
				type: "POST",
				url: "{{ url('listarEgresos') }}",
				data: {
					_token : csrf_token,
					'fecha_inicio' : fecha_inicio.value,
					'fecha_fin' : fecha_fin.value
				},
				dataType: "html",
				success: function (response) {
					$('#loadData').html(response);
					$(`#listar`).modal('hide');
				}
			});
		}
    </script>
@endsection