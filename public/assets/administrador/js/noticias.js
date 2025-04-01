var contarFila = 0;
var arrayPics = [];

function guardarNoticia(){
    var token= $('#token').val();
    let fileInput = document.getElementById("file");
  
    var lugar = $("#inputLugar").val();
    var titulo = $("#inputTitulo").val();
    var descpshort = $("#inputDescShort").val();
    var fecha = $("#drgpickerFecha").val();
    var descripcion = $("#inputDesc").val();
    var lengimg = fileInput.files.length;

    if (lugar == "") {
        $("#inputLugar").focus();
        swal("Ingrese el lugar de la noticia", "", "warning");
    } else if (titulo == "") {
        $("#inputTitulo").focus();
        swal("Ingrese un título a la noticia", "", "warning");
    } else if (descpshort == "") {
        $("#inputDescShort").focus();
        swal("Ingrese una descripción corta para la noticia", "", "warning");
    } else if (fecha == "") {
        $("#drgpickerFecha").focus();
        swal("Debe seleccionar una fecha", "", "warning");
    } else if (descripcion == "") {
        $("#inputDesc").focus();
        swal("Ingrese una descripción de la noticia", "", "warning");
    } else if (lengimg == 0) {
        swal("No ha seleccionado alguna imagen para la noticia", "", "warning");
    } else {
        $('#modalFullSend').modal('show');
        descpshort = descpshort.replace(/(\r\n|\n|\r)/gm, "//");
        descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
        descpshort= descpshort.trim();
        descripcion= descripcion.trim();
        //var URLactual = window.location.href;
        var data = new FormData(formNoticia);
        data.append("lugar", lugar);
        data.append("descripcioncorta", descpshort);
        data.append("titulo", titulo);
        data.append("fecha", fecha);
        data.append("descripcion", descripcion);
        data.append("hashtag",arrayHash.toString());
        data.append("num_img", lengimg);
        setTimeout(() => {
            sendNuevaNoticia(token, data, "/registrar-noticia"); 
        }, 700);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNuevaNoticia(token, data, url){
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
                    text:'Noticia Registrada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/registrar_noticia';
                },1500);
                /*if(myArr.noformat==0){
                    swal({
                        title:'Excelente!',
                        text:'Noticia Registrada',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });

                    setTimeout(function(){
                        window.location = '/registrar_noticia';
                    },1500);
                }else{
                    if(parseInt(myArr.noformat) == parseInt(myArr.num_total)){
                        swal("No se pudo subir las imagenes", "Verifique si cumple con el formato establecido", "error");
                    }else{
                        $('#numimgnoup').html(myArr.noformat+" / "+ myArr.num_total);
                        $('#modalAlertInfo').modal('show');
                    }
                }*/
                
            } else if (myArr.resultado == "noimagen") {
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
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

function cargar_noticias(name){
    var con = 1; var estadoItem='';
    var simbolo= '"';
    var html =
        "<table class='table datatables' id='tablaNoticias'>" +
            "<thead class='thead-dark'>" +
                "<tr style='pointer-events:none;'>" +
                    "<th>N°</th>" +
                    "<th>Fecha</th>" +
                    "<th>Título</th>" +
                    "<th>Descripción Corta</th>" +
                    "<th>N° Fotos</th>" +
                    "<th>Estado</th>" +
                    "<th>Opciones</th>" +
                "</tr>" +
            "</thead>" +
        "<tbody>";
    var data= JSON.parse(name);
    $(data).each(function(i,v){
        if(v.estado=="1"){
            estadoItem="Visible";
        }else{
            estadoItem="No Visible";
        }
        var descpcort= v.descripcion_corta.replace('//','<br>');

        html +="<tr id='Tr"+i +"'>"+
            "<td style='text-align: center;'>"+con+"</td>"+
            "<td>"+v.fecha+"</td>"+
            "<td><span class='lugarNews'>"+v.lugar+"</span> | "+v.titulo+"</td>"+
            "<td style='text-align: justify;'>"+descpcort+"</td>"+
            "<td>"+v.num_fotos+"</td>" +
            "<td>"+estadoItem+"</td>" +
            "<td style='display: flex;'>"+
                "<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarNoticia("+simbolo+v.id+simbolo+")'>"+
                    "<i class='fas fa-pencil-alt'></i>"+
                "</button>";
                if(v.estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerNews("+simbolo+v.id+simbolo+", "+i+")'>"+
                        "<i class='fas fa-eye-slash'></i>"+
                    "</button>";
                }else if(v.estado=="0"){
                    html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarNews("+simbolo+v.id+simbolo+", "+i+")'>"+
                        "<i class='fas fa-eye'></i>"+
                    "</button>";
                }
            html+="</td>" +
        "</tr>";
        con++;
    });
    html += "</tbody></table>";
    $("#divNoticias").html(html);
    setTimeout(function(){
        $('#modalCargando').modal('hide');
        $("#tablaNoticias")
            .removeAttr("width")
            .DataTable({
                autoWidth: true,
                lengthMenu: [
                    [8, 16, 32, 64, -1],
                    [8, 16, 32, 64, "Todo"],
                ],
                //para cambiar el lenguaje a español
                language: {
                    lengthMenu: "Mostrar _MENU_ registros",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty:
                        "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sSearch: "Buscar:",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "Último",
                        sNext: "Siguiente",
                        sPrevious: "Anterior",
                    },
                    sProcessing: "Procesando...",
                },
                columnDefs: [
                    { width: 40, targets: 0, className: "text-center" },
                    { className: "dt-head-center", targets: [1, 2, 3, 4] },
                ],
            });
    },1000);
}

function urlregistrarNoticia(){
    window.location= '/registrar_noticia';
}

function editarNoticia(id){
    //window.location= '/actualizar-noticia/'+utf8_to_b64(id);
    //window.location= '/actualizar-noticia/'+utf8ToBase64_moderno(id);
    window.location= '/actualizar-noticia/'+id;
}

/* FUNCION PARA CARGAR NOTICIAS INDIVIDUAL */
function cargarNoticiaIn(texto, imagen){
    let hasht='';
    let id='';

    $(texto).each(function(i,v){
        id= v.id;
        $('#idnoticia').val(v.id);
        $('#idnoticiapics').val(v.id);
        $('#inputELugar').val(v.lugar);
        $('#drgpickerFechaE').val(v.fecha);
        $('#inputETitulo').val(v.titulo);
        //$('#inputEDescShort').val(v.descripcion_corta);
        replaceCaracter(v.descripcion_corta, '#inputEDescShort');
        replaceCaracter(v.descripcion, '#inputEDesc');
        //console.log(v.hashtag);
        if(v.hashtag!='' && v.hashtag!=null){
            hasht= v.hashtag;
            arrayHashedit= hasht.split(",");
        }
    });

    if(hasht.length>0){
        drawHashtag();
    }

    setTimeout(function(){
        $('#modalCargando').modal('hide');
        cargarPics(imagen, id);
    },800);
}

/* FUNCION QUE GRAFICA LOS HASHTAG DE LA NOTICIA */
function drawHashtag(){
    arrayHashedit.forEach(function(item){
        contadorHash++;
        var input = document.createElement('input');//creo elemento input y le creo un salto de línea
        var salto = document.createElement('br');
        var btn_eliminar = document.createElement('button');
        var divgroup= document.createElement('div');
    
        divgroup.id="divGroup"+contadorHash;
        divgroup.className="d-flex flex-row";
        
        btn_eliminar.innerHTML='<i class="fas fa-trash mr-2"></i> Eliminar';
        btn_eliminar.type = 'button';
        btn_eliminar.className="btn btn-danger ml-2";
        btn_eliminar.id = "btn"+contadorHash;
    
        input.type = 'text';
        input.className="formEdit";
        input.id = "inputHash"+contadorHash;
        input.name = 'btn'+contadorHash;
        input.value = item;
        input.setAttribute('disabled',''); // propiedad disabled
        input.style.cssText= 'width: 60%;';
        divgroup.append(input);
        divgroup.append(btn_eliminar);
        contenedor.append(salto);//todo lo agrego al div de almacenar
        contenedor.append(divgroup);
        
        var botones = document.getElementById('btn'+contadorHash);
        botones.addEventListener('click', function(){
            var posi= this.id.substr(3, this.id.length);
            var divactual= document.getElementById("divGroup"+posi);
            var input_name = divactual.querySelector('input[name='+this.id+']');
            while (divactual.firstChild) {
                divactual.removeChild(divactual.firstChild);
            }
            var index1= arrayHashedit.indexOf(input_name.value);
            if(index1 > -1){
                arrayHashedit.splice(index1,1);
            }
            contenedor.removeChild(salto);
            contenedor.removeChild(divactual);
        });
    })
}


/* FUNCION QUE TRAZA SALTOS DE LÍNEA EN EL TEXTAREA DE LA NOTICIA */
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
    /*
    var cadena="";
    for(var i=0; i<dato.length; i++){
      if(dato[i]=="/"){
        //cadena = dato[i];
        cadena += '\n'
        //$('#inputEDesc').val(cadena += '\n');
      }else{
        cadena += dato[i];
      }
    }
    $('#inputEDesc').val(cadena);*/
}

/* FUNCION QUE CARGA LAS IMAGENES DE LA NOTICIA SELECCIONADA */
function cargarPics(array, id){
    var con=0;
    if(array.length==0){
        drawNoData();
    }

    $(array).each(function(i,v){
        //console.log(v.imagen);
        var data={
            "id": v.id,
            "imagen":v.imagen,
            "nombre": v.imagen,
            "id_noticia": id,
            "pos": i
        }
        arrayPics.push(data);
        con++;
    });

    if(arrayPics.length>0){
        imprimirDatos();
    }
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
                "<img src='/noticias-img/"+item.imagen+"' alt='Imagen Noticia "+i+"' class='avatar-img'>"+
            "</div>"+
            "</div><div style='grid-row: 1/2;'>"+
            "<button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarPic("+item.id+","+i+", "+item.id_noticia+")'>"+
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

/* FUNCION QUE ACTUALIZA LAS NOTICIAS EN TEXTO */
function updateonlytextnews(){
    var token= $('#token').val();
    var idnoti= $('#idnoticia').val();
    var lugar = $("#inputELugar").val();
    var titulo = $("#inputETitulo").val();
    var descpshort = $("#inputEDescShort").val();
    var fecha = $("#drgpickerFechaE").val();
    var descripcion = $("#inputEDesc").val();

    if (lugar == "") {
        $("#inputELugar").focus();
        swal("Ingrese el lugar de la noticia", "", "warning");
    } else if (titulo == "") {
        $("#inputETitulo").focus();
        swal("Ingrese un título a la noticia", "", "warning");
    } else if (descpshort == "") {
        $("#inputEDescShort").focus();
        swal("Ingrese una descripción corta para la noticia", "", "warning");
    } else if (fecha == "") {
        $("#drgpickerFechaE").focus();
        swal("Debe seleccionar una fecha", "", "warning");
    } else if (descripcion == "") {
        $("#inputEDesc").focus();
        swal("Ingrese una descripción de la noticia", "", "warning");
    } else {
        $('#modalFullSendEdit').modal('show');
        descpshort = descpshort.replace(/(\r\n|\n|\r)/gm, "//");
        descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
        var data = new FormData();
        data.append("id", idnoti);
        data.append("lugar", lugar);
        data.append("descripcioncorta", descpshort);
        data.append("titulo", titulo);
        data.append("fecha", fecha);
        data.append("descripcion", descripcion);
        data.append("hashtag",arrayHashedit.toString());
        //console.log(idnoti, lugar, descpshort, titulo, fecha, descripcion, arrayHashedit.toString());
        setTimeout(() => {
            sendUpdateNoticia(token, data, "/actualizar-noticia-texto");
        }, 900);
    }
}

/* FUNCION QUE ACTUALIZA LAS NOTICIAS EN IMAGENES */
function updatepicsnews(e){
    e.preventDefault();
    var token= $('#token').val();
    let fileInput = document.getElementById("file");
    //var idnoti= $('#idnoticiapics').val();
    var lengimg = fileInput.files.length;
    if (lengimg == 0) {
        swal("No ha seleccionado imagen para la noticia", "", "warning");
    } else {
        $('#modalFullSendEdit').modal('show');
        var data = new FormData(formENoticia);
        data.append("num_img", lengimg);
        setTimeout(() => {
            sendUpdatePicsNoticia(token, data, "/actualizar-noticia-img");
        }, 900);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA ACTUALIZAR */
function sendUpdateNoticia(token, data, url){
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
                    text:'Noticia Actualizada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    regresar();
                },1500);
            } else if (myArr.resultado == false) {
                swal("No se pudo Actualizar", "", "error");
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

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO´DE NUEVAS IMÁGENES */
function sendUpdatePicsNoticia(token, data, url){
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
                    text:'Noticia Actualizada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    regresar();
                },1500);
                /*if(myArr.noformat==0){
                    swal({
                        title:'Excelente!',
                        text:'Fotografías Subidas',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });

                    setTimeout(function(){
                        regresar();
                    },1500);
                }else{
                    if(parseInt(myArr.noformat) == parseInt(myArr.num_total)){
                        swal("No se pudo subir las imagenes", "Verifique si cumple con el formato establecido", "error");
                    }else{
                        $('#numimgnoup').html(myArr.noformat+" / "+ myArr.num_total);
                        $('#modalAlertInfo').modal('show');
                    }
                }*/
            } else if (myArr.resultado == "noimagen") {
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            }else if (myArr.resultado == false) {
                swal("No se pudo Actualizar", "", "error");
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

/* FUNCION PARA RETORNAR A LA INTERFAZ DE LISTADO DE NOTICIA */
function regresar() {
    window.location = "/listado-noticias";
}

function eliminarPic(id, i, idnoticia){
    var estado = "0";
    //limpiarArray(i);
    var token= $('#token').val();
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
            url: "/in-activar-img-noticia",
            type: "POST",
            dataType: "json",
            headers:{
                'X-CSRF-TOKEN': token
            },
            data: {
                id: id,
                estado: estado,
                idnoticia: idnoticia
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
                                html: "<p>Se han eliminado todas las <b>imágenes</b>"+
                                    " de esta Noticia</p>"
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

/* FUNCION PARA INACTIVAR NOTICIA */
function removerNews(id, i) {
    var estado = "0";
    var estadoItem='No Visible';
    var token= $('#token').val();
    var simbolo= '"';
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
            url: "/in-activar-noticia",
            type: "POST",
            dataType: "json",
            headers:{
                'X-CSRF-TOKEN': token
            },
            data: {
                id: id,
                estado: estado,
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
                        //cargar_noticias();
                        document.getElementById('Tr'+i).cells[5].innerText= estadoItem;
        
                        var html="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarNoticia("+simbolo+id+simbolo+")'>"+
                            "<i class='fas fa-pencil-alt'></i>"+
                        "</button>";
                        if(estado=="1"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerNews("+simbolo+id+simbolo+", "+i+")'>"+
                                "<i class='fas fa-eye-slash'></i>"+
                            "</button>";
                        }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarNews("+simbolo+id+simbolo+", "+i+")'>"+
                                    "<i class='fas fa-eye'></i>"+
                                "</button>";
                        }
                        var element= document.getElementById('Tr'+i).cells[6];
                        $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
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
  
/* FUNCION PARA ACTIVAR NOTICIA */
function activarNews(id, i) {
    var simbolo= '"';
    var estado = "1";
    var estadoItem='Visible';
    var token= $('#token').val();
    $.ajax({
      url: "/in-activar-noticia",
      type: "POST",
      headers:{
        'X-CSRF-TOKEN': token
      },
      dataType: "json",
      data: {
        id: id,
        estado: estado,
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
            document.getElementById('Tr'+i).cells[5].innerText= estadoItem;
            var html="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarNoticia("+simbolo+id+simbolo+")'>"+
                "<i class='fas fa-pencil-alt'></i>"+
            "</button>";
            if(estado=="1"){
                html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerNews("+simbolo+id+simbolo+", "+i+")'>"+
                    "<i class='fas fa-eye-slash'></i>"+
                "</button>";
            }else if(estado=="0"){
                html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarNews("+simbolo+id+simbolo+", "+i+")'>"+
                        "<i class='fas fa-eye'></i>"+
                    "</button>";
            }
            var element= document.getElementById('Tr'+i).cells[6];
            $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Activar", "", "error");
        }
      },
    });
}