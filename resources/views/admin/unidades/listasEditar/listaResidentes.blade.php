{{-- Vista para agregar recidentes a una unidad --}}

<h3>Residentes</h3>
<br>

{{-- Modal para agregar mascotas  --}}
<div id="residentes" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" >Agregar residentes</h4>
      </div>
      <div class="modal-body">
        <form id="dataResidente">
            <input type="hidden" name="id_residente" id="id_residente">
            <label for="tipo_residente">Tipo de residente</label>
            <select class="form-control" name="tipo_residente" id="tipo_residente">
                <option value="inquilino">Inquilino</option>
                <option value="familiar">Familiar</option>
                <option value="propietario">Propietario</option>
            </select>
            <br>

            <label class="validate-label-1" for="nombre_residente">Nombres</label>
            <input class="form-control validate-input-1" type="text" id="nombre_residente" name="nombre_residente">
            <br>

            <label class="validate-label-2" for="apellido_residente">Apellidos</label>
            <input class="form-control validate-input-2" type="text" id="apellido_residente" name="apellido_residente">
            <br>

            <label for="fecha_nacimiento_residente">Fecha de Nacimiento</label>
            <input class="form-control" type="date" id="fecha_nacimiento_residente" name="fecha_nacimiento_residente">
            <br>

            <label for="ocupacion_residente">Ocupación</label>
            <input class="form-control" type="text" id="ocupacion_residente" name="ocupacion_residente">
            <br>

            <label for="profesion_residente">Profesión</label>
            <input class="form-control" type="text" id="profesion_residente" name="profesion_residente">
            <br>

            <label for="direccion_residente">Lugar de trabajo</label>
            <input class="form-control" type="text" id="direccion_residente" name="direccion_residente">
            <br>

            <label for="email_residente">Email</label>
            <input class="form-control" type="email" id="email_residente" name="email_residente">
            <br>

            <label for="genero_residente">Género</label>
            <select class="form-control" name="genero_residente" id="genero_residente">
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="me abstengo">Me abstengo</option>
            </select>
            <br>

            <label for="id_tipo_documento_residente">Tipo de documento</label>
            <select class="form-control" name="id_tipo_documento_residente" id="id_tipo_documento_residente">
                @foreach ($tipos_documentos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                @endforeach
            </select>
            <br>

            <label class="validate-label-3" for="documento_residente">Documento</label>
            <input type="text" class="form-control validate-input-3" name="documento_residente" id="documento_residente">
            <br>

        </form>

      </div>
      <div class="modal-footer">
        <button onclick="guardarResidente();" type="button" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>




<button type="button" class="btn btn-primary" onclick="openModalAddResidente()">Agregar residente</button>
<br>

<table class="table">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Tipo</th>
            <th>Nombre completo</th>
            <th>Ocupación</th>
            <th>Lugar de trabajo</th>
            <th>Género</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody id="data-residentes">
        @foreach ($unidad->residentes()->where('estado','Activo')->get() as $residente)
                <tr data="residente_{{ $residente->id }}">
                <th>{{ $residente->documento  }}</th>
                <th>{{ $residente->tipo_residente  }}</th>
                <th>{{ $residente->nombre  }} {{ $residente->apellido  }}</th>
                <th>{{ $residente->ocupacion  }}</th>
                <th>{{ $residente->direccion  }}</th>
                <th>{{ $residente->genero  }}</th>
                <td>
                    <a class="btn btn-lg btn-default" onclick="consultarResidente({{ $residente->id }})">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a class="btn btn-lg btn-default" onclick="desactivarResidente({{ $residente->id }})">
                        <i class="fa fa-times"></i>
                    </a>                        
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<script>

    //variables para controlar la lista de residentes
    /**********************************************/
    var listaResidentes = new Array();
    var nroResidentes = 0;

    function openModalAddResidente(){
        $('#dataResidente').trigger('reset');
        $('#id_residente').val('');
        $('#residentes').modal('show');
    }

    //metodos de guardado de la lista de residentes en el cliente
    /**********************************************************/
    function guardarResidente(){

        if(verificarFormulario('dataResidente',3)){
            let id = $('#id_residente').val();
            if(id != ''){

                let auxResidente = {
                    action: 'editar',
                    id: id,
                    tipo_residente: tipo_residente.value,
                    nombre: nombre_residente.value,
                    apellido: apellido_residente.value,
                    profesion: profesion_residente.value,
                    ocupacion: ocupacion_residente.value,
                    email: email_residente.value,
                    direccion: direccion_residente.value,
                    fecha_nacimiento: fecha_nacimiento_residente.value,
                    genero: genero_residente.value,
                    documento: documento_residente.value,
                    tipo_documento : id_tipo_documento_residente.value
                }


                //agregar a la lista o reempleazar el ya registrado
                let busqueda = searchResidente('id',id);
                if(busqueda != null){
                    listaResidentes.splice(busqueda, 1, auxResidente);
                }else{
                    listaResidentes.push(jQuery.extend(true, {}, auxResidente));
                }
                
                $(`#data-residentes tr[data=residente_${id}]`).html(`
                        <td>${auxResidente.documento}</td>
                        <td>${auxResidente.tipo_residente}</td>
                        <td>${auxResidente.nombre} ${auxResidente.apellido}</td>
                        <td>${auxResidente.ocupacion}</td>
                        <td>${auxResidente.direccion}</td>
                        <td>${auxResidente.genero}</td>
                        <td>
                            <a class="btn btn-lg btn-default" onclick="consultarResidente(${id})">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-lg btn-default" onclick="desactivarResidente(${id})">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                `);  
    
                swal('Logrado!','Empleado editado corretamente','success').then(()=>{
                    $('#dataResidente').trigger('reset');
                    $('#residentes').modal('hide');
                });
            }else{

                let auxResidente = {
                    idResidente: nroResidentes,
                    action: 'agregar',
                    tipo_residente: tipo_residente.value,
                    nombre: nombre_residente.value,
                    apellido: apellido_residente.value,
                    profesion: profesion_residente.value,
                    ocupacion: ocupacion_residente.value,
                    email: email_residente.value,
                    direccion: direccion_residente.value,
                    fecha_nacimiento: fecha_nacimiento_residente.value,
                    genero: genero_residente.value,
                    documento: documento_residente.value,
                    tipo_documento : id_tipo_documento_residente.value
                }

                listaResidentes.push(jQuery.extend(true, {}, auxResidente));


                $('#data-residentes').append(`
                    <tr data="${nroResidentes}">
                        <td>${auxResidente.documento}</td>
                        <td>${auxResidente.tipo_residente}</td>
                        <td>${auxResidente.nombre} ${auxResidente.apellido}</td>
                        <td>${auxResidente.ocupacion}</td>
                        <td>${auxResidente.direccion}</td>
                        <td>${auxResidente.genero}</td>
                        <td>
                            <a class="btn btn-danger btn-lg" onclick="eliminarResidente(${nroResidentes})">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `);

                nroResidentes++;
                ingreso = true;

                swal('Logrado!','Residente agregado corretamente','success').then(()=>{
                    $('#dataResidente').trigger('reset');
                    $('#residentes').modal('hide');
                });
            }

        }
    }

    function eliminarResidente(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar este residente?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaResidentes.splice(searchResidente('idResidente',id),1);
                $(`#data-residentes tr[data=${id}]`).remove();
            }
        });

    }

    function searchResidente(campo,id){
        for (var i = 0; i < listaResidentes.length; i++) {
            if (listaResidentes[i][campo] == id) {
                return i;
            }
        }
        return null;
    }

    function desactivarResidente(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer desactivar este residente?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaResidentes.push({
                    action : 'inactivar',
                    id : id
                });
                swal('Logrado!','Residente inactivado corretamente','success');
                retiro = true;
            }
        });
    }


    function consultarResidente(id){
        $.ajax({
            type: "GET",
            url: "{{ url('residentes') }}/"+id,
            dataType: "json"
        }).done((data)=>{
            console.log(data);

            $('#id_residente').val(data.id);
            $('#tipo_residente').val(data.tipo_residente);
            $('#nombre_residente').val(data.nombre);
            $('#apellido_residente').val(data.apellido);
            $('#fecha_nacimiento_residente').val(data.fecha_nacimiento);
            $('#profesion_residente').val(data.profesion);
            $('#ocupacion_residente').val(data.ocupacion);
            $('#email_residente').val(data.email);
            $('#direccion_residente').val(data.direccion);
            $('#genero_residente').val(data.genero);
            $('#id_tipo_documento_residente').val(data.tipo_documento_id);
            $('#documento_residente').val(data.documento);
            $('#residentes').modal('show');
        });
    }



</script>