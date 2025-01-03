var arrayAbout= [];

function cargar_about(inforAbout){
    //console.log(inforAbout);
    if(inforAbout.length==0){
        $('#infor-institucion').html("<p class='p-nodata-yet'>Sin especificar...</p>");
    }else{
        //console.log(inforAbout);
        //var myArr = JSON.parse(inforAbout);
        $(inforAbout).each(function(i,v){
            arrayAbout.push({
                "id" : v.id,
                "descripcion" : v.descripcion
            });
            var descp= v.descripcion.replaceAll('//','<br>');
            $('#infor-institucion').html("<p class='p-data-full'>"+descp+"</p>");
        });
    }

    setTimeout(() => {
        $('#modalCargando').modal('hide');
    }, 1000);
}

/* FUNCION ABRIR MODAL EDITAR INFORMACIÓN */
function openmodalEditInfor(){
    if(arrayAbout.length>0){
        $('#idabout').val(arrayAbout[0].id);
        replaceCaracter(arrayAbout[0].descripcion, '#inputAbout');
    }else{
        $('#inputAbout').html("");
        $('#inputAbout').focus();
    }
    
    $('#modal-edit-about').modal('show');
}

/* FUNCION QUE GUARDA LA INFORMACIÓN */
function guardarRegistroAbout(){
    var idabout= $('#idabout').val();
    var about= $('#inputAbout').val();
    var tiporegistro='';

    var token= $('#token').val();

    if(about==''){
        $('#inputAbout').focus();
        swal('Por favor ingrese la Información','','warning');
    }else{
        var element = document.querySelector('.btn-about');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        about = about.replace(/(\r\n|\n|\r)/gm, "//");

        about= about.trim();

        if(idabout!=''){
            tiporegistro='update';
            arrayAbout[0].descripcion= about;
        }else{
            tiporegistro='insert';
        }

        var formData = new FormData();
        formData.append("id", idabout);
        formData.append("descripcion", about);
        sendUpdateAbout(formData, token, '/registrar-about', element, '#modal-edit-about', 0, tiporegistro);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DE ACERCA */
function sendUpdateAbout(data, token, url, el, modal, posi, registro){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            if(myArr.resultado==true){
                Swal.fire({
                    title:'Excelente!',
                    text:'Registro Guardado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    el.removeAttribute("disabled");
                    el.style.removeProperty("pointer-events");
                    if(registro=='update'){
                        $('#ididabout').val('');
                        reDrawInfo(posi);
                        $(modal).modal('hide');
                    }else if(registro=='insert'){
                        window.location='/about';
                    }
                    
                },1500);
            } else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "No se pudo Guardar",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }
        }else if(xr.status === 400){
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
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

/* FUNCION QUE ACTUALIZA LA INFORMACIÓN EN LA INTERFAZ*/
function reDrawInfo(posi){
    //console.log(arrayAbout[posi], posi, tipo);
    var descp= arrayAbout[posi].descripcion.replaceAll('//','<br>');
    //console.log(descp);
    $('#infor-institucion').html("<p class='p-data-full'>"+descp+"</p>");
}

/* FUNCION QUE TRAZA SALTOS DE LÍNEA EN EL TEXTAREA DE LA INFORMACIÓN */
function replaceCaracter(dato, elemento){
    var posicion = dato.indexOf("//");
    //console.log(posicion);
    //cadena = dato.slice(0, posicion) + '\n' + dato.slice(posicion + 2);
    while (posicion >= 0)
    {
        // remplaza "ato" por "atito"
        dato = dato.slice(0, posicion) + '\n' + dato.slice(posicion + 2);
        // busca la siguiente ocurrencia de la palabra
        posicion = dato.indexOf("//");
        //console.log(posicion);
    }
    $(elemento).val(dato);
}