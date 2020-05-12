{{-- Vista para agregar mascotas a una unidad --}}
<style>
    .foto{
        height: 50px;
    }
</style>

<h3>Vehículos</h3>
<br>

{{-- Modal para agregar mascotas  --}}
<div id="vehiculos" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Agregar vehículo</h4>
      </div>
      <div class="modal-body">
        <form id="dataVehiculo">

            <div id="alertaVehiculo" style="display:none;" class="alert alert-danger-original alert-dismissible" role="alert">
                <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                <h5>
                    Si selecciona una nueva imagen al editar se cambiara la foto que se encuentre registrada.
                </h5>
            </div>

            <input type="hidden" name="id_vehiculo" id="id_vehiculo">

            <label class="btn btn-warning" for="foto1">Foto Vehículo</label>
            <input onchange="changeFileVehiculo(1)" style="display:none"  accept="image/jpeg" type="file" name="foto1" id="foto1">
            <label id="filename1" for="">Nombre del archivo: </label>
            <br>

            <label class="btn btn-warning" for="foto2">Foto tarjeta de propiedad cara 1</label>
            <input onchange="changeFileVehiculo(2)" style="display:none"  accept="image/jpeg" type="file" name="foto2" id="foto2">
            <label id="filename2" for="">Nombre del archivo: </label>
            <br>

            <label class="btn btn-warning" for="foto3">Foto tarjeta de propiedad cara 2</label>
            <input onchange="changeFileVehiculo(3)" style="display:none"  accept="image/jpeg" type="file" name="foto3" id="foto3">
            <label id="filename3" for="">Nombre del archivo: </label>
            <br>

            <label for="tipo_vehiculo">Tipo de vehículo</label>
            <select name="tipo_vehiculo" id="tipo_vehiculo" class="form-control">
                <option value="carro">Carro</option>
                <option value="moto">Moto</option>
                <option value="otro">Otro</option>
            </select>
            <br>

            <label for="marca_vehiculo">Marca</label>
            <input class="form-control" type="text" id="marca_vehiculo" name="marca_vehiculo">
            <br>

            <label for="color_vehiculo">Color</label>
            <input class="form-control" type="text" id="color_vehiculo" name="color_vehiculo">
            <br>

            <label class="validate-label-1" for="placa_vehiculo">Placa</label>
            <input class="form-control validate-input-1" type="text" id="placa_vehiculo" name="placa_vehiculo">
            <br>

            <label class="validate-label-2" for="registra_vehiculo">Propietario del vehículo</label>
            <input class="form-control validate-input-2" type="text" id="registra_vehiculo" name="placa_vehiculo">
            <br>

        </form>

      </div>
      <div class="modal-footer">
        <button onclick="guardarVehiculo();" type="button" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>



<button type="button" class="btn btn-primary" onclick="openModalAddVehiculo()">Agregar vehículo</button>
<br>

<table class="table">
    <thead>
        <tr>
            <th>Placa</th>
            <th>Foto vehículo</th>
            <th>Foto tarjeta cara 1</th>
            <th>Foto tarjeta cara 2</th>
            <th>Tipo</th>
            <th>Marca</th>
            <th>Color</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody id="data-vehiculos">
        @foreach ($unidad->vehiculos()->where('estado','Activo')->get() as $vehiculo)
            <tr data="vehiculo_{{$vehiculo->id}}">
                <td>{{ $vehiculo->placa }}</td>
                <td><img class="foto" src="{{ asset('imgs/private_imgs/'.$vehiculo->foto_vehiculo) }}" alt="No aplica"></td>
                <td><img class="foto" src="{{ asset('imgs/private_imgs/'.$vehiculo->foto_tarjeta_1) }}" alt="No aplica"></td>
                <td><img class="foto" src="{{ asset('imgs/private_imgs/'.$vehiculo->foto_tarjeta_2) }}" alt="No aplica"></td>
                <td>{{ $vehiculo->tipo }}</td>
                <td>{{ $vehiculo->marca }}</td>
                <td>{{ $vehiculo->color }}</td>
                <td>
                    <a class="btn btn-lg btn-default" onclick="consultarVehiculo({{ $vehiculo->id }})">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a class="btn btn-lg btn-default" onclick="desactivarVehiculo({{ $vehiculo->id }})">
                        <i class="fa fa-times"></i>
                    </a>                        
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<script>

    //mostrar el nombre de la imagen seleccionada
    function changeFileVehiculo(n){
        let label = document.querySelector('#filename'+n);
        label.innerHTML = 'Nombre del archivo: ' + document.querySelector('#foto'+n).files[0].name;
    }

    //variables para controlar la lista de vehiculos
    /**********************************************/
    var listaVehiculos = new Array();
    var nroVehiculos = 0;
    var auxNroVehiculos = 0;

    function openModalAddVehiculo(){
        $('#dataVehiculo').trigger('reset');
        $('#id_vehiculo').val('');
        $('#alertaVehiculo').css('display','none');
        $('#vehiculos').modal('show');
    }


    //metodos de guardado de la lista de vehiculos en el cliente
    /**********************************************************/
    function guardarVehiculo(){

        if(verificarFormulario('dataVehiculo',2)){
            
            let id = $('#id_vehiculo').val();
            if(id != ''){
                let copiaFoto1 = foto1.files;
                let copiaFoto2 = foto2.files;
                let copiaFoto3 = foto3.files;
                let auxVehiculo = {
                    id: id,
                    action: 'editar',
                    foto1: jQuery.extend(true, {}, copiaFoto1),
                    foto2: jQuery.extend(true, {}, copiaFoto2),
                    foto3: jQuery.extend(true, {}, copiaFoto3),
                    tipo: tipo_vehiculo.value,
                    marca: marca_vehiculo.value,
                    color: color_vehiculo.value,
                    placa: placa_vehiculo.value,
                    registra: registra_vehiculo.value
                }



                auxNroVehiculos = nroVehiculos;

                //agregar a la lista o reempleazar el ya registrado
                let busqueda = searchVehiculo('id',id);
                if(busqueda != null){
                    listaVehiculos.splice(busqueda, 1, auxVehiculo);
                }else{
                    listaVehiculos.push(jQuery.extend(true, {}, auxVehiculo));
                }

                //imagenes del vehículo
                let copia1 = document.querySelector('#foto1').files[0];
                let copia2 = document.querySelector('#foto2').files[0];
                let copia3 = document.querySelector('#foto3').files[0];
                if(copia1 != null && copia1 != undefined){
                    let reader = new FileReader();

                    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
                    reader.readAsDataURL(copia1);
                    
                    // Le decimos que cuando este listo ejecute el código interno
                    reader.onload = function(){
                        image = document.querySelector(`#img_1${auxNroVehiculos}`);
                        image.src = reader.result;
                    }
                }

                if(copia2 != null && copia2 != undefined){
                    let reader = new FileReader();

                    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
                    reader.readAsDataURL(copia2);
                    
                    // Le decimos que cuando este listo ejecute el código interno
                    reader.onload = function(){
                        image = document.querySelector(`#img_2${auxNroVehiculos}`);
                        image.src = reader.result;
                    }
                }

                if(copia3 != null && copia3 != undefined){
                    let reader = new FileReader();

                    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
                    reader.readAsDataURL(copia3);
                    
                    // Le decimos que cuando este listo ejecute el código interno
                    reader.onload = function(){
                        image = document.querySelector(`#img_3${auxNroVehiculos}`);
                        image.src = reader.result;
                    }
                }


    
                $(`#data-vehiculos tr[data=vehiculo_${id}]`).html(`
                        <td>${auxVehiculo.placa}</td>
                        <td><img id="img_1${nroVehiculos}" class="foto" src="" alt="No aplica"></td>
                        <td><img id="img_2${nroVehiculos}" class="foto" src="" alt="No aplica"></td>
                        <td><img id="img_3${nroVehiculos}" class="foto" src="" alt="No aplica"></td>
                        <td>${auxVehiculo.tipo}</td>
                        <td>${auxVehiculo.marca}</td>
                        <td>${auxVehiculo.color}</td>
                        <td>
                            <a class="btn btn-lg btn-default" onclick="consultarVehiculo(${id})">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-lg btn-default" onclick="desactivarVehiculo(${id})">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                `);    

                nroVehiculos++;

                swal('Logrado!','Vehiculo editado corretamente','success').then(()=>{
                    $('#dataVehiculo').trigger('reset');
                    $('#vehiculos').modal('hide');
                    let label1 = document.querySelector('#filename1');
                    label1.innerHTML = 'Nombre del archivo: ';
                    let label2 = document.querySelector('#filename2');
                    label2.innerHTML = 'Nombre del archivo: ';
                });


            }else{
            
                let copiaFoto1 = foto1.files;
                let copiaFoto2 = foto2.files;
                let copiaFoto3 = foto3.files;
                let auxVehiculo = {
                    idVehiculo: nroVehiculos,
                    action: 'agregar',
                    foto1: jQuery.extend(true, {}, copiaFoto1),
                    foto2: jQuery.extend(true, {}, copiaFoto2),
                    foto3: jQuery.extend(true, {}, copiaFoto3),
                    tipo: tipo_vehiculo.value,
                    marca: marca_vehiculo.value,
                    color: color_vehiculo.value,
                    placa: placa_vehiculo.value,
                    registra: registra_vehiculo.value
                }

                listaVehiculos.push(jQuery.extend(true, {}, auxVehiculo));

                //imagenes del vehículo
                let copia1 = document.querySelector('#foto1').files[0];
                let copia2 = document.querySelector('#foto2').files[0];
                let copia3 = document.querySelector('#foto3').files[0];
                if(copia1 != null && copia1 != undefined){
                    let reader = new FileReader();

                    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
                    reader.readAsDataURL(copia1);
                    
                    // Le decimos que cuando este listo ejecute el código interno
                    reader.onload = function(){
                        image = document.querySelector(`#img_1${auxNroVehiculos}`);
                        image.src = reader.result;
                    }
                }

                if(copia2 != null && copia2 != undefined){
                    let reader = new FileReader();

                    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
                    reader.readAsDataURL(copia2);
                    
                    // Le decimos que cuando este listo ejecute el código interno
                    reader.onload = function(){
                        image = document.querySelector(`#img_2${auxNroVehiculos}`);
                        image.src = reader.result;
                    }
                }

                if(copia3 != null && copia3 != undefined){
                    let reader = new FileReader();

                    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
                    reader.readAsDataURL(copia3);
                    
                    // Le decimos que cuando este listo ejecute el código interno
                    reader.onload = function(){
                        image = document.querySelector(`#img_3${auxNroVehiculos}`);
                        image.src = reader.result;
                    }
                }


                auxNroVehiculos = nroVehiculos;

                $('#data-vehiculos').append(`
                    <tr data="${nroVehiculos}">
                        <td>${auxVehiculo.placa}</td>
                        <td><img id="img_1${nroVehiculos}" class="foto" src="" alt="No aplica"></td>
                        <td><img id="img_2${nroVehiculos}" class="foto" src="" alt="No aplica"></td>
                        <td><img id="img_3${nroVehiculos}" class="foto" src="" alt="No aplica"></td>
                        <td>${auxVehiculo.tipo}</td>
                        <td>${auxVehiculo.marca}</td>
                        <td>${auxVehiculo.color}</td>
                        <td>
                            <a class="btn btn-danger btn-lg" onclick="eliminarVehiculo(${nroVehiculos})">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `);

                nroVehiculos++;
                ingreso = true;

                swal('Logrado!','Vehiculo agregado corretamente','success').then(()=>{
                    $('#dataVehiculo').trigger('reset');
                    $('#vehiculos').modal('hide');
                    let label1 = document.querySelector('#filename1');
                    label1.innerHTML = 'Nombre del archivo: ';
                    let label2 = document.querySelector('#filename2');
                    label2.innerHTML = 'Nombre del archivo: ';
                });
            }

        }
    }

    function eliminarVehiculo(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar este vehículo?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaVehiculos.splice(searchVehiculo('idVehiculo',id),1);
                $(`#data-vehiculos tr[data=${id}]`).remove();
            }
        });

    }

    function searchVehiculo(campo,id){
        for (var i = 0; i < listaVehiculos.length; i++) {
            if (listaVehiculos[i][campo] == id) {
                return i;
            }
        }
        return null;
    }

    function desactivarVehiculo(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer desactivar este vehículo?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaVehiculos.push({
                    action : 'inactivar',
                    id : id
                });
                retiro = true;
                swal('Logrado!','Vehículo inactivado corretamente','success');
            }
        });
    }


    function consultarVehiculo(id){
        $.ajax({
            type: "GET",
            url: "{{ url('vehiculos') }}/"+id,
            dataType: "json"
        }).done((data)=>{
            $('#id_vehiculo').val(data.id);
            $('#tipo_vehiculo').val(data.tipo);
            $('#marca_vehiculo').val(data.marca);
            $('#color_vehiculo').val(data.color);
            $('#placa_vehiculo').val(data.placa);
            $('#registra_vehiculo').val(data.registra);
            $('#alertaVehiculo').css('display','block');
            $('#vehiculos').modal('show');
        });
    }


</script>