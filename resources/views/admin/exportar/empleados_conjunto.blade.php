@extends('admin.PDF.plantilla')

@section('style')
    <style>
        .imagen{
            width: 300px;
        }

        .w-300p{
            width: 300px;
        }

        td{
            padding: 8px;
            margin: 10px 5px 10px 5px;
        }

    </style>
    
@endsection

@section('contenido')
    <h2>Empleados conjunto</h2>
    <table>
        <tbody>
            @foreach ($empleados as $empleado)
                <tr>
                    <td class="w-300p">
                        <img class="imagen" src="{{ public_path("imgs/private_imgs/{$empleado->foto}") }}" alt="">
                    </td>
                    <td>
                        <b>Nombre completo: </b>{{ $empleado->nombre_completo }}<br>
                        <b>Identificación: </b>{{ $empleado->cedula }}<br>
                        <b>Dirección: </b>{{ $empleado->direccion }}<br>
                        <b>Cargo: </b>{{ $empleado->cargo }}<br>
                        <b>Fecha ingreso: </b>{{ date('d-m-Y',strtotime($empleado->fecha_ingreso)) }}<br>
                        <b>Fecha retiro: </b>{{ ($empleado->fecha_retiro)? date('d-m-Y',strtotime($empleado->fecha_retiro)) : 'No aplica' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection