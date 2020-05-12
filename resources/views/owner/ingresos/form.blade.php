{{-- Ventana modal de las encomiendas --}}
{{-- ******************************** --}}
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" data-backdrop="static">
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
                <form method="post">
                    @csrf {{ method_field('POST') }}
                    {{-- Cada campo --}}
                    <div class="row">
                        <div class="col-md-4 error-validate-1">
                            <i class="fa fa-usd"></i>
                            <label class="margin-top">
                                Valor
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input type="number" class="form-control field-1" name="valor" placeholder="Ejemplo: 200000" autocomplete="off" id="valor">
                        </div>
                    </div>
                    <br>
                    {{-- Cada campo --}}
                    <div class="row">
                        <div class="col-md-4 error-validate-6">
                            <i class="fa fa-font"></i>
                            <label class="margin-top">
                                Descripción
                            </label>
                        </div>
                        <div class="col-md-8">
                            <textarea name="descripcion" id="descripcion" cols="30" rows="5" placeholder="Ejemplo: Esto se aprobó para un arreglo de la piscina" class="form-control field-6" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <br>
                    {{-- Cada campo --}}
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fa fa-user-circle-o"></i>
                            <label class="margin-top">
                                Persona (Que Pagó)
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="persona_pago" placeholder="Ejemplo: William Henao..." autocomplete="off" id="persona_pago">
                        </div>
                    </div>
                    <br>
                    {{-- Cada campo --}}
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fa fa-user-circle-o"></i>
                            <label class="margin-top">
                                Persona (Que Recibió)
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="persona_recibe" placeholder="Ejemplo: William Henao..." autocomplete="off" id="persona_recibe">
                        </div>
                    </div>
                    <br>
                    {{-- Cada campo --}}
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fa fa-building"></i>
                            <label class="margin-top">
                                Conjunto
                            </label>
                        </div>
                        <div class="col-md-8">
                            <select name="id_conjunto" id="id_conjunto" class="form-control field-3 select-22">
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
                    {{-- Cada campo --}}
                    <div class="row">
                        <div class="col-md-4 error-validate-5">
                            <i class="fa fa-building"></i>
                            <label class="margin-top">
                                Tipo Unidad
                            </label>
                        </div>
                        <div class="col-md-8">
                            <select name="id_apto" id="id_apto" class="form-control field-5 select-22">
                                <option value="default">Seleccione...</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-success" id="send_form">
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