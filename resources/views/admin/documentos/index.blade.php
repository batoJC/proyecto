@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('documentos') }}">Módulo documental</a>
                </li>
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
                        <a target="_blanck" href="https://youtu.be/39_J3QMXBWc">¿Qupe puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <a class="btn btn-success" onclick="addForm()">
        <i class="fa fa-plus"></i>
        Agregar documento
    </a>
    
    @include('admin.documentos.form')
    <br><br>
    <table id="documentos-table" class="table table-stripped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Visible porteria</th>
                <th>Visible propietarios</th>
                <th>Acciones</th>
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
                url: "{{ url('api.documentos.admin') }}",
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
		var table  = $('#documentos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'descripcion', name: 'descripcion'},
          		{ data: 'porteria', name: 'porteria'},
          		{ data: 'propietario', name: 'propietario'},
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

		//mostrar el nombre de los archivos seleccionados
		function show_info_files(){
			archivos_load.innerText  = `Archivos: \r\n`;

			for (let i = 0; i < archivos.files.length; i++) {
				archivos_load.innerText  += `${archivos.files[i].name} \r\n`;
			}

		}

		// Agregar registro 
        // ---------------
        function addForm(){
            $('#documentos').modal('show');
            $('#dataDocumento')[0].reset();
        }

		function guardar(){
			if(verificarFormulario('dataDocumento',1)){
				$.ajax({
					type: "POST",
					url: "{{url('documentos')}}",
                    dataType: "json",
                    contentType: false,
                    processData: false,
					data: new FormData(dataDocumento),
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
            				$('#documentos').modal('hide');
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
        
        function eliminar(id){
            swal({
                title:'Advertencia!',
                text: '¿Seguro de que quere eliminar este documento?',
                icon: 'warning',
                buttons: true
            }).then(res=>{
                if(res){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('documentos') }}/"+id,
                        data: {
                            '_method':'DELETE',
                            '_token' : $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json"
                    }).done(res=>{
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
								table.ajax.reload();
                            });
                        }else{
                            swal('Error!',res.msg,'error');	
                        }
                    });
                }
            });
        }


        function ver(id){
            $.ajax({
                type: "GET",
                url: "{{ url('documentos') }}/"+id,
                data: {
                    '_token' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "HTML"
            }).done(res => {
				$('#info_documento').modal('show');
				$('#body_info_documento').html(res);
            });
        }

    </script>
@endsection