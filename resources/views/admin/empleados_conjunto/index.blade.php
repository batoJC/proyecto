@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('home') }}">Inicio</a>
				</li>
				  <li>Empleados</li>
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
		Agregar Empleado
	</a>
	
	@include('admin.empleados_conjunto.form')
	<br><br>
	<table id="empleados-table" class="table table-stripped">
		<thead>
			<tr>
				<th>Fecha ingreso</th>
				<th>Documento</th>
				<th>Foto</th>
				<th>Nombre completo</th>
				<th>Cargo</th>
				<th>Dirección</th>
				<th>Estado</th>
				<th>Fecha retiro</th>
				<th>Opciones</th>
			</tr>
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
                url: "{{ url('api.empleados_conjunto.admin') }}",
                data: data,
                dataType: "json",
                success: function (response) {
                    callback(
                        response
                    );
                    $('[data-toggle="tooltip"]').tooltip({
                        container: 'body'
					});
					let filas = $('tbody [role="row"]');
					for (let i = 0; i < filas.length; i++) {
						let data = JSON.parse(filas[i].cells[2].innerText);
						console.log(data);
						if(!(data[0] == null)){
							filas[i].cells[2].innerHTML =  `<img class="foto show_img" src="imgs/private_imgs/${data}" alt="Foto">`;
							$('.show_img').click(function(e) {
								$('#show_image img')[0].src = $(e)[0].currentTarget.src;
								$('#show_image').css("display", "flex").hide().fadeIn(400);
							});
						}else{
							filas[i].cells[2].innerText = 'No hay foto del empleado';
						}
					}
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#empleados-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha_ingreso', name: 'fecha_ingreso'},
          		{ data: 'cedula', name: 'cedula'},
          		{ data: 'foto', name: 'foto', orderable: false, searchable: false},
          		{ data: 'nombre_completo', name: 'nombre_completo'},
          		{ data: 'cargo', name: 'cargo'},
          		{ data: 'direccion', name: 'direccion'},
          		{ data: 'estado', name: 'estado'},
          		{ data: 'fecha_retiro', name: 'fecha_retiro'},
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

		var action = 'add';

		//mostrar el nombre del archivo
		function fileNameChange(){
			document.querySelector('#fileName').innerText = 'Nombre del archivo de la foto: '+document.querySelector('#foto').files[0].name;
		}

		// Agregar registro 
        // ---------------
        function addForm(){
			action = 'add';
            $('#empleados').modal('show');
            $('#dataEmpleado')[0].reset();
			$('input[name="_method"]').val('POST');
			$('#divArchivo').show();
			$('#alerta').hide();
        }

		//Mostrar info para editar de un registro
		//---------------------------------------
		function showEdit(id){
			$.ajax({
				type: "GET",
				url: "{{url('empleados_conjunto')}}/"+id,
				dataType: "json",
				success: function (data) {
					action = 'edit';
					$('#empleados').modal('show');
					$('#dataEmpleado')[0].reset();
					$('input[name="_method"]').val('PUT');
					$('#divArchivo').show();
					$('#alerta').show();

					//cargar datos
					$('#fecha_ingreso').val(data.fecha_ingreso);
					$('#id').val(data.id);
					$('#nombre_completo').val(data.nombre_completo);
					$('#cedula').val(data.cedula);
					$('#direccion').val(data.direccion);
					$('#cargo').val(data.cargo);
				}
			});
        }

		function guardarEmpleado(){
			if(verificarFormulario('dataEmpleado',5)){
				let id = $('#id').val();
				let url = (action == 'add')? '{{url('empleados_conjunto')}}' : '{{url('empleados_conjunto')}}/'+id;

				$.ajax({
					type: "POST",
					url: url,
					dataType: "json",
					data : new FormData($('#dataEmpleado')[0]),
					contentType: false,
					processData: false,
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
            				$('#empleados').modal('hide');
							table.ajax.reload();
						});
					}else{
						swal('Error!',res.msg,'error');	
					}
				}).fail((res)=>{
					swal('Error!','Ocurrió un error en el servidor','error');
				});
			}
		}

		var fecha_retiro;
		var input;
		var id_empleado;
		//retirar un empleado
		function retirar(id){
			id_empleado = id;
			input = document.createElement("div");
            input.innerHTML = '<label class="label_alerta">Ingrese la fecha de retiro:</label>';
            input.append(document.createElement("br"));
            fecha_retiro = document.createElement("input");
            fecha_retiro.id = 'fecha_retiro';
            fecha_retiro.type = 'date';
            fecha_retiro.className = 'form-control';
            input.append(fecha_retiro);


            swal({
                title: "Ingrese la fecha de retiro del empleado.",
                content: input,
                buttons: {
                    cancel: {
                        text: "Cancelar",
                        value: false,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Guardar retiro",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }      
                }         
            }).then((res)=>{
				if(res){
					if(fecha_retiro != ''){
						$.ajax({
							type: "POST",
							url: "{{url('retiroEmpleadoConjunto')}}/"+id_empleado,
							data: {
								_token :  $('meta[name="csrf-token"]').attr('content'),
								fecha_retiro : fecha_retiro.value
							},
							dataType: 'json',
							success: function (response) {
								if(response.res){
									swal('Logrado!',response.msg,'success').then(res=>{
										table.ajax.reload();
									});
								}else{
									swal('Error!',response.msg, 'error');
								}
							}
						}).fail(res=>{
							swal('Error!','0currió un error el servidor','error');
						});
					}else{
						swal('Error!','Debe de ingresar una fecha de retiro.','error').then(res=>{
							retirar(id_empleado);
						})
					}
				}
			})
		}
		
		// Eliminar registro
		// *****************
		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este empleado?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
	          	$.ajax({
					url: "{{ url('empleados_conjunto') }}" + "/" + id,
					type: "POST",
					dataType: 'json',
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(data){
						if(data.res == 1){
							swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
								.then((value) => {
									table.ajax.reload();
							});
						}else{
							swal("Error!", data.msg, "error");
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


    </script>
@endsection