//FUNCION QUE OBTIENE EL LISTADO DE LAS REDES SOCIALES REGISTRADAS
function getListadoSocialMedia(socialmedia) {
    var con = 1; var estadoItem = ''; var html="";

    if (socialmedia.length === 0) {
        html += "<tr style='text-align: center;'>" +
            "<td colspan='6'>No hay registros</td>" +
            "</tr>";
    }

    $(socialmedia).each(function (i, v) {
        let firstv = v.nombre[0].toUpperCase();
        let cadena = firstv + (v.nombre.substring(1, v.nombre.length));
        var confid = v.id;
        //var confid = utf8_to_b64(v.id);
        //confid = '"' + confid + '"';
        if (v.estado == "1") {
            estadoItem = "Visible";
        } else {
            estadoItem = "No Visible";
        }

        html += "<tr id='Tr" + i + "'>" +
            "<td>" + con + "</td>" +
            "<td>" + cadena + "</td>" +
            "<td>" + v.usuario + "</td>" +
            "<td>" + v.enlace + "</td>" +
            "<td>" + estadoItem + "</td>" +
            "<td>" +
            "<div class='dropdown show'>" +
                "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                    "<i class='fas fa-cog'></i>" +
                "</a>" +
                "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>" +
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemSM(" + confid + ")'>Editar</a>";
                    if (v.estado == "1") {
                        html += "<a class='dropdown-item' href='javascript:void(0)' onclick='inactivarItemSM(" + confid + ", " + i + ")'>Inactivar</a>";
                    } else if (v.estado == "0") {
                        html += "<a class='dropdown-item' href='javascript:void(0)' onclick='activaritemSM(" + confid + ", " + i + ")'>Activar</a>";
                    }
            html += "<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarItemSM(" + confid + ")'>Eliminar</a>"+
            "</div>" +
        "</div>" +
        "</td>" +
        "</tr>";
        con++;
    });

    $('#tablaListadoSM > tbody').html(html);
    setTimeout(function () {
        $('#modalCargando').modal('hide');
    }, 500);
}

function openmodalAdd() {
    $('#modalAggSocial').modal('show');
}

function addRedSocial(){
    if(puedeConfigurarM(nameInterfaz) === 'si'){
    window.location='/agg-red-social';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

//FUNCION QUE TRANSFORMA EL PRIMER CARACTER DEL SELECT2 EN MAYÚSCULA
function getval(sel, op, e){
    //console.log(e.currentTarget.textContent);
    var cadena='';
    if(sel.value!='0'){
        let firstv = sel.value[0].toUpperCase();
        cadena= firstv+ (sel.value.substring(1, sel.value.length));
        $('#span-socialmedia').html(cadena);
        if(sel.value=='nueva'){
            Swal.fire({
                title: 'Ingrese la nueva Red Social',
                input: 'text',
                inputPlaceholder: "Instagram",
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false    
            }).then((result) => {
                if (result.value) {
                    var data = {
                        id: result.value,
                        text: result.value
                    };
                    var newOption = new Option(data.text, data.id, false, false);
                    $('#selSocialMedia').append(newOption).trigger('change');
                    let firstv = result.value[0].toUpperCase();
                    cadena= firstv+ (sel.value.substring(1, sel.value.length));
                    $('#span-socialmedia').html(cadena);
                }
            });
        }
    }
    //console.log(sel.value);
}

$('#selSocialMedia').on("change", function(e) {
    var lastValue = $(this).select2('data')[0].text;
    $('#span-socialmedia').html(lastValue);
});

$('#modalAggSocial').on('shown.bs.modal', function() {
    $(document).off('focusin.modal');
});

/* FUNCION PARA GUARDAR EL REGISTRO DE LA NUEVA RED SOCIAL */
function guardarRegistroRS(){
    var token= $('#token').val();
    var media= $('#selSocialMedia :selected').val();
    var usuario = $('#inputUsuario').val();
    var enlace= $('#textsocialmedia').val();

    if(media=='0'){
        $('#selSocialMedia').focus();
        swal('Debe seleccionar la red social','','warning');
    } else if(usuario==''){
        $('#inputUsuario').focus();
        swal('Debe ingresar el usuario','','warning');
    } else if(enlace==''){
        $('#textsocialmedia').focus();
        swal('Debe ingresar el enlace para la red social','','warning');
    } else{
        if(puedeGuardarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("media", media);
        formData.append("usuario", usuario);
        formData.append("enlace", enlace);
        guardarSocialMedia(token, formData, media, '/registro-socialm');
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function guardarSocialMedia(token, data, media, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Registro Guardado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    $('#modalAggSocial').modal('hide');
                    $('#selSocialMedia').select2('val','0');
                    $('#textsocialmedia').val("");
                    $('#inputUsuario').val("");
                    window.location='/red-social';
                },1500);
            }else if(myArr.resultado=="existe"){
                let text = document.getElementById("span-socialmedia").textContent;
                //let firstv = media[0].toUpperCase() ;
                //let cadena= firstv+ (media.substring(1, media.length));
                swal('Ya existe un registro con la red social '+text,' Por favor, le recomendamos actualizar el registro','error');
            }else if(myArr.resultado==false){
                swal('No se pudo guardar el registro','','error');
            }
        }else if(xr.status === 400){
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

/* FUNCION PARA ACTUALIZA EL REGISTRO DE LA NUEVA RED SOCIAL */
function editarItemSM(id){
    var url= "/get-socialm-item/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                $('#idsocialmedia').val(v.id);
                $('#inputEUsuario').val(v.usuario);
                $('#textEsocialmedia').val(v.enlace);
                let firstv = v.nombre[0].toUpperCase();
                let cadena= firstv+ (v.nombre.substring(1, v.nombre.length));
                $('#span-Esocialmedia').html(cadena);
                $('#inputEsocialmedia').val(cadena);
            });
            setTimeout(() => {
                $('#modalEditSocial').modal('show');
            }, 300);
        }else if(xr.status === 400){
            //console.log('ERROR CONEXION');
            setTimeout(function () {
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }, 500);
        }
    }

    xr.send(null);
}

/* FUNCION PARA ACTUALIZAR EL REGISTRO DE RED SOCIAL */
function actualizarRegistroRedS(){
    var token= $('#token').val();
    var id= $('#idsocialmedia').val();
    var usuario = $('#inputEUsuario').val();
    var enlace= $('#textEsocialmedia').val();

    if(usuario==''){
        $('#inputEUsuario').focus();
        swal('Debe ingresar el usuario','','warning');
    } else if(enlace==''){
        $('#textEsocialmedia').focus();
        swal('Debe ingresar el enlace para la red social','','warning');
    } else{
        if(puedeActualizarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("id", id);
        formData.append("usuario", usuario);
        formData.append("enlace", enlace);
        actualizarSocialMedia(token, formData, '/actualizar-socialmedia');
        }else{
            swal('No tiene permiso para actualizar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA ACTUALIZAR */
function actualizarSocialMedia(token, data, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
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
                
                $('#modalEditSocial').modal('hide');
                setTimeout(function(){
                    $('#textEsocialmedia').val("");
                    $('#inputEUsuario').val("");
                    window.location='/red-social';
                },1500);
            }else if(myArr.resultado==false){
                swal('No se pudo actualizar el registro','','error');
            }
        }else if(xr.status === 400){
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


/* FUNCION PARA INACTIVAR SOCIAL MEDIA */
function inactivarItemSM(id, i){
    var token= $('#token').val();
    var estado="0";
    var estadoItem='No Visible';
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: '<strong>¡Aviso!</strong>',
        type: 'warning',
        html: '¿Está seguro que desea inactivar este registro?',
        showCloseButton: false,
        showCancelButton: true,
        allowOutsideClick: false,
        focusConfirm: false,
        focusCancel: true,
        cancelButtonColor: '#d33',
        confirmButtonText:
            '<i class="fa fa-check-circle"></i> Sí',
        confirmButtonAriaLabel: 'Thumbs up, Si',
        cancelButtonText:
            '<i class="fa fa-close"></i> No',
        cancelButtonAriaLabel: 'Thumbs down'
    }).then((result)=> {
        if(result.value)
        {
            $.ajax({
                url:'/in-activar-socialm',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data:{
                    id:id, estado:estado
                },
                success: function(res){
                    if(res.resultado==true){
                        swal({
                            title:'Excelente!',
                            text:'Registro Inactivado',
                            type:'success',
                            showConfirmButton: false,
                            timer: 1700
                        });
                        setTimeout(function(){
                            window.location='/red-social';
                        },1500);
                    }else if(res.resultado==false){
                        swal('No se pudo Inactivar','','error');
                    }
                },
                statusCode:{
                    400: function(){
                        Swal.fire({
                            title: 'Ha ocurrido un Error',
                            html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                                'Intente nuevamente</p>',
                            type: 'error'
                        });
                    }
                }
            });
        }else if(result.dismiss === Swal.DismissReason.cancel){
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

/* FUNCION PARA ACTIVAR SOCIAL MEDIA */
function activaritemSM(id, i) {
    var estado = "1";
    var token= $('#token').val();
    var estadoItem='Visible';
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
        url: "/in-activar-socialm",
        type: "POST",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': token
        },
        data: {
            id: id,
            estado: estado,
        },
        success: function (res) {
            if (res.resultado == true) {
                swal({
                    title:'Excelente!',
                    text:'Registro Activado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });
                setTimeout(function(){
                    window.location='/red-social';
                },1500);
            } else if (res.resultado == false) {
                swal("No se pudo Activar", "", "error");
            }else if(res.resultado == 'inactivo'){
                swal('No se pudo Activar', 'Se encuentra inactiva esta red Social', 'error');
            }
        },
        statusCode:{
            400: function(){
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

/* FUNCION PARA ELIMINAR SOCIAL MEDIA */
function eliminarItemSM(id){
    var token= $('#token').val();
    if(puedeEliminarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: '<strong>¡Aviso!</strong>',
        type: 'warning',
        html: '¿Está seguro que desea eliminar este registro?',
        showCloseButton: false,
        showCancelButton: true,
        allowOutsideClick: false,
        focusConfirm: false,
        focusCancel: true,
        cancelButtonColor: '#d33',
        confirmButtonText:
            '<i class="fa fa-check-circle"></i> Sí',
        confirmButtonAriaLabel: 'Thumbs up, Si',
        cancelButtonText:
            '<i class="fa fa-close"></i> No',
        cancelButtonAriaLabel: 'Thumbs down'
    }).then((result)=> {
        if(result.value)
        {
            $.ajax({
                url:'/delete-socialm',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data:{
                    id:id
                },
                success: function(res){
                    if(res.resultado==true){
                        swal({
                            title:'Excelente!',
                            text:'Registro Eliminado',
                            type:'success',
                            showConfirmButton: false,
                            timer: 1700
                        });
                        setTimeout(function(){
                            window.location='/red-social';
                        },1500);
                    }else if(res.resultado==false){
                        swal('No se pudo Inactivar','','error');
                    }
                },
                statusCode:{
                    400: function(){
                        Swal.fire({
                            title: 'Ha ocurrido un Error',
                            html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                                'Intente nuevamente</p>',
                            type: 'error'
                        });
                    }
                }
            });
        }else if(result.dismiss === Swal.DismissReason.cancel){
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}


/**
  * --------------------------REDES SOCIALES------------------------ 
*/
function backInterfaceDep(){
    window.location='/red-social';
}


//FUNCION QUE ABRE EL SWEET ALERT PARA REGISTRAR LA RED SOCIAL
function openModalAggDep(){
    var token= $('#token').val();
    if(puedeGuardarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: 'Ingrese la nueva Red Social',
        input: 'text',
        inputPlaceholder: "Instagram",
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false    
    }).then((result) => {
        if (result === false) return false;
        if (result.value) {
            $.ajax({
                url: "/registrar-red-social",
                type: "POST",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    red: result.value
                },
                success: function (res) {
                    //console.log(res);
                    if (res.resultado == true) {
                        swal({
                            title: "Excelente!",
                            text: "Registro Guardado",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1600,
                        });
                        
                        setTimeout(function () {
                            window.location='/agg-red-social';
                        }, 1500);
                    } else if (res.resultado == false) {
                        swal("No se pudo Guardar", "", "error");
                    }
                },
                statusCode:{
                    400: function(){
                        Swal.fire({
                            title: 'Ha ocurrido un Error',
                            html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                                'Intente nuevamente</p>',
                            type: 'error'
                        });
                    }
                }
            });
        }
    });
    }else{
        swal('No tiene permiso para guardar','','error');
    }
}

//FUNCION QUE MUESTRA LA INFORMACIÓN DE LAS REDES SOCIALES
function getListadoRedSocial(red){
    var con = 1; var estadoItem = ''; var html="";

    if (red.length === 0) {
        html += "<tr style='text-align: center;'>" +
            "<td colspan='4'>No hay registros</td>" +
            "</tr>";
    }

    $(red).each(function (i, v) {
        if (v.estado == "1") {
            estadoItem = "Visible";
        } else {
            estadoItem = "No Visible";
        }

        html += "<tr id='Tr" + i + "'>" +
            "<td>" + con + "</td>" +
            "<td>" + v.nombre + "</td>" +
            "<td>" + estadoItem + "</td>" +
            "<td>" +
            "<div class='dropdown show'>" +
                "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                    "<i class='fas fa-cog'></i>" +
                "</a>" +
                "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>" +
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemRS(" + v.id + ")'>Editar</a>";
                    if (v.estado == "1") {
                        html += "<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarItemRS(" +  v.id + ", " + i + ")'>Inactivar</a>";
                    } else if (v.estado == "0") {
                        html += "<a class='dropdown-item' href='javascript:void(0)' onclick='activaritemRS(" +  v.id + ", " + i + ")'>Activar</a>";
                    }
        html += "</div>" +
        "</div>" +
        "</td>" +
        "</tr>";
        con++;
    });

    
    $('#tablaListadoSM_r > tbody').html(html);
    setTimeout(function () {
        $('#modalCargando').modal('hide');
    }, 500);
}

//FUNCION QUE CARGA LA INFORMACION DE LA RED SOCIAL A EDITAR
function editarItemRS(id){
    var url= "/get-red-social/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);
    
    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);
            $(myArr).each(function(i,v){
                $('#idredsocialitem').val(v.id);
                $('#inputRedSocial').val(v.nombre);
            });
            setTimeout(function(){
                $('#modalEditRS').modal('show');
            },300);
        }else if(xr.status === 400){
            //console.log('ERROR CONEXION');
            setTimeout(function () {
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }, 500);
        }
    }

    xr.send(null);
}

//FUNCION QUE ACTUALIZA LA INFORMACION DE LA RED SOCIAL
function actualizarRegistroRS(){
    var token= $('#token').val();
    var id= $('#idredsocialitem').val();
    var red= $('#inputRedSocial').val();

    if(red==''){
        $('#inputRedSocial').focus();
        swal('Por favor, ingrese un nombre a la Red Social','','warning');
    }else{

        if(puedeActualizarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append('id', id);
        formData.append('red', red);

        $.ajax({
            url: "/actualizar-red-social",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: formData,
            success: function (res) {
                //console.log(res);
                if (res.resultado == true) {
                    swal({
                        title: "Excelente!",
                        text: "Registro Actualizado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    
                    setTimeout(function () {
                        window.location='/agg-red-social';
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Guardar", "", "error");
                }
            },
            cache: false,
            contentType: false,
            processData: false,
            statusCode:{
                400: function(){
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            }
        });

        }else{
            swal('No tiene permiso para actualizar','','error');
        }
    }
}

//FUNCION QUE INACTIVA LA RED SOCIAL SELECCIONADA
function eliminarItemRS(id, pos){
    var token= $('#token').val();
    var estado = "0";
    var html="";
    var estadoItem="";

    if(estado=="1"){
        estadoItem="Visible";
    }else{
        estadoItem="No Visible";
    }

    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea inactivar este registro?",
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
                url: "/in-activar-reds",
                type: "POST",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    id: id,
                    estado: estado
                },
                success: function (res) {
                    if (res.resultado == true) {
                        swal({
                            title: "Excelente!",
                            text: "Registro Inactivado",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1600,
                        });
                        
                        html+="<div class='dropdown show'>" +
                        "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                            "<i class='fas fa-cog'></i>" +
                        "</a>" +
                        "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>" +
                            "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemRS(" + id + ")'>Editar</a>";
                            if (estado == "1") {
                                html += "<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarItemRS(" +  id + ", " + pos + ")'>Inactivar</a>";
                            } else if (estado == "0") {
                                html += "<a class='dropdown-item' href='javascript:void(0)' onclick='activaritemRS(" +  id + ", " + pos + ")'>Activar</a>";
                            }
                        html += "</div>";

                        setTimeout(function () {
                            var elementState= document.getElementById('Tr'+pos).cells[2];
                            $(elementState).html(estadoItem);

                            var elementOption= document.getElementById('Tr'+pos).cells[3];
                            $(elementOption).html(html);
                            estadoItem="";
                        });
                    }else if (res.resultado == false) {
                        swal("No se pudo Inactivar", "", "error");
                    }else if(res.resultado == 'activo'){
                        swal('No se pudo Inactivar', 'Se encuentra en uso esta red Social', 'error');
                    }
                },
                statusCode:{
                    400: function(){
                        Swal.fire({
                            title: 'Ha ocurrido un Error',
                            html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                                'Intente nuevamente</p>',
                            type: 'error'
                        });
                    }
                }
            });
        }else if (result.dismiss === Swal.DismissReason.cancel) {
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

//FUNCION QUE ACTIVA LA RED SOCIAL SELECCIONADA
function activaritemRS(id, pos){
    var token= $('#token').val();
    var estado = "1";
    var html="";
    var estadoItem="";

    if(estado=="1"){
        estadoItem="Visible";
    }else{
        estadoItem="No Visible";
    }

    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
        url: "/in-activar-reds",
        type: "POST",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': token},
        data: {
            id: id,
            estado: estado
        },
        success: function (res) {
            //console.log(res);
            if (res.resultado == true) {
                swal({
                    title: "Excelente!",
                    text: "Registro Activado",
                    type: "success",
                    showConfirmButton: false,
                    timer: 1600,
                });
                
                html+="<div class='dropdown show'>" +
                    "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                        "<i class='fas fa-cog'></i>" +
                    "</a>" +
                "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>" +
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemRS(" + id + ")'>Editar</a>";
                    if (estado == "1") {
                        html += "<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarItemRS(" +  id + ", " + pos + ")'>Inactivar</a>";
                    } else if (estado == "0") {
                        html += "<a class='dropdown-item' href='javascript:void(0)' onclick='activaritemRS(" +  id + ", " + pos + ")'>Activar</a>";
                    }
                html += "</div>";

                setTimeout(function () {
                    var elementState= document.getElementById('Tr'+pos).cells[2];
                    $(elementState).html(estadoItem);

                    var elementOption= document.getElementById('Tr'+pos).cells[3];
                    $(elementOption).html(html);
                    estadoItem="";
                });
            } else if (res.resultado == false) {
                swal("No se pudo Activar", "", "error");
            }
        },
        statusCode:{
            400: function(){
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}