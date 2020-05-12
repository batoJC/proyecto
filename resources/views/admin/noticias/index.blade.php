@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Noticias</li>
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
	
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Noticia
	</a>
	@include('admin.noticias.form')
	<br><br>
	<table id="noticias-table" class="table table-stripped">
		<thead>
			<th>Titulo</th>
			<th>Descripción</th>
			<th>Autor de la noticia</th>
			<th>Fecha de creación</th>
			<th>Acciones</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
@endsection
@section('ajax_crud')
	<script>
		var actualizarTabla = (data,callback,settings) => {
			$.ajax({
				type: "GET",
				url: "{{ url('api.noticias_admin') }}",
				data: data,
				dataType: "json",
				success: function (response) {
				callback(
					response
				);
				$('[data-toggle="tooltip"]').tooltip({
					container: 'body'
				});
				}
			});
		}


		// Listar los registros
		// *************************************
		var table  = $('#noticias-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'titulo', name: 'titulo'},
          		{ data: 'descripcion', name: 'descripcion'},
          		{ data: 'nombre_completo', name: 'nombre_completo'},
          		{ data: 'created_at', name: 'created_at'},
          		{ data: 'action', name: 'action', orderable: false, searchable: false},
          	],
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
			$('.modal-title').text('Noticia');
			$('#send_form').hide();

			$.ajax({
				url: "{{ url('noticias') }}" + "/" + id, 
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#titulo').val(data.titulo);
					$('#descripcion').val(data.descripcion);
					// Visajes para que la imagen se vea
					$('#imgSalida').attr('src', "{{ asset('imgs/private_imgs') }}" + "/" +data.foto);
					$('#imgSalida').css({
						display: 'inline-block'
					});
					$('.btn-logotype-brand').hide();
					$('.btn-speacial').hide();
					// **********************************
					$('#id_user').val(data.id_user);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta noticia no existe", "error");
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
			$('.modal-title').text('Agregar Noticia');
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

		// Editar registro
		// ***************

		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Noticia');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');

			$.ajax({
				url: "{{ url('noticias') }}" + "/" + id, 
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#titulo').val(data.titulo);
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
					$('#id_user').val(data.id_user);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta noticia no existe", "error");
				}
			});	
		}

		// Eliminar registro
		// *****************

		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta noticia?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
	          	$.ajax({
					url: "{{ url('noticias') }}" + "/" + id,
					type: "POST",
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(){
						swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
							table.ajax.reload();
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

		// Si se hace submit sobre el formulario
		// *************************************
		$('#modal-form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();
			send_form.disabled = true;
			if(save_method == "add") url = "{{ url('noticias') }}";
			else url = "{{ url('noticias') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
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
						table.ajax.reload();
					});
					send_form.disabled = false;
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
					send_form.disabled = false;
				}
			});	
		});
	</script>
@endsection