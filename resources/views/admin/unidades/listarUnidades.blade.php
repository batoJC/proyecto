@extends('../layouts.app_dashboard_admin')
<style>
    textarea{
        width: 100% !important;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('home') }}">Inicio</a>
                </li>
                  <li>
                      <a href="{{ url('unidades') }}">Tipos de unidad</a>
                </li>
                <li>{{ ucfirst(strtolower($tipo->nombre)) }}</li>
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
                        <a target="_blanck" href="https://youtu.be/LLvi697BC7Q">¿Cómo editar?</a>
                        <a target="_blanck" href="https://youtu.be/sCf97c6O-TE">¿Cómo agregar novedades?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <h1 class="text-center">
        Listado de {{ strtolower($tipo->nombre) }}
    </h1>
    
    @include('admin.unidades.modalInfo')
    
    <a class="btn btn-success" href="{{ url('addUnidad', ['tipo'=> $tipo->id]) }}">Agregar {{ strtolower($tipo->nombre) }}</a>
    
    <div class="container-fluid">
        <br><br>
        <table id="unidades-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Número / letra</th>
                    @if (in_array('coeficiente', $atributos))
                        <th>Coeficiente(%)</th>
                    @endif
                    <th>División</th>
                    @if (in_array('observaciones', $atributos))
                        <th>Observaciones</th>
                    @endif
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>        
    </div>
</div>

	



@endsection
@section('ajax_crud')
    <script>

        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.unidades.admin',['tipo'=>$tipo->id]) }}",
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
		var table  = $('#unidades-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'numero_letra', name: 'numero_letra'},
                @if (in_array('coeficiente', $atributos))
                    { data: 'coeficiente', name: 'coeficiente'},
                @endif
          		{ data: 'division', name: 'division'},
                @if (in_array('observaciones', $atributos))
                    { data: 'observaciones', name: 'observaciones'},
                @endif
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

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        // Eliminar registro
        // ---------------
        function deleteData(id){
            swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta unidad?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{ url('unidades') }}" + "/" + id,
                    type: "POST",
                    data: {
                          '_method': 'DELETE',
                          '_token' : csrf_token,
                    }, 
                    success: function(e){
                        if(e.res){
                            swal('Logrado!',e.msg,'success').then(()=>{
                                table.ajax.reload();
                            });
                        }else{
                            swal('Error!',e.msg,'error');
                        }
                    },
                    error: function(data){
                        swal('Error!','Ocurrió un error en el servidor','error');
                    }
                  });
              } else {
                swal("Operación cancelada", "Lo sentimos, vuelve a intentarlo", "error");
              }
            });
        }

        var input;

        function retiroTotal(id){

            //verificar que sea una acción deseada por el usuario
            swal({
                title: '¿Seguro de querer hacer un retiro total de esta unidad?',
                text: 'Al realizar este procedimiento habilita al propietario para volver a registrar nuevos inquilinos, mascotas, vehículos etc en dicha unidad y genera una carta de retiro para la unidad.',
                icon: 'warning',
                buttons: true
            }).then((res)=>{
                if(res){
                    input = document.createElement("textarea");
                    input.placeholder = "Ingrese por favor un texto a incluir en la carta de retiro.";

                    swal({
                        title: "Ingrese el contenido de la carta de retiro.",
                        content: input,
                        buttons: true                       
                    }).then((res1)=>{
                        if (res1) {
                            let data = new FormData();
                            data.append('encabezado','Retiro total');
                            data.append('cuerpo',input.value);
                            data.append('unidad_id',id);
                            data.append('_token',csrf_token);

                            $.ajax({
                                type: "POST",
                                url: "{{url('cartas')}}",
                                contentType: false,
                                dataType: "json",
                                cache: false,
                                processData: false,
                                data: data,
                            }).done((res)=>{
                                if(res.res){
                                    swal('Logrado!',res.msg,'success');
                                }else{
                                    swal('Error!',res.msg,'error');
                                }
                            }).fail((res)=>{
                                swal('Error!','Ocurrió un error en el servidor, por favor intentelo más tarde.','error');
                            });
                        }
                    });
                    input.focus();
                }
            })           
        }


        function registrarNovedad(id){
            input = document.createElement("textarea");
            input.placeholder = "Ingrese por favor un texto a incluir como nueva novedad en la unidad.";

            swal({
                title: "Ingrese el contenido de la novedad.",
                content: input,
                buttons: true                       
            }).then((res1)=>{
                if (res1) {
                    let data = new FormData();
                    data.append('contenido',input.value);
                    data.append('unidad_id',id);
                    data.append('_token',csrf_token);

                    $.ajax({
                        type: "POST",
                        url: "{{url('novedades')}}",
                        contentType: false,
                        dataType: "json",
                        cache: false,
                        processData: false,
                        data: data,
                    }).done((res)=>{
                        console.log(res);

                        if(res.res){
                            swal('Logrado!',res.msg,'success');
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                    }).fail((res)=>{
                        swal('Error!','Ocurrió un error en el servidor, por favor intentelo más tarde.','error');
                    });
                }
            });
            input.focus();
        }



    </script>
@endsection