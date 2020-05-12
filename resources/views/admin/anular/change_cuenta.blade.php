{{-- cargar cuenta nueva a partir de una fecha --}}
{{-- guardar esa cuenta y anular la que hay --}}

<br>
<div class="container">
    <form id="data_cuenta">
    <input type="hidden" name="propietario_cuenta" id="propietario_cuenta" value="{{ $cuenta->propietario_id }}">
        <div class="row">
            <div class="col-md-6 text-center">
                <div class="col-md-4 text-center validate-label-1">
                    <i class="fa fa-sort-numeric-asc"></i>
                    <label class="margin-top">
                        Consecutivo
                    </label>
                </div>
                <div class="col-md-6">
                    <select name="consecutivo_cuenta" id="consecutivo_cuenta" class="form-control select-2 validate-input-1">
                        <option value="">Seleccione un consecutivo</option>
                        @foreach($consecutivos as $consecutivo)
                            <option value="{{ $consecutivo->id }}">
                                {{ $consecutivo->prefijo }} {{ $consecutivo->numero }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="col-md-4 text-center validate-label-2">
                    <i class="fa fa-calendar"></i>
                    <label class="margin-top">
                        Fecha
                    </label>
                </div>
                <div class="col-md-6">
                    <input name="fecha_cuenta" id="fecha_cuenta" value="{{ $cuenta->fecha }}" type="date" class="form-control  validate-input-2" min="{{ $cuenta->fecha }}">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 text-center">
                <div class="col-md-4 text-center validate-label-2">
                    <i class="fa fa-calendar"></i>
                    <label class="margin-top">
                        Fecha pronto pago
                    </label>
                </div>
                <div class="col-md-6">
                <input name="fecha_pronto_pago" value="{{ $cuenta->fecha_pronto_pago }}" id="fecha_pronto_pago" type="date" class="form-control  validate-input-2">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="col-md-4 text-center validate-label-3">
                    <i class="">%</i>
                    <label class="margin-top">
                        Porcentaje de descuento
                    </label>
                </div>
                <div class="col-md-6">
                    <input name="descuento" value="{{ $cuenta->descuento }}" id="descuento" type="number" class="form-control validate-input-3">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-6 text-center">
                <button type="button" onclick="visualizar();" class="btn btn-success"><i class="fa fa-spinner"></i> Generar cuenta de cobro</button>
            </div>
        </div>
        <br>
    </form>
    <div id="data_cuentas"></div>
</div>

<script>
    function visualizar(){
        if(verificarFormulario('data_cuenta',3)){
            $.ajax({
                type: "POST",
                url: "{{ url('anularLoadCuenta') }}",
                data: {
                    _token : csrf_token,
                    fecha : fecha_cuenta.value,
                    propietario : propietario_cuenta.value,
                    consecutivo : consecutivo_cuenta.value,
                    fecha_pronto_pago : fecha_pronto_pago.value,
                    descuento : descuento.value
                },
                dataType: "html",
                success: function (response) {
                    // console.log(response);
                    $('#data_cuentas').html(response);
                }
            });
        }
    }

</script>