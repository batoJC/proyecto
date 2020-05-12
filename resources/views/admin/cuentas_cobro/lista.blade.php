@if (count($datos['cuentas']) <= 0)
    <h3 class="text-center">No hay cuentas para generar.</h3>
@else
<style>
    .borde{
        border: 2px solid grey;
        margin: 5px;
        border-radius: 10px;
        padding: 15px;
    }

    i{
        cursor: pointer;
    }

    i:hover{
        color: #1ABB9C;
    }

</style>
<div class="container-fluid">
@foreach ($datos['cuentas'] as $cuenta)
    <div data-consecutivo="{{ $cuenta['consecutivo'] }}" id="cuenta_{{ $cuenta['propietario']->id }}">
        @include('admin.cuentas_cobro.cuenta')
    </div>
    <br><br>
@endforeach
</div>
{{-- {{ dd($datos) }} --}}
<div class="row">
    <div class="col-12 text-center">
        <button onclick="guardar();" class="btn btn-success">Guardar</button>
    <form method="GET" action="{{ url('guardarDescargarCuentasCobro') }}">
        <input id="consecutivo_form" name="consecutivo" value="" type="hidden">
        <input id="fecha_pronto_pago_form" name="fecha_pronto_pago" value="" type="hidden">
        <input id="descuento_form" name="descuento" value="" type="hidden">
        @csrf
        <button onclick="return loadData();" type="submit" class="btn btn-primary">Guardar y descargar</button>
    </form>
    </div>
</div>
<br>
<br>

<script>
    $('[data-toggle="tooltip"]').tooltip();

    function eliminarCuota(tipo,id,unidad,propietario){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar esta cuenta?',
            icon : 'warning',
            buttons : true
        }).then(res=>{
            if(res){
                $.ajax({
                    type: "POST",
                    url: "{{url('eliminarCuota')}}",
                    data: {
                        _token : csrf_token,
                        tipo : tipo,
                        id : id,
                        unidad : unidad
                    },
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        if (res.res) {
                            swal('Logrado!',res.msg,'success').then(()=>{
                                cargarCuenta(propietario);
                            });
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                    }
                });
            }
        })
    }


    function editarValor(tipo,id,unidad,propietario){
        input = document.createElement("div");
        input.innerHTML = '<label class="label_alerta">Ingrese el nuevo valor para la cuota:</label>';
        input.append(document.createElement("br"));
        valor = document.createElement("input");
        valor.id = 'valor';
        valor.className = 'form-control';
        valor.type = 'number'
        input.append(valor);
        valor.placeholder = "valor.";

        swal({
            title: "Nuevo valor.",
            content: input,
            buttons: true                       
        }).then((res)=>{
            if(res){
                valor = $('#valor');
                $.ajax({
                    type: "POST",
                    url: "{{url('editarCuota')}}",
                    data: {
                        _token : csrf_token,
                        tipo : tipo,
                        id : id,
                        unidad : unidad,
                        valor : valor.val()
                    },
                    dataType: "json",
                    success: function (res) {
                        if(res.res){
                            cargarCuenta(propietario);
                        }
                    }
                });
            }
        });
    }

    function cargarCuenta(propietario){
        console.log("{{url('previsualizarCuenta')}}/"+`${$('#cuenta_'+propietario).attr('data-consecutivo')}/${propietario}`);
        $.ajax({
            type: "POST",
            url: "{{url('previsualizarCuenta')}}/"+`${$('#cuenta_'+propietario).attr('data-consecutivo')}/${propietario}`,
            data: {
                _token : csrf_token,
                fecha_pronto_pago :  fecha_pronto_pago.value,
                descuento: consecutivo.value
            },
            dataType: "html",
            success: function (response) {
                $('#cuenta_'+propietario).html(response);
            }
        });
    }


    function guardar(){
        $('#loading').css("display", "flex")
        .hide()
        .fadeIn(800,()=>{
            $.ajax({
                type: "POST",
                url: "{{ url('guardarCuentasCobro') }}",
                data: {
                    _token : csrf_token,
                    consecutivo : consecutivo.value,
                    fecha_pronto_pago : fecha_pronto_pago.value,
                    descuento : descuento.value
                },
                dataType: "json",
                success: function (response) {
                    if(response.res){
                        swal('Logrado!',response.msg,'success').then(res=>{
                            location.reload();
                        });
                    }else{
                        swal('Error!',response.msg,'error');
                    }
                    $('#loading').fadeIn(800);
                }
            });
        });
    }

    function loadData(){
        consecutivo_form.value = consecutivo.value;
        fecha_pronto_pago_form.value = fecha_pronto_pago.value;
        descuento_form.value = descuento.value;
        return true;
    }


</script>
@endif