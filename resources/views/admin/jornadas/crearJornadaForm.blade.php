<div class="modal fade" id="crearJornadasModal" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	Registrar Jornada
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form id="data_jornada">
        			
					{{-- Cada campo --}}
					<div class="row">
                        <h1 class="text-center">Inicio Jornada</h1>
                        <div class="col-4 col-md-3 text-center">
                            <div class="m-5">
                                <label for="">Día</label>
                                <input value="01" min="01" max="{{ cal_days_in_month(CAL_GREGORIAN, $month, $year) }}" class="form-control input" type="number" id="inicio_dia" required>
                                <span class="validity span"></span>
                            </div>
                        </div>
                        <div class="col-4 col-md-3 text-center">
                             <div class="m-5">
                                <label for="">Hora</label>
                                <input value="01" min="01" max="12" class="form-control input" type="number" id="inicio_hora" required>
                                <span class="validity span"></span>
                            </div>
                        </div>
                        <div class="col-4 col-md-3 text-center">
                             <div class="m-5">
                                <label for="">Minuto</label>
                                <input value="00" min="00" max="59" class="form-control input" type="number" id="inicio_minuto">
                                <span class="validity span"></span>
                            </div>
                        </div>
                        <div class="col-4 col-md-3 text-center">
                            <div class="m-5">
                                <label style="visibility: hidden;" for="">test</label>
                                <select class="form-control input" id="inicio_tipo">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    {{-- Cada campo --}}
					<div class="row">
                        <h1 class="text-center">Final Jornada</h1>
                        <div class="col-4 col-md-3 text-center">
                            <div class="m-5">
                                <label for="">Día</label>
                                <input value="01" min="01" max="{{ cal_days_in_month(CAL_GREGORIAN, $month, $year) }}" class="form-control input" type="number" id="final_dia" required>
                                <span class="validity span"></span>
                            </div>
                        </div>
                        <div class="col-4 col-md-3 text-center">
                             <div class="m-5">
                                <label for="">Hora</label>
                                <input value="01" min="01" max="12" class="form-control input" type="number" id="final_hora" required>
                                <span class="validity span"></span>
                            </div>
                        </div>
                        <div class="col-4 col-md-3 text-center">
                             <div class="m-5">
                                <label for="">Minuto</label>
                                <input value="00" min="00" max="59" class="form-control input" type="number" id="final_minuto">
                                <span class="validity span"></span>
                            </div>
                        </div>
                        <div class="col-4 col-md-3 text-center">
                            <div class="m-5">
                                <label style="visibility: hidden;" for="">test</label>
                                <select class="form-control input" id="final_tipo">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="col- col-md-12 text-center">
                        <input id="continuo" type="checkbox" name="continuo">
                        <label for="continuo">Jornada continua</label>
                    </div>
                    <br>
                    <br>
                    <div class="row text-center">
                        <button type="button" onclick="generarJornadas();" class="btn bg-blue"><i class="fa fa-magic"></i> Generar jornadas</button>
                    </div>
                    <br>
        		</form>

                    <div id="div_jornadas" class="hide">
                        <h3 class="text-center">Jornadas generadas</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>HOD</th>
                                    <th>HON</th>
                                    <th>HODF</th>
                                    <th>HONF</th>
                                    <th>HEDO</th>
                                    <th>HENO</th>
                                    <th>HEDF</th>
                                    <th>HENF</th>
                                </tr>
                            </thead>
                            <tbody id="data_jornadas">

                            </tbody>
                        </table>
                        <br>
                        <div class="row text-center">
                            <form id="data_form_jornadas" action="{{ url('jornadasStore') }}" method="post">
                                @csrf
                                <input type="hidden" id="datos" name="datos">
                                <input type="hidden" value="{{ $empleado->id }}" id="empleado" name="empleado">
                                <input id="periodo" name="periodo" value="{{$year}}-{{$month}}" type="hidden">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar jornadas</button>
                            </form>
                        </div>
                    </div>
      		</div>
    	</div>
  	</div>
</div>