<br>

<style>
    .borde{
        border-radius: 10px;
        border: 2px solid grey;
    }
</style>

<div class="container-fluid borde">

    @if ($egreso->anulado)
        <h1 class="red text-center">Anulado</h1>
        <h3><b>Motivo: </b>{{ $egreso->detalle }}</h3>
    @endif
    <div class="row">
        <div class="col-6 col-md-6">
            <h4><b>Consecutivo: </b> {{ $egreso->prefijo }} {{ $egreso->numero }}</h4>
            <h4><b>Fecha: </b> {{ date('d-m-Y',strtotime($egreso->fecha)) }}</h4>
            <h4><b>Factura: </b> {{ $egreso->factura }}</h4>
            <h4><b>Proveedor: </b> {{ $egreso->proveedor->nombre_completo }}</h4>
        </div>
        <div class="col-6 col-md-6">
            <h4><b>Soporte: </b></h4>
            @if ($egreso->soporte != '')
                <a class="btn btn-default" href="{{ 'imgs/private_imgs/'.$egreso->soporte }}" target="_black">ver soporte <i class="fa fa-eye"></i></a>
                {{-- <img class="show_img foto" src="{{ 'imgs/private_imgs/'.$egreso->soporte }}" alt="soporte"> --}}
            @else
                <h3>No se cargo soporte.</h3>
            @endif
        </div>
    </div>
    <br>
        <h3 class="text-center">Detalles</h3>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Concepto</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($egreso->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->codigo }}</td>
                    <td>{{ $detalle->concepto }}</td>
                    <td>$ {{ number_format($detalle->valor) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Retención: $ {{ number_format($egreso->retencion) }}</h3>
    <h3>Valor total: $ {{ number_format($egreso->valorTotal()) }}</h3>
    <br>
    <div class="row">
        <div class="col-12 text-center">
            <a href="{{ url('egresosPdf',$egreso->id) }}" target="_blanck" class="btn btn-default" type="button">
                <i class="fa fa-download"></i> Decargar en PDF</a>
                @if (!$egreso->anulado)
                    <button onclick="anular()" class="btn btn-danger">Anular</button>
                @endif
        </div>
    </div>
</div>
<br>
<br>
<br>
<script>
    $('.show_img').click(function(e) {
        $('#show_image img')[0].src = $(e)[0].currentTarget.src;
        $('#show_image').css("display", "flex")
    .hide().fadeIn(400);
    });


    function anular(){
        input = document.createElement("div");
        input.innerHTML = '<label class="label_alerta">Ingrese el motivo de anulación del egreso:</label>';
        input.append(document.createElement("br"));
        detalle = document.createElement("textarea");
        detalle.id = 'detalle';
        detalle.className = 'form-control';
        input.append(detalle);
        detalle.placeholder = "Ingrese por el motivo.";


        swal({
            title: "Ingrese el motivo.",
            content: input,
            buttons: true
        }).then((res)=>{
            detalle = $('#detalle');
            console.log(detalle.val());
            if (res){
                $.ajax({
                    type: "POST",
                    url: "{{url('anularEgreso',$egreso->id)}}",
                    data: {
                        _token : csrf_token,
                        detalle : detalle.val()
                    },
                    dataType: "json"
                }).done((res)=>{
                    console.log(res);
                    if(res.res){
                        swal('Logrado!',res.msg,'success').then(()=>{
                            $.ajax({
                                type: "GET",
                                url: "{{ url('egresos',$egreso->id) }}/",
                                data: {
                                    _token : csrf_token
                                },
                                dataType: "html",
                                success: function (response) {
                                    $('#loadData').html(response);
                                    $(`#buscar`).modal('hide');

                                }
                            });
                        });
                    }else{
                        swal('Error!',res.msg,'error');
                    }
                }).fail((res)=>{
                    console.log(res);
                    swal('Error!','Ocurrió un error en el servidor','error');
                });
            }
        });
    }

</script>
