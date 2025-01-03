function showPassword(id, span) {
    var x = document.getElementById(id);
    var element = document.querySelector("#"+span);
    var html = "";
    if (x.type === "password") {
      x.type = "text";
      html = "<span class='fas fa-unlock-alt'></span>";
      element.innerHTML = html;
    } else {
      x.type = "password";
      html = "<span class='fas fa-lock'></span>";
      element.innerHTML = html;
    }
}

function openmodalAdd(){
    window.location='/registrar-new-usuario';
}

function guardarUsuario(){
    var nombre= $('#inputNameUser').val();
    var usuario= $('#inputUsuario').val();
    var clave= $('#inputPassword').val();
    var rclave= $('#inputRePassword').val();
    var tipo= $('#selectTypeUser').val();

    if(nombre==''){
        $('#inputNameUser').focus();
        swal('Debe ingresar los nombres del Usuario','','warning');
    }else if(usuario==''){
        $('#inputUsuario').focus();
        swal('Debe ingresar un Usuario','','warning');
    }else if(clave==''){
        $('#inputPassword').focus();
        swal('Debe ingresar una clave al Usuario','','warning');
    }else if(rclave==''){
        $('#inputRePassword').focus();
        swal('Debe ingresar nuevamente la clave del Usuario','','warning');
    }else if(tipo=='0'){
        $('#selectTypeUser').focus();
        swal('Debe seleccionar el Tipo de Usuario','','warning');
    }else{
        if(clave.length <=7){
            $('#inputPassword').focus();
            swal('ngrese una clave mas extensa','','warning');
  
            return;
        }
        if(clave!=rclave){
            swal('Las Claves del Usuario no coinciden','','warning');
        }else{
            $('#modalFullSend').modal('show');
            var datos = new FormData();
            datos.append('nombre', nombre);
            datos.append('usuario', usuario);
            datos.append('clave', clave);
            datos.append('tipou', tipo);
            registro(datos);
        }
    }
}

function registro(datos){
    var token= $('#token').val();
    $.ajax({
        url:'/store-new-usuario',
        type:'POST',
        dataType:'json',
        headers: {'X-CSRF-TOKEN': token},
        contentType: false,
        processData: false,
        data:datos,
        success:function(res){
            //console.log(res);
            if(res.usuario==false){
                $('#modalFullSend').modal('hide');
                Swal.fire({
                    title: "Nombre de usuario existente",
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }else if(res.registro==false){
                $('#modalFullSend').modal('hide');
                Swal.fire({
                    title: "No se pudo registrar el usuario",
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }else if(res.registro==true){
                swal({
                    title:'Excelente!',
                    text:'Usuario Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setInterval(function(){
                    $('#modalFullSend').modal('hide');
                    window.location.href="/usuarios";
                },1500);
            }
          }
      })
}

function generarPassword(id){
    $('#idperfilusuario').val(id);
    $('#modalAggPassword').modal('show');
}

function guardarClavePerfil(){
    var token= $('#token').val();
    var id= $('#idperfilusuario').val();
    var clave= $('#inputPasswordUser').val();

    if(clave==''){
        $('#inputPasswordUser').focus();
        swal('Debe ingresar una clave al Usuario','','warning');
    }else{
        if(clave.length <=7){
            $('#inputPasswordUser').focus();
            swal('ngrese una clave mas extensa','','warning');
  
            return;
        }else{
            var datos = new FormData();
            datos.append('idusuario', id);
            datos.append('clave', clave);
            sendUpdatePassword(token, datos, '/update-password-usuario', '/usuarios');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdatePassword(token, data, url, urlpage){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            //$('#modalFullSend').modal('hide');
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Clave Actualizada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location= urlpage;
                },1500);
                
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            }
        }else if(xr.status === 400){
            //$('#modalFullSend').modal('hide');
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

function eliminarItemUser(id, pos){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='Inactivo';
    var classbadge="badge badge-secondary";
    var html="";
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
            url: "/in-activar-usuario",
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
                    
                    setTimeout(function () {
                        var elementState= document.getElementById('Tr'+pos).cells[5];
                        $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                        html+="<div class='dropdown show'>"+
                        "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
                        "<i class='fas fa-cog'></i>"+
                        "</a>"+
                        "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>"+
                        "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemUser("+id+")'>Editar</a>"+
                        "<a class='dropdown-item' href='javascript:void(0)' onclick='generarPassword("+id+")'>Cambiar Clave</a>";
                        if(estado=="1"){
                            html+="<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarItemUser("+id+", "+pos+")'>Inactivar</a>";
                        }else if(estado=="0"){
                            html+="<a class='dropdown-item' href='javascript:void(0)' onclick='activarItemUser("+id+", "+pos+")'>Activar</a>";
                        }
                        html+="</div>"+
                        "</div>";
                        var element= document.getElementById('Tr'+pos).cells[6];
                        $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
}

function activarItemUser(id, pos){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Activo';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
      url: "/in-activar-usuario",
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
                text: "Registro Activado",
                type: "success",
                showConfirmButton: false,
                timer: 1600,
            });
            
            setTimeout(function () {
                var elementState= document.getElementById('Tr'+pos).cells[5];
                $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                html+="<div class='dropdown show'>"+
                    "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
                    "<i class='fas fa-cog'></i>"+
                    "</a>"+
                    "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>"+
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemUser("+id+")'>Editar</a>"+
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='generarPassword("+id+")'>Cambiar Clave</a>";
                if(estado=="1"){
                    html+="<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarItemUser("+id+", "+pos+")'>Inactivar</a>";
                }else if(estado=="0"){
                    html+="<a class='dropdown-item' href='javascript:void(0)' onclick='activarItemUser("+id+", "+pos+")'>Activar</a>";
                }
                html+="</div>"+
                "</div>";
                var element= document.getElementById('Tr'+pos).cells[6];
                $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
}

function editarItemUser(id){
    window.location= '/edit-view-usuario/'+id;
}

function actualizarUsuario(){
    var token= $('#token').val();
    var id= $('#idusuario').val();
    var nombre= $('#inputENameUser').val()
    var tipo= $('#selectETypeUser').val();

    if(nombre==''){
        $('#inputENameUser').focus();
        swal('Debe ingresar los nombres del Usuario','','warning');
    }else if(tipo=='0'){
        $('#selectETypeUser').focus();
        swal('Debe seleccionar el Tipo de Usuario','','warning');
    }else{
        $('#modalFullSend').modal('show');
        var datos = new FormData();
        datos.append('nombre', nombre);
        datos.append('idusuario', id);
        datos.append('tipou', tipo);
        setTimeout(()=>{
            sendUpdateUser(token, datos, '/update-usuario')
        }, 1200);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateUser(token, data, url){
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
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Datos Actualizados',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location='/usuarios';
                },1500);
                
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
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

function updatePClavePerfil(e){
    e.preventDefault();
    var token= $('#token').val();
    var id= $('#iduser').val();
    var clave= $('#inputPassword').val();
    var rclave= $('#inputPasswordR').val();

    if(clave==''){
        $('#inputPassword').focus();
        swal('Debe ingresar una clave','','warning');
    }else if(rclave==''){
        $('#inputPasswordR').focus();
        swal('Debe ingresar una clave','','warning');
    }else{
        if(clave.length <=7){
            $('#inputPassword').focus();
            swal('ngrese una clave mas extensa','','warning');
            return;
        }
        if(clave!=rclave){
            swal('Las Claves no coinciden','','warning');
        }else{
            $('#modalFullSend').modal('show');
            var datos = new FormData();
            datos.append('idusuario', id);
            datos.append('clave', clave);
            sendUpdatePassword(token, datos, '/update-password-usuario', '/perfil');
        }
    }
}

function urlback(){
    window.location= '/usuarios';
}