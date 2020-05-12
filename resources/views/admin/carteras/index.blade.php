@extends('../layouts.app_dashboard_admin')
@section('title', 'Cuota Administrativa')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Cartera</li>
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
						<a target="_blanck" href="#">Video 1</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	{{-- lista de apartamentos --}}
	<form id="datos">
		@csrf
		<div class="row justify-content-center">
			<div class="col-1 col-md-2 text-center">
				<i class="fa fa-building-o"></i>
				<label class="margin-top">
					Unidad Privada
				</label>
			</div>
			<div class=" col-md-3">
				<select name="unidad_id" id="unidad_id" class="form-control select-2">
					<option value="">Seleccione...</option>
					@foreach($unidades as $unidad)
						<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre.' - '.$unidad->numero_letra }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-1 col-md-2 text-center">
				<i class="fa fa-user"></i>
				<label class="margin-top">
					Propietario
				</label>
			</div>
			<div class=" col-md-3">
				<select name="user_id" id="user_id" class="form-control select-2">
					<option value="">Seleccione...</option>
					@foreach($propietarios as $propietario)
						<option value="{{ $propietario->id }}">{{ $propietario->nombre_completo.' - '.$propietario->numero_cedula }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-1 col-md-2 text-center">
				<button onclick="cargar();" class="btn btn-success" type="button">Consultar</button>
			</div>
		</div>
		<br>
		<br>
		<div id="tabla" class="container-fluid">
	
		</div>
		
	
	</form>
</div>


	

@endsection
@section('ajax_crud')
	<script>
		$(document).ready(function() {
			$('#unidad_id').select2();
			$('#user_id').select2();
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
            });
            
		});


		const cargar = () => {
				//ocultar la tabla
			$('.table').fadeOut(30,()=>{
				$('#tabla').html('');
			});

			$('#cuentas').val('');

			//obtener los datos de las cuotas
			$.ajax({
				url: '{{ url("consultarCartera") }}',
				type: 'POST',
				data: new FormData(datos),
				processData: false,
				contentType: false,
				dataType: 'html',
			})
			.done(function(data) {
				
				$('#tabla').html(data);

				//agregar todas los detaller de una cartera
				// for (var i = 0; i < data.length; i++) {
				// 	agregarDetalle(data[i]);
				// }
				
				// Data table library
				// ******************
				$('.table').DataTable({
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

				//mostrar la tabla
				$('.table').fadeIn(300);
			})
			.fail(function(data) {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		}

		//funcion para agregar datos en la tabla
		// const agregarDetalle = (detalle) =>{

		// 	$('#datos').append(`<tr>
		// 					<td>${detalle.cuenta}</td>
		// 					<td>${detalle.fecha}</td>
		// 					<td>${detalle.user}</td>
		// 					<td>${detalle.movimiento}</td>
		// 					<td>$${new Intl.NumberFormat('COP').format(detalle.capital)}</td>
        //                     <td>$${new Intl.NumberFormat('COP').format(detalle.interes)}</td>
        //                     <td>
        //                         <a onclick="deleteData('${detalle.id}')" class="btn btn-default">
        //                             <i class="fa fa-trash"></i>
        //                         </a>
        //                     </td>
		// 				</tr>`);
		// }


		// const deleteData = (id) => {
		// 	swal(
		// 		{
		// 			title:'Advertencia!',
		// 			text:'¿Seguro de querer eliminar este registro?',
		// 			buttons: true,
		// 			icon: 'warning'
		// 		}
		// 	).then((res)=>{
		// 		if(res){
		// 			$.ajax({
		// 				type: "POST",
		// 				dataType: "json",
		// 				url: "../eliminarOperacionCartera/"+id,
		// 			}).done((res)=>{
		// 				if(res == 1){
		// 					swal('Logrado!','Operación eliminada correctamente','success').then(()=>{
		// 						cargar($('#id_tipo_unidad'));
		// 					});
		// 				}
		// 			}).fail((res)=>{
		// 				console.log(res);
		// 			});
		// 		}else{
		// 			swal('Aviso','Operación cancelada','info');
		// 		}
		// 	});
		// }


	</script>
@endsection
