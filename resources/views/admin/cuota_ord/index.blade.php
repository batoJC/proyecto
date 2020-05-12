@extends('../layouts.app_dashboard_admin')
@section('title', 'Cuotas Extraordinarias')
<style>

    tr{
        cursor: pointer;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('admin') }}">Inicio</a>
                </li>
                <li>Cuota Extraordinaria</li>
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
    @include('admin.cuota_ord.form')
    
    <button class="btn btn-success" onclick="agregarCuota()"><i class="fa fa-plus"></i> Agregar cuota extraordinaria</button>
    <button id="btn_eliminar" class="btn" disabled href="#" onclick="eliminarSeleccion()"><i class="fa fa-trash"></i> Eliminar seleccionadas</button>
    
    <table id="extraordinarias-table" class="table">
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Vigencia Inicio</th>
                <th>Vigencia Fin</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>

   



@endsection

@section('ajax_crud')
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#valor_aux').maskMoney({precision:0});
        });

        
        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.extraordinarias.admin') }}",
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
		var table  = $('#extraordinarias-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'concepto', name: 'concepto'},
          		{ data: 'vigencia_inicio', name: 'vigencia_inicio'},
          		{ data: 'vigencia_fin', name: 'vigencia_fin'},
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

        $(document).ready(function () {
            $('.select-multiple').select2({
                dropdownParent: $('#dataCuota'),
                multiple: true
            });

            $('.select-2').select2({
                dropdownParent: $('#dataCuota'),
            });
        });

        $('#extraordinarias-table tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
            btn_eliminar.disabled = table.rows('.selected').data().length == 0;
        });

        var cuotas = null;
        var indice = 0;
        //Elimina todas las cuentas seleccionadas
        function eliminarSeleccion(){
            cuotas = table.rows('.selected').data();
            swal('Advertencia!',"¿Seguro de quere eliminar las cuotas seleccionadas? \n\r "
                        + cuotas.length +
                        ' Seleccionadas','warning',{
                buttons: {
                    cancel:{
                        value: false,
                        text: 'No',
                        visible: true
                    },
                    confirm:{
                        value: true,
                        text: 'Si'
                    }
                }
            }).then(res=>{
                if(res){
                    indice = 0;
                    $('#loading').css("display", "flex")
                    .hide().fadeIn(800,()=>{
                        eliminarR(indice);
                    });
                }
            });
        }

        function eliminarR(i){
            if(i < cuotas.length){
                indice = i;
                $.ajax({
                    url : "{{ url('cuota_ext_ord') }}" + "/" + cuotas[indice].id,
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token' : csrf_token,
                    },
                    dataType: "json"
                }).done((res)=>{
                    if(res.res){
                        indice++;
                        eliminarR(indice);
                    }else{
                        swal('Error!',res.msg,'error');
                        table.ajax.reload();
                        $('#loading').fadeOut(800);
                    }
                }).fail((res)=>{
                    swal('Error!','Ocurrió un error en el servidor','error');
                    table.ajax.reload();
                        $('#loading').fadeOut(800);
                });
            }else{
                swal('Logrado!','Proceso terminado con exito','success');
                table.ajax.reload();
                $('#loading').fadeOut(800);
            }
        }



        //token para ajax
        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        var action = 'add';
        var id;

        //abrir modal para agregar cuota
        function agregarCuota(){

            action = 'add';
			$('input[name="_method"]').val('POST');
            changeType('coeficiente')
            $('#title_modal_cuotas').text('Agregar cuota extraordinaria');
            $('#cuotasExt').modal('show');
            $('#dataCuota').trigger('reset');

        }

        // cargar detalles
		/*****************/
		function detalles(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: "{{url('detalleCuotaExtraordinaria')}}"+"/"+id,
				data: {_token:csrf_token},
				dataType: "html",
				success: function (response) {
					$('#detalles').html(response);
					$('#modal-detalles').modal('show');
				}
			});
		}

        //change of type de calculation for value of cuota
        function changeType(aux){
            tipo.value = aux;
            label_tipo.innerText = (aux=='valor')? 'Valor por unidad' : 'Valor total';
        }

        //abrir modal para editar cuota
        function editarCuota(auxId){

            action = 'edit';
			$('input[name="_method"]').val('PUT');
            id = auxId;
            consultar(id);
            $('#title_modal_cuotas').text('Editar cuota extraordinaria');
            $('#cuotasExt').modal('show');

        }

        //guardar informacion de la cuota(s)
        /***********************************/
        function guardarCuota(){
            if(verificarFormulario('dataCuota',5)){
                btn_guardar.disabled = true;
                //mandar a agregar una cuota administrativa
                if(action == 'add'){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('cuota_ext_ord') }}",
                        data: $('#dataCuota').serialize(),
                        dataType: "json"
                    }).done((res)=>{
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
                                $('#cuotasExt').modal('hide');
                                table.ajax.reload();
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                        btn_guardar.disabled = false;
                    }).fail((res)=>{
                        swal('Error!','Ocurrió un error en el servidor','error');
                        btn_guardar.disabled = false;
                    });
                }

                //mandar a editar una cuota extraordinaria
                if(action == 'edit'){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('cuota_ext_ord') }}/"+id,
                        // Envio de formulario en objeto MUY OP
                        // ************************************
                        data: new FormData($('#dataCuota')[0]),
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done((res)=>{
                        console.log(res);
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
                                $('#cuotasExt').modal('hide');
                                table.ajax.reload();
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                    }).fail((res)=>{
                        console.log(res);
                        swal('Error!','Ocurrió un error en el servidor','error');
                    });
                }



            }
        }


        //consultar Informacion de una cuota
        /***********************************/
        function consultar(auxId){
            $.ajax({
                type: "GET",
                url: "{{url('cuota_ext_ord')}}/"+auxId,
                data: "data",
                dataType: "json"
            }).done((data)=>{
				console.log(data);
                $('#valor').val(data.cuota.valor);
                $('#vigencia_inicio').val(data.cuota.vigencia_inicio);
                $('#vigencia_fin').val(data.cuota.vigencia_fin);
                $('#presupuesto_cargar_id').val(data.cuota.presupuesto_cargar_id);
                $('#acta_id').val(data.cuota.acta_id);
                $('#unidades').val(data.unidades);

                $('.select-2').select2({
                    dropdownParent: $('#cuotasExt'),
                });

				$('.select-multiple').select2({
					dropdownParent: $('#cuotasExt'),
					multiple: true
				});

            }).fail((data)=>{
                console.log(data);
            });
        }


        //eliminar cuota
        /****************/
        function eliminar(auxId){
            swal({
                title:'Advertencia!',
                text: '¿Seguro de querer eliminar esta cuota?',
                icon: 'warning',
                buttons: true
            }).then((res)=>{
                if(res){
                    $.ajax({
                        url : "{{ url('cuota_ext_ord') }}" + "/" + auxId,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token' : csrf_token,
                        },
                        dataType: "json"
                    }).done((res)=>{
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
                                table.ajax.reload();
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                    }).fail((res)=>{
                        swal('Error!','Ocurrió un error en el servidor','error');
                    });
                }
            });
        }



    </script>

@endsection