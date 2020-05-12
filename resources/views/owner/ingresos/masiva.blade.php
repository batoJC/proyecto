@extends('../layouts.app_dashboard')

@section('title', 'Ingresos')

@section('content')
	<div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
            	<ul class="breadcrumb">
					<li>
						<a href="{{ asset('owner') }}">Inicio</a>
					</li>
				  	<li>
				  		<a href="{{ asset('ingresos_oficina') }}">Ingresos Oficina</a>
				  	</li>
				  	<li> Carga Masiva de Ingresos </li>
				</ul>
				<p class="carga-masiva">
					Recuerde que para subir usuario masivamente debe de respetar la estructura
					de la base de datos y el orden de los datos en la tabla <br>de ingresos por lo tanto, 
					te invitamos a usar el archivo base para saber como debes subir los archivos para que no
					ocurran errores.
				</p>
				<p class="carga-masiva">¿No sabes dónde está el archivo base? <br>Puedes descargarlo acá: &nbsp; 
					<a href="{{ url('download_ingresos') }}" target="_blank">
				        <i class="fa fa-file-archive-o"></i>
				        &nbsp; Archivo Base de Ingresos
				    </a>
				</p>
				<br>
				<h4>Tipos de unidades Vinculadas al {{ $conjunto->nombre }}</h4>
				<br>
				<table class="table datatable" id="tipos_unidad_table">
					<thead>
						<tr>
							<th>Id</th>
							<th>Tipo De Unidad</th>
							<th>Numero / Letra</th>
							<th>Dueño</th>
							<th>Inquilino</th>
						</tr>
					</thead>
					<tbody>
						@foreach($tipo_unidad as $tipo_un)
							<tr>
								<td>{{ $tipo_un->id }}</td>
								<td>{{ $tipo_un->tipo_unidad }}</td>
								<td>{{ $tipo_un->numero_letra }}</td>
								<td>{{ $tipo_un->dueno->nombre_completo }}</td>
								<td>
									@if($tipo_un->id_inquilino == NULL)
										No aplica
									@else
										{{ $tipo_un->inquilino->nombre_completo }}
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<br>
				<h4>Formulario de envio masivo</h4>
				<br>
				<form action="{{ url('ingresos_csv_post') }}" class="col-md-5 form-masiva-ingresos" method="post" enctype="multipart/form-data">
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
	                {{-- Cada campo --}}
					<div class="row">
						<div class="col-md-5 error-validate-file">
							<i class="fa fa-file"></i>
							<label class="margin-top">
								&nbsp; Archivo (Extensión .CSV)
							</label>
						</div>
						<div class="col-md-7">
							<button type="button" class="btn btn-logotype-brand">
								<i class="fa fa-plus icon-plus-brand-logo"></i>
							</button>
							<input type="file" name="archivo" id="file-input-special" class="upload hidden">
							<input type="hidden" value="{{ $conjunto->id }}" name="id_conjunto" class="hidden">
							<p id="name-file"></p>
							<button type="button" class="btn btn-default btn-speacial" style="display:none;">
								Quitar el Archivo&nbsp;
								<i class="fa fa-trash"></i>
							</button>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6 text-center">
							<button type="button" class="btn btn-success" id="send_form">
			    				<i class="fa fa-send"></i>
			    				&nbsp; Cargar Ingresos
			    			</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('ajax_crud')
	<script>
        // Select 2 
        // ********
        $('.select-22222').select2();

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
