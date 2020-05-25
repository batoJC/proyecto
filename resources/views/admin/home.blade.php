@extends('../layouts.app_dashboard_admin')
<style>
	.dinero{
		font-size: 100px;
	}

	.hover-green:hover{
		color: #1ABB9C;
		transition:0.3s;
	}

</style>
@section('content')
	<div class="row padding-row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb breadcrumb-home">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Panel Administrativo</li>
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
						<a target="_blanck" href="https://youtu.be/luHxmyKi9lo">Primeros pasos</a>
						<a target="_blanck" href="https://youtu.be/SuMPsPGTG_U">¿Cómo configurar el correo del conjunto?</a>
						<a target="_blanck" href="https://youtu.be/QlkRdLoz8k4">Módulo administrativo</a>
						<a target="_blanck" href="https://youtu.be/PHioOQlS5N4">Módulo financiero</a>
					</li>
				</ul>
			</div>
		</div>
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
					</div>
					<div class="modal-body">
						<div class="col-md-5 no-padding logo-background-terminos"></div>
						<div class="col-md-7 textos-terminos">
							<h4 class="text-center terminos-title">
								Gestion Copropietarios
							</h4>
							{{ $reglamento->descripcion }}
							<br>
							<a href="{{ asset('reglamentos/'.$reglamento->archivo) }}" target="_blank" class="link">
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
			{{-- {{ die() }} --}}
		@else
			<div class="col-md-12 stats-adm box-shadow">
				{{-- Titulo --}}
				<h4 class="text-center">Quejas, Reclamos, Sugerencias y Peticiones</h4>
				{{-- Divisiones hijas... --}}
				<div class="col-md-2 no-padding">
					<div class="col-md-4 no-padding">
						<i class="fa fa-standard fa-standard-1 fa-commenting-o"></i>
					</div>
					<div class="col-md-5 no-padding text-center line-height-50 quejas-text">
						A vencer:
					</div>
					<div class="col-md-3 no-padding text-center line-height-50 quejas-count">
						{{ count($quejas_reclamos_ven) }}
					</div>
				</div>
				<div class="col-md-2 no-padding">
					<div class="col-md-4 no-padding">
						<i class="fa fa-standard fa-standard-2 fa-commenting-o"></i>
					</div>
					<div class="col-md-5 no-padding text-center line-height-50 quejas-text">
						Pendientes:
					</div>
					<div class="col-md-3 no-padding text-center line-height-50 quejas-count">
						{{ count($quejas_reclamos_pen) }}
					</div>
					{{-- Quejas/Reclamos pendientes: {{ count($quejas_reclamos_pen) }} --}}
				</div>
				<div class="col-md-2 no-padding">
					<div class="col-md-4 no-padding">
						<i class="fa fa-standard fa-standard-3 fa-commenting-o"></i>
					</div>
					<div class="col-md-5 no-padding text-center line-height-50 quejas-text">
						Resueltas:
					</div>
					<div class="col-md-3 no-padding text-center line-height-50 quejas-count">
						{{ count($quejas_reclamos_res) }}
					</div>
				</div>
				<div class="col-md-2 no-padding">
					<div class="col-md-4 no-padding">
						<i class="fa fa-standard fa-standard-4 fa-commenting-o"></i>
					</div>
					<div class="col-md-5 no-padding text-center line-height-50 font-14 quejas-text">
						En proceso:
					</div>
					<div class="col-md-3 no-padding text-center line-height-50 quejas-count">
						{{ count($quejas_reclamos_pro) }}
					</div>
				</div>
				<div class="col-md-2 no-padding">
					<div class="col-md-4 no-padding">
						<i class="fa fa-standard fa-standard-5 fa-commenting-o"></i>
					</div>
					<div class="col-md-5 no-padding text-center line-height-50 quejas-text">
						Cerradas: 
					</div>
					<div class="col-md-3 no-padding text-center line-height-50 quejas-count">
						{{ count($quejas_reclamos_cer) }}
					</div>
				</div>
				<div class="col-md-2">
					<div class="col-md-4 no-padding">
						<i class="fa fa-standard fa-standard-6 fa-commenting-o"></i>
					</div>
					<div class="col-md-5 no-padding text-center line-height-50 quejas-text">
						Totalidad: 
					</div>
					<div class="col-md-3 no-padding text-center line-height-50 quejas-count">
						{{ count($quejas_reclamos_all) }}
					</div>
				</div>
			</div>
			<div class="col-md-3 logo-adm box-shadow">
				{{-- Titulo --}}
				<h4 class="text-center">Logo del conjunto</h4>
				<div class="container-logo-adm text-center">
					{{-- Condicional del logo --}}
					@if($conjuntos->logo != null)
						<div class="background-logo-adm" style="background: url('{{ asset('imgs/logos_conjuntos/'.$conjuntos->logo) }}') no-repeat center center; background-size: 90%;">
						</div>
						<form method="post" id="form-logo-adm-delete">
							@csrf
							<button class="btn btn-default btn-gestion-adm">
								<i class="fa fa-trash"></i>
							</button>
						</form>
					@else
						<button class="btn btn-default btn-logotype-brand btn-plus-gu">
							<i class="fa fa-plus"></i>
						</button>
						<form method="post" id="form-logo-adm" enctype="multipart/form-data">
							@csrf
							<input type="file" name="foto" id="file-input" class="upload hidden">
							<img id="imgSalida" class="text-center" alt="Image..." />
							<button type="submit" class="btn btn-success btn-default btn-speacial-2 btn-upload-margin">
								Guardar
							</button>
							<button type="button" class="btn btn-danger-custom btn-default btn-speacial btn-upload-margin">
								Quitar
							</button>
						</form>
					@endif
				</div>
			</div>
			<div class="col-md-offset-1 col-md-8 other-stats-adm box-shadow text-center">
				<br>
				<h2><b>Saldo actual:</b></h2>
				<h3><span  class="@if ($saldoActual < 0)
					red
				@else
					green
				@endif dinero"> ${{ number_format($saldoActual) }}<span></h3>
				<br>
				<br>
				<h3><a class="hover-green" href="{{ url('flujo_efectivo') }}">Ver flujo efectivo <i class="fa fa-eye"></i></a></h3>
			</div>
		@endif
	</div>
	@if (Auth::user()->habeas_data == 'Acepto')
		<br>
		<br>
		{{-- Modal para agregar reglamento --}}
		<div class="modal fade" id="modal_reglamento" tabindex="-1" role="dialog" data-backdrop="static">
			<div class="modal-dialog" role="document">
				<div class="modal-content modal-padding">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">x</span>
						</button>
						<h4 class="text-center modal-title">
							Reglamento para política de datos
						</h4>
					</div>
					<div class="modal-body">
						<form method="post" id="form_reglamento" onsubmit="return false;" enctype="multipart/form-data">
							@csrf {{ method_field('POST') }}
							<input type="hidden" name="id" id="id">
							{{-- Cada campo --}}
							<div class="row">
								<div class="col-md-4 validate-label-1">
									<i class="fa fa-bank"></i>
									<label class="margin-top">
										Descripción
									</label>
								</div>
								<div class="col-md-8">
									<textarea class="form-control validate-input-1" name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
								</div>
							</div>
							<br>
							<div class="row" id="div_agregar_file">
								<div class="col-md-4">
									<i class="fa fa-file-o"></i>
									<label class="margin-top">
										Archivo
									</label>
								</div>
								<div class="col-md-8">
									<style>
										#archivo_agregar{
											display: none;
										}
									</style>
									<label class="btn btn-primary" for="archivo_agregar"><i class="fa fa-plus"></i> Seleccionar archivo</label>
									<input type="file" onchange="nombreArchivoAgregar();" name="archivo_agregar" id="archivo_agregar" accept="application/pdf">
									<label id="name_file_reglamento_agregar" for="">Archivo seleccionado</label>
								</div>
							</div>
							<div class="row hide" id="div_editar_file">
								<div class="col-md-4">
									<i class="fa fa-file-o"></i>
									<label class="margin-top">
										Archivo
									</label>
								</div>
								<div class="col-md-8 text-center">
									<style>
										#archivo_editar{
											display: none;
										}
									</style>
									<label class="btn btn-primary" for="archivo_editar"><i class="fa fa-exchange"></i> Cambiar archivo</label>
									<input type="file" onchange="nombreArchivoEditar();" name="archivo_editar" id="archivo_editar" accept="application/pdf">
									<label id="name_file_reglamento_editar" for="">Archivo seleccionado</label>
									<br>
									<a target="_blanck" id="a_file_reglamento" href=""><i class="fa fa-eye"></i> Ver archivo</a>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12 text-center">
									<button onclick="return guardarReglamento();" type="button" class="btn btn-success" id="send_form">
										<i class="fa fa-send"></i>
										&nbsp; Enviar
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div style="padding:10px;" class="col-12 box-shadow">
				<h3><b>Asignar reglamento del conjunto</b></h3>
				<h4>Debe de subir un reglamento de tratamientos de su conjunto con una descripción y un archivo con el documento completo.</h4>
				<a onclick="openModalReglamento();" class="btn btn-success" href="#">Click aquí para cambiar o agregar</a>
			</div>
		</div>
		{{-- script para agregar o cambiar reglamento --}}
		<script>
			let actionReglamento = 'add';

			function nombreArchivoAgregar(){
				name_file_reglamento_agregar.innerText = archivo_agregar.files[0].name;
			}

			function nombreArchivoEditar(){
				name_file_reglamento_editar.innerText = archivo_editar.files[0].name;
			}

			function openModalReglamento(){
				name_file_reglamento_agregar.innerText = 'Nombre archivo';
				name_file_reglamento_editar.innerText = 'Nombre archivo';
				$('#form_reglamento').trigger('reset');
				$.ajax({
					type: "POST",
					url: "{{ url('reglamento.show') }}",
					data: {
						_token: csrf_token
					},
					dataType: "json",
					success: function (response) {
						response = response[0];
						actionReglamento = 'add';
						$('#div_agregar_file').removeClass('hide');
						$('#div_editar_file').addClass('hide');
						if(response){
							//agregar la información al modal
							actionReglamento = 'edit';
							id.value = response.id;
							descripcion.value = response.descripcion;
							a_file_reglamento.href = '{{ asset('reglamentos') }}/'+response.archivo
							$('#div_agregar_file').addClass('hide');
							$('#div_editar_file').removeClass('hide');
						}
						$('#modal_reglamento').modal('show');
					}
				});
			}

			function guardarReglamento(){
				if(verificarFormulario('form_reglamento',1)){
					let ruta = (actionReglamento == 'add')? "{{ url('reglamento.add') }}": "{{ url('reglamento.edit') }}/"+id.value;
					$.ajax({
						type: "POST",
						url: ruta,
						data: new FormData(form_reglamento),
						dataType: "json",
						processData: false,
						contentType: false,
						success: function (response) {
							if(response.res){
								swal('Logrado!',response.msg,'success').then(res=>{
									$('#modal_reglamento').modal('hide');
								});
							}else{
								swal('Error!',response.msg,'error');
							}
						}
					}).fail(res=>{
						swal('Error!','ocurrió un error en el servidor','error');
					});
				}
				return false;
			}

		</script>
		<br>
		<br>
		<br>
		<div class="container-fluid">
			<div style="padding:10px;" class="col-12 box-shadow">
				<h3><b>Contraseña correo</b></h3>
				<h4>Para el envió de correos es necesario que configure un correo de gmail habilitando la opción que permite el envió de correos desde otra plataforma, además debe de proporcionar la clave para que se pueda realizar dicha operación, tenga en cuenta que su contraseña quedara guardada de forma segura en nuestra plataforma.</h4>
				<a class="btn btn-success" href="{{ url('passwordEmail') }}">Click aquí para cambiar o asignar la contraseña</a>
			</div>
		</div>
		<br>
		@if ($conjuntos->password != null)
			<br>
			<div class="container-fluid">
				<div style="padding:10px;" class="col-12 box-shadow">
					<h3><b>Probar envio de correos. </b></h3>
					<h4>Ahora puedes probar el envio de correo electrónico, para esto solo debes de ingresar un correo y esperar el correo de prueba.</h4>
					<label for="email">Correo</label>
					<input class="form-control" id="email" type="email" autocomplete="off">
					<br>
					<button onclick="enviarCorreo();" class="btn btn-success" href="#">Click aquí para enviar un correo de prueba</button>
				</div>
			</div>
			<br>
			<br>
		@endif
		<br>

	{{-- Modal para agregar cuentas bancarias  --}}
	<div id="modal-cuenta-bancaria" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" id="myModalLabel2">Agregar cuenta bancaria</h4>
			</div>
			<div class="modal-body">
				<form id='data-cuenta-bancaria'>
					@csrf
					<div class="form-group">
						<label class="validate-label-1" for="banco">Banco</label>
						<input id="banco" name="banco" class="form-control validate-input-1" type="text">
					</div>
					<div class="form-group">
						<label for="nro_cuenta" class="validate-label-2">Número de cuenta</label>
						<input id="nro_cuenta" name="nro_cuenta" class="form-control validate-input-2" type="text">
					</div>
					<div class="form-group">
						<label for="tipo" class="validate-label-3">Tipo de cuenta</label>
						<input id="tipo" name="tipo" class="form-control validate-input-3" type="text">
					</div>
				</form>
			</div>
			<div class="modal-footer">
			<button onclick="guardarCuentaBancaria();" type="button" class="btn btn-primary">Guardar</button>
			</div>
			</div>
		</div>
	</div>
			<div class="container-fluid">
				<div style="padding:10px;" class="col-12 box-shadow">
					<h3><b>Cuentas bancarias del conjunto. </b></h3>
					<button onclick="mostrarModal('cuenta-bancaria')" class="btn btn-success"><i class="fa fa-plus"></i>  Agregar</button>
					<table class="table">
						<thead>
							<tr>
								<th>Banco</th>
								<th>Nro Cuenta</th>
								<th>Tipo</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($cuentas_bancarias as $cuenta)
								<tr>
									<td>{{ $cuenta->banco }}</td>
									<td>{{ $cuenta->nro_cuenta }}</td>
									<td>{{ $cuenta->tipo }}</td>
									<td>
										<a arget="_blanck" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-default" onclick="eliminarCuentaBancaria({{ $cuenta->id }})">
											<i class="fa fa-trash"></i>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<br>
			<br>
		<br>
		<div class="container-fluid">
			<div style="padding:10px;" class="col-12 box-shadow">
				<h3><b>Archivos base CSV </b></h3>
				<h4>Este es el listado de todos los archivos base CSV, para las cargas masivas del sistema.</h4>
				<table class="table">
					<thead>
						<tr>
							<th>Archivo</th>
							<th>Descargar</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>CSV carga de Usuarios</td>
							<td>
							<a download href="{{ url('download_users') }}" target="_blanck" data-toggle="tooltip" data-placement="top" title="Descargar" class="btn btn-default">
									<i class="fa fa-download"></i>
								</a></td>
						</tr>
						<tr>
							<td>CSV carga de presupuestos</td>
							<td>
								<a download href="{{ url('download_presupuesto') }}" target="_blanck"  data-toggle="tooltip" data-placement="top" title="Descargar" class="btn btn-default">
									<i class="fa fa-download"></i>
								</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<br>
		<br>
		<br>
		
	@endif


@endsection
@section('ajax_crud')
	
	<script>

		var csrf_token = $('meta[name="csrf-token"]').attr('content');


		//funciones para agrega cuentas bancarias
		function guardarCuentaBancaria(){
			if(verificarFormulario('data-cuenta-bancaria',3)){
				$.ajax({
					type: "POST",
					url: "{{ url('cuentasBancarias') }}",
					contentType : false,
					processData: false,
					data: new FormData($('#data-cuenta-bancaria')[0]),
					dataType: "json"
				}).done(res => {
					if(res.res){
						swal('Logrado!',res.msg,'success').then(res=>{
							location.reload();
						});
					}else{
						swal('Error!',res.msg,'error');
					}
				});
			}
		}

		function mostrarModal(modal){
			$(`#modal-${modal}`).modal('show');
            $(`#data-${modal}`)[0].reset();
		}

		function eliminarCuentaBancaria(id){
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta cuenta bancaria?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{ url('cuentasBancarias') }}" + "/" + id,
                    type: "POST",
                    data: {
                          '_method': 'DELETE',
                          '_token' : csrf_token,
                    }, 
                    success: function(e){
                        if(e.res){
                            swal('Logrado!',e.msg,'success').then(()=>{
                                window.location.reload();
                            });
                        }else{
                            swal('Error!',e.msg,'error');
                        }
                    },
                    error: function(data){
                        swal('Error!','Ocurrió un error en el servidor','error');
                    }
                  });
              } else {
                swal("Operación cancelada", "Lo sentimos, vuelve a intentarlo", "error");
              }
            });
		}


		
		// Validacion modal habeas data
		// ****************************
		
		habeas_data = '{{ Auth::user()->habeas_data }}';

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

		// Cargue temporal de la imagen
		// ****************************

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
			    
			    	if (!file.type.match(imageType))
			    	return;
			  
			    	var reader = new FileReader();
			    	reader.onload = fileOnload;
			    	reader.readAsDataURL(file);
			    }
			  
			    function fileOnload(e) {
			    	var result=e.target.result;
			    	$('#imgSalida').attr("src", result);
			    	$('#imgSalida').fadeIn(600);
			    	$('.btn-speacial').fadeIn(600);
			    	$('.btn-speacial-2').fadeIn(600);
			    	$('.btn-logotype-brand').fadeOut(100);
			    }
			});
		});

		$('.btn-speacial').click(function(event){
			$('#imgSalida').fadeOut(100);
			$('.btn-logotype-brand').fadeIn(200);
			$('.btn-speacial').hide();
			$('.btn-speacial-2').hide();
			$('#file-input').val('');
		});

		// Submit del form
		// ***************

		$('#form-logo-adm').on('submit', function(e){
			e.preventDefault();
			
			$.ajax({
				url: '{{ url('logo_conjunto_store') }}',
				type: 'POST',
				// Envio de form con objeto
				// ************************
				data: new FormData($('#form-logo-adm')[0]),
				contentType: false,
				processData: false,
				// ************************
				success: function(data){
					// console.log(data);
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
						location.reload();
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});

		// Formulario de eliminar el logo
		// ******************************

		$('#form-logo-adm-delete').on('submit', function(e){
			e.preventDefault();

			csrf_token  = $('meta[name="csrf-token"]').attr('content');

			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este logo?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
					url : '{{ url('logo_conjunto_delete') }}',
					type: 'POST',
					data: {
						'_token' : csrf_token,
						id_conjunto : "{{ $conjuntos->id }}",
					},
					success: function(data){
                        swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
                            .then((value) => {
                            location.reload();
                        });
                    },
                    error: function(){
                      swal("¡Opps! Ocurrió un error", {
                          icon: "error",
                        });
                    }
				});
              } else {
                swal("Operación cancelada", "Lo sentimos, vuelve a intentarlo", "error");
              }
            });
		});
	</script>
	@if ($conjuntos->password != null)
		<script>
			
			function enviarCorreo(){
				if(email.value != ''){
					$.ajax({
						type: "POST",
						url: "sendEmailPrueba",
						data: {
							'correo': email.value,
							'_token': $('[name=csrf-token]')[0].content
						}
					}).done((res)=>{
						if(res.res){
							swal('Logrado!',res.msg,'success');
						}else{
							swal('Error!',res.msg,'error');
						}
					});
				}else{
					swal('Error!','Debe de ingresar una dirección de correo electrónico.','warning').then(()=>{
						email.focus();
					});
				}
			}
			
		</script>
	@endif
@endsection