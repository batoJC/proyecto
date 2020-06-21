{{-- @extends('admin.PDF.plantilla')

@section('style')
    
    
@endsection

@section('contenido')



@endsection --}}

@extends('admin.PDF.plantilla')

@section('style')
    <style>
        @page main {
            size: A4 portrait;
            margin: 2cm;
        }

        .mainPage {
            page: main;
            page-break-after: always;
        }

        table{
        	border-collapse: collapse;
        	width: 100%;
        	border-top: 2px solid black;
        	border-bottom: 2px solid black;
        }

        th{
			padding: 8px 13 8px 13px;
        	border-bottom: 1px solid black;
			font-family: sans-serif !important;
			font-weight: 100;
			font-size: 12px;
			text-align: center;
			text-transform: uppercase;
        }

        td{
        	border-bottom: 1px solid black;
			font-family: serif !important;
            /* color: green; */
            font-style: oblique;
			font-weight: 100 !important;
			font-size: 14px;
			text-align: center;
        }
    </style>
    
@endsection

@section('contenido')

    @php
        $atributos = [];
        $aux = $unidad->tipo->atributos;
        foreach ($aux as $value) {
            $atributos[] = $value->nombre;
        }
    @endphp

    <h2>Información actual de la unidad</h2>
    <br>
     {{-- lista de residentes si tiene estado --}}
     @if (in_array('lista_residentes', $atributos) and $unidad->residentes()->where('estado','Activo')->count() > 0)
        <h3>Listado de residentes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Tipo</th>
                    <th>Nombre completo</th>
                    <th>Ocupación</th>
                    <th>Profesión</th>
                    <th>Email</th>
                    <th>Lugar de trabajo</th>
                    <th>Fecha Nacimiento</th>
                    <th>Género</th>
                    <th>Fecha ingreso</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->residentes()->where('estado','Activo')->get() as $residente)
                    <tr>
                        <td>{{ $residente->documento }}</td>
                        <td>{{ $residente->tipo_residente }}</td>
                        <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                        <td>{{ $residente->ocupacion }}</td>
                        <td>{{ $residente->profesion }}</td>
                        <td>{{ $residente->email }}</td>
                        <td>{{ $residente->direccion }}</td>
                        <td>{{ date('d-m-Y',strtotime($residente->fecha_nacimiento)) }}</td>
                        <td>{{ $residente->genero }}</td>
                        <td>{{ date('d-m-Y',strtotime($residente->fecha_ingreso)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif
    {{-- lista de mascotas se puede usar la fecha_retiro --}}
    @if (in_array('lista_mascotas', $atributos) and $unidad->mascotas()->where('estado','Activo')->count() > 0)
        <h3>Listado de mascotas</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha Nacimiento</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Raza</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->mascotas()->where('estado','Activo')->get() as $mascota)
                    <tr>
                        <td>{{ $mascota->codigo }}</td>
                        <td>{{ ($mascota->fecha_nacimiento)? date('d-m-Y',strtotime($mascota->fecha_nacimiento)) : 'No aplica' }}</td>
                        <td>{{ $mascota->nombre }}</td>
                        <td>{{ $mascota->tipo->tipo }}</td>
                        <td>{{ $mascota->raza }}</td>
                        <td>{{ $mascota->descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif
    {{-- lista de vehículos no la tienen --}}
    @if (in_array('lista_vehiculos', $atributos) and $unidad->vehiculos()->where('estado','Activo')->count() > 0)
        <h3>Listado de vehículos</h3>
        <table class="table" id="prueba">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Color</th>
                    <th>A nombre de</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->vehiculos()->where('estado','Activo')->get() as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->placa }}</td>
                        <td>{{ $vehiculo->tipo }}</td>
                        <td>{{ $vehiculo->marca }}</td>
                        <td>{{ $vehiculo->color }}</td>
                        <td>{{ $vehiculo->registra }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif
    {{-- lista de empleados estan --}}
    @if (in_array('lista_empleados', $atributos) and $unidad->empleados()->where('estado','Activo')->count() > 0)
        <h3>Listado de empleados</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre completo</th>
                    <th>Género</th>
                    <th>Fecha ingreso</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->empleados()->where('estado','Activo')->get() as $empleado)
                    <tr>
                        <td>{{ $empleado->documento }}</td>
                        <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                        <td>{{ $empleado->genero }}</td>
                        <td>{{ date('d-m-Y',strtotime($empleado->fecha_ingreso)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif
    {{-- lista de visitantes frecuentes colocar activos--}}
    @if (in_array('lista_visitantes', $atributos) and $unidad->visitantes()->where('estado','Activo')->count() > 0)
        <h3>Listado de visitantes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Identificación</th>
                    <th>Nombre completo</th>
                    <th>Parentesco</th>
                    <th>Fecha ingreso</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->visitantes()->where('estado','Activo')->get() as $visitante)
                    <tr>
                        <td>{{ $visitante->identificacion }}</td>
                        <td>{{ $visitante->nombre }}</td>
                        <td>{{ $visitante->parentesco }}</td>
                        <td>{{ date('d-m-Y',strtotime($visitante->fecha_ingreso)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif
    {{-- fin activos --}}
    <br>
    <h2>Histórico</h2>
    <br>
    @if (in_array('propietario', $atributos) and $unidad->propietarios->where('pivot.estado','Inactivo')->count() > 0)
        <h3>Listado de propietarios quienes se les facturaba</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre completo</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Fecha ingreso</th>
                    <th>Fecha retiro</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->propietarios->where('pivot.estado','Inactivo') as $propietario)
                    <tr>
                        <td>{{ $propietario['numero_cedula'] }}</td>
                        <td>{{ $propietario['nombre_completo'] }}</td>
                        <td>{{ $propietario['email'] }}</td>
                        <td>{{ $propietario['direccion'] }}</td>
                        <td>{{ date('d-m-Y',strtotime($propietario['pivot']['fecha_ingreso'])) }}</td>
                        <td>{{ date('d-m-Y',strtotime($propietario['pivot']['fecha_retiro'])) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif

    {{-- lista de residentes si tiene estado --}}
    @if (in_array('lista_residentes', $atributos) and $unidad->residentes()->where('estado','Inactivo')->count() > 0)
        <h3>Listado de residentes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Tipo</th>
                    <th>Nombre completo</th>
                    <th>Ocupación</th>
                    <th>Profesión</th>
                    <th>Email</th>
                    <th>Lugar de trabajo</th>
                    <th>Fecha Nacimiento</th>
                    <th>Género</th>
                    <th>Fecha ingreso</th>
                    <th>Fecha retiro</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->residentes()->where('estado','Inactivo')->get() as $residente)
                    <tr>
                        <td>{{ $residente->documento }}</td>
                        <td>{{ $residente->tipo_residente }}</td>
                        <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                        <td>{{ $residente->ocupacion }}</td>
                        <td>{{ $residente->profesion }}</td>
                        <td>{{ $residente->email }}</td>
                        <td>{{ $residente->direccion }}</td>
                        <td>{{ date('d-m-Y',strtotime($residente->fecha_nacimiento)) }}</td>
                        <td>{{ $residente->genero }}</td>
                        <td>{{ date('d-m-Y',strtotime($residente->fecha_ingreso)) }}</td>
                        <td>{{ date('d-m-Y',strtotime($residente->fecha_salida)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif

    {{-- lista de mascotas se puede usar la fecha_retiro --}}
    @if (in_array('lista_mascotas', $atributos) and $unidad->mascotas()->where('estado','Inactivo')->count() > 0)
        <h3>Listado de mascotas</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha Nacimiento</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Raza</th>
                    <th>Descripción</th>
                    <th>Fecha ingreso</th>
                    <th>Fecha retiro</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->mascotas()->where('estado','Inactivo')->get() as $mascota)
                    <tr>
                        <td>{{ $mascota->codigo }}</td>
                        <td>{{ ($mascota->fecha_nacimiento)? date('d-m-Y',strtotime($mascota->fecha_nacimiento)) : 'No aplica' }}</td>
                        <td>{{ $mascota->nombre }}</td>
                        <td>{{ $mascota->tipo->tipo }}</td>
                        <td>{{ $mascota->raza }}</td>
                        <td>{{ $mascota->descripcion }}</td>
                        <td>{{ date('d-m-Y',strtotime($mascota->fecha_ingreso)) }}</td>
                        <td>{{ date('d-m-Y',strtotime($mascota->fecha_salida)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif

    {{-- lista de vehículos no la tienen --}}
    @if (in_array('lista_vehiculos', $atributos))
        <h3>Listado de vehículos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Color</th>
                    <th>A nombre de</th>
                    <th>Fecha ingreso</th>
                    <th>Fecha retiro</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->vehiculos()->where('estado','inactivo')->get() as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->placa }}</td>
                        <td>{{ $vehiculo->tipo }}</td>
                        <td>{{ $vehiculo->marca }}</td>
                        <td>{{ $vehiculo->color }}</td>
                        <td>{{ $vehiculo->registra }}</td>
                        <td>{{ date('d-m-Y',strtotime($vehiculo->fecha_ingreso)) }}</td>
                        <td>{{ date('d-m-Y',strtotime($vehiculo->fecha_salida)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif

    {{-- lista de empleados estan --}}
    @if (in_array('lista_empleados', $atributos) and $unidad->empleados()->where('estado','Inactivo')->count() > 0)
            <h3>Listado de empleados</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre completo</th>
                        <th>Género</th>
                        <th>Fecha ingreso</th>
                        <th>Fecha retiro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unidad->empleados()->where('estado','Inactivo')->get() as $empleado)
                        <tr>
                            <td>{{ $empleado->documento }}</td>
                            <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                            <td>{{ $empleado->genero }}</td>
                            <td>{{ date('d-m-Y',strtotime($empleado->fecha_ingreso)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($empleado->fecha_retiro)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        @endif

    {{-- lista de visitantes frecuentes colocar activos--}}
    @if (in_array('lista_visitantes', $atributos) and $unidad->visitantes()->where('estado','Inactivo')->count() > 0)
        <h3>Listado de visitantes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Identificación</th>
                    <th>Nombre completo</th>
                    <th>Parentesco</th>
                    <th>Fecha ingreso</th>
                    <th>Fecha retiro</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unidad->visitantes()->where('estado','Inactivo')->get() as $visitante)
                    <tr>
                        <td>{{ $visitante->identificacion }}</td>
                        <td>{{ $visitante->nombre }}</td>
                        <td>{{ $visitante->parentesco }}</td>
                        <td>{{ date('d-m-Y',strtotime($visitante->fecha_ingreso)) }}</td>
                        <td>{{ date('d-m-Y',strtotime($visitante->fecha_retiro)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif
    {{-- fin historico --}}

@endsection