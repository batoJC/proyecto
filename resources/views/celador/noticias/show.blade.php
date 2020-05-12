@extends('../layouts.app_dashboard_celador')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('home') }}">Inicio</a>
		</li>
		<li>
			<a href="{{ url('noticias') }}">Noticias</a>
		</li>
	  	<li> {{ $noticia->titulo }}</li>
	</ul>
	<div class="col-lg-12">
		<div class="col-lg-12 no-padding news-div text-center">
			<h1 class="text-center">{{ $noticia->titulo }}</h1>
			<div class="col-lg-6">
				<img width="auto" height="500px" src="{{ asset('imgs/private_imgs/'.$noticia->foto) }}" alt="Photo">
			</div>
			<div class="col-lg-6">
				<h2 class="text-left">{{ $noticia->descripcion }}</h2>
				<p class="autor">
					<i class="fa fa-user-circle"></i>
					Autor: <i>{{ $noticia->user->nombre_completo }}</i>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Fecha: <i>{{ $noticia->created_at }}</i>
				</p>
			</div>
			@if ($noticia->evidencia)
				@include('dueno.evidencias.show')
				<a onclick="ver({{ $noticia->evidencia->id }})" href="#">
					Ver evidencia &nbsp;
					<i class="fa fa-arrow-right"></i>
				</a>
			@endif
			<br>
			<br>
			<br>
		</div>
	</div>
@endsection