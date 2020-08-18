@extends('../layouts.app_dashboard_admin')

@section('title', 'Exportar')
<style>
    .tarjeta{
        transition: 0.3s all;
        border: 2px solid #1ABB9C;
        width: 100%;
        margin: 10px auto 10px auto;
        padding: 20px;
        border-radius: 5px;
    }

    .tarjeta:hover {
        background: #1ABB9C;
        cursor: pointer;
    }

    .selected {
        background: #1ABB9C;
        cursor: pointer;
        color: white;
    }
    .selected > h3{

        color: white !important;
    }

    .selected > .ayuda{
        color: white !important;
    }

    .selected > .icon{
        color: white !important;
    }

    .selected > .btn{
        background: white !important;
        color: #1ABB9C !important;
    }

    .tarjeta:hover > h3{
        color: white;
    }

    .tarjeta:hover > .ayuda{
        color: white;
    }

    .tarjeta:hover > .icon{
        color: white;
    }

    .tarjeta:hover > .btn{
        background: white;
        color: #1ABB9C;
    }

    .tarjeta > input {
        float: left;
        -ms-transform: scale(1.5); /* IE */
        -moz-transform: scale(1.5); /* FF */
        -webkit-transform: scale(1.5); /* Safari y Chrome */
        -o-transform: scale(1.5); /* Opera */
        padding: 10px;
    }

    .btn:hover {
        color: black !important;
    }

    .tarjeta > .ayuda{
        font-size: 20px !important;
        float: right;
        cursor: pointer;
    }

    .tarjeta > h3 {
        color: #1ABB9C;
        font-size: 22px;
    }
    
    .tarjeta > .btn{
        background: #1ABB9C;
        color: white;
    }

    .p-30{
        padding: 30px;
    }


    .icon{
        color: #1ABB9C;
        font-size: 64px !important;
        font-weight: 100 !important;
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
				  <li>Exportar</li>
			</ul>
		</div>
	</div>
	<div class="container-fluid bg-white p-30">
        <br>
        <div class="row" id="div_download_completed">
            <div class="col-12 col-md-12">
                <button id="btn_all" onclick="seleccionarTodos();" class="btn"><i class="fa fa-check-square"></i> Seleccionar todos</button>
                <button id="btn_nothing" onclick="deseleccionarTodos();" class="btn hide"><i class="fa fa-square-o"></i> Deseleccionar todos</button>
                <button onclick="downloadSeveral(seleccionados());" class="btn btn-success"><i class="fa fa-download"></i> Descargar seleccionados</button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 col-md-4">
                <label for="unidades" class="tarjeta text-center" id="div_unidades">
                    <input onchange="seleccionar(this)" type="checkbox" name="unidades" id="unidades" value="1">
                    <i
                        data-placement="left" 
                        title="Todas las unidades con los listados de inquilinos, mascotas, etc." 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-building icon"></i>
                    <h3 class="text-center">Unidades</h3>
                    <button onclick="crearData('unidades')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="pqr" class="tarjeta text-center" id="div_pqr">
                    <input onchange="seleccionar(this)" type="checkbox" name="pqr" id="pqr" value="1">
                    <i
                        data-placement="left" 
                        title="Todas las PQR registradas" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-commenting-o icon"></i>
                    <h3 class="text-center">PQR</h3>
                    <button onclick="crearData('pqr')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="novedades_conjunto" class="tarjeta text-center" id="div_novedades_conjunto">
                    <input onchange="seleccionar(this)" type="checkbox" name="novedades_conjunto" id="novedades_conjunto" value="1">
                    <i
                        data-placement="left" 
                        title="Novedades registradas sobre el conjunto" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-list-alt icon"></i>
                    <h3 class="text-center">Novedades conjunto</h3>
                    <button onclick="crearData('novedades_conjunto')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <label for="evidencias" class="tarjeta text-center" id="div_evidencias">
                    <input onchange="seleccionar(this)" type="checkbox" name="evidencias" id="evidencias" value="1">
                    <i
                        data-placement="left" 
                        title="Todas las evidencias registradas" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-shield icon"></i>
                    <h3 class="text-center">Evidencias</h3>
                    <button onclick="crearData('evidencias')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="novedades_unidades" class="tarjeta text-center" id="div_novedades_unidades">
                    <input onchange="seleccionar(this)" type="checkbox" name="novedades_unidades" id="novedades_unidades" value="1">
                    <i
                        data-placement="left" 
                        title="Novedades registradas, separadas por cada unidad" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-list-alt icon"></i>
                    <h3 class="text-center">Novedades unidades</h3>
                    <button onclick="crearData('novedades_unidades')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="ingresos_y_retiros" class="tarjeta text-center" id="div_ingresos_y_retiros">
                    <input onchange="seleccionar(this)" type="checkbox" name="ingresos_y_retiros" id="ingresos_y_retiros" value="1">
                    <i
                        data-placement="left" 
                        title="Todas las cartas generadas separadas por cada unidad" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-exchange icon"></i>
                    <h3 class="text-center">Ingresos y retiros</h3>
                    <button onclick="crearData('ingresos_y_retiros')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <label for="empleados" class="tarjeta text-center" id="div_empleados">
                    <input onchange="seleccionar(this)" type="checkbox" name="empleados" id="empleados" value="1">
                    <i
                        data-placement="left" 
                        title="Lista de los empleados del conjunto" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-users icon"></i>
                    <h3 class="text-center">Empleados</h3>
                    <button onclick="crearData('empleados')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="mantenimientos" class="tarjeta text-center" id="div_mantenimientos">
                    <input onchange="seleccionar(this)" type="checkbox" name="mantenimientos" id="mantenimientos" value="1">
                    <i
                        data-placement="left" 
                        title="Descargue todos los soportes de mantenimientos" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-wrench icon"></i>
                    <h3 class="text-center">Mantenimientos</h3>
                    <button onclick="crearData('mantenimientos')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="zonas_sociales" class="tarjeta text-center" id="div_zonas_sociales">
                    <input onchange="seleccionar(this)" type="checkbox" name="zonas_sociales" id="zonas_sociales" value="1">
                    <i
                        data-placement="left" 
                        title="Listado de las zonas sociales y su valor de uso" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-cube icon"></i>
                    <h3 class="text-center">Zonas sociales</h3>
                    <button onclick="crearData('zonas_sociales')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <label for="reservas" class="tarjeta text-center" id="div_reservas">
                    <input onchange="seleccionar(this)" type="checkbox" name="reservas" id="reservas" value="1">
                    <i
                        data-placement="left" 
                        title="Listado de las reservas pendientes y aceptadas" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-calendar icon"></i>
                    <h3 class="text-center">Reservas</h3>
                    <button onclick="crearData('reservas')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="inventario" class="tarjeta text-center" id="div_inventario">
                    <input onchange="seleccionar(this)" type="checkbox" name="inventario" id="inventario" value="1">
                    <i
                        data-placement="left" 
                        title="Inventario del conjunto" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-barcode icon"></i>
                    <h3 class="text-center">Inventario</h3>
                    <button onclick="crearData('inventario')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="deudas" class="tarjeta text-center" id="div_deudas">
                    <input onchange="seleccionar(this)" type="checkbox" name="deudas" id="deudas" value="1">
                    <i
                        data-placement="left" 
                        title="Listado de las deudas que se tienen" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-usd icon"></i>
                    <h3 class="text-center">Deudas</h3>
                    <button onclick="crearData('deudas')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <label for="flujo_efectivo" class="tarjeta text-center" id="div_flujo_efectivo">
                    <input onchange="seleccionar(this)" type="checkbox" name="flujo_efectivo" id="flujo_efectivo" value="1">
                    <i
                        data-placement="left" 
                        title="Reporte del flujo de efectivo" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-pie-chart icon"></i>
                    <h3 class="text-center">Flujo efectivo</h3>
                    <button onclick="crearData('flujo_efectivo')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="jornadas" class="tarjeta text-center" id="div_jornadas">
                    <input onchange="seleccionar(this)" type="checkbox" name="jornadas" id="jornadas" value="1">
                    <i
                        data-placement="left" 
                        title="Las jornadas registradas por cada empleado" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-archive icon"></i>
                    <h3 class="text-center">Jornadas</h3>
                    <button onclick="crearData('jornadas')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
            <div class="col-12 col-md-4">
                <label for="liquidaciones" class="tarjeta text-center" id="div_liquidaciones">
                    <input onchange="seleccionar(this)" type="checkbox" name="liquidaciones" id="liquidaciones" value="1">
                    <i
                        data-placement="left" 
                        title="Listado de las liquidaciones separadas por cada empleado" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-file-text icon"></i>
                    <h3 class="text-center">Liquidaciones</h3>
                    <button onclick="crearData('liquidaciones')" class="btn">Descargar <i class="fa fa-download"></i></button>
                </label>
            </div>
        </div>
    </div>
</div>


	
@endsection
@section('ajax_crud')
	<script>

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        function seleccionar(input){
            if(input.checked){
                $('#div_'+input.id).addClass('selected');
            }else{
                $('#div_'+input.id).removeClass('selected');
            }
        }


        function seleccionarTodos(){
            let inputs = $('input[type=checkbox]');
            for (let i = 0; i < inputs.length; i++) {
                const e = inputs[i];
                e.checked = true;
                seleccionar(e);
            }
            $('#btn_nothing').removeClass('hide');
            $('#btn_all').addClass('hide');
        }

        function deseleccionarTodos(){
            let inputs = $('input[type=checkbox]');
            for (let i = 0; i < inputs.length; i++) {
                const e = inputs[i];
                e.checked = false;
                seleccionar(e);
            }
            $('#btn_nothing').addClass('hide');
            $('#btn_all').removeClass('hide');
        }

        
        function seleccionados(){
            let inputs = $('input[type=checkbox]');
            let data = new FormData();
            for (let i = 0; i < inputs.length; i++) {
                const e = inputs[i];
                if(e.checked){
                   data.append(e.id,true);
                }
            }
            return data;
        }

        function crearData(id){
            let data = new FormData();
            data.append(id,true);
            downloadSeveral(data);
        }

        //descargar seleccionados
        function downloadSeveral(data){
            swal('Advertencia!','Este proceso puede tomar un gran tiempo según la información solicitada.','info').then(res=>{
                if(JSON.stringify(Object.fromEntries(data)) != '{}'){
                    $('#loading').css("display", "flex")
                    .hide().fadeIn(800,()=>{
                        $('#loading').css('display','flex');
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', "{{ url('downloadSeveral') }}");
                        data.append('_token',csrf_token);
                        // xhr.responseType = 'blob';

                        xhr.onload = function(e) {
                            let data = JSON.parse(this.response);
                            if (this.status == 200) {
                                var link = document.createElement('a');
                                link.href = '../'+data.data;
                                link.download = "export.zip";
                                link.click();
                                $('#loading').fadeOut(800);
                            }else{
                                swal('Error!',data.msg,'error');
                                $('#loading').fadeOut(800);
                            }
                        };

                        xhr.send(data);
                    });
                }else{
                    swal('Advertencia!','Debe de seleccionar al menos un item para descargar!','warning');
                }
            });
        }



    </script>
@endsection
