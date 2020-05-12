@extends('../layouts.app_dashboard_admin')

@section('content')
	<ul class="breadcrumb breadcrumb-home">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
		<li>Panel Administrativo</li>
		<li>Contraseña correo conjunto</li>
	</ul>

	<div class="container-fluid">
		<div class="row center-align">
			<div class="col-12 col-md-6">
				<h3>Contraseña del correo</h3>

				<div class="form-group">
					<label for="">Nueva contraseña</label>
					<input id="password" class="form-control" type="password">
				</div>

				<div class="form-group">
					<label for="">Confirme contraseña</label>
					<input id="confirm_password" class="form-control" type="password">
				</div>

				<button onclick="guardar();" class="btn btn-success">Guardar Cambios</button>

			</div>
		</div>
	</div>

@endsection
@section('ajax_crud')
	<script>
		function guardar(){
			if((confirm_password.value===password.value) && password.value != ''){
				//hacer el cambio de la contraseña
				$.ajax({
					type: "POST",
					url: "chagePasswordEmail",
					data: {
						'password': password.value,
						'confirm_password': confirm_password.value,
						'_token': $('[name=csrf-token]')[0].content
					}
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
							location.href = 'admin';
						});
					}else{
						swal('Error!',res.msg,'error');
					}
				});
			}else{
				swal('Error!','Las contraseñas no coinciden.','warning').then(()=>{
					confirm_password.value = '';
					password.value = '';
				});
			}
		}
	</script>
@endsection