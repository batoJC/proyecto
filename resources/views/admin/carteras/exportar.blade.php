@extends('../layouts.app_dashboard_admin')
@section('title', 'Cuota Administrativa')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
	  	<li>Exportar Comprobante</li>
	</ul>
		@csrf
		<div class="row">
			<div class="col-12 col-md-4">
				<label for="prefijo">Prefijo</label>
				<select class="form-control" name="prefijo" id="prefijo">
					<option value="">Seleccione el prefijo del consecutivo</option>
					@foreach($consecutivos as $consecutivo)
						<option value="{{ $consecutivo->prefijo }}">{{ $consecutivo->prefijo }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-12 col-md-4">
				<label for="">Número</label>
				<input class="form-control" id="numero" type="number">
			</div>
			<div class="col-12 col-md-4 text-center">
				<br>
				<input onclick="cargar();" class="btn btn-success" type="button" value="Mostrar">
			</div>
		</div>
		<br><br>
        <div id="tabla">

        </div>
		

@endsection
@section('ajax_crud')
	<script>
		$(document).ready(function() {
			$('#id_tipo_unidad').select2();
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
            });
            
		});


		const cargar = () => {
			console.log('tree');
			let prefijo = $('#prefijo').val();
			let numero = $('#numero').val();
			if (prefijo != "" && numero != "") {
				//ocultar la tabla
				$('.table').fadeOut(30,()=>{
					$('#datos').html('');
				});

				$('#cuentas').val('');

				//obtener los datos de las cuotas
				$.ajax({
					url: 'detalleCuenta/'+prefijo+'/'+numero,
					type: 'POST',
					dataType: 'json',
				})
				.done(function(data) {
                    console.log(data);
                    
                    $('#tabla').html(`
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cuenta</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Movimiento</th>
                                <th>Capital</th>
                                <th>Interes</th>
                            </tr>
                        </thead>
                        <tbody id="datos">
                        </tbody>
                    </table>
                    `);

					//agregar todas los detalles de una cartera
					for (var i = 0; i < data.length; i++) {
						agregarDetalle(data[i]);
					}
					
					if (data.length > 0){
						$('#tabla').append(`
						<div class="row">
							<div class="col-12 text-center">
								<a class="btn btn-success" href="../pdfCombrante/${prefijo}/${numero}" target="_blank">Generar PDF</a>
							</div>
						</div>
						`);
					}

					//mostrar la tabla
					$('.table').fadeIn(300);
				})
				.fail(function(data) {
					console.log(data);
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
			}else{
				swal('Error!','Debes de ingresar el prefijo y el número','error');
			}
		}

		//funcion para agregar datos en la tabla
		const agregarDetalle = (detalle) =>{

			$('#datos').append(`<tr>
							<td>${detalle.cuenta}</td>
							<td>${detalle.fecha}</td>
							<td>${detalle.user}</td>
							<td>${detalle.movimiento}</td>
							<td>$${detalle.capital}</td>
                            <td>$${detalle.interes}</td>
						</tr>`);
		}


	</script>
@endsection
