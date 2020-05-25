@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('home') }}">Inicio</a>
                </li>
                  <li>Mantenimientos</li>
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
                        <a target="_blanck" href="https://youtu.be/TRNp8L09YaM">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <a class="btn btn-success" onclick="addForm()">
        <i class="fa fa-plus"></i>
        Agregar recordatorio de mantenimiento
    </a>
    
    @include('admin.mantenimientos.form')
    <br><br>
    <table id="mantenimientos-table" class="table table-stripped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<input type="file" onchange="realizadoArchivo()" id="archivo" accept="application/pdf" class="hide">

	
@endsection
@section('ajax_crud')
	<script>
        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.mantenimientos') }}",
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

        let id_mantenimiento = 0;
        
        // Listar los registros
		// *************************************
		var table  = $('#mantenimientos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'descripcion', name: 'descripcion'},
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
            $('#mantenimientos').modal('show');
            $('#dataMantenimiento')[0].reset();
        }

		function guardar(){
			if(verificarFormulario('dataMantenimiento',2)){
				$.ajax({
					type: "POST",
					url: "{{url('mantenimientos')}}",
					dataType: "json",
					data: $('#dataMantenimiento').serialize(),
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
                            $('#mantenimientos').modal('hide');
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
                text: '¿Seguro de que quere eliminar el recordatorio de este mantenimiento?',
                icon: 'warning',
                buttons: true
            }).then(res=>{
                if(res){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('mantenimientos') }}/"+id,
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

        function realizadoArchivo(){
            let file = archivo.files[0];
            if(file){
                $('#loading').css("display", "flex")
                .hide().fadeIn(800,()=>{
                    $('#loading').css('display','flex');
                    let data = new FormData();
                    data.append('_token',$('meta[name="csrf-token"]').attr('content'));
                    data.append('archivo',file);
                    $.ajax({
                        type: "POST",
                        url: "{{ url('mantenimientoRealizado') }}/"+id_mantenimiento,
                        data: data,
                        processData: false,
                        contentType: false,
                        dataType: "json"
                    }).done(res=>{
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
                                table.ajax.reload();
                                $('#loading').fadeOut(800);
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                                $('#loading').fadeOut(800);
                        }
                    });
                });
            }
        }

        function realizado(id){
            id_mantenimiento = id;
            swal({
                title:'Advertencia!',
                text: '¿Seguro de que quere marcar este recordatorio como realizado?',
                icon: 'warning',
                buttons: {
                    confirm1: {
                        text: "Guardar sin archivo",
                        value: 'sin archivo',
                        visible: true,
                        className: "btn-default",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Guardar con archivo",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }  
                }
            }).then(res=>{
                console.log(res);
                if(res == 'sin archivo'){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('mantenimientoRealizado') }}/"+id,
                        data: {
                            _token : $('meta[name="csrf-token"]').attr('content')
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
                }else if(res == true){
                    archivo.click();
                }
            });
        }


    </script>
@endsection