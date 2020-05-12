@extends('../layouts.app_dashboard_dueno')

<link rel="stylesheet" href="{{ asset('vendors/fullcalendar/core/main.css')}}">
<link rel="stylesheet" href="{{ asset('vendors/fullcalendar/daygrid/main.css')}}">
<link rel="stylesheet" href="{{ asset('vendors/fullcalendar/timegrid/main.css')}}">
<link rel="stylesheet" href="{{ asset('vendors/fullcalendar/list/main.css')}}">

<script src="{{ asset('vendors/fullcalendar/core/main.js')}}"></script>
<script src="{{ asset('vendors/fullcalendar/daygrid/main.js')}}"></script>
<script src="{{ asset('vendors/fullcalendar/timegrid/main.js')}}"></script>
<script src="{{ asset('vendors/fullcalendar/list/main.js')}}"></script>
<script src="{{ asset('vendors/fullcalendar/interaction/main.js')}}"></script>


<style>
	#calendar{
		/* height: 80%; */
		/* width: auto; */
		max-width: 700px;
		margin: auto;
	}

	.fc-content{
		cursor: pointer !important;
	}

	.fc-event-container > a {
		width: 50% !important;
		margin-left: 20px !important;
	}

	.letrero .swal-text{
		color: red;
		font-size: 25;
		text-align: center;
	}


</style>

@section('content')
	{{-- @include('admin.reservas.form') --}}
	@include('dueno.reservas.form')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
		<li>
			<a href="{{ asset('zonas_comunes') }}">Zonas Sociales</a>
		</li>
		<li>Reservas {{ $zona_comun->nombre }}</li>
	</ul>
	<div class="container-fluid">
		<div class="col-12 text-center">
			<button class="fc-button bg-red">Recahazadas</button>
			<button class="fc-button bg-green">Aprobadas</button>
			<button class="fc-button bg-primary">Pendientes</button>
		</div>
		<div id="calendar"></div>
	</div>
	<br>
	<br>
    <br>
    <br>
@endsection
@section('ajax_crud')
<script>
	var calendarEl;
	var calendar;
	var _token = $('meta[name="csrf-token"]').attr('content');


    document.addEventListener('DOMContentLoaded', () => {
        calendarEl = document.getElementById('calendar');

        createCalendar(new Date(), 'dayGridMonth');

    });

    function createCalendar(date, view) {
        calendarEl.innerHTML = '';

        calendar = new FullCalendar.Calendar(calendarEl, {
            defaultDate: date,
            plugins: ['dayGrid', 'interaction', 'timeGrid', 'list'],
            defaultView: view,
            allDayHtml: 'Todo<br>el día',
            buttonText: {
                month: "Mes",
                week: "Semana",
                day: "Día",
                list: "Agenda",
                today: "Hoy"

            },

            events: '{{ url('allReservas',['zona_comun'=>$zona_comun->id]) }}',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridDay'
            },
            dateClick: (info) => {
                if (info.view.type == 'dayGridMonth') {
                    createCalendar(info.date, 'timeGridDay');
                } else {
                    mostrarModalAgregrar(info.date);
                }
            },
            eventClick: (info) => {
				mostrarModalEvento(info.event.id);
            },
            locale: 'Es'
        });

        calendar.render();
	}

	var aux;

	function mostrarModalAgregrar(fecha){
		let year = fecha.getFullYear();
		let month = fecha.getMonth() + 1;
		let day = fecha.getDate();
		let hours = fecha.getHours();
		let minutes = fecha.getMinutes();

		$('#formReserva').trigger('reset');

		let fecha_start = `${year}-${((month<=9)?'0'+month:month)}-${((day<=9)?'0'+day:day)}`;
		let hora_start = `${((hours<=9)?'0'+hours:hours)}:${((minutes<=9)?'0'+minutes:minutes)}:00`;

		fecha_inicio.value = fecha_start;
		fecha_fin.value = fecha_start;
		hora_inicio.value = hora_start;


		$('#modal_reserva').modal('show');
	}

	function registrarReserva(){
		if(verificarFormulario('formReserva',6)){
			$.ajax({
				type: "POST",
				url: "{{url('reservas')}}",
				data: new FormData(formReserva),
				contentType : false,
				processData: false,
				dataType: "json",
				success: function (response) {
					if(response.res){
						swal('logrado!',response.msg,'success',{className:'letrero'}).then(res=>{
							createCalendar(new Date(fecha_inicio.value), 'dayGridMonth');
							$('#modal_reserva').modal('hide');
						});
					}else{
						swal('Error!',response.msg,'error');
					}
				}
			}).fail(res=>{
				swal('Error!','Ocurrió un error en el servidor!','error');
			});
		}

		return false;
	}

	
	function actualizarPantalla(){
		calendar.refetchEvents();
	}

	function mostrarModalEvento(id){
		$.ajax({
			type: "GET",
			url: "{{ url('reservas') }}/"+id,
			data: {
				_token
			},
			dataType: "html",
			success: function (response) {
				// console.log(response);
				$('#infoReserva').modal('show');
				$('#info_reserva').html(response);
			}
		});
	}
	</script>
@endsection