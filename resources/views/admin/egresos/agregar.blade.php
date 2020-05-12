<h3 class="text-center">Agregar Egreso</h3>
<br>
<form id="dataEgreso" class="container" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-6 col-md-6">
            <div class="col-md-4 text-center validate-label-1">
                <i class="fa fa-calendar"></i>
                <label class="margin-top">
                    Fecha
                </label>
            </div>
            <div class="col-md-8">
            <input name="fecha" id="fecha" value="{{ date('Y-m-d') }}" type="date" class="form-control validate-input-1">
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="col-md-4 text-center validate-label-2">
                <i class="fa fa-sort-numeric-asc"></i>
                <label class="margin-top">
                    Consecutivo
                </label>
            </div>
            <div class="col-md-8">
                <select name="consecutivo" id="consecutivo" class="form-control select-2 validate-input-2">
                    <option value="">Seleccione un consecutivo</option>
                    @foreach($consecutivos as $consecutivo)
                        <option value="{{ $consecutivo->id }}">
                            {{ $consecutivo->prefijo }} {{ $consecutivo->numero }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col col-md-6">
            <div class="col-md-4 text-center validate-label-3">
                <i class="fa fa-barcode"></i>
                <label class="margin-top">
                    Número de factura
                </label>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control validate-input-3" name="nro_factura" id="nro_factura">
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="col-md-4 text-center validate-label-4">
                <i class="fa fa-user"></i>
                <label class="margin-top">
                    Proveedor
                </label>
            </div>
            <div class="col-md-8">
                <select name="proveedor" id="proveedor" class="form-control select-2 validate-input-4">
                    <option value="">Seleccione un proveedor</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}">
                            {{ $proveedor->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <br>
    <h3 class="text-center">Detalles</h3>
    <button onclick="abrirModal('detalles')" type="button" class="btn btn-primary">Agregar detalle</button>
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Concepto</th>
                <th>Valor</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="listaDetalles">
            
        </tbody>
    </table>
    <br>

    <h5>Soporte</h5>
    <label class="btn btn-default" for="soporte">Seleccione un soporte</label>
    <input style="display:none;" type="file" name="soporte" id="soporte"><h4 class="red" id="name_soporte">Nombre archivo:</h4>
    
    <br>
    <div class="row">
        <div class="col text-center">
            <button id="btn_guardar" onclick="guardar();" type="button" class="btn btn-success">Guardar</button>
        </div>
    </div>
</form>
<br>
<br>
<br>

<div id="detalles" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Agregar Detalle</h4>
		</div>
		<div class="modal-body">
			<form id="detallesForm" class="container-fluid">
				@csrf			
				
				<div class="row">
					<div class="col-12 col-md-12">
						<label for="codigo">Código</label>
						<input class="form-control" type="text"  id="codigo" name="codigo">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 col-md-12">
						<label class="validate-label-1" for="concepto">Concepto</label>
						<input class="form-control validate-input-1" type="text"  id="concepto" name="concepto">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 col-md-12">
						<label class="validate-label-2" for="valor">Valor $</label>
						<input class="form-control validate-input-2" onchange="changeValor(this,'valor');" type="text"  id="valor_aux" name="valor_aux">
						<input class="form-control" type="hidden"  id="valor" name="valor">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 col-md-12">
                        <label class="validate-label-3" for="presupuesto">Presupuesto a cargar </label>
                        <select class="form-control select-2 validate-input-3" name="presupuesto" id="presupuesto">
                            <option value="">Seleccione el presupuesto...</option>
                            @foreach ($presupuestos as $presupuesto)
                            <option value="{{ $presupuesto->id }}">{{$presupuesto->Tipo_ejecucion_pre->tipo}}</option>
                            @endforeach
                        </select>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12 text-center">
						<button onclick="agregarDetalle()" type="button" class="btn btn-success">Agregar</button>
					</div>
				</div>

			</form>
	
		</div>
	
		</div>
	</div>
</div>
<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
<script>
        
    $('#valor_aux').maskMoney({precision:0});
    $('.select-2').select2();
    $('#presupuesto').select2({
        dropdownParent: $('#detallesForm')
    });

    $(function() {
        $('#soporte').change(function(e) {
            addImage(e); 
        });

        function addImage(e){
            var file = e.target.files[0],
            imageType = /image.*/;
        
            // if (!file.type.match(imageType))
            // return;
        
            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
        }
        
        function fileOnload(e) {
            name_soporte.innerText = `Nombre archivo: ${soporte.files[0].name}`;
            var result = e.target.result;
            $('#imgSalida').attr("src", result);
            $('#imgSalida').fadeIn(600);
            $('.btn-speacial').fadeIn(600);
            $('.btn-logotype-brand').fadeOut(200);
        }
    });

    var dataDetalles = new FormData();
    var nro_detalles = 0;
    var aux = null;


    //agregar detalle al egreso
    function agregarDetalle(){
        if(verificarFormulario('detallesForm',3)){
            nro_detalles++;
            dataDetalles.append(nro_detalles,`${codigo.value}##${concepto.value}##${valor.value}##${presupuesto.value}`);
            $('#listaDetalles').append(`
                <tr data-row="detalle-${nro_detalles}">
                    <td>${codigo.value}</td>
                    <td>${concepto.value}</td>
                    <td>$${new Intl.NumberFormat('COP').format(valor.value)}</td>
                    <td>
                        <a class="btn btn-danger" onclick="eliminarDetalle(${nro_detalles})"><i class="fa fa-trash" ></i></a>
                    </td>
                </tr>
            `);
            $('#detalles').modal('hide');
        }
    }


    function eliminarDetalle(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer quitar este detalle?',
            icon : 'warning',
            buttons : true
        }).then(res => {
            if (res) {
                dataDetalles.delete(id);
                $(`[data-row=detalle-${id}]`).remove();
            }
        });
    }


    function guardar(){
        if(verificarFormulario('dataEgreso',4)){
            btn_guardar.disabled = true;
            let i = 1; 
            let data = new FormData(dataEgreso);
            dataDetalles.forEach(element => {
                data.append('detalle_'+i,element);
                i++;
            });

            data.append('nro_detalles',i);

            if (i == 1) {
                swal('Error!','Debes de agregar al menos un detalle','error');
                return;                
            }else{
                $.ajax({
                    type: "POST",
                    url: "{{url('egresos')}}",
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    data: data,
                }).done((res)=>{
                    if(res.res){
                        swal('Logrado!',res.msg,'success').then(()=>{
                            $.ajax({
                                type: "GET",
                                url: "{{ url('egresos') }}/"+res.id,
                                data: {
                                    _token : csrf_token
                                },
                                dataType: "html",
                                success: function (response) {
                                    $('#loadData').html(response);
                                }
                            });
                        });
                    }else{
                        swal('Error!',res.msg,'error');	
                        btn_guardar.disabled = false;
                    }
                }).fail((res)=>{
                    swal('Error!','Ocurrió un error en el servidor','error');
                        btn_guardar.disabled = false;
                });
            }
        }
    }
</script>