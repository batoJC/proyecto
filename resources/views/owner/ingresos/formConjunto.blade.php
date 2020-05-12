{{-- Ventana modal de las encomiendas --}}
{{-- ******************************** --}}
<div class="modal fade" id="modal-form-conjunto" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-padding">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                <h4 class="text-center modal-title">
                    <i class="fa fa-user"></i>
                    &nbsp; 
                </h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('ingresos_csv') }}">
                    @csrf {{ method_field('POST') }}
                    {{-- Cada campo --}}
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fa fa-building"></i>
                            <label class="margin-top">
                                Conjunto
                            </label>
                        </div>
                        <div class="col-md-8">
                            <select name="id_conjunto" id="id_conjunto" class="form-control field-3 select-2">
                                <option value="">Seleccione...</option>
                                @foreach($conjunto as $conjunt)
                                    <option value="{{ $conjunt->id }}">
                                        {{ $conjunt->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success" id="send_form">
                                <i class="fa fa-send"></i>
                                &nbsp; Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>