@extends('../layouts.app_dashboard_admin')

@section('title', 'Mascotas')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Mascotas</li>
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
		Agregar Mascota
	</a>
	@include('admin.unidades.modalInfo')
	
	@include('admin.mascotas.form')
	<br><br>
	<table id="mascotas-table" class="table table-stripped">
		<thead>
			<th>Unidad</th>
			<th>Código</th>
			<th>Foto</th>
			<th>Tipo</th>
			<th>Nombre</th>
			<th>Estado</th>
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
                url: "{{ url('api.mascotas.admin') }}",
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
						if(data != null){
							filas[i].cells[2].innerHTML =  `<img class="foto show_img" src="imgs/private_imgs/${data}" alt="Foto">`;
							$('.show_img').click(function(e) {
								$('#show_image img')[0].src = $(e)[0].currentTarget.src;
								$('#show_image').css("display", "flex")
							.hide().fadeIn(400);
							});
						}else{
							filas[i].cells[2].innerText = 'No tiene';
						}
					}
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#mascotas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'codigo', name: 'codigo'},
          		{ data: 'foto', name: 'foto', orderable: false, searchable: false},
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'estado', name: 'estado'},
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


		//mostrar el nombre de la imagen seleccionada
		function changeFile(){
			let label = document.querySelector('#filename');
			label.innerHTML = 'Nombre del archivo: ' + document.querySelector('#foto').files[0].name;
		}

		//Mostrar el modal para insertar tipos de mascotas
		/***************************************************/
		function checkTipo(e){
			let selected = $(e).val();
			if(selected == 'otro'){
				$('#modalAddTipoMascota').modal('show');
				$('#mascotas').modal('hide');
			}
		}

		//Guardar el tipo de mascotas
		/****************************/
		$('#formTipo').submit(function (e) { 
			e.preventDefault();

			//validar que el campo tipo no se encuentre vacío
			if($('.field-tipo').val() == ''){
				$('.field-tipo').css({
					border: '1px solid rgba(244,67,54,.8)',
				});
				$('.error-validate-tipo').css({
					color: 'rgba(244,67,54,.8)'
				});
				alertify.error("Estos campos son requeridos, por favor completelos.");
				return;
			} else {
				$('.field-tipo').css({
					border: '1px solid rgba(76,175,80,.9)',
				});
				$('.error-validate-tipo').css({
					color: 'rgba(76,175,80,.9)'
				});
			}

			$.ajax({
				type: "POST",
				url: "{{ url('tipos_mascotas') }}",
				data: $("#formTipo").serialize(),
				dataType: "json"
			}).done((data)=>{
				swal('Logrado!','tipo de mascota agregado correctamente','success').then(()=>{
					$('#tipo_mascota').prepend(`<option value="${data.id}" selected>${data.tipo}</optio>`);
					$('#modalAddTipoMascota').modal('hide');
					$('#mascotas').modal('show');
				});
			}).fail((data)=>{
				console.log(data);
			});
		});


		function addForm(){
			$('#mascotas').modal('show');
			$('#dataMascota').trigger('reset');
		}

		//Enviar al servidor para guardar 
		/*******************************/
		function guardarMascota(){
			if(verificarFormulario('dataMascota',1)){
				var csrf_token = $('meta[name="csrf-token"]').attr('content');

				let data = new FormData();
				data.append('codigo',codigo.value);
				data.append('nombre',nombre.value);
				data.append('raza',raza.value);
				data.append('fecha_nacimiento',fecha_nacimiento.value);
				data.append('descripcion',descripcion.value);
				data.append('tipo_id',tipo_mascota.value);
				data.append('unidad_id',unidad_id.value);
				data.append('foto',foto.files[0]);
				data.append('_token',csrf_token);

				$.ajax({
					type: "POST",
					url: "{{url('mascotas')}}",
					data: data,
					dataType: "json",
					contentType: false,
					processData: false
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
							table.ajax.reload();
							$('#mascotas').modal('hide');
						});
					}else{
						swal('Error!',res.msg,'error');	
					}
				}).fail((res)=>{
					swal('Error!','Ocurrió un error en el servidor','error');
				});
			}
		}



	</script>
@endsection