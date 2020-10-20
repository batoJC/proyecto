@extends('../layouts.app_dashboard_admin')
<style>
    textarea{
        width: 100% !important;
    }
</style>
@section('content')

	<ul class="breadcrumb">
		<li>
			<a href="{{ url('home') }}">Inicio</a>
		</li>
	  	<li>
              <a href="{{ url('unidades') }}">Tipos de unidad</a>
        </li>
        <li>
            <a href="{{ url('unidadestipo', ['tipo' => $tipo->id]) }}">
                    {{ ucfirst(strtolower($tipo->nombre)) }}
            </a>
        </li>
        <li>Agregar</li>
    </ul>
    <div class="container-fluid">
        <div class="row text-center">
            <form id="data_main">
                @csrf()
                <input type="hidden" value="{{ $tipo->id }}" name="tipo_unidad" id="tipo_unidad">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                        <h1 class="text-center">Agregar nuevo {{ strtolower($tipo->nombre) }}</h1>
                        <br><br>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Número / letra</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="numero_letra" id="numero_letra" type="text" class="form-control" placeholder="Ingrese el número o letra de la unidad">
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">División</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="division_id" id="division_id">
                                    <option value="">Seleccione la división</option>
                                    @foreach ($divisiones as $division)
                                        <option value="{{ $division->id }}">{{ $division->tipo_division->division }} {{ $division->numero_letra }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br><br>

                        @if (in_array('coeficiente', $atributos))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Coeficiente (%)</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input name="coeficiente" id="coeficiente" type="text" class="form-control" placeholder="Ingrese el coeficiente de la unidad, se debe usar coma en lugar de punto.">
                                </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Referencia de pago</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input name="referencia" id="referencia" type="text" class="form-control" placeholder="Ingrese la referencia de pago de la unidad">
                                </div>
                            </div>
                            <br><br>
                        @endif

                        @if (in_array('unidad_id', $atributos))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Unidad</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <select class="form-control" name="unidad_id" id="unidad_id">
                                        <option value="">Seleccione la unidad a la que pertenece</option>
                                        @foreach ($unidades as $unidad)
                                            <option value="{{ $unidad->id }}">{{ $unidad->numero_letra }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br><br>
                        @endif

                        {{-- seleccione el propietario a quien se le factura --}}
                        @if (in_array('propietario', $atributos))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Propietario</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <select class="form-control select-2" name="propietario" id="propietario">
                                        <option value="">Seleccione a el propietario que se le factura</option>
                                        @foreach ($propietarios as $propietario)
                                            <option value="{{ $propietario->id }}">{{ $propietario->numero_cedula }} {{ $propietario->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br><br>
                        @endif

                        @if (in_array('observaciones', $atributos))
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Observaciones</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="10" placeholder="Observaciones..."></textarea>
                                </div>
                            </div>
                            <br><br>
                        @endif

                    </div>
                </div>
            </form>
            <br><br>


            {{-- lista de residentes --}}
            @if (in_array('lista_residentes', $atributos))
                @include('admin.unidades.listasAgregar.listaResidentes')
            @endif

            {{-- lista de mascotas --}}
            @if (in_array('lista_mascotas', $atributos))
                @include('admin.unidades.listasAgregar.listaMascotas')
            @endif

            {{-- lista de vehículos --}}
            @if (in_array('lista_vehiculos', $atributos))
                @include('admin.unidades.listasAgregar.listaVehiculos')
            @endif

            {{-- lista de empleados --}}
            @if (in_array('lista_empleados', $atributos))
                @include('admin.unidades.listasAgregar.listaEmpleados')
            @endif

            {{-- lista de visitantes frecuentes --}}
            @if (in_array('lista_visitantes', $atributos))
                @include('admin.unidades.listasAgregar.listaVisitantes')
            @endif


            <div class="row text-center">
                <div class="col-xs-4"></div>
                <div class="col-xs-4">
                    <button class="btn btn-success" onclick="guardar('{{ json_encode($atributos) }}')">
                        <i class="fa fa-send"></i>
                        Guardar {{ strtolower($tipo->nombre) }}
                    </button>
                </div>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
        </div>
    </div>
@endsection
@section('ajax_crud')
    <script>
        let atributos = null;
        let estado = 0;
        var tiempo = null;
        var ingreso = false;

        $(document).ready(function () {
            var propietarios = $('.select-2');
            if(propietarios != undefined){
                $('.select-2').select2({});
            }

            window.addEventListener("beforeunload", function (e) {
                var confirmationMessage = "\o/";

                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage;                            //Webkit, Safari, Chrome
            });

        });

        var unidad = 0;

        function guardar(aux){

            atributos = JSON.parse(aux);
            if(verificar(atributos)){
                $('#loading').css("display", "flex")
                .hide()
                .fadeIn(800,()=>{
                    $('#loading').css('display','flex');

                    $.ajax({
                        type: "POST",
                        url: "{{ url('unidades') }}",
                        data: $('#data_main').serialize(),
                        dataType: "json"
                    }).done((e)=>{
                        if(e.res){
                            unidad = e.data.id;
                            var csrf_token = $('meta[name="csrf-token"]').attr('content');

                            crearCarta((res)=>{
                                //guardar lista de residentes
                                if(atributos.indexOf('lista_residentes') != -1){
                                    listaResidentes.forEach(residente => {
                                        estado++;

                                        let data = new FormData();
                                        data.append('tipo_residente',residente.tipo_residente);
                                        data.append('nombre',residente.nombre);
                                        data.append('apellido',residente.apellido);
                                        data.append('profesion',residente.profesion);
                                        data.append('ocupacion',residente.ocupacion);
                                        data.append('direccion',residente.direccion);
                                        data.append('email',residente.email);
                                        data.append('fecha_nacimiento',residente.fecha_nacimiento);
                                        data.append('genero',residente.genero);
                                        data.append('tipo_documento_id',residente.tipo_documento);
                                        data.append('documento',residente.documento);
                                        data.append('unidad_id',unidad);
                                        data.append('carta_ingreso_id',res.data.id);
                                        data.append('_token',csrf_token);

                                        $.ajax({
                                            type: "POST",
                                            url: "{{url('residentes')}}",
                                            contentType: false,
                                            dataType: "json",
                                            cache: false,
                                            processData: false,
                                            data: data,
                                        }).done((data)=>{
                                            estado--;
                                        }).fail((data)=>{
                                            estado--;
                                        });


                                    });
                                }

                                //guardar lista de mascotas
                                if(atributos.indexOf('lista_mascotas') != -1){
                                    listaMascotas.forEach(mascota => {
                                        estado++;

                                        let data = new FormData();
                                        data.append('codigo',mascota.codigo);
                                        data.append('nombre',mascota.nombre);
                                        data.append('raza',mascota.raza);
                                        data.append('fecha_nacimiento',mascota.fecha_nacimiento);
                                        data.append('descripcion',mascota.descripcion);
                                        data.append('tipo_id',mascota.tipo);
                                        data.append('unidad_id',unidad);
                                        data.append('carta_ingreso_id',res.data.id);
                                        data.append('foto',mascota.foto[0]);
                                        data.append('_token',csrf_token);

                                        $.ajax({
                                            type: "POST",
                                            url: "{{url('mascotas')}}",
                                            contentType: false,
                                            dataType: "json",
                                            cache: false,
                                            processData: false,
                                            data: data,
                                        }).done((data)=>{
                                            estado--;
                                        }).fail((data)=>{
                                            estado--;
                                        });


                                    });
                                }

                                //guardar lista de vehículos
                                if(atributos.indexOf('lista_vehiculos') != -1){
                                    listaVehiculos.forEach(vehiculo => {
                                        estado++;

                                        let data = new FormData();
                                        data.append('tipo',vehiculo.tipo);
                                        data.append('marca',vehiculo.marca);
                                        data.append('color',vehiculo.color);
                                        data.append('placa',vehiculo.placa);
                                        data.append('registra',vehiculo.registra);
                                        data.append('unidad_id',unidad);
                                        data.append('foto1',vehiculo.foto1[0]);
                                        data.append('foto2',vehiculo.foto2[0]);
                                        data.append('carta_ingreso_id',res.data.id);
                                        data.append('foto3',vehiculo.foto3[0]);
                                        data.append('_token',csrf_token);

                                        $.ajax({
                                            type: "POST",
                                            url: "{{url('vehiculos')}}",
                                            contentType: false,
                                            dataType: "json",
                                            cache: false,
                                            processData: false,
                                            data: data,
                                        }).done((data)=>{
                                            estado--;
                                        }).fail((data)=>{
                                            estado--;
                                        });


                                    });
                                }

                                //guardar lista de empleados
                                if(atributos.indexOf('lista_empleados') != -1){
                                    listaEmpleados.forEach(empleado => {
                                        estado++;

                                        let data = new FormData();
                                        data.append('nombre',empleado.nombre);
                                        data.append('apellido',empleado.apellido);
                                        data.append('genero',empleado.genero);
                                        data.append('tipo_documento_id',empleado.tipo_documento);
                                        data.append('documento',empleado.documento);
                                        data.append('unidad_id',unidad);
                                        data.append('_token',csrf_token);

                                        $.ajax({
                                            type: "POST",
                                            url: "{{url('empleados')}}",
                                            contentType: false,
                                            dataType: "json",
                                            cache: false,
                                            processData: false,
                                            data: data,
                                        }).done((data)=>{
                                            estado--;
                                        }).fail((data)=>{
                                            estado--;
                                        });

                                    });
                                }

                                //guardar lista de visitantes
                                if(atributos.indexOf('lista_visitantes') != -1){
                                    listaVisitantes.forEach(visitante => {
                                        estado++;

                                        let data = new FormData();
                                        data.append('nombre',visitante.nombre);
                                        data.append('identificacion',visitante.identificacion);
                                        data.append('parentesco',visitante.parentesco);
                                        data.append('unidad_id',unidad);
                                        data.append('_token',csrf_token);

                                        $.ajax({
                                            type: "POST",
                                            url: "{{url('visitantes')}}",
                                            contentType: false,
                                            dataType: "json",
                                            cache: false,
                                            processData: false,
                                            data: data,
                                        }).done((data)=>{
                                            estado--;
                                        }).fail((data)=>{
                                            estado--;
                                        });
                                    });
                                }

                                //verifivar que se termino de cargar los datos
                                tiempo = setInterval(()=>{
                                    if(estado==0){
                                        swal('Logrado!',e.msg,'success').then(()=>{
                                            window.location = '{{ url('unidadestipo', ['tipo' => $tipo->id]) }}';
                                        });
                                        clearTimeout(tiempo);
                                    }
                                },500)
                            },e.data.id);
                        }else{
                            swal('Error!',e.msg,'error');
                            $('#loading').fadeOut(800);
                        }
                    }).fail((e)=>{
                        swal('Error!','Ocurrió un error en el servidor','error');
                        clearTimeout(tiempo);
                        $('#loading').fadeOut(800);
                    });
                });
            }

        }

        function verificar(atributos){
            for (let i = 0; i < atributos.length; i++) {
                let input = $(`#${atributos[i]}`);
                if($(`#${atributos[i]}`).val() == "" && atributos[i] != 'observaciones'){
                    swal('Error!','debe de ingresar todos los datos','error').then(()=>{
                        input.focus();
                    });
                    return false;
                }
            }
            return true;
        }

        function crearCarta(fnc,unidad){
            input = document.createElement("div");
            input.innerHTML = '<label class="label_alerta">Ingrese el encabezado de la carta:</label>';
            input.append(document.createElement("br"));
            encabezado = document.createElement("textarea");
            encabezado.id = 'encabezado';
            encabezado.className = 'form-control';
            input.append(encabezado);
            encabezado.placeholder = "Ingrese por favor el encabezado de la carta.";
            input.append(document.createElement("br"));
            input.innerHTML += '<label class="label_alerta">Ingrese el contenido para la carta:</label>';
            input.append(document.createElement("br"));
            cuerpo = document.createElement("textarea")
            cuerpo.className = 'form-control';
            input.append(cuerpo);
            cuerpo.placeholder = "Ingrese por favor un el cuerpo de la carta.";
            estado++;


            swal({
                title: "Ingrese el contenido de la carta.",
                content: input,
                buttons: {
                    cancel: {
                        text: "Guardar sin carta",
                        value: false,
                        visible: true,
                        className: "",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Guardar con carta",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }
            }).then((res1)=>{
                encabezado = $('#encabezado');
                if (res1) {
                    let data = new FormData();
                    data.append('encabezado',encabezado.val());
                    data.append('cuerpo',cuerpo.value);
                    data.append('propietario',e.propietario.id);
                    data.append('unidad_id',unidad);
                    data.append('_token',csrf_token);

                    estado++;

                    $.ajax({
                        type: "POST",
                        url: "{{url('cartas')}}",
                        contentType: false,
                        dataType: "json",
                        cache: false,
                        processData: false,
                        data: data,
                    }).done((res)=>{
                        estado--;
                        fnc(res);
                    }).fail((res)=>{
                        swal('Error!','Ocurrió un error en el servidor, por favor intentelo más tarde.','error');
                        var res = {data:{id:''}};
                        estado--;
                        fnc(res);
                    });
                    estado--;

                }else{
                    estado--;
                    var res = {data:{id:''}};
                    fnc(res);
                }
            });
        }

    </script>
@endsection