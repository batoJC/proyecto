@extends('../layouts.app_dashboard')

@section('title', 'Usuarios')

@section('content')
	<div class="right_col" role="main">
        <div class="row">
            <div class="col-11 col-md-11">
                <ul class="breadcrumb">
					<li>
						<a href="{{ asset('home') }}">Inicio</a>
					</li>
				  	<li>Administradores</li>
				</ul>				
				<a class="btn btn-success" onclick="addForm()">
					<i class="fa fa-plus"></i>
					Agregar Administrador
				</a>
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
							<a target="_blanck" href="https://youtu.be/rYdVNFNxWd8">¿Qué puedo hacer?</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="col-md-12">
                @include('owner.usuarios.form')
				@include('owner.usuarios.formTipoDocumentos')
				<br><br>
				<table id="usuarios-table" class="table">
					<thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Tipo de Documento</th>
                            <th>Numero de Documento</th>
                            <th>Email</th>
                            <th>Conjunto Perteneciente</th>
                            <th>Celular</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
					</thead>
					<tbody>
                        
					</tbody>
				</table>
            </div>
        </div>
    </div>
@endsection
@section('ajax_crud')
	<script>
        let csrf_token = $('meta[name="csrf-token"]').attr('content');

        var actualizarTabla = (data,callback,settings) => {
			$.ajax({
				type: "GET",
				url: "{{ url('api.usuarios_owner') }}",
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
                        let data = JSON.parse(filas[i].cells[6].innerText);
                        console.log(data);
                        let insertar =  (data == 'Activo')? `<span class="bg-green">${data}</span>` : `<span class="bg-red">${data}</span>`;
                        filas[i].cells[6].innerHTML = insertar;
                    }
				}
			});
		}

        // Listar los registros
		// *************************************
		var table  = $('#usuarios-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre_completo', name: 'nombre_completo'},
          		{ data: 'tipo_documento', name: 'tipo_documento'},
          		{ data: 'numero_cedula', name: 'numero_cedula'},
          		{ data: 'email', name: 'email'},
          		{ data: 'conjunto', name: 'conjunto'},
          		{ data: 'celular', name: 'celular'},
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


        function selectConjunto(e){
            e.value;
            $.ajax({
                type: "GET",
                url: "{{url('conjuntos')}}/"+e.value,
                data: {
                    _token : csrf_token
                },
                dataType: "json",
                success: function (response) {
                    email.value = response.correo;
                }
            });
        }


        //Mostrar el modal para insertar tipos de documentos
        /***************************************************/
        function checkTipo(e){
            let selected = $(e).val();
            if(selected == 'otro'){
                $('#modalAddTipoDocumento').modal('show');
                $('#modal-form').modal('hide');
            }
        }

        //Guardar el tipo de documento
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
                alertify.error("Estos campos son requeridos, por favor diligencielos.");
                return;
            } else {
                $('.field-tipo').css({
                    border: '1px solid rgba(76,175,80,.9)',
                });
                $('.error-validate-tipo').css({
                    color: 'rgba(76,175,80,.9)'
                });
            }

            console.log($(e).serialize());

            $.ajax({
                type: "POST",
                url: "{{ url('tipo_documentos') }}",
                data: $("#formTipo").serialize(),
                dataType: "json"
            }).done((data)=>{
                console.log(data);
                swal('Logrado!','tipo de documento insertado correctamente','success').then(()=>{
                    $('#tipo_documento').prepend(`<option value="${data.id}" selected>${data.tipo}</optio>`);
                    $('#modalAddTipoDocumento').modal('hide');
                    $('#modal-form').modal('show');
                });
            }).fail((data)=>{
                console.log(data);
            });
        });


        // Mostrar Registros de residentes
        // *******************************
        function showFormAsociados(id){
            $('.modal-title').text('Conjuntos Asociados');

            $.ajax({
                url: "{{ url('conjuntos_usuarios_all') }}" + "/" + id,
                type: "GET",
                success: function(data){
                    if($.isEmptyObject(data)){
                        $('#tbody_conjunto_table').remove();
                        location.replace('{{ url('conjuntos_usuarios') }}');
                    } else {
                        $('#modal-form-conjuntos').modal('show');
                        $('#table_conjunto_tbl').append("<tbody id='tbody_conjunto_table'></tbody>");

                        for(var i=0; i < data.length; i++){
                            $('#tbody_conjunto_table').append("<tr id="+'tr'+i+"></tr>");
                            var arreglo = Object.values(data[i]);
                            for(var j=0; j < arreglo.length; j++){
                                $('#tr'+i+'').append("<td>"+arreglo[j]+"</td>");
                            }
                        }
                    }
                },
                error: function(){
                    swal("Ocurrió un error", "Lo sentimos, Este apartamento no existe", "error");
                }
            });
        }

        // Mostrar registro
        // ----------------
        function showForm(id){

            $('#modal-form form')[0].reset();
            $('.element-hidden').hide();
            $('.modal-title').text('Administrador');
            $('.password-div').hide();
            $('input[name="password"]').removeClass('field-3');
            $('#send_form').hide();
            $.ajax({
                url: "{{ url('usuarios') }}" + "/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modal-form').modal('show');
                    // Data....
                    $('#nombre_completo').val(data.nombre_completo);
                    $('#direccion').val(data.direccion);
                    $('#tipo_cedula').val(data.tipo_cedula);
                    $('#numero_cedula').val(data.numero_cedula);
                    $('#email').val(data.email);
                    $('#edad').val(data.edad);
                    $('#genero').val(data.genero);
                    $('#tipo_cuenta').val(data.tipo_cuenta);
                    $('#numero_cuenta').val(data.numero_cuenta);
                    $('#telefono').val(data.telefono);
                    $('#celular').val(data.celular);
                    $('#id_conjunto').val(data.id_conjunto);
                },
                error: function(){
                    swal("Ocurrió un error", "Lo sentimos, Este usuario no existe", "error");
                }
            });
        }

        // Agregar registro 
        // ---------------
        function addForm(){
        	save_method = "add";
	        $('input[name=_method]').val('POST');
            $('.password-div').show();
            $('input[name="password"]').addClass('field-3');
            $('#modal-form').modal('show');
            $('.element-hidden').hide();
            $('#modal-form form')[0].reset();
            selectConjunto(id_conjunto);
            $('.modal-title').text('Agregar Administradores');
            $('#send_form').attr('type', 'button');
        }

        // Editar registro
        // ---------------
        function editForm(id){
            save_method = "edit";
            $('input[name="_method"]').val('PUT');
            $('#modal-form form')[0].reset();
            selectConjunto(id_conjunto);
            $('.element-hidden').hide();
            $('.modal-title').text('Editar Administrador');
            $('.password-div').hide();
            $('input[name="password"]').removeClass('field-3');
            $('#send_form').show();
            $('#send_form').attr('type', 'button');
            $.ajax({
                url: "{{ url('usuarios') }}" + "/" + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modal-form').modal('show');
                    // Data....
                    $('#id').val(data.id);
                    $('#nombre_completo').val(data.nombre_completo);
                    $('#direccion').val(data.direccion);
                    $('#numero_cedula').val(data.numero_cedula);
                    $('#email').val(data.email);
                    $('#edad').val(data.edad);
                    $('#genero').val(data.genero);
                    $('#tipo_cuenta').val(data.tipo_cuenta);
                    $('#telefono').val(data.telefono);
                    $('#celular').val(data.celular);
                    $('#id_conjunto').val(data.id_conjunto);
                },
                error: function(){
                    swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
                }
            });
        }

        // Eliminar registro
        // ---------------
        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este usuario?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{ url('usuarios') }}" + "/" + id,
                    type: "POST",
                    data: {
                          '_method': 'DELETE',
                          '_token' : csrf_token,
                    }, 
                    success: function(data){
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

        // Cambiar el estado del usuario
        // *****************************
        function desativateUser(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: "¿Estás seguro?",
                text: "Este procedimiento cambiará el estado del usuario",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('disusuarios') }}" + "/" + id,
                        type: "POST",
                        data: {
                            '_token' : csrf_token,
                        },
                        success: function(data){
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

        // Guardar el registro que se este edidanto o uno nuevo
        // ---------------------------------------
        $('#modal-form form').on('submit', function(e){
        	e.preventDefault();
        	id = $('#id').val();
        	if(save_method == "add") url = "{{ url('usuarios') }}";
        	else url = "{{ url('usuarios') }}" + "/" + id;

        	$.ajax({
        		url: url,
        		type: 'POST',
        		data: $('#modal-form form').serialize(),
        		success: function(data){
        			$('#modal-form').modal('hide');
        			swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
                        .then((value) => {
                        table.ajax.reload();
                    });
        		},
        		error: function(){
	        		swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
        		}
        	});
        });




	</script>
@endsection

