@php
    $id_rol = Auth::user()->id_rol;
@endphp
@if ($id_rol == 2 
    || ($id_rol == 3 and $documento->propietario)/*Propietarios*/ 
    || ($id_rol == 4 and $documento->porteria)/*Porteria*/)

    <h3 class="text-center">{{ $documento->nombre }}</h3>
    <h4>{{ $documento->descripcion }}</h4>
    <br>
    <h3 class="green">Lista archivos</h3>
    <table class="table">
        <tbody>
            @foreach (explode(';',$documento->archivos) as $archivo)
                <tr>
                    <td>{{ $archivo }}</td>
                    <td>
                        <a target="_blank" href="{{ asset('document/'.$archivo) }}" class="btn btn-default">
                            <i class="fa fa-eye"></i>
                        </a>    
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@endif
