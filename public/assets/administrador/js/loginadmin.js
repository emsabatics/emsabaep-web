function showPassword(id) {
    var x = document.getElementById(id);
    var element = document.querySelector("#spanLock");
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

document.getElementById('formDataLogin')
  .addEventListener('onsubmit', function(e){
    e.preventDefault()
    // code
});

$("#txtUsuario").attr('autocomplete', 'off');

$('#btnLoginAdmin').click(function(){
  var usuario= $('#txtUsuario').val();
  var clave= $('#txtPassword').val();
  if(usuario==''){
    $('#txtUsuario').focus();
    //Swal.fire('Debe ingresar su usuario','','warning');
    Swal.fire({
      title: "Debe ingresar su usuario",
      type: "warning",
      showCancelButton: false,
      confirmButtonColor: "#3085d6",
      confirmButtonText: "Entendido!"
    });
  }else if(clave==''){
    $('#txtPassword').focus();
    Swal.fire({
      title: "Debe ingresar su clave",
      type: "warning",
      showCancelButton: false,
      confirmButtonColor: "#3085d6",
      confirmButtonText: "Entendido!"
    });
  }else{
    //document.querySelector('#btnLoginAdmin').disabled = true;
    var response = grecaptcha.getResponse();
    if(response.length == 0){
      //reCaptcha not verified
      Swal.fire({
        title: "Faltó comprobación del ReCAPTCHA",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Entendido!"
      });
    }else{
      //reCaptch verified
      login();
    }
  }
});

$('#txtPassword').keypress(function(event){
  if(event.which==13){
    var usuario= document.querySelector('#txtUsuario').value;
    var clave= document.querySelector('#txtPassword').value;
    if(usuario==''){
      $('#txtUsuario').focus();
      Swal.fire({
        title: "Debe ingresar su usuario",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Entendido!"
      });
    }else if(clave==''){
      $('#txtPassword').focus();
      Swal.fire({
        title: "Debe ingresar su clave",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Entendido!"
      });
    }else{
      //login(usuario,clave);
      var response = grecaptcha.getResponse();
      if(response.length == 0){
        //reCaptcha not verified
        Swal.fire({
          title: "Faltó comprobación del ReCAPTCHA",
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Entendido!"
        });
      }else{
        //reCaptch verified
        login();
      }
    }
  }
});

function login(){
  var datos = new FormData($('#formDataLogin')[0]);
  var token= $('#token').val();

  var element = document.getElementById("btnLoginAdmin");
  var html = "<i class='fa fa-solid fa-spinner fa-spin'></i>";
  element.innerHTML = html;
  element.setAttribute("disabled", "");
  element.style.pointerEvents = "none";

	$.ajax({
		url:'/iniciar-sesion',
		type:'POST',
		dataType:'json',
    headers: {'X-CSRF-TOKEN': token},
    contentType: false,
    processData: false,
		data: datos,
		success:function(res){
      //console.log("MENSAJE: "+res);
      if(res.usuario==false){
				$('#txtUsuario').focus();
        Swal.fire({
          title: "Nombre de usuario no existe",
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok"
        });

        setTimeout(() => {
          // Resetea el reCAPTCHA
          grecaptcha.reset();
          element.innerHTML = "Iniciar Sesión";
          element.removeAttribute("disabled");
          element.style.removeProperty("pointer-events");
        }, 900);

			}else if(res.clave==false){
				$('#txtPassword').focus();
        Swal.fire({
          title: "La clave no es correcta para este usuario",
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok"
        });

        setTimeout(() => {
          // Resetea el reCAPTCHA
          grecaptcha.reset();
          element.innerHTML = "Iniciar Sesión";
          element.removeAttribute("disabled");
          element.style.removeProperty("pointer-events");
        }, 900);

			}else if(res.respuesta==true){
        Swal.fire({
          title:'Bienvenido al Sistema!',
          text:'',
          type:'success',
          showConfirmButton: false,
          timer: 1600
        });

        setTimeout(function(){
          window.location.href="home";
        },1500);
      }else if(res.usuario=='inactivouser'){
        $('#txtUsuario').focus();
        Swal.fire({
          title: "Usuario inhabilitado",
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok"
        });
        setTimeout(() => {
          // Resetea el reCAPTCHA
          grecaptcha.reset();
          element.innerHTML = "Iniciar Sesión";
          element.removeAttribute("disabled");
          element.style.removeProperty("pointer-events");
        }, 900);
      }else if(res.captcha=='error'){
        Swal.fire({
          title: "Faltó comprobación del ReCAPTCHA",
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok"
        });
        setTimeout(() => {
          // Resetea el reCAPTCHA
          grecaptcha.reset();
          element.innerHTML = "Iniciar Sesión";
          element.removeAttribute("disabled");
          element.style.removeProperty("pointer-events");
        }, 900);
      }
		}
	})
}