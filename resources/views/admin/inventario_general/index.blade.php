@extends('../layouts.app_dashboard_admin')

@section('title', 'Inventario General')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Inventario General</li>
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
		Agregar Inventario
	</a>
	@include('admin.inventario_general.form')
	<br><br>
	<table id="inventarios-table" class="table">
		<thead>
			<th>Nombre</th>
			<th>Descripción</th>
			<th>Valor</th>
			<th>Condicion</th>
			<th>Acciones</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>


	
@endsection
@section('ajax_crud')
	<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
	<script>
		$(document).ready(function () {
			$('#valor_aux').maskMoney({precision:0});
		});
		
		var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.inventarios') }}",
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
		var table  = $('#inventarios-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'descripcion', name: 'descripcion'},
          		{ data: 'valor', name: 'valor'},
          		{ data: 'condicion', name: 'condicion'},
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


		//cambio de valor en garantia
        function garantiaF(){
            let valor = document.querySelector('#garantia').checked;
            if(valor){//mostrar
                $('#row_valido_hasta').removeClass('hide').fadeOut(0).fadeIn(300);
            }else{//ocultar
                $('#row_valido_hasta').fadeOut(300,()=>{
                    $('#row_valido_hasta').addClass('hide');
                });
            }
        }


		//mostrar el nombre de los archivos seleccionados
		function show_info_files(){
			archivos_load.innerText  = `Archivos \r\n`;

			for (let i = 0; i < fotos.files.length; i++) {
				archivos_load.innerText  += `${fotos.files[i].name} \r\n`;
			}

		}

		//mostrar un artículo del inventario
		function showArticulo(id){
			$.ajax({
				type: "GET",
				url: "{{ url('inventario') }}/"+id,
				dataType: "HTML",
				success: function (response) {
					$('#body_info_articulo').html(response);
					$('#info_articulo').modal('show');
					$('.show_img').click(function(e) {
						$('#show_image img')[0].src = $(e)[0].currentTarget.src;
						$('#show_image').css("display", "flex")
						.hide().fadeIn(400);
					});
				}
			});
		}

		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Inventario de Zona Común');

			$.ajax({
				url: "{{ url('inventario') }}" + "/" +id,
				type: 'GET',
				dataType: 'JSON',
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#nombre').val(data.nombre);
					$('#descripcion').val(data.descripcion);
					$('#valor').val(data.valor);
					$('#estado').val(data.estado);
					$('#id_acta').val(data.id_acta);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Este registro no existe", "error");
				}
			});
		}

		// Agregar registro
		// ****************

		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form form')[0].reset();
			$('#modal-form').modal('show');
			$('.modal-title').text('Agregar Inventario');
		}

		// Eliminar registro
		// *****************
		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este registro?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
	          	$.ajax({
					url: "{{ url('inventario') }}" + "/" + id,
					type: "POST",
					dataType : 'json',
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(data){
						if(data.res){
							swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
								.then((value) => {
									table.ajax.reload();
							});
						}
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

		// Evento Submit 
		// *************

		$('#modal-form form').on('submit', function(e){
			e.preventDefault();

			if(verificarFormulario('dataForm',5)){
				id = $('#id').val();
				if(save_method == "add") url = "{{ url('inventario') }}";
				else url = "{{ url('inventario') }}" + "/" + id;

				$.ajax({
					url: url,
					type: 'POST',
					contentType: false,
                    processData: false,
					data: new FormData(dataForm),
					success: function(data){
						// console.log(data);
						$('#modal-form').modal('hide');
						console.log(data);
						swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
								$('#modal-form').modal('hide');
								table.ajax.reload();
						});
					}, 
					error: function(){
						swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
					}
				});
			}
		});
	</script>
@endsection
