@extends('../layouts.app_dashboard')

@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
                ADMIN SUPERIOR
            </div>
            <div class="col-1 col-md-12 text-right">
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
							<a target="_blanck" href="https://youtu.be/6Gxt2GJLYmM">Primeros pasos</a>
							<a target="_blanck" href="https://youtu.be/-2kx2r66Ofw">¿Cómo cambiar valor de variables del liquidador?</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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
			<h3><b>Asignar reglamento</b></h3>
			<h4>Debe de subir un reglamento de tratamientos de datos con una descripción y un archivo con el documento completo.</h4>
			<a onclick="openModalReglamento();" class="btn btn-success" href="#">Click aquí para cambiar o agregar</a>
		</div>
	</div>
	<br><br>
	<div class="container-fluid">
		<div style="padding:10px;" class="col-12 box-shadow">
			<h2 class="text-center">
				<b>Lista de variables para el liquidador de nómina</b>
			</h2>
			<br>
			<table class="table">
				<thead>
					<tr>
						<th class="text-center">Descripción</th>
						<th class="text-center">Valor</th>
						<th class="text-center">Editar</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($variablesLiquidador as $variable)
						<tr>
							<td class="text-center">{{ $variable->descripcion }}</td>
							<td class="text-center">{{ $variable->value }}</td>
							<td class="text-center">
								<i onclick="editarVariable('{{ $variable->name }}')" class="fa fa-pencil green"></i>
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
	
	<br>
	<br>
    </div>
@endsection
@section('ajax_crud')
    {{-- script para agregar o cambiar reglamento --}}
	<script>
		let actionReglamento = 'add';
		var csrf_token = $('meta[name="csrf-token"]').attr('content');


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
				url: "{{ url('reglamento.owner.show') }}",
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
				let ruta = (actionReglamento == 'add')? "{{ url('reglamento.owner.add') }}": "{{ url('reglamento.owner.edit') }}/"+id.value;
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


		var id_variable = 0;

		function editarVariable(id){
			id_variable = id;
			input = document.createElement("div");
			input.innerHTML = '<label class="label_alerta">Ingrese el nuevo valor para la variable:</label>';
			input.append(document.createElement("br"));
			valor = document.createElement("input");
			valor.id = 'valor';
			valor.className = 'form-control';
			valor.type = 'text'
			input.append(valor);
			valor.placeholder = "valor.";

			swal({
				title: "Nuevo valor.",
				content: input,
				buttons: true                       
			}).then((res)=>{
				if(res){
					valor = $('#valor');
					console.log(id_variable);
					$.ajax({
						type: "POST",
						url: "{{url('editarVariable')}}",
						data: {
							_token : csrf_token,
							id : id_variable,
							valor : valor.val()
						},
						dataType: "json",
						success: function (res) {
							if(res.res){
								swal('Logrado!',res.msg,'success').then(res=>{
									location.reload();
								});
							}else{
								swal('Error',res.msg,'error');
							}
						}
					});
				}
			});
		}

	</script>
@endsection