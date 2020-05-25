@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('home') }}">Inicio</a>
				</li>
				  <li>Visitante</li>
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
						<a target="_blanck" href="https://youtu.be/ohHVODp-noM">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar visitante
	</a>
	@include('admin.unidades.modalInfo')
	
	@include('admin.visitantes.form')
	<br><br>
	<table id="visitantes-table" class="table table-stripped">
		<thead>
			<tr>
				<th>Unidad</th>
				<th>Identificación</th>
				<th>Nombre completo</th>
				<th>Parentesco</th>
				<th>Fecha ingreso</th>
				<th>Estado</th>
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
                url: "{{ url('api.visitantes.admin') }}",
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
		var table  = $('#visitantes-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'identificacion', name: 'identificacion'},
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'parentesco', name: 'parentesco'},
          		{ data: 'fecha_ingreso', name: 'fecha_ingreso'},
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


		// Agregar registro 
        // ---------------
        function addForm(){
            $('#visitantes').modal('show');
            $('#dataVisitante')[0].reset();
        }

		function guardarVisitante(){
			if(verificarFormulario('dataVisitante',4)){
				$.ajax({
					type: "POST",
					url: "{{url('visitantes')}}",
					dataType: "json",
					data: $('#dataVisitante').serialize(),
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
            				$('#visitantes').modal('hide');
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
    </script>
@endsection