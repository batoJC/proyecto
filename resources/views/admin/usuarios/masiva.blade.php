@extends('../layouts.app_dashboard_admin')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('home') }}">Inicio</a>
		</li>
		<li>
			<a href="{{ asset('usuarios') }}">Usuarios</a>
		</li>
	  	<li>Cargue &nbsp;Masivo de Usuarios</li>
	</ul>
	<p class="carga-masiva">
		Recuerde que para subir usuario masivamente debe de respetar la estructura
		de la base de datos y el orden de los datos en la tabla <br>de usuarios por lo tanto, 
		te invitamos a usar el archivo base para saber como debes subir los archivos para que no
		ocurran errores.
	</p>
	<p class="carga-masiva">¿No sabes dónde está el archivo base? <br>Puedes descargarlo acá: &nbsp; 
		<a href="{{ url('download_users') }}" target="_blank">
	        <i class="fa fa-file-archive-o"></i>
	        &nbsp; Archivo Base de Usuarios
	    </a>
	</p>
	<br>
	<form action="{{ url('users_csv_post') }}" class="col-md-5" method="post" enctype="multipart/form-data">
		@csrf {{ method_field('POST') }}
		{{-- Contador de errores --}}
		{{-- ******************* --}}
		@if(count($errors) > 0)
			<div class="alert alert-danger-original alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                	<span aria-hidden="true" class="button">x</span>
                </button>
				@foreach($errors->all() as $er)
					<ul>
						<li class="no-list">{{ $er }}</li>
					</ul>
				@endforeach
            </div>
		@endif
		<br>
		{{-- ******************* --}}
		{{-- Cada campo --}}
		<div class="row">
			<div class="col-md-5 error-validate-file">
				<i class="fa fa-file"></i>
				<label class="margin-top">
					&nbsp; Archivo (Extensión .XLSX)
				</label>
			</div>
			<div class="col-md-7">
				<button type="button" class="btn btn-logotype-brand">
					<i class="fa fa-plus icon-plus-brand-logo"></i>
				</button>
				<input type="file" name="archivo" id="file-input-special" class="upload hidden">
				<p id="name-file"></p>
				<button type="button" class="btn btn-default btn-speacial" style="display:none;">
					Quitar el Archivo&nbsp;
					<i class="fa fa-trash"></i>
				</button>
			</div>
		</div>
		<br>
		{{-- Cada campo --}}
		<div class="row">
			<div class="col-md-5 error-validate-1">
				<i class="fa fa-lock"></i>
				{{-- <label class="margin-top" id="captcha" onselectstart="return false;" oncontextmenu="return false;">
					&nbsp;
				</label> --}}
				<label class="margin-top" id="captcha">
					&nbsp;
				</label>
			</div>
			<div class="col-md-5">
				<input type="number" class="form-control field-1" id="codigo" name="codigo" placeholder="Digite el codigo" autocomplete="off">
				<input type="number" class="hidden" name="hidden_codigo" id="hidden_codigo">
			</div>
		</div>
		<br><br>
		<div class="row">
			<div class="col-md-10 text-center">
				<button type="button" class="btn btn-success" id="send_form">
    				<i class="fa fa-send"></i>
    				&nbsp; Cargar Usuarios
    			</button>
			</div>
		</div>
	</form>
@endsection
@section('ajax_crud')
	<script>

		// Custom para el label Captcha
    	// ****************************
	    random_number = Math.floor((Math.random() * 100000000000000000) + 1);
	    // console.log(random_number);
	    $('#hidden_codigo').hide();
	    $('#captcha').text(random_number);
	    $('#hidden_codigo').val(random_number);

		setInterval(function(){
	    	// Custom para el label Captcha
	    	// ****************************
		    random_number = Math.floor((Math.random() * 100000000000000000) + 1);
		    // console.log(random_number);
		    $('#captcha').text(random_number);
		    $('#hidden_codigo').val(random_number);
		}, 30000);


		// Eventos para el logo de la brand.
		// **********************************

		$('.btn-speacial').hide();
		$('#name-file').hide();

		$('.btn-logotype-brand').click(function(event) {
			
			$('.upload').click();

			$('#file-input-special').change(function(e) {
				console.log($(this).val());
				$('#name-file').text($(this).val());
				$('#name-file').fadeIn(400);
				$('.btn-speacial').fadeIn(600);
			    $('.btn-logotype-brand').fadeOut(200);
			});
		});

		$('.btn-speacial').click(function(event){
			$('#name-file').fadeOut(300);
			$('.btn-logotype-brand').fadeIn(200);
			$('.btn-speacial').hide();
			$('#file-input-special').val('');
		});
	</script>
@endsection