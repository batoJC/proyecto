@extends('../layouts.app_dashboard_admin')

@section('title', 'Otros Cobros')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('admin') }}">Inicio</a>
                </li>
                  <li>Multas</li>
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
                        <a target="_blanck" href="https://youtu.be/8-uHeISTNF8">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <a class="btn btn-success" onclick="agregarCuota()">
        <i class="fa fa-plus"></i>
        Agregar Multa
    </a>
    <br><br>
    @include('admin.multas.form')
    <table id="multas-table" class="table">
        <thead>
            <th>Concepto</th>
            <th>Propietario</th>
            <th>Valor</th>
            <th>Vigenecia inicio</th>
            <th>Vigencia Fin</th>
            <th>Acciones</th>
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
                url: "{{ url('api.multas.admin') }}",
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
		var table  = $('#multas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'concepto', name: 'concepto'},
          		{ data: 'propietario', name: 'propietario'},
          		{ data: 'valor', name: 'valor'},
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

        function nombreArchivo(){
            name_file.innerText = archivo.files[0].name;
        }

        //token para ajax
        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        var action = 'add';
        var id;

        //cambio de valor en intereses
        function intereses(){
            let valor = document.querySelector('#interes').checked;
            if(valor){//mostrar
                $('#row_vigencia_fin').removeClass('hide').fadeOut(0).fadeIn(300);
            }else{//ocultar
                $('#row_vigencia_fin').fadeOut(300,()=>{
                    $('#row_vigencia_fin').addClass('hide');
                });
            }
            // console.log(document.querySelector('#interes').checked);
        }


        //abrir modal para agregar cuota
        function agregarCuota(){

            action = 'add';
			$('input[name="_method"]').val('POST');
            // $('#campo_select_unidad').fadeIn(300);
            // $('#campo_unidad').fadeOut(300);
            $('#title_modal_multas').text('Agregar Multa');
            $('#multas').modal('show');
            $('#dataCuota').trigger('reset');
            $('.select-multiple').select2({
                dropdownParent: $('#multas'),
                multiple: true
            });

            $('.select-2').select2({
                dropdownParent: $('#multas'),
            });
            $('#row_enviar').show();
            intereses();
        }

        //abrir modal para editar cuota
        function editarCuota(auxId){

            action = 'edit';
			$('input[name="_method"]').val('PUT');
            $('#campo_select_unidad').fadeOut(300);
            $('#campo_unidad').fadeIn(300);
            $('#dataCuota').trigger('reset');

            id = auxId;
            consultar(id);
            $('#title_modal_multas').text('Editar Multa');
            $('#multas').modal('show');

        }

        //guardar informacion de la cuota(s)
        /***********************************/
        function guardarCuota(){

            if(verificarFormulario('dataCuota',5)){
                document.querySelector('#btn_guardar').disabled = true;
                //mandar a agregar una cuota administrativa
                if(action == 'add'){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('multas') }}",
                        data: new FormData(dataCuota),
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done((res)=>{
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
                                table.ajax.reload();
                                $('#multas').modal('hide');
                                document.querySelector('#btn_guardar').disabled = false;
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                                document.querySelector('#btn_guardar').disabled = false;
                        }
                    }).fail((res)=>{
                        swal('Error!','Ocurrió un error en el servidor','error');
                                document.querySelector('#btn_guardar').disabled = false;
                    });
                }

                //mandar a editar una cuota administrativa
                if(action == 'edit'){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('multas') }}/"+id,
                        // Envio de formulario en objeto MUY OP
                        // ************************************
                        data: new FormData(dataCuota),
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done((res)=>{
                        console.log(res);
                        if(res.res){
                            swal('Logrado!',res.msg,'success').then(()=>{
                                table.ajax.reload();
                                $('#multas').modal('hide');
                                document.querySelector('#btn_guardar').disabled = false;
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                            document.querySelector('#btn_guardar').disabled = false;
                        }
                    }).fail((res)=>{
                        console.log(res);
                        swal('Error!','Ocurrió un error en el servidor','error');
                            document.querySelector('#btn_guardar').disabled = false;
                    });
                }



            }
        }


        //consultar Informacion de una cuota
        /***********************************/
        function consultar(auxId){
            $.ajax({
                type: "GET",
                url: "{{url('multas')}}/"+auxId,
                data: "data",
                dataType: "json"
            }).done((data)=>{
				// console.log(data);
                $('#valor_aux').val(data.valor).change();
                $('#vigencia_inicio').val(data.vigencia_inicio);
                $('#vigencia_fin').val(data.vigencia_fin);
                $('#presupuesto_cargar_id').val(data.presupuesto_cargar_id);
                $('#concepto').val(data.concepto);
                $('#descripcion').val(data.descripcion);
                $('#propietarios').val(data.user_id);
                $('#unidad').val(data.unidad);
                $('#unidades').val(['todas']);

                // if(data.interes){
                //     $('#interes').click();
                // }

                document.querySelector('#interes').checked = data.interes;
                intereses();

                $('.select-multiple').select2({
                    dropdownParent: $('#dataCuota'),
                    multiple: true
                });

                $('.select-2').select2({
                    dropdownParent: $('#dataCuota'),
                });

                //mostrar modal
                $('#multas').modal('show');
                $('#title_modal_multas').text('Mostrar Multa');
                $('#row_enviar').hide();

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
                        url : "{{ url('multas') }}" + "/" + auxId,
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