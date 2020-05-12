@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('home') }}">Inicio</a>
                </li>
                  <li>Evidencias</li>
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
                        <a target="_blanck" href="https://youtu.be/st6NRBhpOKE">¿Qué puedo hacer?</a>
						<a target="_blanck" href="https://youtu.be/LAAR8dfIHs4">¿Cómo enlazar una evidencia a una noticia?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <a class="btn btn-success" onclick="addForm()">
        <i class="fa fa-plus"></i>
        Agregar evidencia
    </a>
    
    @include('admin.evidencias.form')
    <br><br>
    <table id="evidencias-table" class="table table-stripped">
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
        $(document).ready(function () {
            $('.select-2').select2({
                dropdownParent: $('#dataEvidencia'),
            })
        });

		var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.evidencias') }}",
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
		var table  = $('#evidencias-table').DataTable({
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


		$('#imgSalida').hide();
		$('.btn-speacial').hide();

		$('.btn-logotype-brand').click(function(event) {
			
			$('.upload').click();

			$(function() {
				$('#file-input').change(function(e) {
					addImage(e); 
				});

			    function addImage(e){
			    	var file = e.target.files[0],
			   		imageType = /image.*/;
			    
			    	if (!file.type.match(imageType))
			    	return;
			  
			    	var reader = new FileReader();
			    	reader.onload = fileOnload;
			    	reader.readAsDataURL(file);
			    }
			  
			    function fileOnload(e) {
			    	var result=e.target.result;
			    	$('#imgSalida').attr("src", result);
			    	$('#imgSalida').fadeIn(600);
			    	$('.btn-speacial').fadeIn(600);
			    	$('.btn-logotype-brand').fadeOut(200);
			    }
			});
		});

		$('.btn-speacial').click(function(event){
			$('#imgSalida').fadeOut(300);
			$('.btn-logotype-brand').fadeIn(200);
			$('.btn-speacial').hide();
			$('#file-input').val('');
		});

		// Agregar registro 
        // ---------------
        function addForm(){
            $('#evidencias').modal('show');
            $('#dataEvidencia')[0].reset();
            $('#btnGuardar').show();
        }

		function guardar(){
			if(verificarFormulario('dataEvidencia',2)){
                document.querySelector('#btnGuardar button').disabled = true;
				$.ajax({
					type: "POST",
					url: "{{url('evidencias')}}",
                    dataType: "json",
                    contentType: false,
                    processData: false,
					data: new FormData(dataEvidencia),
				}).done((res)=>{
					if(res.res){
						swal('Logrado!',res.msg,'success').then(()=>{
							table.ajax.reload();
							$('#evidencias').modal('hide');
						});
					}else{
						swal('Error!',res.msg,'error');	
					}                    
				}).fail((res)=>{
					swal('Error!','Ocurrió un error en el servidor','error');
				}).always(res=>{
                    document.querySelector('#btnGuardar button').disabled = false;
                });
			}
        }
        
        function eliminar(id){
            swal({
                title:'Advertencia!',
                text: '¿Seguro de que quere eliminar esta evidencia?',
                icon: 'warning',
                buttons: true
            }).then(res=>{
                if(res){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('evidencias') }}/"+id,
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
            $('#infoEvidencia').modal('show');
            $.ajax({
                type: "GET",
                url: "{{ url('evidencias') }}/"+id,
                data: {
                    '_token' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json"
            }).done(res => {
                console.log(res);
                fecha_info.innerText = res.fecha;
                contenido_info.innerText = res.contenido;
                let aux = res.fotos.split(';');
                $('.images').html('')
                aux.forEach(e => {
                    $('.images').append(`<img class="foto show_img" src="imgs/private_imgs/${e}" alt="foto">`);
                });
                $('.show_img').click(function(e) {
                    $('#show_image img')[0].src = $(e)[0].currentTarget.src;
                    $('#show_image').css("display", "flex")
                .hide().fadeIn(400);
                });
            });
        }

    </script>
@endsection