@extends('../layouts.app_dashboard_admin')

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


</style>

@section('content')
	@include('admin.reservas.form')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
		<li>
			<a href="{{ asset('zonas_comunes') }}">Zonas sociales</a>
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
                }/* else {
                    console.log(info.date);
                }*/
            },
            eventClick: (info) => {
				mostrarModalEvento(info.event.id);
            },
            locale: 'Es'
        });

        calendar.render();
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