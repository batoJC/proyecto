@extends('admin.PDF.plantilla')

@section('style')

    <style>
        table{
        	border-collapse: collapse;
        	width: 100%;
        	border-top: 2px solid black;
        	border-bottom: 2px solid black;
        }

        th{
            text-align: left;
            border: 1px solid black;
            margin: 0px !important;
            font-size: 18px !important;
        }

        td{
            text-align: left;
            border: 1px solid black;
            margin: 0px !important;
        }
    </style>
    
@endsection

@section('contenido')
    <h2>Listado de zonas sociales</h2>
    <br>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Valor uso</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($zonas_sociales as $zona_social)
                <tr>
                    <td>{{ $zona_social->nombre }}</td>
                    <td>$ {{ number_format($zona_social->valor_uso) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection