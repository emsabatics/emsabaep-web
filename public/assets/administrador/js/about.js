var arrayAbout= [];
var arrayPics = [];

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

            if(v.imagen!='' && v.imagen!=null){
                var data={
                    "id": v.id,
                    "imagen":v.imagen,
                    "nombre": v.imagen,
                    "pos": i
                }
                arrayPics.push(data);
            }

            var descp= v.descripcion.replaceAll('//','<br>');
            $('#infor-institucion').html("<p class='p-data-full'>"+descp+"</p>");

            if(arrayPics.length>0){
                imprimirDatos();
            }else{
                drawNoData();
            }
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
        if(puedeGuardarSM(nameInterfaz) === 'si'){
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
        }else{
            swal('No tiene permiso para guardar','','error');
        }
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

/* FUNCION QUE GRAFICA EL DIV INFORMATIVO DE NO IMAGENES */
function drawNoData(){
    var html="<div class='row nonews'>"+
        "<div class='col-lg-12 no-data'>"+
        "<div class='imgadvice'>"+
            "<img src='/assets/administrador/img/icons/no-content-img.png' alt='Construccion'>"+
        "</div>"+
        "<span class='mensaje-noticia mt-4 mb-4'>No hay <strong>imágenes</strong> disponibles por el momento...</span>"+
        "</div>"+
    "</div>";

    var element= document.getElementById('cardListPicsUp');
    element.style.cssText= 'grid-template-columns: 1fr';
    element.innerHTML= html;
    //$('#cardListPicsUp').html(html);
}

/* FUNCION QUE GRAFICA LAS IMAGENES EXTRAIDAS DE LA BD */
function imprimirDatos(){
    var html=""; var longi= (arrayPics.length)-1;
    //console.log(arrayPics);
    arrayPics.forEach((item, i) => {
        html+="<div class='item-list grid-item-list' id='divpics"+i+"'>"+
            "<div style='grid-row: 1/2;'>"+
            "<div class='avatar'>"+
                "<img src='/files-img/"+item.imagen+"' alt='Imagen de la Institución "+i+"' class='avatar-img'>"+
            "</div>"+
            "</div><div style='grid-row: 1/2;'>"+
            "<button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarPic("+item.id+","+i+")'>"+
                "<i class='fas fa-trash'></i>"+
            "</button>"+
            "</div><div style='grid-row: 2/2;'>"+
            "<div class='info-user'>"+
                "<div class='status'>"+item.nombre+"</div>"+
            "</div>"+
            "</div>"+
        "</div>";
        //"<div class='separator-dashed' id='divseparador"+i+"'></div>";
      lastIdDiv= i;
    });
    //$('#rowPicsInd').html(html);
    $('#cardListPicsUp').html(html);
}

function eliminarPic(id, i){
    var estado = "0";
    //limpiarArray(i);
    var token= $('#token').val();
    if(puedeEliminarSM(nameInterfaz) === 'si'){
    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar esta imagen?",
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
            url: "/in-activar-img-about",
            type: "POST",
            dataType: "json",
            headers:{
                'X-CSRF-TOKEN': token
            },
            data: {
                id: id
            },
            success: function (res) {
                if (res.resultado == true) {
                    var numpics= res.numimg;
                    swal({
                        title: "Excelente!",
                        text: "Fotografía Eliminada",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    setTimeout(function () {
                        limpiarArray(i);
                        
                        if(parseInt(numpics)==0){
                            drawNoData();
                            Swal.fire({
                                title: "<strong>¡Aviso!</strong>", 
                                type: "info",
                                html: "<p>Se han eliminado todas las <b>imágenes</b> </p>"
                            });
                        }
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Eliminar", "", "error");
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
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function limpiarArray(item){
    //console.log("ITEM: "+item);
    var index= arrayPics.map(object => object.pos).indexOf(item);
    //console.log("INDEX: "+index);
    $('#divpics'+item).remove();
    $('#divseparador'+item).remove();
    arrayPics.splice(index,1);
    //console.log(arrayPics);
}

/* FUNCION QUE ACTUALIZA LAS IMAGENES EN ACERCA DE LA INSTITUCIÓN*/
function updatepicsabout(e){
    e.preventDefault();
    var token= $('#token').val();
    let fileInput = document.getElementById("file");
    //var idnoti= $('#idnoticiapics').val();
    var idimg= arrayAbout[0].id;
    var lengimg = fileInput.files.length;
    if (lengimg == 0) {
        swal("No ha seleccionado imagen", "", "warning");
    } else {
        if(puedeActualizarSM(nameInterfaz) === 'si'){
        $('#modalCargando').modal('show');
        var data = new FormData(formEAbout);
        data.append("id_img", idimg);
        data.append("num_img", lengimg);
        setTimeout(() => {
            sendUpdatePicsAbout(token, data, "/actualizar-img-about");
        }, 900);
        }else{
            swal('No tiene permiso para actualizar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO´DE NUEVAS IMÁGENES */
function sendUpdatePicsAbout(token, data, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#modalCargando').modal('hide');
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Imagen Actualizada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = window.location.href;
                },1500);
            } else if (myArr.resultado == "noimagen") {
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            }else if (myArr.resultado == false) {
                swal("No se pudo Actualizar", "", "error");
            }
        }else if(xr.status === 400){
            $('#modalCargando').modal('hide');
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