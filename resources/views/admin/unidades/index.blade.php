@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('home') }}">Inicio</a>
                </li>
                  <li>Tipos de unidad</li>
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
                        <a target="_blanck" href="#">Video 1</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <h1 class="text-center">Listado de los tipos de unidad</h1>
    <div class="alert alert-success-original alert-dismissible" role="alert">
        <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">x</span>
        </button>
        <h5>
            Para agregar una unidad debe de seleccionar un tipo de unidad
        </h5>
    </div>
    <div class="container-fluid">
    @php
        $contador = 0;
        foreach ($tipos as $value) {
            if($contador == 0){
                echo '<div class="row">';
            }
    
            echo '<div class="col-xs-12 col-sm-4">
                    <div onclick="changePage('.$value->id.')" class="card">
                        <i class="fa fa-cubes"></i>
                        <br><br>
                        <h3>'.$value->nombre.'</h3>
                    </div>
                </div>';
    
            $contador++;
            if($contador == 3){
                echo '</div><br>';
                $contador = 0;
            }
        }
    
        if($contador != 0){
            echo '</div><br>';
        }
    
    @endphp
    
    </div>
</div>

	
@endsection
@section('ajax_crud')
    <script>
        function changePage(id){
            window.location += 'tipo/'+id
        }
    </script>
@endsection