@extends('../layouts.app_dashboard_admin')

@section('title', 'Tipo de Unidad')
	
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Carga masiva unidad</li>
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
						<a target="_blanck" href="https://youtu.be/8TDSJMmooKo">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Archivo
	</a>
    <a class="btn btn-success" onclick="">
		<i class="fa fa-download"></i>
		Descargar Plantilla
	</a>
	@include('admin.tipo_unidad.carga_masiva.form')
	<br><br>
	<table id="archivos-table" class="table table-stripped">
		<thead>				
            <th>Nombre Archivo</th>
            <th>Ultimo Registro</th>
            <th>Fallos</th>
            <th>Procesados</th>
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
                url: "{{ url('api.archivos_masivos')}}/{{$tipo_unidad->id}}",
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
		var table  = $('#archivos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre', name: 'nombre'},
                { data: 'ultimoRegistro', name: 'ultimoRegistro'},
                { data: 'fallos', name: 'fallos'},
                { data: 'procesados', name: 'procesados'},
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

		
		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form')
		});


		// Mostrar Registro
		// ****************
		function showForm(id){
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Tipo de Unidad');
			$('#send_form').hide();

			$.ajax({
				url: "{{ url('tipo_unidad') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					$('#modal-form form').trigger('reset');
					$('.check span').remove()
					var elem = document.querySelectorAll('.js-switch');
					elem.forEach((e)=>{
						new Switchery(e,{color:'#169F85'});
					})

					// Datos
					$('#nombre').val(data[0].nombre);
					$('#id').val(data[0].id);
					data[0].atributos.forEach(e => {
						$(`input[name='${e.nombre}']`).click();
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Este apartamento no existe", "error");
				}
			});
		}

		// Agregar registro
		// ****************

		function addForm(){  
            console.log('dentro')          
			$('#archivos').modal('show');
			$('#dataArchivo').trigger('reset');
		}

		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Unidad');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');

			$.ajax({
				url: "{{ url('tipo_unidad') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#modal-form form').trigger('reset');
					$('.check span').remove()
					var elem = document.querySelectorAll('.js-switch');
					elem.forEach((e)=>{
						new Switchery(e,{color:'#169F85'});
					})
					$('#nombre').val(data[0].nombre);
					$('#id').val(data[0].id);
					data[0].atributos.forEach(e => {
						$(`input[name='${e.nombre}']`).click();
					});
				},
				error: function(){
					swal("¡Opps! Ocurrió un error", {
                          icon: "error",
                    });
				}
			});
		}

        //mostrar el nombre de la imagen seleccionada
		function changeFile(){
			let label = document.querySelector('#file');
			label.innerHTML = 'Nombre del archivo: ' + document.querySelector('#file').files[0].name;
		}


        //Enviar al servidor para guardar 
		/*******************************/
		function guardarArchivo(){
			if(verificarFormulario('dataArchivo',1)){
				var csrf_token = $('meta[name="csrf-token"]').attr('content');

				let data = new FormData();
				data.append('nombre_archivo',fileName.value);
                data.append('tipo_unidad',tipo_unidad.value);                			                			
				data.append('archivo',file.files[0]);
				data.append('_token',csrf_token);

				$.ajax({
					type: "POST",
					url: "{{url('archivos')}}",
					data: data,
					dataType: "json",
					contentType: false,
					processData: false
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
							table.ajax.reload();
							$('#archivos').modal('hide');
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