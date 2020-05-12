{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modalAddTipoDivision" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-padding">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <h4 class="text-center">
                        Agregar Tipo de División
                        &nbsp; 
                    </h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="formTipo">
                        @csrf {{ method_field('POST') }}
                        <div class="row">
                            <div class="col-md-4 error-validate-tipo">
                                <i class="fa fa-address-book"></i>
                                <label class="margin-top">
                                    Tipo
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control field-tipo" name="division" placeholder="Ingrese el nuevo tipo de documento" autocomplete="off" id="division">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">
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