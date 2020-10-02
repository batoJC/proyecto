@extends('../layouts.app_dashboard_admin')
@section('title', 'Cuotas Administrativas')
<style>
    .hide{
        display: none;
    }

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
                <li>Cuota Administrativa</li>
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
                        <a target="_blanck" href="https://youtu.be/heZERUUtI6w">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('admin.cuota_admon.form')

    <button class="btn btn-success" onclick="agregarCuota()">
        <i class="fa fa-plus"></i> Agregar cuota administrativa</button>

    <button id="btn_eliminar" class="btn" disabled href="#" onclick="eliminarSeleccion()"><i class="fa fa-trash"></i> Eliminar seleccionadas</button>

    <table id="administrativas-table" class="table">
        <thead>
            <tr>
                <th>Vigencia Inicio</th>
                <th>Vigencia Fin</th>
                <th>Interes</th>
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
            $('#valor_fijo_aux').maskMoney({precision:0});
            $('#incremento_valor_fijo_aux').maskMoney({precision:0});
        });
        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.administrativas.admin') }}",
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

        $('#administrativas-table tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('selected');
            btn_eliminar.disabled = table.rows('.selected').data().length == 0;
        });

        // Listar los registros
		// *************************************
		var table  = $('#administrativas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'vigencia_inicio', name: 'vigencia_inicio'},
          		{ data: 'vigencia_fin', name: 'vigencia_fin'},
          		{ data: 'interes', name: 'interes'},
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
                        $('#loading').css('display','flex');
                        eliminarR(indice);
                    });
                }
            });
        }

        function eliminarR(i){
            if(i < cuotas.length){
                indice = i;
                $.ajax({
                    url : "{{ url('cuota_admon') }}" + "/" + cuotas[indice].id,
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


        $(document).ready(function () {
            $('.select-multiple').select2({
                dropdownParent: $('#dataCuota'),
                multiple: true
            });

            $('.select-2').select2({
                dropdownParent: $('#dataCuota'),
            });



        });

        //token para ajax
        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        var action = 'add';
        var id;

        //abrir modal para agregar cuota
        function agregarCuota(){
            action = 'add';
			$('input[name="_method"]').val('POST');
            $('#valor_fijo_aux').change();
            $('#incremento_valor_fijo_aux').change();
            seleccionarCalculo(1);
            $('#title_cuotas_admon').text('Agregar cuota administrativa');
            $('#cuotas_admon').modal('show');
            $('#dataCuota').trigger('reset');
            //mostrar select de unidad,campo valor
            $('#campo_valor').fadeOut(200);
            $('#campo_unidad').fadeOut(200);
            $('#campo_select_unidad').fadeIn(200);
            $('#text_tipo_calculo').text('');
            $('#row_presupuesto_calcular').removeClass('hide').fadeOut(300);
            $('#row_valor_fijo').addClass('hide').fadeOut(300);
            $('#row_incremento_porcentual').addClass('hide').fadeOut(300);
            $('#row_incremento_fijo').addClass('hide').fadeOut(300);

        }

        // cargar detalles
		/*****************/
		function detalles(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: "{{url('detalleCuotaAdministracion')}}"+"/"+id,
				data: {_token:csrf_token},
				dataType: "html",
				success: function (response) {
					$('#detalles').html(response);
					$('#modal-detalles').modal('show');
				}
			});
		}

        //seleccionar el tipo de calculo para
        //la cuota de administración
        function seleccionarCalculo(tipo){
            $('#tipo_calculo').val(tipo);
            switch (tipo) {
                case 1://por presupuesto
                    $('#text_tipo_calculo').text('Por presupuesto');
                    $('#row_presupuesto_calcular').removeClass('hide').fadeOut(0).fadeIn(300);
                    $('#row_valor_fijo').addClass('hide').fadeOut(300);
                    $('#row_incremento_porcentual').addClass('hide').fadeOut(300);
                    $('#row_incremento_fijo').addClass('hide').fadeOut(300);
                    break;
                case 2://por total de gastos
                    $('#text_tipo_calculo').text('Por total de gastos');
                    $('#row_presupuesto_calcular').removeClass('hide').fadeOut(300);
                    $('#row_valor_fijo').addClass('hide').fadeOut(300);
                    $('#row_incremento_porcentual').addClass('hide').fadeOut(300);
                    $('#row_incremento_fijo').addClass('hide').fadeOut(300);
                    break;
                case 3://por valor fijo
                    $('#text_tipo_calculo').text('Por valor fijo');
                    $('#row_presupuesto_calcular').addClass('hide').fadeOut(300);
                    $('#row_valor_fijo').removeClass('hide').fadeOut(0).fadeIn(300);
                    $('#row_incremento_porcentual').addClass('hide').fadeOut(300);
                    $('#row_incremento_fijo').addClass('hide').fadeOut(300);
                    break;
                case 4://incremento porcentual
                    $('#text_tipo_calculo').text('Incremento porcentual');
                    $('#row_presupuesto_calcular').addClass('hide').fadeOut(300);
                    $('#row_valor_fijo').addClass('hide').fadeOut(300);
                    $('#row_incremento_porcentual').removeClass('hide').fadeOut(0).fadeIn(300);
                    $('#row_incremento_fijo').addClass('hide').fadeOut(300);
                    break;
                case 5://incremento fijo
                    $('#text_tipo_calculo').text('Incremento valor fijo');
                    $('#row_presupuesto_calcular').removeClass('hide').fadeOut(300);
                    $('#row_valor_fijo').addClass('hide').fadeOut(300);
                    $('#row_incremento_porcentual').addClass('hide').fadeOut(300);
                    $('#row_incremento_fijo').removeClass('hide').fadeOut(0).fadeIn(300);
                    break;
            }
        }

        //abrir modal para editar cuota
        function editarCuota(auxId){

            action = 'edit';
			$('input[name="_method"]').val('PUT');
            id = auxId;
            consultar(id);
            $('#title_cuotas_admon').text('Editar cuota administrativa');
            $('#cuotas_admon').modal('show');
            //ocultar select de unidad,campo_valor
            $('#campo_valor').fadeIn(200).change();
            $('#campo_unidad').fadeIn(200);
            $('#campo_select_unidad').fadeOut(200);

        }

        //guardar informacion de la cuota(s)
        /***********************************/
        function guardarCuota(){
            if(verificarFormulario('dataCuota',5)){
                if($('#tipo_calculo').val() == 0){
                    swal('Error!','Debe de seleccionar el tipo de calculo.','error');
                    return;
                }

                //mandar a agregar una cuota administrativa
                if(action == 'add'){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('cuota_admon') }}",
                        data: $('#dataCuota').serialize(),
                        dataType: "json"
                    }).done((res)=>{
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
                                $('#cuotas_admon').modal('hide');
                                table.ajax.reload();
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                    }).fail((res)=>{
                        swal('Error!','Ocurrió un error en el servidor','error');
                    });
                }

                //mandar a editar una cuota administrativa
                if(action == 'edit'){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('cuota_admon') }}/"+id,
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
                                $('#cuotas_admon').modal('hide');
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
                url: "{{url('cuota_admon')}}/"+auxId,
                data: "data",
                dataType: "json"
            }).done((data)=>{
                $('#valor').val(data.cuota.valor);
                $('#vigencia_inicio').val(data.cuota.vigencia_inicio);
                $('#vigencia_fin').val(data.cuota.vigencia_fin);
                $('#presupuesto_calcular_id').val(data.cuota.presupuesto_calcular_id);
                $('#presupuesto_cargar_id').val(data.cuota.presupuesto_cargar_id);
                $('#acta_id').val(data.cuota.acta_id);
                $('#unidad').val(data.unidad.tipo+' '+data.unidad.numero_letra);

                $('.select-2').select2({
                    dropdownParent: $('#cuotas_admon'),
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
                        url : "{{ url('cuota_admon') }}" + "/" + auxId,
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