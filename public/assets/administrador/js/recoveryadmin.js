function showPassword(id, span) {
    var x = document.getElementById(id);
    var element = document.querySelector('#'+span);
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

document.getElementById('formAcceso')
  .addEventListener('onsubmit', function(e){
    e.preventDefault()
    // code
});

$('#btnRecoveryAdmin').click(function(){
    var usuario= $('#datoUsuario').val();
    var clave= $('#passwordUsuario').val();
    var rclave= $('#rpasswordUsuario').val();

    if(usuario==''){
        $('#datoUsuario').focus();
        Swal.fire({
          title: "Debe ingresar un Usuario",
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Entendido!"
        });
    }else if(clave==''){
        $('#passwordUsuario').focus();
        Swal.fire({
            title: "Debe ingresar una clave al Usuario",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Entendido!"
        });
    }else if(rclave==''){
        $('#rpasswordUsuario').focus();
        Swal.fire({
            title: "Debe ingresar nuevamente la clave del Usuario",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Entendido!"
        });
    }else{
        if(clave.length <=7){
            $('#passwordUsuario').focus();
            Swal.fire({
                title: "Ingrese una clave mas extensa",
                type: "error",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ok!"
            });
            return;
        }

        if(clave!=rclave){
            Swal.fire({
                title: "Las Claves del Usuario no coinciden",
                type: "error",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ok!"
            });
        }else{
            registro();
        }
    }
});

function registro(){
    var datos = new FormData($('#formAcceso')[0]);
    var token= $('#token').val();
    $.ajax({
        url:'/cambiar-password-usuario',
        type:'POST',
        dataType:'json',
        headers: {'X-CSRF-TOKEN': token},
        contentType: false,
        processData: false,
        data:datos,
        success:function(res){
            //console.log(res);
            if(res.usuario==false){
                Swal.fire({
                    title: "Nombre de usuario no existe",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }else if(res.registro==false){
                Swal.fire({
                    title: "No se pudo actualizar las credenciales",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }else if(res.registro==true){
                //console.log('/login');
                Swal.fire({
                    title: "Clave Actualizada",
                    type: "success",
                    showConfirmButton: false,
                    timer: 1600,
                });
                setInterval(function(){
                    window.location.href="/login";
                },1500);
            }
          }
      })
}

function showMessage(){
    var token= $('#token').val();
    Swal.fire({
        title: 'Ingrese la Clave de Acceso',
        input: 'password',
        inputPlaceholder: "*******************",
        showCancelButton: false,
        confirmButtonText: 'Aceptar',
        allowOutsideClick: false    
    }).then((result) => {
        if (result === false) return false;
        if (result.value) {
            $.ajax({
                url: "/get-password-admin",
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
                            title: "Clave Correcta",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1600,
                        });
                        
                        setTimeout(function () {
                            document.getElementById('registro-box').style.display='block';
                            Swal.close();
                        }, 1500);
                    } else if (res.resultado == false) {
                        toastr.warning("Clave Incorrecta", "Clave de Acceso", "!Aviso!");
                        showMessage();
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
}