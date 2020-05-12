{{-- Vista para agregar mascotas a una unidad --}}
<style>
    .foto_mascota{
        height: 50px;
    }
</style>

<h3>Mascotas</h3>
<br>

{{-- Modal para agregar mascotas  --}}
<div id="mascotas" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Agregar mascota</h4>
      </div>
      <div class="modal-body">
        <form id="dataMascota">
            <label class="btn btn-warning" for="foto">Foto</label>
            <input onchange="changeFile()" style="display:none"  accept="image/jpeg" type="file" name="foto" id="foto">
            <label id="filename" for="">Nombre del archivo: </label>
            <br>
            <label for="nombre_mascota">Nombre</label>
            <input class="form-control" type="text" id="nombre_mascota" name="nombre_mascota">
            <br>
            <label for="codigo">Código</label>
            <input class="form-control" min="0" type="number" id="codigo" name="codigo">
            <br>
            <label for="raza">Raza</label>
            <input class="form-control" type="text" id="raza" name="raza">
            <br>
            <label for="fecha_nacimiento_mascota">Fecha de Nacimiento</label>
            <input class="form-control" type="date" id="fecha_nacimiento_mascota" name="fecha_nacimiento_mascota">
            <br>
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
            <br>
            <label for="tipo_mascota">Tipo de mascota</label>
            <select onchange="checkTipo(this);" class="form-control" name="tipo_mascota" id="tipo_mascota">
                <option value="">Seleccione tipo de mascota...</option>
                @foreach ($tipos_mascotas as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                @endforeach
                <option value="otro">Otro</option>
            </select>

        </form>

      </div>
      <div class="modal-footer">
        <button onclick="guardarMascota();" type="button" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>

{{-- Modal para agregar tipos de mascota  --}}
<div class="modal fade" id="modalAddTipoMascota" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-padding">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                <h4 class="text-center">
                    Agregar Tipo de Mascota
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
                            <input type="text" class="form-control field-tipo" name="tipo" placeholder="Ingrese el nuevo tipo de documento" autocomplete="off" id="tipo">
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


<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mascotas"><i class="fa fa-plus"></i> Agregar mascota</button>
<br>

<table class="table">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Código</th>
            <th>Fecha Nacimiento</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Raza</th>
            <th>Observaciones</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody id="data-mascotas">
    </tbody>
</table>

<hr>

<script>

    //mostrar el nombre de la imagen seleccionada
    function changeFile(){
        let label = document.querySelector('#filename');
        label.innerHTML = 'Nombre del archivo: ' + document.querySelector('#foto').files[0].name;
    }

    //Mostrar el modal para insertar tipos de mascotas
    /***************************************************/
    function checkTipo(e){
        let selected = $(e).val();
        if(selected == 'otro'){
            $('#modalAddTipoMascota').modal('show');
            $('#mascotas').modal('hide');
        }
    }

    //Guardar el tipo de mascotas
    /****************************/
    $('#formTipo').submit(function (e) { 
        e.preventDefault();

        //validar que el campo tipo no se encuentre vacío
        if($('.field-tipo').val() == ''){
            $('.field-tipo').css({
                border: '1px solid rgba(244,67,54,.8)',
            });
            $('.error-validate-tipo').css({
                color: 'rgba(244,67,54,.8)'
            });
            alertify.error("Estos campos son requeridos, por favor completelos.");
            return;
        } else {
            $('.field-tipo').css({
                border: '1px solid rgba(76,175,80,.9)',
            });
            $('.error-validate-tipo').css({
                color: 'rgba(76,175,80,.9)'
            });
        }

        $.ajax({
            type: "POST",
            url: "{{ url('tipos_mascotas') }}",
            data: $("#formTipo").serialize(),
            dataType: "json"
        }).done((data)=>{
            console.log(data);
            swal('Logrado!','tipo de mascota agregado correctamente','success').then(()=>{
                $('#tipo_mascota').prepend(`<option value="${data.id}" selected>${data.tipo}</optio>`);
                $('#modalAddTipoMascota').modal('hide');
                $('#mascotas').modal('show');
            });
        }).fail((data)=>{
            console.log(data);
        });
    });

    //variables para controlar la lista de mascotas
    /**********************************************/
    var listaMascotas = new Array();
    var nroMascotas = 0;
    var auxNroMascotas = 0;



    //metodos de guardado de la lista de mascotas en el cliente
    /**********************************************************/
    function guardarMascota(){
        if(true){//no se pide ninguna validación
            //slice()
            let copiaFoto = foto.files;
            let auxMascota = {
                id: nroMascotas,
                foto: jQuery.extend(true, {}, copiaFoto),
                nombre: nombre_mascota.value,
                codigo: codigo.value,
                raza: raza.value,
                fecha_nacimiento: fecha_nacimiento_mascota.value,
                descripcion: descripcion.value,
                tipo: tipo_mascota.value
            }

            let auxTipoText = $(`#tipo_mascota option[value='${tipo_mascota.value}']`)[0].innerHTML;
            listaMascotas.push(jQuery.extend(true, {}, auxMascota));

            //imagen de la mascota
            let copia = document.querySelector('#foto').files[0];
            if(copia != null && copia != undefined){
                let reader = new FileReader();

                // Leemos el archivo subido y se lo pasamos a nuestro fileReader
                reader.readAsDataURL(copia);
                
                // Le decimos que cuando este listo ejecute el código interno
                reader.onload = function(){
                    image = document.querySelector(`#img_mascota${auxNroMascotas}`);
                    image.src = reader.result;
                }
            }


            auxNroMascotas = nroMascotas;

            $('#data-mascotas').append(`
                <tr data="${nroMascotas}">
                    <td><img id="img_mascota${nroMascotas}" class="foto_mascota" src="" alt="No aplica"></td>
                    <td>${auxMascota.codigo}</td>
                    <td>${auxMascota.fecha_nacimiento}</td>
                    <td>${auxMascota.nombre}</td>
                    <td>${auxTipoText}</td>
                    <td>${auxMascota.raza}</td>
                    <td>${auxMascota.descripcion}</td>
                    <td>
                        <a class="btn btn-danger btn-lg" onclick="eliminarMascota(${nroMascotas})">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `);

            nroMascotas++;
            ingreso = true;

            swal('Logrado!','Mascota agregada corretamente','success').then(()=>{
                $('#dataMascota').trigger('reset');
                $('#mascotas').modal('hide');
                let label = document.querySelector('#filename');
                label.innerHTML = 'Nombre del archivo: ';
            });

        }
    }

    //eliminar mascota de la lista del cliente
    function eliminarMascota(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar esta mascota?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaMascotas.splice(searchMascota(id),1);
                $(`#data-mascotas tr[data=${id}]`).remove();
            }
        });

    }

    function searchMascota(id){
        for (var i = 0; i < listaMascotas.length; i++) {
            if (listaMascotas[i].id == id) {
                return i;
            }
        }
        return null;
    }


</script>