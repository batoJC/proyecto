<style>
    textarea{
        width: 100% !important;
    }

    .label_alerta{
        width: 100%;
        text-align: left;
    }

</style>
@php
    $usuario = Auth::user();
@endphp

<h4><b>Estado: </b>{{ mb_strtoupper($reserva->estado) }}</h4>
@if ($usuario->id_rol == 2 || $usuario->id_rol == 4 || $usuario->id == $reserva->propietario_id)
    <h4><b>Fecha de solicitud: </b>{{ $reserva->fecha_solicitud }}</h4>
    <h4><b>Motivo: </b>{{ $reserva->motivo }}</h4>
    <h4><b>Asistentes: </b>{{ $reserva->asistentes }}</h4>
@endif
<h4><b>Fecha y hora inicio: </b>{{ $reserva->fecha_inicio }}</h4>
<h4><b>Fecha y hora fin: </b>{{ $reserva->fecha_fin }}</h4>
@if ($usuario->id_rol == 2 || $usuario->id_rol == 4 || $usuario->id == $reserva->propietario_id)
    <h4><b>Propietario: </b>{{ $reserva->propietario->nombre_completo }}</h4>
@endif
<h4><b>Zona social: </b>{{ $reserva->zona_comun->nombre }}</h4>

@switch($usuario->id_rol)
    @case(2){{-- Admin --}}
        @switch($reserva->estado)
        @case('pendiente')
            <div class="row" id="option">
                <div class="col-md-12 text-center">
                    <button onclick="aceptar()" class="btn bg-green"><i class="fa fa-check"></i> Aceptar</button>
                    <button onclick="rechazar()" class="btn bg-red"><i class="fa fa-times"></i> Rechazar</button>
                </div>
            </div>
            <script>
                //para las acciones de la reserva
                function aceptar(){
                    swal('Advertencia!','¿Seguro de querer aceptar esta reserva?','info',{buttons:true}).then(res=>{
                        if(res){
                            $.ajax({
                                type: "POST",
                                url: "{{url('aceptarReserva',['reserva'=>$reserva->id])}}",
                                data: {
                                    _token
                                },
                                dataType: 'json',
                                success: function (response) {
                                    if(response.res){
                                        swal('logrado!',response.msg,'success').then(res=>{
                                            actualizarPantalla();
                                            $('#infoReserva').modal('hide');
                                        });
                                    }else{
                                        swal('Error!',response.msg,'error');
                                    }
                                }
                            }).fail(res=>{
                                swal('Error!','Ocurrió un error en el servidor!','error');
                            });
                        }
                    });
                }

                var input;

                function rechazar(){
                    $('#infoReserva').modal('hide');

                    input = document.createElement("textarea");
                    input.placeholder = "Ingrese por favor un texto con el motivo de rechazo.";

                    swal({
                        title: "Ingrese el motivo de rechazo.",
                        content: input,
                        buttons: true                       
                    }).then((res)=>{
                        if(res){
                            $.ajax({
                                type: "POST",
                                url: "{{url('rechazarReserva',['reserva'=>$reserva->id])}}",
                                data: {
                                    _token : _token,
                                    motivo : input.value
                                },
                                dataType: 'json',
                                success: function (response) {
                                    if(response.res){
                                        swal('logrado!',response.msg,'success').then(res=>{
                                            actualizarPantalla();
                                        });
                                    }else{
                                        swal('Error!',response.msg,'error');
                                        $('#infoReserva').modal('show');
                                    }
                                }
                            }).fail(res=>{
                                swal('Error!','Ocurrió un error en el servidor!','error');
                            });
                        }else{
                            $('#infoReserva').modal('show');
                        }
                    });
                    input.focus();
                }

            </script>
            @break
        @case('rechazada')
            <h4><b>Motivo de rechazo:</b>{{ $reserva->motivo_rechazo }}</h4>
            @break
        @case('aceptada')
            <div class="row" id="option">
                <div class="col-md-12 text-center">
                    <button onclick="rechazar();" class="btn bg-red"><i class="fa fa-times"></i> Rechazar</button>
                </div>
            </div>
            <script>
                var input;

                function rechazar(){
                    $('#infoReserva').modal('hide');

                    input = document.createElement("textarea");
                    input.placeholder = "Ingrese por favor un texto con el motivo de rechazo.";

                    swal({
                        title: "Ingrese el motivo de rechazo.",
                        content: input,
                        buttons: true                       
                    }).then((res)=>{
                        if(res){
                            $.ajax({
                                type: "POST",
                                url: "{{url('rechazarReserva',['reserva'=>$reserva->id])}}",
                                data: {
                                    _token : _token,
                                    motivo : input.value
                                },
                                dataType: 'json',
                                success: function (response) {
                                    if(response.res){
                                        swal('logrado!',response.msg,'success').then(res=>{
                                            actualizarPantalla();
                                        });
                                    }else{
                                        swal('Error!',response.msg,'error');
                                        $('#infoReserva').modal('show');
                                    }
                                }
                            }).fail(res=>{
                                swal('Error!','Ocurrió un error en el servidor!','error');
                            });
                        }else{
                            $('#infoReserva').modal('show');
                        }
                    });
                    input.focus();
                }
            </script>
            @break
        @default
            
        @endswitch
        @break
    @case(3){{-- Propietario --}}
        @if ($reserva->propietario_id == $usuario->id)
            @switch($reserva->estado)
                @case('aceptada')
                        <div class="row" id="option">
                            <div class="col-md-12 text-center">
                                <button onclick="rechazar()" class="btn bg-red"><i class="fa fa-times"></i> Rechazar</button>
                            </div>
                        </div>
                        <script>
                            var input;

                            function rechazar(){
                                $('#infoReserva').modal('hide');

                                input = document.createElement("textarea");
                                input.placeholder = "Ingrese por favor un texto con el motivo de rechazo.";

                                swal({
                                    title: "Ingrese el motivo de rechazo.",
                                    content: input,
                                    buttons: true                       
                                }).then((res)=>{
                                    if(res){
                                        $.ajax({
                                            type: "POST",
                                            url: "{{url('rechazarReservaPropietario',['reserva'=>$reserva->id])}}",
                                            data: {
                                                _token : _token,
                                                motivo : input.value
                                            },
                                            dataType: 'json',
                                            success: function (response) {
                                                if(response.res){
                                                    swal('logrado!',response.msg,'success').then(res=>{
                                                        actualizarPantalla();
                                                    });
                                                }else{
                                                    swal('Error!',response.msg,'error');
                                                    $('#infoReserva').modal('show');
                                                }
                                            }
                                        }).fail(res=>{
                                            swal('Error!','Ocurrió un error en el servidor!','error');
                                        });
                                    }else{
                                        $('#infoReserva').modal('show');
                                    }
                                });
                                input.focus();
                            }
                        </script>
                    @break
                @case('pendiente')
                        <div class="row" id="option">
                            <div class="col-md-12 text-center">
                                <button onclick="eliminar()" class="btn bg-red"><i class="fa fa-trash"></i> Eliminar</button>
                            </div>
                        </div>
                        <script>
                            function eliminar(){
                                swal('Advertencia!','¿Seguro de querer eliminar esta reserva?','info',{buttons:true}).then(res=>{
                                    if(res){
                                        $.ajax({
                                            type: "POST",
                                            url: "{{url('eliminarReserva',['reserva'=>$reserva->id])}}",
                                            data: {
                                                _token
                                            },
                                            dataType: 'json',
                                            success: function (response) {
                                                if(response.res){
                                                    swal('logrado!',response.msg,'success').then(res=>{
                                                        actualizarPantalla();
                                                        $('#infoReserva').modal('hide');
                                                    });
                                                }else{
                                                    swal('Error!',response.msg,'error');
                                                }
                                            }
                                        }).fail(res=>{
                                            swal('Error!','Ocurrió un error en el servidor!','error');
                                        });
                                    }
                                });
                            }

                        </script>
                    @break
                @case('rechazada')
                        <h4><b>Motivo de rechazo:</b>{{ $reserva->motivo_rechazo }}</h4>
                    @break
                    @default
                    
            @endswitch
        @endif
        @break
    @case(4){{-- Porteria --}}
        
        @break
    @default
        
@endswitch
