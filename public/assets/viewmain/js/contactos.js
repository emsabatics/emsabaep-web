function sendmessage(){
    var token= $('#token').val();
  
    var names = $("#nameinput").val();
    var email = $("#emailinput").val();
    var descpshort = $("#messageinput").val();
    var telefono= $('#telefonoinput').val();
    var cuenta= $('#cuentaservicioinput').val();

    if (names == "") {
        $("#nameinput").focus();
        swal("Ingrese los Nombres y Apellidos", "", "warning");
    } else if (email == "") {
        $("#emailinput").focus();
        swal("Ingrese un email", "", "warning");
    } else if (descpshort == "") {
        $("#messageinput").focus();
        swal("Ingrese el detalle del mensaje", "", "warning");
    } else if (telefono == "" || telefono.length<10) {
        $("#telefonoinput").focus();
        swal("Ingrese el número de teléfono de contacto", "", "warning");
    } else if (cuenta == "") {
        $("#cuentaservicioinput").focus();
        swal("Ingrese el número de cuenta del servicio", "", "warning");
    } else {
        var isEmail= validarEmail(email);
        if(isEmail==0){
            swal("Dirección de Correo Electrónico Inválida", "", "warning");
        }else{
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
                descpshort = descpshort.replace(/(\r\n|\n|\r)/gm, "//");
                descpshort= descpshort.trim();

                var element = document.querySelector('.btnsendmessage');
                element.setAttribute("disabled", "");
                element.style.pointerEvents = "none";

                var data = new FormData();
                data.append("nombres", names);
                data.append("email", email);
                data.append("descripcion", descpshort);
                data.append("telefono", telefono);
                data.append("cuenta", cuenta);
                data.append("g-recaptcha-response", response);
                setTimeout(() => {
                    sendNuevaNoticia(token, data, "/registrar-mensaje", element); 
                }, 700);
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNuevaNoticia(token, data, url, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");

            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Hemos recibido tu mensaje',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/contactus';
                },1500);
                
            } else if (myArr.resultado == false) {
                // Resetea el reCAPTCHA
                grecaptcha.reset();
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }else if(res.captcha=='error'){
                // Resetea el reCAPTCHA
                grecaptcha.reset();
                Swal.fire({
                  title: "Faltó comprobación del ReCAPTCHA",
                  type: "error",
                  showCancelButton: false,
                  confirmButtonColor: "#3085d6",
                  confirmButtonText: "Ok"
                });
            }
        }else if(xr.status === 400){
            // Resetea el reCAPTCHA
            grecaptcha.reset();
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

function validarEmail(valor) {
    emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    //Se muestra un texto a modo de ejemplo, luego va a ser un icono
    if (emailRegex.test(valor)) {
        //console.log("DIRECCION VALIDA");
        return 1;
    } else {
        //console.log("DIRECCION INVALIDA");
        return 0;
    }
}

function solonumeros(e){
    key=e.keyCode || e.which;
    teclado=String.fromCharCode(key);
    numeros="0123456789";
    especiales="8-37-38-40-41-45-46";//array
    teclado_especial=false;
    for(var i in especiales)
    {
        if(key==especiales[i])
        {
          teclado_especial=true;
        }
    }
    if(numeros.indexOf(teclado)==-1 &&!teclado_especial){
        return false;
    }
}

$('#button_info_planilla').click(function(){
    var html= "";
    $(getfilecuenta).each(function(i,v){
        if(v.tipo=="imagen"){
            html+= "<img src='/files-img/"+v.archivo+"' alt='Información'>";
        }else if(v.tipo=="video"){
            html+="<video width='640' height='360' poster='assets/administrador/img/icons/camara-de-video.png' controls>"+
                "<source src='/files-img/"+v.archivo+"' type='video/mp4'>"+
            "</video>";
        }
    });
    $('#div_fileinfo').html(html);
    setTimeout(() => {
        //console.log(getfilecuenta);
        $('#modal_info').modal('show');
    }, 500);
});

function cerrarModal(){
    $('#modal_info').modal('hide');
    $('#div_fileinfo').html("");
}