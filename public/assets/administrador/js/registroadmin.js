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

document.getElementById('formDataRegistro')
  .addEventListener('onsubmit', function(e){
    e.preventDefault()
    // code
});

$('#btnRegistroAdmin').click(function(){
    var nombre= $('#nameUsuario').val();
    var usuario= $('#datoUsuario').val();
    var clave= $('#passwordUsuario').val();
    var rclave= $('#rpasswordUsuario').val();

    if(nombre==''){
      $('#nameUsuario').focus();
      Swal.fire({
        title: "Debe ingresar los nombres del Usuario",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Entendido!"
      });
    }else if(usuario==''){
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
    var datos = new FormData($('#formDataRegistro')[0]);
    var token= $('#token').val();
    $.ajax({
        url:'/registrar-usuario',
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
                    title: "Nombre de usuario existente",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }else if(res.registro==false){
                Swal.fire({
                    title: "No se pudo registrar el usuario",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }else if(res.registro==true){
                //console.log('/login');
                setInterval(function(){
                    window.location.href="/login";
                },1500);
            }
          }
      })
}