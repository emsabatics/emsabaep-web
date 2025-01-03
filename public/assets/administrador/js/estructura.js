var arrayEstructura= [];

/* FUNCION QUE CARGA LA INFORMACIÓN */
function cargar_estructura(inforEstructura){
    //console.log(inforEstructura);
    if(inforEstructura.length==0){
        $('#divEstructura').html("<p id='p-nodata-es' class='p-nodata-yet'>Sin especificar...</p>");
    }else{
        let id='';
        let archivo='';
        let tipofile='';
        //console.log(inforEstructura);
        //var myArr = JSON.parse(inforEstructura);
        $(inforEstructura).each(function(i,v){
            id= v.id;
            archivo= v.archivo;
            tipofile= v.tipo_archivo;
            arrayEstructura.push({
                "id" : v.id,
                "descripcion" : v.descripcion,
                "archivo": v.archivo,
                "tipo": v.tipo_archivo
            });
            $('#idEstructura').val(v.id);
            $('#idstructurapics').val(v.id);
            var descp= v.descripcion.replaceAll('//','<br>');
            //$('#divEstructura').html("<p class='p-data-full'>"+descp+"</p>");
            $('#divEstructura').html(descp);
        });

        setTimeout(() => {
            $('#modalCargando').modal('hide');
            cargarPics(archivo, id, tipofile);
        }, 700);
    }

    setTimeout(() => {
        $('#modalCargando').modal('hide');
    }, 1000);
}

/* FUNCION QUE CARGA LAS IMAGENES DE LA ESTRUCTURA */
function cargarPics(imagen, id, tipofile){
    if(imagen.length==0){
        drawNoData();
    }else{
        imprimirDatos(imagen, id, tipofile);
    }
}

/* FUNCION QUE GRAFICA EL DIV INFORMATIVO DE NO IMAGENES */
function drawNoData(){
    var html="<div class='row nonews'>"+
        "<div class='col-lg-12 no-data'>"+
        "<div class='imgadvice'>"+
            "<img src='/assets/administrador/img/icons/no-content-img.png' alt='Construccion'>"+
        "</div>"+
        "<span class='mensaje-noticia mt-4 mb-4'>No hay <strong>archivos</strong> disponibles por el momento...</span>"+
        "</div>"+
    "</div>";

    var element= document.getElementById('cardListPicsUp');
    element.style.cssText= 'grid-template-columns: 1fr';
    element.innerHTML= html;
    //$('#cardListPicsUp').html(html);
}

/* FUNCION QUE GRAFICA LAS IMAGENES EXTRAIDAS DE LA BD */
function imprimirDatos(imagen, id, tipofile){
    var html="";
    if(tipofile=='pdf'){
        html+="<div class='card-list grid-card-list' id='cardListEstructura'>"+
            "<div class='item-list grid-item-list' id='divpics'>"+
                "<div style='grid-row: 1/2;'>"+
                    "<div class='avatar' style='text-align: center;height: 78px;'>"+
                        "<img src='/assets/administrador/img/icons/icon-pdf-color.svg' alt='File' class='avatar-img' style='width: 78px;height: 78px;'>"+
                    "</div>"+
                "</div>"+
                "<div style='grid-row: 1/2;'>"+
                    "<button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarPic("+id+")'>"+
                        "<i class='fas fa-trash'></i>"+
                    "</button>"+
                "</div>"+
                "<div style='grid-row: 2/2;'>"+
                    "<div class='info-user'>"+
                        "<div class='status'>"+imagen+"</div>"+
                    "</div>"+
                "</div>"+
            "</div>"+
        "</div>";
    }else{
    html+="<div class='item-list grid-item-list' id='divpics1'>"+
            "<div style='grid-row: 1/2;'>"+
            "<div class='avatar'>"+
                "<img src='/estructura-img/"+imagen+"' alt='Imagen' class='avatar-img'>"+
            "</div>"+
            "</div><div style='grid-row: 1/2;'>"+
            "<button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarPic("+id+")'>"+
                "<i class='fas fa-trash'></i>"+
            "</button>"+
            "</div><div style='grid-row: 2/2;'>"+
            "<div class='info-user'>"+
                "<div class='status'>"+imagen+"</div>"+
            "</div>"+
            "</div>"+
        "</div>";
    }
    $('#cardListPicsUp').html(html);
}

function eliminarPic(id){
    var token= $('#token').val();
    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar este archivo?",
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
            url: "/in-activar-img-estructura",
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
                        text: "Archivo Eliminado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    setTimeout(function () {
                        drawNoData();
                        Swal.fire({
                            title: "<strong>¡Aviso!</strong>", 
                            type: "info",
                            html: "<p>Se han eliminado todas los <b>archivos</b>"
                        });
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
}

/* FUNCION QUE ACTUALIZA LAS NOTICIAS EN IMAGENES */
function updatepicsnews(e){
    e.preventDefault();
    var token= $('#token').val();
    let fileInput = document.getElementById("file");
    //var idnoti= $('#idnoticiapics').val();
    var lengimg = fileInput.files.length;
    if (lengimg == 0) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else {
        $('#modalFullSendEdit').modal('show');
        var data = new FormData(formEstructuraI);
        data.append("num_img", lengimg);
        setTimeout(() => {
            sendUpdatePics(token, data, "/actualizar-estructura-img");
        }, 900);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA ACTUALIZAR */
function sendUpdatePics(token, data, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#modalFullSendEdit').modal('hide');
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Archivo Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location= '/estructura';
                },1500);
            } else if (myArr.resultado == false) {
                swal("No se pudo Actualizar", "", "error");
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            }
        }else if(xr.status === 400){
            $('#modalFullSendEdit').modal('hide');
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

/* FUNCION QUE HABILITA EL EDITOR DE TEXTO PARA MODIFICAR LA INFORMACIÓN */
var edit = function() {
    const elementP = document.getElementById("p-nodata-es");
    if(document.body.contains(elementP)){
        elementP.remove(); // Removes the div with the 'p-nodata-es' id
    }

    $('#divEstructura').summernote({
        focus: true,
        toolbar: [
            // [groupName, [list of button]]
            //['font', ['strikethrough', 'superscript', 'subscript']],
            //['color', ['color']],
            //['para', ['ul', 'ol', 'paragraph']],
            //['height', ['height']]
            //['style', ['bold', 'italic', 'underline', 'clear']],
            //['fontsize', ['fontsize']],
            ['style', ['bold', 'italic', 'underline']],
            //['insert', ['picture']],
            ['para', ['ul']],
        ],
        fontNames: ['Arial', 'Arial Black'],
        fontNames: 'Arial',
        fontSize: 18,
        lineHeight: 2.0,
        placeholder: 'Ingrese la información...',
        /*onImageUpload: function(files, editor, welEditable){
            for(var i= files.length-1; i>=0; i++){
                sendFile(files(files[i], this));
            }
        }*/
    });

    elementEdit.setAttribute("disabled", "");
    elementEdit.style.pointerEvents = "none";

    elementSave.removeAttribute("disabled");
    elementSave.style.removeProperty("pointer-events");
};

function sendFile(file, el){
    var token= $('#token').val();
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', '/save-structure', true);
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
                        $('#divEstructura').summernote('destroy');
                        //console.log(markup);

                        elementSave.setAttribute("disabled", "");
                        elementSave.style.pointerEvents = "none";

                        elementEdit.removeAttribute("disabled");
                        elementEdit.style.removeProperty("pointer-events");
                    }else if(registro=='insert'){
                        window.location='/estructura';
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

/* FUNCION QUE GUARDA LA INFORMACIÓN */
var save = function() {
    var idestructura= $('#idEstructura').val();
    var estructura = $('#divEstructura').summernote('code');
    var tiporegistro='';
    var token= $('#token').val();

    //console.log(estructura, estructura.length);
    if(estructura=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    }else{
        var element = document.querySelector('#save');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        estructura = estructura.replace(/(\r\n|\n|\r)/gm, "//");
        estructura= estructura.trim();

        if(idestructura!=''){
            tiporegistro='update';
            arrayEstructura[0].descripcion= estructura;
        }else{
            tiporegistro='insert';
        }

        var formData = new FormData();
        formData.append("id", idestructura);
        formData.append("descripcion", estructura);
        sendUpdateAbout(formData, token, '/registrar-estructura', element, 0, tiporegistro);
    }
    /**/
};

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DE ESTRUCTURA */
function sendUpdateAbout(data, token, url, el, posi, registro){
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
                        $('#divEstructura').summernote('destroy');
                        //console.log(markup);

                        elementSave.setAttribute("disabled", "");
                        elementSave.style.pointerEvents = "none";

                        elementEdit.removeAttribute("disabled");
                        elementEdit.style.removeProperty("pointer-events");
                    }else if(registro=='insert'){
                        window.location='/estructura';
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