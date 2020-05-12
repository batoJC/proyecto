@extends('../layouts.app_dashboard_admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('home') }}">Inicio</a>
                </li>
                  <li>Novedades</li>
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
                        <a target="_blanck" href="https://youtu.be/oKpkD0g1Whs">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <a class="btn btn-success" onclick="addForm()">
        <i class="fa fa-plus"></i>
        Agregar novedad
    </a>
    
    @include('admin.novedades.form')
    <br><br>
    <table id="novedades-table" class="table table-stripped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
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
                url: "{{ url('api.novedades_conjunto') }}",
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
		var table  = $('#novedades-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'descripcion', name: 'descripcion'},
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

		// Agregar registro 
        // ---------------
        function addForm(){
            $('#novedades').modal('show');
            $('#dataNovedad')[0].reset();
            $('#btnGuardar').show();
        }

		function guardar(){
			if(verificarFormulario('dataNovedad',2)){
				$.ajax({
					type: "POST",
					url: "{{url('novedadesConjunto')}}",
					dataType: "json",
					data: $('#dataNovedad').serialize(),
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
                            table.ajax.reload();
                            $('#novedades').modal('hide');
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
                text: '¿Seguro de que quere eliminar esta novedad?',
                icon: 'warning',
                buttons: true
            }).then(res=>{
                if(res){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('novedadesConjunto') }}/"+id,
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
            $('#novedades').modal('show');
            $('#dataNovedad')[0].reset();
            $('#btnGuardar').hide();
            $.ajax({
                type: "GET",
                url: "{{ url('novedadesConjunto') }}/"+id,
                data: {
                    '_token' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json"
            }).done(res => {
                fecha.value = res.fecha;
                contenido.value = res.contenido;
            });
        }

    </script>
@endsection