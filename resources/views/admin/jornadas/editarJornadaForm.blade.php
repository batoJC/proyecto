<div class="modal fade" id="editarJornadaModal" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	Editar jornada
		        </h4>
      		</div>
      		<div class="modal-body">
              <form method="POST" id="dataJornadaEditar" action="{{ url('updateJornada') }}">
                    <input type="hidden" id="jornada_id" name="jornada_id">
                    <input type="hidden" value="{{ $empleado->id }}" id="empleado" name="empleado">
                    <input id="periodo" name="periodo" value="{{$year}}-{{$month}}" type="hidden">
                    @csrf

                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input id="fecha" name="fecha" type="date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="entrada">Entrada</label>
                        <input id="entrada" name="entrada" type="time" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="salida">Salida</label>
                        <input id="salida" name="salida" type="time" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HOD">Horas ordinarias diurnas</label>
                        <input id="HOD" name="HOD" type="number" min="0" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HON">Horas ordinarias nocturnas</label>
                        <input id="HON" name="HON" type="number" min="0" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HODF">Horas ordinarias diurnas festivas</label>
                        <input id="HODF" name="HODF" type="number" min="0" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HONF">Horas ordinarias nocturnas festivas</label>
                        <input id="HONF" name="HONF" type="number" min="0" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HEDO">Horas extra diurnas ordinarias</label>
                        <input id="HEDO" name="HEDO" type="number" min="0" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HENO">Horas extra nocturnas ordinarias</label>
                        <input id="HENO" name="HENO" type="number" min="0" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HEDF">Horas extra diurnas festivas</label>
                        <input id="HEDF" name="HEDF" type="number" min="0" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="HENF">Horas extra nocturnas festivas</label>
                        <input id="HENF" name="HENF" type="number" min="0" class="form-control">
                    </div>
                    <br>
                    <div class="form-group text-center">
                        <button class="btn btn-success form-control" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                    
                </form>
      		</div>
    	</div>
  	</div>
</div>