@if (in_array('propietario', $atributos) && $tipo == "Inactivo" && count($unidad->propietarios->where('pivot.estado',$tipo)) > 0)
    <table class="table infoUnidad" id="propietarios">
        <thead>
            <tr>
                <th colspan="5" style="text-align:center;">Listado de propietarios quienes se les facturaba</th>
            </tr>
            <tr>
                <th>Documento</th>
                <th>Nombre completo</th>
                <th>Correo</th>
                <th>Fecha ingreso</th>
                <th>Fecha retiro</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unidad->propietarios->where('pivot.estado',$tipo) as $propietario)
                <tr>
                    <td>{{ $propietario['numero_cedula'] }}</td>
                    <td>{{ $propietario['nombre_completo'] }}</td>
                    <td>{{ $propietario['email'] }}</td>
                    <td>{{ date('d-m-Y',strtotime($propietario['pivot']['fecha_ingreso'])) }}</td>
                    <td>{{ date('d-m-Y',strtotime($propietario['pivot']['fecha_retiro'])) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

{{-- lista de residentes si tiene estado --}}
@if (in_array('lista_residentes', $atributos) && $unidad->residentes()->where('estado',$tipo)->count() > 0)
    <table class="table infoUnidad" id="residentes">
        <thead>
            <tr>
                <th colspan="{{ ($tipo == 'Inactivo')? 10: 9 }}">Listado del residentes</th>
            </tr>
            <tr>
                <th>Tipo</th>
                <th>Documento</th>
                <th>Nombre completo</th>
                <th>Ocupación</th>
                <th>Lugar de trabajo</th>
                <th>Email</th>
                <th>Fecha Nacimiento</th>
                <th>Género</th>
                <th>Fecha ingreso</th>
                @if ($tipo == 'Inactivo')
                    <th>Fecha retiro</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($unidad->residentes()->where('estado',$tipo)->get() as $residente)
                <tr>
                    <td>{{ $residente->tipo_residente }}</td>
                    <td>{{ $residente->documento }}</td>
                    <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                    <td>{{ $residente->ocupacion }}</td>
                    <td>{{ $residente->direccion }}</td>
                    <td>{{ $residente->email }}</td>
                    <td>{{ date('d-m-Y',strtotime($residente->fecha_nacimiento)) }}</td>
                    <td>{{ $residente->genero }}</td>
                    <td>{{ date('d-m-Y',strtotime($residente->fecha_ingreso)) }}</td>
                    @if ($tipo == 'Inactivo')
                        <td>{{ date('d-m-Y',strtotime($residente->fecha_salida)) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

{{-- lista de mascotas se puede usar la fecha_retiro --}}
@if (in_array('lista_mascotas', $atributos) && $unidad->mascotas()->where('estado',$tipo)->count() > 0)
    <table class="table infoUnidad" id="mascotas">
        <thead>
            <tr>
                <th colspan="{{ ($tipo == 'Inactivo')? 8:  7}}">Listado de mascotas</th>
            </tr>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Fecha Nacimiento</th>
                <th>Tipo</th>
                <th>Raza</th>
                <th>Descripción</th>
                <th>Fecha ingreso</th>
                @if ($tipo == 'Inactivo')
                    <th>Fecha retiro</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($unidad->mascotas()->where('estado',$tipo)->get() as $mascota)
                <tr>
                    <td>{{ $mascota->codigo }}</td>
                    <td>{{ $mascota->nombre }}</td>
                    <td>{{ date('d-m-Y',strtotime($mascota->fecha_nacimiento)) }}</td>
                    <td>{{ $mascota->tipo->tipo }}</td>
                    <td>{{ $mascota->raza }}</td>
                    <td>{{ $mascota->descripcion }}</td>
                    <td>{{ date('d-m-Y',strtotime($mascota->fecha_ingreso)) }}</td>
                    @if ($tipo == "Inactivo")
                        <td>{{ date('d-m-Y',strtotime($mascota->fecha_retiro)) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

{{-- lista de vehículos no la tienen --}}
@if (in_array('lista_vehiculos', $atributos) && $unidad->vehiculos()->where('estado',$tipo)->count() > 0)
<table class="table infoUnidad" id="vehiculos">
    <thead>
        <tr>
            <th colspan="{{ ($tipo == 'Inactivo')? 8: 7 }}">Listado de vehículos</th>
        </tr>
        <tr>
            <th>Placa</th>
            <th>Tipo</th>
            <th>Marca</th>
            <th>Color</th>
            <th>Propietario del vehículo</th>
            <th>Fecha ingreso</th>
            @if ($tipo == 'Inactivo')
                <th>Fecha retiro</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($unidad->vehiculos()->where('estado',$tipo)->get() as $vehiculo)
            <tr>
                <td>{{ $vehiculo->placa }}</td>
                <td>{{ $vehiculo->tipo }}</td>
                <td>{{ $vehiculo->marca }}</td>
                <td>{{ $vehiculo->color }}</td>
                <td>{{ $vehiculo->registra }}</td>
                <td>{{ date('d-m-Y',strtotime($vehiculo->fecha_ingreso)) }}</td>
                @if ($tipo == 'Inactivo')
                    <td>{{ date('d-m-Y',strtotime($vehiculo->fecha_retiro)) }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- lista de empleados estan --}}
@if (in_array('lista_empleados', $atributos) && $unidad->empleados()->where('estado',$tipo)->count() > 0)
    <table class="table infoUnidad" id="empleados">
        <thead>
            <tr>
                <th colspan="{{ ($tipo == 'Inactivo')? 5: 4 }}">Listado de empleados</th>
            </tr>
            <tr>
                <th>Documento</th>
                <th>Nombre completo</th>
                <th>Género</th>
                <th>Fecha ingreso</th>
                @if ($tipo == "Inactivo")
                    <th>Fecha retiro</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($unidad->empleados()->where('estado',$tipo)->get() as $empleado)
                <tr>
                    <td>{{ $empleado->documento }}</td>
                    <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                    <td>{{ $empleado->genero }}</td>
                    <td>{{ date('d-m-Y',strtotime($empleado->fecha_ingreso)) }}</td>
                    @if ($tipo == 'Inactivo')
                        <td>{{ date('d-m-Y',strtotime($empleado->fecha_retiro)) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

{{-- lista de visitantes frecuentes colocar activos--}}
@if (in_array('lista_visitantes', $atributos) && $unidad->visitantes()->where('estado',$tipo)->count() > 0)
    <table class="table infoUnidad" id="visitantes">
        <thead>
            <tr>
                <th colspan="{{ ($tipo == 'Inactivo')? 5: 4 }}">Listado de visitantes</th>
            </tr>
            <tr>
                <th>Identificación</th>
                <th>Nombre completo</th>
                <th>Parentesco / Otro</th>
                <th>Fecha ingreso</th>
                @if ($tipo == "Inactivo")
                    <th>Fecha retiro</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($unidad->visitantes()->where('estado',$tipo)->get() as $visitante)
                <tr>
                    <td>{{ $visitante->identificacion }}</td>
                    <td>{{ $visitante->nombre }}</td>
                    <td>{{ $visitante->parentesco }}</td>
                    <td>{{ date('d-m-Y',strtotime($visitante->fecha_ingreso)) }}</td>
                    @if ($tipo == "Inactivo")
                        <td>{{ date('d-m-Y',strtotime($visitante->fecha_retir0o)) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif