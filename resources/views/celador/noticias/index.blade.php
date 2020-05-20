@extends('../layouts.app_dashboard_celador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('noticias') }}">Inicio</a>
                </li>
                  <li>Noticias</li>
            </ul>
        </div>
        <div class="col-1 col md-1 text-right">
            <div class="btn-group">
                <i  data-placement="left" 
                    title="Ayuda" 
                    data-toggle="dropdown" 
                    type="button" 
                    aria-expanded="false"
                    class="fa blue fa-question-circle-o ayuda">
                </i>
                <ul role="menu" class="dropdown-menu pull-right">
                    <li>
                        <a target="_blanck" href="https://youtu.be/25rVPzEFYzQ">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('celador.evidencias.show')
    <br>
    @foreach($noticias as $notic)
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="col-lg-12 no-padding news-div">
                <img src="{{ asset('imgs/private_imgs/'.$notic->foto) }}" alt="Photo">
                <h2>{{ $notic->titulo }}</h2>
                <p>{{ str_limit($notic->descripcion, 100) }}</p>
                <p class="autor">
                    <i class="fa fa-user-circle"></i>
                    {{ $notic->user->nombre_completo }}
                    &nbsp;&nbsp;
                    <a href="{{ url('noticias/'.$notic->id) }}">
                        Leer más &nbsp;
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </p>
            </div>
        </div>
    @endforeach
</div>

	
@endsection
@section('ajax_crud')
	<script>

    </script>
@endsection