@extends('../layouts.app_dashboard_admin')

@section('title', 'Liquidador de nómina')
<style>
    .cursor-pointer{
        cursor: pointer;
    }

    input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }

    input[type=number] { -moz-appearance:textfield; }

    input:invalid+span:after {
        position: absolute;
        content: '✖';
        padding-left: 5px;
        color: red;
    }

    input:valid+span:after {
        position: absolute;
        content: '✓';
        padding-left: 5px;
        color: green;
    }

    .input{
        /* float: left; */
        margin-left: 2px;
        width: 80px !important;

    }

    .span{
        float: right;
        position: absolute;
        margin-top: 5px;
    }

    .m-5{
        margin: 5px;
    }

    .m-5 > * {
        display: inline-block !important;
        margin-right: 5px;
    }

</style>
@section('content')
@include('admin.jornadas.crearJornadaForm')
@include('admin.jornadas.editarJornadaForm')

<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
                </li>
                <li>
					<a href="{{ url('empleados_conjunto') }}">Empleados conjunto</a>
                </li>
                <li>
					<a href="{{ url('liquidador',['empleado' => $empleado->id]) }}">Liquidador de nómina</a>
				</li>
				  <li>Jornadas</li>
			</ul>
		</div>
    </div>
    @if(session('status'))
        <div class="alert alert-success-original alert-dismissible" role="alert">
            <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            <h4>
                {{ session('status') }}
            </h4>
        </div>
        @php
            session()->forget('status');
        @endphp
    @endif

    @if(session('error'))
        <div class="alert alert-danger-original alert-dismissible" role="alert">
            <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            <h4>
                {{ session('error') }}
            </h4>
        </div>
        @php
            session()->forget('error');
        @endphp
    @endif
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8">
                <h3>{{ $empleado->nombre_completo }}</h3>
                <p>
                    Identificación: {{ $empleado->cedula }} <br>
                    Salario: ${{ number_format($empleado->salario) }}
                </p>
            </div>
            <div class="col-12 col-md-4">
                <form id="form_periodo" method="GET">
                    <label for="">Periodo</label>
                    <input id="periodo" name="periodo" onchange="form_periodo.submit();" value="{{$year}}-{{$month}}" type="month" class="form-control">
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <button onclick="openCrearJornadaModal();" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Jornada </button>
            </div>
            <div class="col-md-2">
                <form target="_blank" method="POST" action="{{ url('pdfJornadas') }}">
                    @csrf
                    <input type="hidden" name="empleado" value="{{ $empleado->id }}">
                    <input name="periodo" value="{{$year}}-{{$month}}" type="hidden">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> Decargar pdf</button>
                </form>
            </div>
        </div>
        <br>

        <h3 class="text-center">Jornadas registradas</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias diurnas">HOD</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias nocturnas">HON</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias diurnas festivas">HODF</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias nocturnas festivas">HONF</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra diurnas ordinarias">HEDO</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra nocturnas ordinarias">HENO</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra diurnas festivas">HEDF</th>
                    <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra nocturnas festivas">HENF</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jornadas as $jornada)
                    <tr>
                        <th>{{ date('d-m-Y',strtotime($jornada->fecha)) }}</th>
                        <th>{{ date('h:i A',strtotime($jornada->entrada)) }}</th>
                        <th>{{ date('h:i A',strtotime($jornada->salida)) }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias diurnas">{{ $jornada->HOD }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias nocturnas">{{ $jornada->HON }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias diurnas festivas">{{ $jornada->HODF }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas ordinarias nocturnas festivas">{{ $jornada->HONF }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra diurnas ordinarias">{{ $jornada->HEDO }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra nocturnas ordinarias">{{ $jornada->HENO }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra diurnas festivas">{{ $jornada->HEDF }}</th>
                        <th class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Horas extra nocturnas festivas">{{ $jornada->HENF }}</th>
                        <th>
                            <a onclick="editar({{ $jornada->id }})" href="#"><i data-toggle="tooltip" data-placement="top" title="Editar" class="fa fa-pencil green"></i></a>&nbsp;&nbsp;
                            <a onclick="eliminar({{ $jornada->id }})" href="#"><i data-toggle="tooltip" data-placement="top" title="Eliminar" class="fa fa-trash red"></i></a>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    </div>

</div>


	
@endsection
@section('ajax_crud')
	<script>

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        function openCrearJornadaModal(){
            $('#crearJornadasModal').modal('show');
        }


        function generarJornadas(){
            if(data_jornada.checkValidity()){
                let inicio = `{{$year}}-{{$month}}-${inicio_dia.value} ${inicio_hora.value}:${inicio_minuto.value} ${inicio_tipo.value}`;
                let final = `{{$year}}-{{$month}}-${final_dia.value} ${final_hora.value}:${final_minuto.value} ${final_tipo.value}`;
                // console.log(inicio,final);
                $.ajax({
                    type: "POST",
                    url: "{{ url('liquidadorJornadas') }}",
                    data: {
                        fecha_inicio: inicio,
                        fecha_fin: final,
                        continuo: (continuo.checked)?1 : 0,
                        _token : csrf_token
                    },
                    dataType: "json",
                    success: function (response) {
                        $('#div_jornadas').removeClass('hide');

                        data_jornadas.innerHTML = '';
                        for (let i = 0; i < response.length; i++) {
                            const e = response[i];
                            data_jornadas.innerHTML += `<tr>
                                <td>${e.fecha}</td>
                                <td>${e.entrada}</td>
                                <td>${e.salida}</td>
                                <td>${e.HOD}</td>
                                <td>${e.HON}</td>
                                <td>${e.HODF}</td>
                                <td>${e.HONF}</td>
                                <td>${e.HEDO}</td>
                                <td>${e.HENO}</td>
                                <td>${e.HEDF}</td>
                                <td>${e.HENF}</td>
                            </tr>`;
                        }
                        datos.value = JSON.stringify(response);
                        console.log(response);
                    }
                });
            }else{
                $('#div_jornadas').addClass('hide');
                datos.value = '';
                swal('Error!','Revise los valores ingresados','warning');
            }
        }


        function eliminar(id){
            swal({
                title:'Advertencia!',
                text: '¿Seguro de que querer eliminar esta jornada?',
                icon: 'warning',
                buttons: {
                    confirm1: {
                        text: "No",
                        value: false,
                        visible: true,
                        className: "btn-default",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Si",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }  
                }
            }).then(res=>{
                if(res){
                   $.ajax({
                       type: "POST",
                       url: "{{ url('deleteJornada') }}",
                       data: {
                           id: id,
                           _token : csrf_token
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
                       }
                   });
                }
            })
        }


        function editar(id){
            $.ajax({
                type: "GET",
                url: "{{url('jornada')}}/"+id,
                data: {
                    _token : csrf_token,
                },
                dataType: "json",
                success: function (response) {
                    $('#editarJornadaModal').modal('show');
                    $('#jornada_id').val(response.id);
                    $('#fecha').val(response.fecha);
                    $('#entrada').val(response.entrada);
                    $('#salida').val(response.salida);
                    $('#HOD').val(response.HOD);
                    $('#HON').val(response.HON);
                    $('#HODF').val(response.HODF);
                    $('#HONF').val(response.HONF);
                    $('#HEDO').val(response.HEDO);
                    $('#HENO').val(response.HENO);
                    $('#HEDF').val(response.HEDF);
                    $('#HENF').val(response.HENF);
                }
            });
        }

    </script>
@endsection
