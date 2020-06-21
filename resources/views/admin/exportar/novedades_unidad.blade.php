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
<h2>{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</h2>
<br>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Novedad</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($unidad->novedades as $novedad)
            <tr>
                <td>{{ date('d-m-Y',strtotime($novedad->fecha)) }}</td>
                <td>{{ $novedad->contenido }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection