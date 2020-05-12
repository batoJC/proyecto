@extends('../layouts.app_dashboard_dueno')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('dueno') }}">Inicio</a>
                </li>
                  <li>Mis Multas</li>
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
    @include('dueno.multas.form')
    
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
	<script>
        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.multas.dueno') }}",
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
                $('#valor').val(data.valor);
                $('#vigencia_inicio').val(data.vigencia_inicio);
                $('#vigencia_fin').val(data.vigencia_fin);
                $('#concepto').val(data.concepto);
                $('#descripcion').val(data.descripcion);

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

	</script>
@endsection