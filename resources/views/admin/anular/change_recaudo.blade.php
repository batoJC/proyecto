@if (!$recaudo)
    
<h3>Nuevo recaudo</h3>
{{-- {{ $recaudo }} --}}

<form id="data_recaudo" class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 text-center">
                <i class="fa fa-calendar"></i>
                <label class="margin-top">
                    Fecha
                </label>
            </div>
            <div class="col-md-8">
            <input id="fecha_recaudo" name="fecha_recaudo" type="date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-1" >
                <i class="fa fa-sort-numeric-asc"></i>
                <label class="margin-top">
                    Consecutivo
                </label>
            </div>
            <div class="col-md-8">
                <select name="consecutivo_recaudo" id="consecutivo_recaudo" class="form-control validate-input-1">
                    <option value="">Seleccione un consecutivo</option>
                    @foreach ($consecutivos as $consecutivo)
                        <option value="{{ $consecutivo->id }}">{{ $consecutivo->prefijo.'-'.$consecutivo->numero }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-2">
                <i class="fa fa-user"></i>
                <label class="margin-top">
                    Popietario
                </label>
            </div>
            <div class="col-md-8">
                <select name="propietario_recaudo" id="propietario_recaudo" class="form-control select-2 validate-input-2">
                        <option value="{{ $cuenta->propietario->id }}">{{ $cuenta->propietario->nombre_completo.' '.$cuenta->propietario->numero_cedula }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-3">
                <i class="fa fa-institution"></i>
                <label class="margin-top">
                    Tipo de pago
                </label>
            </div>
            <div class="col-md-8">
                <select onchange="mostrarBanco(this)" name="tipo_de_pago" id="tipo_de_pago" class="form-control validate-input-3">
                    <option value="Pago por Transferencia Bancaria">Pago por Transferencia Bancaria</option>
                    <option value="Pago en Efectivo">Pago en Efectivo</option>
                    <option value="Consignación">Consignación</option>
                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-4">
                <i class="fa fa-money"></i>
                <label class="margin-top">
                    Valor recibido
                </label>
            </div>
            <div class="col-md-8">
            <input class="form-control validate-input-4" type="number" name="valor" id="valor">
            </div>
        </div>
        <div class="col-md-6 text-center" id="div_banco">
            <div class="col-md-4 text-center">
                <i class="fa fa-institution"></i>
                <label class="margin-top">
                      Banco
                </label>
            </div>
            <div class="col-md-8">
                <select name="banco" id="banco" class="form-control select-2 validate-input-2">
                    <option value="">Seleccione un banco</option>
                    @foreach ($bancos as $banco)
                        <option value="{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}">{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 text-center">
            <button class="btn btn-success " onclick="cargarCuentas();" type="button">Cargar cuentas</button>
        </div>
    </div>
</form>
<br>
<div class="container-fluid" id="data_container">

</div>

<script>

function mostrarBanco(select){
    if(select.value != 'Pago en Efectivo'){
        $('#div_banco').removeClass('hide');
    }else{
        $('#div_banco').addClass('hide');
    }
}

function cargarCuentas(){
    if(tipo_de_pago.value != 'Pago en Efectivo' && banco.value == ''){
        swal('Error!','Debe de ingresar el banco','error').then(res=>{
            banco.focus();
        });
        return;
    }

    if(verificarFormulario('data_recaudo',4)){

        $.ajax({
            type: "POST",
            url: "{{url('anularLoadRecaudoAdd',['cuenta' => $cuenta->id])}}",
            data: {
                _token : csrf_token
            },
            dataType: "html",
            success: function (response) {
                // console.log(response);
                $('#data_container').html(response);
            }
        });
    }
}

</script>

@else
    {{-- generar recaudo con los datos del anterior pero que se pueda modificar al igual se cargara ya sea la 
cuenta de cobro actual o la nueva --}}

<h3>Nuevo recaudo</h3>
{{-- {{ $recaudo }} --}}

<form id="data_recaudo" class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 text-center">
                <i class="fa fa-calendar"></i>
                <label class="margin-top">
                    Fecha
                </label>
            </div>
            <div class="col-md-8">
            <input id="fecha_recaudo" name="fecha_recaudo" type="date" class="form-control" value="{{ $recaudo->fecha }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-1" >
                <i class="fa fa-sort-numeric-asc"></i>
                <label class="margin-top">
                    Consecutivo
                </label>
            </div>
            <div class="col-md-8">
                <select name="consecutivo_recaudo" id="consecutivo_recaudo" class="form-control validate-input-1">
                    <option value="">Seleccione un consecutivo</option>
                    @foreach ($consecutivos as $consecutivo)
                        <option value="{{ $consecutivo->id }}">{{ $consecutivo->prefijo.' '.$consecutivo->numero }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-2">
                <i class="fa fa-user"></i>
                <label class="margin-top">
                    Popietario
                </label>
            </div>
            <div class="col-md-8">
                <select name="propietario_recaudo" id="propietario_recaudo" class="form-control select-2 validate-input-2">
                        <option value="{{ $recaudo->propietario->id }}">{{ $recaudo->propietario->nombre_completo.' '.$recaudo->propietario->numero_cedula }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-3">
                <i class="fa fa-institution"></i>
                <label class="margin-top">
                    Tipo de pago
                </label>
            </div>
            <div class="col-md-8">
                <select onchange="mostrarBanco(this)" name="tipo_de_pago" id="tipo_de_pago" class="form-control validate-input-3">
                    <option value="Pago por Transferencia Bancaria" @if ($recaudo->tipo_depago == "Pago por Transferencia Bancaria")
                        selected
                    @endif>Pago por Transferencia Bancaria</option>
                    <option value="Pago en Efectivo" @if ($recaudo->tipo_depago == "Pago en Efectivo")
                        selected
                    @endif>Pago en Efectivo</option>
                    <option value="Consignación" @if ($recaudo->tipo_depago == "Consignación")
                        selected
                    @endif>Consignación</option>
                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 text-center validate-label-4">
                <i class="fa fa-money"></i>
                <label class="margin-top">
                    Valor recibido
                </label>
            </div>
            <div class="col-md-8">
            <input class="form-control validate-input-4" type="number" value="{{ $recaudo->valor }}" name="valor" id="valor">
            </div>
        </div>
        <div class="col-md-6 text-center" id="div_banco">
            <div class="col-md-4 text-center">
                <i class="fa fa-institution"></i>
                <label class="margin-top">
                      Banco
                </label>
            </div>
            <div class="col-md-8">
                <select name="banco" id="banco" class="form-control select-2 validate-input-2">
                    <option value="">Seleccione un banco</option>
                    @foreach ($bancos as $banco)
                    @if ($recaudo->banco == ($banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo))
                        <option value="{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}" selected>{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}</option>
                    @else
                        <option value="{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}">{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 text-center">
            <button class="btn btn-success " onclick="cargarCuentas();" type="button">Cargar cuentas</button>
        </div>
    </div>
</form>
<br>
<div class="container-fluid" id="data_container">

</div>

<script>

function mostrarBanco(select){
    if(select.value != 'Pago en Efectivo'){
        $('#div_banco').removeClass('hide');
    }else{
        $('#div_banco').addClass('hide');
    }
}

function cargarCuentas(){
    if(tipo_de_pago.value != 'Pago en Efectivo' && banco.value == ''){
        swal('Error!','Debe de ingresar el banco','error').then(res=>{
            banco.focus();
        });
        return;
    }

    if(verificarFormulario('data_recaudo',4)){

        $.ajax({
            type: "POST",
            url: "{{url('anularLoadRecaudo')}}/"+recaudo.id,
            data: {
                _token : csrf_token
            },
            dataType: "html",
            success: function (response) {
                // console.log(response);
                $('#data_container').html(response);
            }
        });
    }
}

</script>

@endif
