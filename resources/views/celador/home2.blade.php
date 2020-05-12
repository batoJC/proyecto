@extends('../layouts.app_dashboard_celador')
<style>
    iframe{
        width: 100%;
        height: 600px;
    }

</style>
@section('content')

<ul class="breadcrumb">
    <li>
        <a href="{{ asset('home') }}">Inicio</a>
    </li>
      <li>Cartas</li>
</ul>
@include('celador.modalInfo')

<br><br>
<table class="table table-stripped datatable">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Unidad</th>
            <th>Cuerpo</th>
            <th>Ver pdf</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cartas as $carta)
            <tr>
                <td>{{ $carta->fecha }}</td>
                <td>{{ $carta->tipo }}</td>
                <td>{{ $carta->unidad->tipo->nombre }} {{ $carta->unidad->numero_letra }}</td>
                <td>{{ $carta->cuerpo }}</td>
                <td>
                    <a class="btn btn-default" onclick="loadDataCarta({{ $carta->id }})">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>    

@endsection
@section('ajax_crud')

    <script>
        var csrf_token = $('meta[name="csrf-token"]').attr('content');


        function loadDataCarta(id){
            $('#titulo_modal').text('Carta');
            $('.modal-body').html(`<iframe id="frame" title="Pdf de la carta"
                src="{{url('cartas')}}/${id}?_token=${csrf_token}">
            </iframe>`);
            $('.info').modal('show');
        }
    </script>

@endsection