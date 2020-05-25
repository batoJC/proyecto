@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('home') }}">Inicio</a>
                </li>
                  <li>Usuarios</li>
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
                        <a target="_blanck" href="https://youtu.be/D7cT7piNkbY">¿Qué puedo hacer?</a>
                        <a target="_blanck" href="https://youtu.be/yIOMkZOxFxY">¿Cómo hacer carga masiva?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- Variable de session para actualización --}}
    {{-- ************************************** --}}
    @if(session('status'))
        <div class="alert alert-success-original alert-dismissible" role="alert">
            <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            {{-- {!! html_entity_decode(session('status')) !!}
            {!! html_entity_decode(session('last')) !!} --}}
            <h4>
                {{ session('status') }}
            </h4>
            <h4>
                {{ session('last') }}
            </h4>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger-original alert-dismissible" role="alert">
            <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            <h4>
                {{ session('error') }}
            </h4>
            <h4>
                {{ session('last') }}
            </h4>
        </div>
    @endif
    <a class="btn btn-success" onclick="addForm()">
        <i class="fa fa-plus"></i>
        Agregar Usuario
    </a>
    <a data-placement="top" title="Descarga archivo excel para una carga masiva de usuarios" data-toggle="tooltip"  class="btn btn-warning" href="{{ url('download_users') }}" target="_blank">
        <i class="fa fa-file-archive-o"></i>
        &nbsp; Descargar Archivo Base de Usuarios
    </a>
    <a data-placement="top" title="Subir archivo excel con lista de usuarios" data-toggle="tooltip"  class="btn btn-info" href="{{ url('users_csv') }}">
        <i class="fa fa-users"></i>
        Agregar Usuario (Carga masiva)
    </a>
    @include('admin.usuarios.form')
    <br><br>
    <table id="usuarios-table" class="table table-striped">
        <thead>
            <th>Nombre Completo</th>
            <th>Tipo de Documento</th>
            <th>Numero de Documento</th>
            <th>Email</th>
            <th>Edad</th>
            <th>Dirección</th>
            <th>Celular</th>
            <th>Rol en el conjunto</th>
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
                url: "{{ url('api.usuarios') }}",
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
		var table  = $('#usuarios-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre_completo', name: 'nombre_completo'},
          		{ data: 'tipo_documento', name: 'tipo_documento'},
          		{ data: 'numero_cedula', name: 'tipo'},
          		{ data: 'email', name: 'email'},
          		{ data: 'edad', name: 'edad'},
          		{ data: 'direccion', name: 'direccion'},
          		{ data: 'celular', name: 'celular'},
          		{ data: 'rol', name: 'rol'},
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


        // Mostrar registro
        // ----------------
        function showForm(id){
            $('#modal-form form')[0].reset();
            $('.element-hidden').hide();
            $('.modal-title').text('Usuario');
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
                    $('#ocupacion').val(data.ocupacion);
                    $('#fecha_nacimiento').val(data.fecha_nacimiento);
                    $('#direccion').val(data.direccion);
                    $('#numero_cedula').val(data.numero_cedula);
                    $('#email').val(data.email);
                    $('#genero').val(data.genero);
                    $('#telefono').val(data.telefono);
                    $('#celular').val(data.celular);
                    $('#id_rol').val(data.id_rol);
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
            $('.modal-title').text('Agregar Usuario');
            $('#send_form').attr('type', 'button');
        }

        // Editar registro
        // ---------------
        function editForm(id){
            save_method = "edit";
            $('input[name="_method"]').val('PUT');
            $('#modal-form form')[0].reset();
            $('.element-hidden').hide();
            $('.modal-title').text('Editar Usuario');
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
                    $('#ocupacion').val(data.ocupacion);
                    $('#fecha_nacimiento').val(data.fecha_nacimiento);
                    $('#direccion').val(data.direccion);
                    $('#numero_cedula').val(data.numero_cedula);
                    $('#email').val(data.email);
                    $('#genero').val(data.genero);
                    $('#tipo_cuenta').val(data.tipo_cuenta);
                    $('#telefono').val(data.telefono);
                    $('#celular').val(data.celular);
                    $('#id_conjunto').val(data.id_conjunto);
                    $('#id_rol').val(data.id_rol);
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

        // Abrir la modal cuando pase algún evento
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
                            $('#modal-form').modal('hide');
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