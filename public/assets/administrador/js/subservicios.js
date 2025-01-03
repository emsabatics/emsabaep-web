function urlbacktoservice(){
    window.location= '/servicios';
}

function urlbackservicio(){
    window.location= '/listsubservice-services/'+utf8_to_b64(idservice);
}

function urlregistrarsubservice(){
    window.location= '/registrar_subservicio/'+utf8_to_b64(idservicio);
}

function guardarSubservicio(){
    var token= $('#token').val();

    var idservicio = $("#idservicio").val();
    var titulo= $('#inputTitleSubservice').val();
    var tregistro = $("#selTypeRegister :selected").val();
    //console.log(descripcion);

    let fileInput = document.getElementById("file");
    var lengimg = fileInput.files.length;
    let fileInputIcon = document.getElementById("fileIcon");
    var lengimgicon = fileInputIcon.files.length;

    if (titulo == "") {
        $('#inputTitleSubservice').focus();
        swal("Ingrese el Título del Servicio", "", "warning");
    } else if(titulo.length > 50){
        $('#inputTitleSubservice').focus();
        swal("Ingrese 50 caracteres como máximo", "", "warning");
    } else if (tregistro == "0") {
        $("#selTypeRegister").focus();
        swal("Seleccione el Tipo de Registro", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else if (lengimgicon == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimgicon > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else {
        var element = document.querySelector('.savesubservice');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');

        var data = new FormData(formSubServicio);
        data.append("tregistro", tregistro);

        setTimeout(() => {
            sendNewSubService(token, data, "/store-subservice", element); 
        }, 700);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewSubService(token, data, url, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#modalFullSend').modal('hide');
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Servicio Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    //urlbackservicio();
                    window.location= window.location.href;
                },1500);
                
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "Documento ya se encuentra registrado.", "error");
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            }
        }else if(xr.status === 400){
            $('#modalFullSend').modal('hide');
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    };
    xr.send(data);
}

function editarSubService(id, index){
    $('#idsubservicio').val(id);
    $('#indexselection').val(index);
    var xr = new XMLHttpRequest();
    xr.open('GET', '/get-name-subservice/'+id, true);
    xr.setRequestHeader('X-CSRF-TOKEN', token);

    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                $('#idservicio').val(v.id_servicio);
                $('#inputUpSubservicio').val(v.nombre);
                if(v.estado=='0'){
                    $("#customSwitchSubService").prop('checked',false);
                    $('#estadoSubServicio').html('Inactivo');
                }else if(v.estado=='1'){
                    $("#customSwitchSubService").prop('checked',true);
                    $('#estadoSubServicio').html('Activo');
                }
            })
            setTimeout(() => {
                $('#modalUpdateSubService').modal('show');
            }, 450);
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    };
    xr.send();
}

$("#customSwitchSubService").on('change', function() {
    if ($(this).is(':checked')) {
        $(this).attr('value', 'activo');
        $('#estadoSubServicio').html('Activo');
    }
    else {
       $(this).attr('value', 'inactivo');
       $('#estadoSubServicio').html('Inactivo');
    }
});

function getSelectEstadoCheck(){
    if( $('#customSwitchSubService').prop('checked') ) {
        return 1;
    }else{
        return 0;
    }
}

function actualizarSubservicio(){
    var token= $('#token').val();
    var itemselection= $('#indexselection').val();
    var idsubservicio= $('#idsubservicio').val();
    var idservicio= $('#idservicio').val();
    var nombre= $('#inputUpSubservicio').val();
    var estadosubservicio= getSelectEstadoCheck();

    if(nombre==''){
        $('#inputUpSubservicio').focus();
        swal('Ingrese un Nombre','','warning');
    }else{
        var formData= new FormData();
        formData.append("idservicio", idservicio);
        formData.append("idsubservicio", idsubservicio);
        formData.append("nombre", nombre);
        formData.append("estadosubservicio", estadosubservicio);

        var element = document.querySelector('.updatesubservicio');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-subservicio', true);
        xr.setRequestHeader('X-CSRF-TOKEN', token);

        xr.onload = function(){
            if(xr.status === 200){
                //console.log(this.responseText);
                var myArr = JSON.parse(this.responseText);
                if(myArr.resultado==true){
                    swal({
                        title:'Excelente!',
                        text:'Registro Actualizado',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });
    
                    setTimeout(function(){
                        $('#tituloSubservicionro'+itemselection).html(nombre);
                        if(estadosubservicio==0){
                            $('#spanHeaderSubServiceStatus-'+itemselection).html('Inactivo');
                        }else if(estadosubservicio==1){
                            $('#spanHeaderSubServiceStatus-'+itemselection).html('Activo');
                        }
                        $('#modalUpdateSubService').modal('hide');
                        $('#inputUpSubservicio').val("");
                        $('#idservicio').val("");
                        $('#idsubservicio').val("");
                        $('#indexselection').val("");
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
                    },1500);
                }else if(myArr.resultado==false){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo guardar el registro','','error');
                }
            }else if(xr.status === 400){
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        };
        xr.send(formData);
    }
}

/* FUNCION PARA ELIMINAR SUBSERVICIO */
function eliminarSubService(id){
    var token=$('#token').val();
    var html="";
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea eliminar este registro?",
        showCloseButton: false,
        showCancelButton: true,
        allowOutsideClick: false,
        focusConfirm: false,
        focusCancel: true,
        cancelButtonColor: "#d33",
        confirmButtonText: '<i class="fa fa-check-circle"></i> Sí',
        confirmButtonAriaLabel: "Thumbs up, Si",
        cancelButtonText: '<i class="fa fa-close"></i> No',
        cancelButtonAriaLabel: "Thumbs down",
      }).then((result) => {
        if (result.value) {
            $.ajax({
            url: "/eliminar-subservicio",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: id
            },
            success: function (res) {
                if (res.resultado == true) {
                    swal({
                        title: "Excelente!",
                        text: "Registro Eliminado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    
                    setTimeout(function () {
                        window.location = window.location.href;
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
}

/* -------------------------------------------------------------------------------------------------- */
//                               CONFIGURACION DETALLE INFORMATIVO
/* -------------------------------------------------------------------------------------------------- */
function viewInfoSubService(id){
    window.location='/subservice-view-detail-infor/'+utf8_to_b64(id)+'/v1';
}

/* -------------------------------------------------------------------------------------------------- */
//                               CONFIGURACION LISTA DESPLEGABLE
/* -------------------------------------------------------------------------------------------------- */
function viewListItemsSubService(id){
    window.location='/subservice-view-detaillist/'+utf8_to_b64(id)+'/v1';
}

/* -------------------------------------------------------------------------------------------------- */
//                               CONFIGURACION TEXTO Y ARCHIVOS
/* -------------------------------------------------------------------------------------------------- */
function viewFileSubService(id){
    window.location='/subservice-view-filelist/'+utf8_to_b64(id)+'/v1';
}
