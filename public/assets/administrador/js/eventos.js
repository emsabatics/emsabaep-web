var isImage= false;

//FUNCION CERRAR MODAL AGG EVENTO
function cerrarModal(){
    $('#modal-event').modal('hide');
    $('#images').html('');
    const file = document.querySelector('#file');
    file.value = '';

    numOfFIles.textContent = `- Ningún archivo seleccionado -`;
}

/* FUNCION PARA GUARDAR EL REGISTRO DE UN NUEVO EVENTO */
function guardarEvento(){
    var token= $('#token').val();
    var titulo= $('#inputTituloEvent').val();
    var descripcion= $('#inputDescEvent').val();
    var desde = $('#R_fechaI').val();
    var hasta = $('#R_fechaH').val();
    var allDay= $('#R_fechaALL').val();
    let fileInput = document.getElementById("file");
    var isHome= "no";
    var getTypeEvent= $('#selectEvent').val();

    var lengimg = fileInput.files.length;

    if (titulo == "") {
        $("#inputTituloEvent").focus();
        swal("Debe ingresar un título al Evento", "", "warning");
    } else if (lengimg == 0) {
        swal("No ha seleccionado imágen para el evento", "", "warning");
    } else if(lengimg>=2){
        swal("Sólo debe seleccionar una imágen para el evento", "", "warning");
    } else if(getTypeEvent=="0"){
        swal("Debe seleccionar el tipo de Evento", "", "warning");
    } else { 
        if(descripcion!=''){
            descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
        }

        if(puedeGuardarM(nameInterfaz) === 'si'){
            descripcion= descripcion.trim();
            var formData = new FormData(formEvento);
            formData.append('ndescripcion', descripcion);
            formData.append('tipoevento', getTypeEvent);
            $('#btnAgendar').addClass('btndisable');
            guardarNewEvent(token, formData, '/registro-eventos');
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function guardarNewEvent(token, data, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            if(myArr.resultado==true){
                $('#btnAgendar').removeClass('btndisable');
                let ideventores= parseInt(myArr.ID);
                let color= myArr.color;
                let titulo= myArr.titulo;
                let desde= myArr.desde;
                let hasta= myArr.hasta;

                swal({
                    title:'Excelente!',
                    text:'Evento Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    calendar.addEvent({
                        groupId: ideventores,
                        title: titulo,
                        start: desde,
                        end: hasta,
                        allDay: true,
                        backgroundColor: color,
                        borderColor: color
                    });
                    $('#images').html('');
                    $('#inputTituloEvent').val('');
                    $('#inputDescEvent').val('');
                    $('#modal-event').modal('hide');
                    limpiarFile();
                },1500);
            } else if (myArr.resultado == "noimagen") {
                $('#btnAgendar').removeClass('btndisable');
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                $('#btnAgendar').removeClass('btndisable');
                swal("Error al copiar los archivos", "", "error");
            } else if(myArr.resultado==false){
                $('#btnAgendar').removeClass('btndisable');
                swal('No se pudo guardar el Evento','','error');
            }
        }else if(xr.status === 400){
            $('#btnAgendar').removeClass('btndisable');
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

/* FUNCION QUE LIMPIA EL INPUT FILE */
function limpiarFile() {
    var lengimg = fileInput.files.length;
    if (lengimg > 0) {
        let fileBuffer = Array.from(fileInput.files);
        fileBuffer.splice((i-1), 1);

        /** Code from: https://stackoverflow.com/a/47172409/8145428 */
        const dT = new ClipboardEvent('').clipboardData || // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
            new DataTransfer(); // specs compliant (as of March 2018 only Chrome)

        for (let file of fileBuffer) { dT.items.add(file); }
        fileInput.files = dT.files;
        //console.log(fileInput.files);
        if(fileInput.files.length==0){
            numOfFIles.textContent = `- Ningún archivo seleccionado -`;
        }else{
            numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;
        }
    }
}

/* FUNCION QUE OBTIENE LOS DATOS DEL REGISTRO SELECCIONADO */
function getAgendaEvent(id){
    var html="";
    var token= $('#token').val();
    document.getElementById('divcontainerUp').style.display='none';

    var data = new FormData();
    data.append("id", id);

    var url= "/eventos/get-item-select";
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);

    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);
            $(myArr).each(function(i,v){
                $('#id_agenda').val(v.groupId);
                $('#txtTituloEv').val(v.title);
                $('#txt_fechaI').val(v.start);
                $('#txt_fechaH').val(v.end);
                //$('#txtDescEv').val(v.descripcion);
                $('#selectEventEdit').val(v.tipo);
                if(v.descripcion!= null && v.descripcion!=''){
                    replaceCaracter(v.descripcion);
                }
                
                fSDate= v.start;
                fEDate= v.end;
                if(v.imagen.length>0){
                    imprimirDatos(v.groupId, v.imagen);
                }else{
                    isImage= false;
                    document.getElementById('divcontainerUp').style.display='block';
                    document.getElementById('rowPicsInd').style.display='none';
                }
            });

            setTimeout(function(){
                $('#modal-event-edit').modal('show');
            },400);
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    }

    xr.send(data);
}

/* FUNCION QUE DIBUJA LA IMAGEN DEL EVENTO */
function imprimirDatos(id, imagen){
    //`${fileInput.files.length} Archivos Seleccionados`
    isImage= true;
    var html="";
    html+="<div class='card shadow mt-4 mb-4' id='divpics"+id+"' style='width: 336px;'>"+
        "<div class='card-body text-center'>"+
          "<div class='mt-2' style='height: 350px;width: 290px;'>"+
            "<a href='javascript:void(0)'>"+
              `<img src="/eventos-img/${imagen}" alt="Evento" class="avatar-img divgetimg">`+
            "</a>"+
          "</div>"+
        "</div>"+
        "<div class='card-footer card-footer-event-edit'>"+
          "<div class='row align-items-center justify-content-between'>"+
            "<div class='col-auto'>"+
              "<small class='btnSpanDel' onclick='eliminarPic("+id+")'>"+
                "<span class='far fa-trash-alt'></span> "+
                "&nbsp;Eliminar"+
              "</small>"+
            "</div>"+
          "</div>"+
        "</div>"+
    "</div>";
    $('#rowPicsInd').html(html);
}

/* FUNCION QUE ACTUALIZA LOS DATOS DEL EVENTO */
function actualizarEvento(){
    var token= $('#token').val();
    var titulo= $('#txtTituloEv').val();
    var descripcion= $('#txtDescEv').val();
    var getTypeEvent= $('#selectEventEdit').val();
    let fileInput = document.getElementById("fileedit");
    var lengimg = fileInput.files.length;

    if (titulo == "") {
        $("#txtTituloEv").focus();
        swal("Debe ingresar un título al Evento", "", "warning");
    } else if(getTypeEvent=="0"){
        swal("Debe seleccionar el tipo de Evento", "", "warning");
    } else{ 
        if(isImage==false){
            //console.log('IMAGE FALSE');
            if (lengimg == 0) {
                swal("No ha seleccionado imágen para el evento", "", "warning");
            }else if(lengimg>=2){
                swal("Sólo debe seleccionar una imágen para el evento", "", "warning");
            }else{
                if(descripcion!=''){
                    descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
                }

                if(puedeActualizarM(nameInterfaz) === 'si'){
                    descripcion= descripcion.trim();
                    var data = new FormData(formeditEvento);
                    data.append('ndescripcion', descripcion);
                    data.append('opcion', "nuevaimagen");
                    data.append('tipoevento', getTypeEvent);
                    $('#btnActionForm').addClass('btndisable');
                    sendUpdateEvent(token, data, '/actualizar-evento');
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else if(isImage==true){
            //console.log('IMAGE TRUE');
            if(descripcion!=''){
                descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
            }
            
            if(puedeActualizarM(nameInterfaz) === 'si'){
                descripcion= descripcion.trim();
                var data = new FormData(formeditEvento);
                data.append('ndescripcion', descripcion);
                data.append('opcion', "conimagen");
                data.append('tipoevento', getTypeEvent);
                $('#btnActionForm').addClass('btndisable');
                sendUpdateEvent(token, data, '/actualizar-evento');
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateEvent(token, data, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
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

                $('#btnActionForm').removeClass('btndisable');
                    //nagend.refetchEvents();
                    $('#modal-event-edit').modal('hide');
                    $('#images-edit').html('');
                    document.getElementById('divcontainerUp').style.display='none';
                    document.getElementById('images-edit').style.display='none';
                    document.getElementById('rowPicsInd').style.display='block';
                    nagend=null;
                setTimeout(function(){
                    window.location='/eventos';
                },1500);
            }else if (myArr.resultado == "noimagen") {
                $('#btnActionForm').removeClass('btndisable');
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                $('#btnActionForm').removeClass('btndisable');
                swal("Error al copiar la imagen", "", "error");
            } else if(myArr.resultado==false){
                $('#btnActionForm').removeClass('btndisable');
                swal('No se pudo actualizar el registro','','error');
            }
        }else if(xr.status === 400){
            $('#btnActionForm').removeClass('btndisable');
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

/* FUNCION QUE ELIMINA EL EVENTO SELECCIONADO */
function eliminarEvento(){
    var token= $('#token').val();
    var id= $('#id_agenda').val();
    var estado="0";
    if(puedeEliminarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: '<strong>¡Aviso!</strong>',
        type: 'warning',
        html: '¿Está seguro que desea eliminar este registro?',
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
            if(puedeEliminarM(nameInterfaz) === 'si'){
                var url= "/inactivar-evento";
                //var params = "id="+id+"&estado="+estado;
                var params = new FormData();
                params.append('id', id);
                params.append('estado', estado);

                var contentType = "application/x-www-form-urlencoded;charset=utf-8";
                var xr = new XMLHttpRequest();
                xr.open('POST', url, true);
                //xr.setRequestHeader("Content-Type", contentType);
                xr.setRequestHeader('X-CSRF-TOKEN', token);

                xr.onload = function(){
                    if(xr.status === 200){
                        var myArr = JSON.parse(this.responseText);
                        if(myArr.resultado==true){
                            swal({
                                title:'Excelente!',
                                text:'Registro Eliminado',
                                type:'success',
                                showConfirmButton: false,
                                timer: 1600
                            });
                            setTimeout(function(){
                                eventDel.event.remove();
                                $('#modal-event-edit').modal('hide');
                                eventDel=null;
                            },1500);
                        }else if(myArr.resultado==false){
                            swal('No se pudo eliminar el registro','','error');
                        }
                    }else if(xr.status === 400){
                        Swal.fire({
                            title: 'Ha ocurrido un Error',
                            html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                                'Intente nuevamente</p>',
                            type: 'error'
                        });
                    }
                }

                xr.send(params);
            }else{
                swal('No tiene permiso para realizar esta acción','','error');
            }
        }else if(result.dismiss === Swal.DismissReason.cancel){
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

/* FUNCION QUE CIERRA EL MODAL DEL EVENTO */
function cerrarEvento(){
    $('#modal-event-edit').modal('hide');
    setTimeout(()=>{
        isImage= true;
        document.getElementById('divcontainerUp').style.display='none';
        document.getElementById('rowPicsInd').style.display='block';
    },500);
}

/* FUNCION QUE TRAZA SALTOS DE LÍNEA EN EL TEXTAREA DEL EVENTO */
function replaceCaracter(dato){
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
    $('#txtDescEv').val(dato);
}

/* FUNCION QUE ELIMINA LA IMAGEN ACTUAL DEL EVENTO PERO SIN ACCEDER A BD */
function eliminarPic(id){
    if(puedeEliminarM(nameInterfaz) === 'si'){
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
            isImage= false;
            document.getElementById('divcontainerUp').style.display='block';
            document.getElementById('rowPicsInd').style.display='none';
            //$('#rowPicsInd').html("");
        } else if (result.dismiss === Swal.DismissReason.cancel) {
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

$('input[name="txt_fechaI"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val(fSDate);
});

$('input[name="txt_fechaH"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val(fEDate);
});

function previewpicsEdit(){
    //imageContainer.innerHTML="";
    let fileInput = document.getElementById("fileedit");
    let numOfFIles = document.getElementById("num-of-files-edit");
    let imageContainer = document.getElementById("images-edit");

    imageContainer.innerHTML="";
    numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;

    for(i of fileInput.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        figCap.innerHTML = i.name;
        figure.appendChild(figCap);
        reader.onload= () =>{
            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            /*let span = document.createElement("span");
            span.setAttribute("class", "span-img");
            span.innerHTML="&times;";*/
            figure.insertBefore(img, figCap);
            //figure.insertBefore(span,img);
        }

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function navegador() {
    var url = "";
    // Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined';

    // Safari 3.0+ "[object HTMLElementConstructor]"
    //var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && window['safari'].pushNotification));

    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/ false || !!document.documentMode;

    // Edge 20+
    var isEdge = !isIE && !!window.StyleMedia;

    // Chrome 1 - 79
    var isChrome =
        !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

    // Edge (based on chromium) detection
    var isEdgeChromium = isChrome && navigator.userAgent.indexOf("Edg") != -1;

    // Blink engine detection
    //var isBlink = (isChrome || isOpera) && !!window.CSS;

    if (isEdgeChromium == true) {
        url = "http:";
    } else if (isChrome == true) {
        url = "http:";
    }else if (isFirefox == true) {
        url = "http:";
    }
    return url;
}

function sumarDias(fecha, dias){
    let arrayFecha= [];
    fecha.setDate(fecha.getDate() + dias);
    arrayFecha[0]= fecha.getDate();
    arrayFecha[1]= fecha.getMonth();
    arrayFecha[2]= fecha.getFullYear();
    return arrayFecha;
}