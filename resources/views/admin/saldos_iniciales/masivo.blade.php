@extends('../layouts.app_dashboard_admin')

@section('title', 'Ejecución Presupuestal Individual')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-12 col-md-6">
            <h1 class="text-center">Carga masiva de saldos iniciales</h1>
            <br>
            <p> -Recuerde verificar el formato de los datos  cargados en el archivo excel. <br>
                -Descargue el archivo base e ingrese toda la información. <br>
                -Cargue el archivo con los datos ingresados.</p>
            <br>
            <form id="data_masivo" action="{{ url('masivo_saldos') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col text-center">
                    <a download href="{{ url('download_base_saldos') }}" class="btn btn-default">Descargar archivo &nbsp&nbsp <i class="fa fa-download"></i></a>
                    <label for="archivo" class="btn btn-success">Cargar archivo &nbsp&nbsp <i class="fa fa-upload"></i></label>
                </div>
                <input onchange="submit()" type="file" name="archivo" id="archivo" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="hide">
            </form>
        </div>
    </div>
</div>
@endsection
@section('ajax_crud')
    <script>
        function submit(){
            data_masivo.submit();
        }
    </script>
@endsection