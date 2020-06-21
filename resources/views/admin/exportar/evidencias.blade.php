@extends('admin.PDF.plantilla')

@section('style')
    <style>
        .imagen{
            height: 180px;
            width: auto;
            /* display: inline-block; */
        }

        /* .div_images{
            margin-top: 15px;
        } */

        td{
            padding: 25px 0px 0px 0px;
            /* margin:  */
            /* border-bottom: 2px solid black; */
        }

    </style>
    
@endsection

@section('contenido')
    <h2>Evidencias</h2>
    <table>
        <tbody>
            @foreach ($evidencias as $evidencia)
                <tr>
                    <td>
                        <tr>
                            <td>
                                <p>
                                    <b>Fecha: </b>{{ date('d-m-Y',strtotime($evidencia->fecha))}}<br>
                                    {{ $evidencia->contenido }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @php
                                    $files = explode(';',$evidencia->fotos);
                                @endphp
                                <div class="div_images text-center">
                                    @foreach ($files as $file)
                                        <img class="imagen" src="{{ public_path("imgs/private_imgs/{$file}") }}" alt="">
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection