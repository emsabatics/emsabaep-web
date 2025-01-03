function openmodalAdd(){
    $('#modalAggPerfil').modal('show');
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

$('#inputPerfilUser').on("input", function() {
    var dInput = this.value;
    let sinaccent= removeAccents(dInput);
    let minuscula= sinaccent.toLowerCase();
    let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","");
    $('#inputTypeProfile').val(cadena);
});

$('#inputEPerfilUser').on("input", function() {
    var dInput = this.value;
    let sinaccent= removeAccents(dInput);
    let minuscula= sinaccent.toLowerCase();
    let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","");
    $('#inputETypeProfile').val(cadena);
});

function guardarRegistroPerfil(){
    var token= $('#token').val();
    var nombre= $('#inputPerfilUser').val();
    var tipo= $('#inputTypeProfile').val();
    var descripcion= $('#inputDescPerfilUser').val();

    if(nombre==''){
        $('#inputPerfilUser').focus();
        swal('Ingrese un nombre','','warning');
    }else if(tipo==''){
        $('#inputTypeProfile').focus();
        swal('Ingrese un tipo','','warning');
    }else if(descripcion==''){
        $('#inputDescPerfilUser').focus();
        swal('Ingrese una descripción','','warning');
    }else{
        var element = document.querySelector('.btnsaveprofile');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var formData= new FormData();
        formData.append("nombre", nombre);
        formData.append("tipo", tipo);
        formData.append("descripcion", descripcion);

        var xr = new XMLHttpRequest();
        xr.open('POST', '/registro-perfiluser', true);
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
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
                        $('#modalAggPerfil').modal('hide');
                        $('#inputPerfilUser').val("");
                        $('#inputDescPerfilUser').val("");
                        urlback();
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

function urlback(){
    window.location='/perfil-usuario';
}

function editarItemProfile(id){
    $('#idperfilusuario').val(id);

    var url= "/get-profile-user/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                $('#inputEPerfilUser').val(v.nombre);
                $('#inputETypeProfile').val(v.tipo);
                $('#inputEDescPerfilUser').val(v.descripcion);
            });
            setTimeout(() => {
                $('#modalEditPerfil').modal('show');
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

function actualizarRegistroProfile(){
    var token= $('#token').val();
    var idregistro= $('#idperfilusuario').val();
    var nombre= $('#inputEPerfilUser').val();
    var tipo= $('#inputETypeProfile').val();
    var descripcion= $('#inputEDescPerfilUser').val();

    if(nombre==''){
        $('#inputEPerfilUser').focus();
        swal('Ingrese un nombre','','warning');
    }else if(tipo==''){
        $('#inputETypeProfile').focus();
        swal('Ingrese un tipo','','warning');
    }else if(descripcion==''){
        $('#inputEDescPerfilUser').focus();
        swal('Ingrese una descripción','','warning');
    }else{
        var element = document.querySelector('.btnsaveprofile');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var formData= new FormData();
        formData.append("idperfil", idregistro);
        formData.append("nombre", nombre);
        formData.append("tipo", tipo);
        formData.append("descripcion", descripcion);

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-perfil-usuario', true);
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
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
                        $('#modalEditPerfil').modal('hide');
                        $('#inputEPerfilUser').val("");
                        $('#inputETypeProfile').val("");
                        $('#inputEDescPerfilUser').val("");
                        $('#idperfilusuario').val("");
                        urlback();
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

function eliminarItemProfile(id, i){
    var token= $('#token').val();
    var estado="0";
    var estadoItem='No Visible';
    var html="";
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
                url:'/in-activar-profileuser',
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
                            urlback();
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
}

function activarItemProfile(id, i){
    var estado = "1";
    var token= $('#token').val();
    var estadoItem='Visible';
    var html="";
    $.ajax({
        url: "/in-activar-profileuser",
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
                    urlback();
                },1500);
            } else if (res.resultado == false) {
                swal("No se pudo Activar", "", "error");
            }else if(res.resultado == 'inactivo'){
                swal('No se pudo Activar', 'Se encuentra inactiva este registro', 'error');
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