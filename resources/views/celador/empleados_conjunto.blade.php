@extends('../layouts.app_dashboard_celador')

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
	<br><br>
	<table id="empleados-table" class="table table-stripped">
		<thead>
			<tr>
				<th>Documento</th>
				<th>Foto</th>
				<th>Nombre completo</th>
				<th>Cargo</th>
				<th>Dirección</th>
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
                url: "{{ url('api.empleados_conjunto.porteria') }}",
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
						let data = JSON.parse(filas[i].cells[1].innerText);
						if(data != null){
							filas[i].cells[1].innerHTML =  `<img class="foto show_img" src="imgs/private_imgs/${data}" alt="Foto">`;
							$('.show_img').click(function(e) {
								$('#show_image img')[0].src = $(e)[0].currentTarget.src;
								$('#show_image').css("display", "flex").hide().fadeIn(400);
							});
						}else{
							filas[i].cells[1].innerText = 'No hay foto del vehículo';
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
          		{ data: 'cedula', name: 'cedula'},
          		{ data: 'foto', name: 'foto', orderable: false, searchable: false},
          		{ data: 'nombre_completo', name: 'nombre_completo'},
          		{ data: 'cargo', name: 'cargo'},
          		{ data: 'direccion', name: 'direccion'},
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

	</script>
@endsection