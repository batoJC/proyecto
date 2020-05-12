@extends('../layouts.app_dashboard_dueno')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('dueno') }}">Inicio</a>
		</li>
	  	<li>Mascotas</li>
	</ul>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Mascota
	</a>
	@include('dueno.mascotas.form')
	<br><br>
	<table class="table table-stripped datatable">
		<thead>
			<th>Tipo</th>
			<th>Nombre</th>
			<th>Dueño</th>
			<th>Tipo de unidad</th>
			<th>Acciones</th>
		</thead>
		<tbody>
			@foreach($mascotas as $masco)
				<tr>
					<td>{{ $masco->tipo }}</td>
					<td>{{ $masco->nombre }}</td>
					<td>{{ $masco->user->nombre_completo }}</td>
					<td>{{ $masco->tipo_unidad->tipo_unidad.' - '.$masco->tipo_unidad->numero_letra }}</td>
					<td>
						<a onclick="showForm('{{ $masco->id }}')" class="btn btn-default">
							<i class="fa fa-search"></i>
						</a>
						<a onclick="editForm('{{ $masco->id }}')" class="btn btn-default">
							<i class="fa fa-pencil"></i>
						</a>
						<a onclick="deleteData('{{ $masco->id }}')" class="btn btn-default">
							<i class="fa fa-trash"></i>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
@section('ajax_crud')
	<script>
		// Eventos para el logo de la brand.
		// **********************************

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

		// Mostrar registro
		// ****************

		function showForm(id){
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Mascota');
			$('#send_form').hide();

			$.ajax({
				url: "{{ url('mascotas') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#id').val(data.id);
					$('#tipo').val(data.tipo);
					$('#nombre').val(data.nombre);
					$('#descripcion').val(data.descripcion);
					// Visajes para que la imagen se vea
					$('#imgSalida').attr('src', "{{ asset('imgs/private_imgs') }}" + "/" +data.foto);
					$('#imgSalida').css({
						display: 'inline-block'
					});
					$('.btn-logotype-brand').hide();
					$('.btn-speacial').hide();
					// **********************************
					$('#id_tipo_unidad').val(data.id_tipo_unidad);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta Queja/Reclamo no existe", "error");
				}
			});
		}

		// Agregar registro
		// ****************

		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Mascota');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');
			// Visajes reset campos
			$('#imgSalida').attr('src', '');
			$('#imgSalida').css({
				display: 'none'
			});
			$('.btn-logotype-brand').show();
			$('.btn-speacial').hide();
			// **********************************
		}

		// Editar registro (Que visaje)
		// ***************************

		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Mascota');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');

			$.ajax({
				url: "{{ url('mascotas') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#tipo').val(data.tipo);
					$('#nombre').val(data.nombre);
					$('#descripcion').val(data.descripcion);
					// Imagen
					// *******
					$('#imgSalida').attr('src', "{{ asset('imgs/private_imgs') }}" + "/" +data.foto);
					$('#imgSalida').css({
						display: 'inline-block'
					});
					$('.btn-logotype-brand').hide();
					$('.btn-speacial').show();
					// *******
					$('#id_tipo_unidad').val(data.id_tipo_unidad);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta Mascota no existe", "error");
				}
			});
		}

		// Eliminar registro
		// *****************

		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta mascota?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
	          	$.ajax({
					url: "{{ url('mascotas') }}" + "/" + id,
					type: "POST",
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(){
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
		}

		// Cuando se haga submit
		// ********************

		$('#modal-form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();
			if(save_method == "add") url = "{{ url('mascotas') }}";
			else url = "{{ url('mascotas') }}" + "/" + id;

			$.ajax({
				url: url,
				type: "POST",
				// Envio de formulario en objeto MUY OP
				// ************************************
				data: new FormData($('#modal-form form')[0]),
				contentType: false,
				processData: false,
				// ************************************
				success: function(data){
					$('#modal-form').modal('hide');
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

	</script>
@endsection