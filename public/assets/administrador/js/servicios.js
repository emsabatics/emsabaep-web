function showInfoServicio(){
    $('#modalCargando').modal('hide');
    $("#tablaService")
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
                { width: 290, targets: 1},
                { className: "dt-head-center", targets: [1, 2, 3, 4] },
            ],
        });
}

function urlregistrarservicio(){
    window.location='/registrar_servicio';
}

function urlback(){
    window.location='/servicios';
}

$('#selTypeService').on("change", function(e) {
    var lastValue = $(this).select2('data')[0].id;
    if(lastValue=='interno'){
        typeService='interno';
        document.getElementById('divEnlaceService').style.display='none';
        $('#inputLinkService').val('');
    }else if(lastValue=='externo'){
        typeService='externo';
        document.getElementById('divEnlaceService').style.display='block';
    }
});

function guardarServicio(){
    var token= $('#token').val();

    var tipo = $("#selTypeService :selected").val();
    var titulo= $('#inputTitleService').val();
    var descpshort = $("#inputDescShortService").val();
    var descripcion = $('#summernote').summernote('code');
    var enlace= $('#inputLinkService').val();
    //console.log(descripcion);

    let fileInput = document.getElementById("file");
    var lengimg = fileInput.files.length;
    let fileInputIcon = document.getElementById("fileIcon");
    var lengimgicon = fileInputIcon.files.length;

    if (tipo == "0") {
        $("#selTypeService").focus();
        swal("Seleccione el Tipo de Servicio", "", "warning");
    } else if (titulo == "") {
        $('#inputTitleService').focus();
        swal("Ingrese el Título del Servicio", "", "warning");
    } else if(titulo.length > 50){
        $('#inputTitleService').focus();
        swal("Ingrese 50 caracteres como máximo", "", "warning");
    } else if (descpshort == "") {
        $("#inputDescShortService").focus();
        swal("Ingrese una descripción corta para el servicio", "", "warning");
    } else if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else if (lengimgicon == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimgicon > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else {
        if(typeService=='externo'){
            if(enlace==''){
                $('#inputLinkService').focus();
                swal("Ingrese el Link Externo del Servicio", "", "warning");
            }else{
                var element = document.querySelector('.saveservice');
                element.setAttribute("disabled", "");
                element.style.pointerEvents = "none";

                $('#modalFullSend').modal('show');
                descripcion= descripcion.trim();
                descpshort= descpshort.trim();
                descpshort = descpshort.replace(/(\r\n|\n|\r)/gm, "//");

                var data = new FormData(formServicio);
                data.append('tiposervicio', typeService);
                data.append("descripcioncorta", descpshort);
                data.append("descripcion", descripcion);

                setTimeout(() => {
                    sendNewService(token, data, "/store-service", element); 
                }, 700);
            }
        }else if(typeService=='interno'){
            var element = document.querySelector('.saveservice');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');
            descripcion= descripcion.trim();

            var data = new FormData(formServicio);
            data.append('tiposervicio', typeService);
            data.append("descripcioncorta", descpshort);
            data.append("descripcion", descripcion);

            setTimeout(() => {
                sendNewService(token, data, "/store-service", element); 
            }, 700);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewService(token, data, url, el){
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
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Servicio Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    urlback();
                },1500);
                
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "Documento ya se encuentra registrado.", "error");
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
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

function interfaceupdateService(id){
    window.location= '/edit-service/'+utf8_to_b64(id);
}

/* FUNCION PARA INACTIVAR SERVICIO */
function inactivarService(id, i){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
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
            url: "/in-activar-servicio",
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
                    var elementState= document.getElementById('Tr'+i).cells[3];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateService("+id+")'>"+
                        "<i class='far fa-edit mr-2'></i>"+
                        "Actualizar"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarService("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-2'></i>"+
                            "Inactivar"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarService("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-2'></i>"+
                                "Activar"+
                            "</button>";
                    }
                    var element= document.getElementById('Tr'+i).cells[4];
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

/* FUNCION PARA ACTIVAR SERVICIO */
function activarService(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
      url: "/in-activar-servicio",
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
            var elementState= document.getElementById('Tr'+i).cells[3];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateService("+id+")'>"+
                "<i class='far fa-edit mr-2'></i>"+
                "Actualizar"+
            "</button>";
            if(estado=="1"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarService("+id+", "+i+")'>"+
                "<i class='fas fa-eye-slash mr-2'></i>"+
                "Inactivar"+
            "</button>";
            }else if(estado=="0"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarService("+id+", "+i+")'>"+
                "<i class='fas fa-eye mr-2'></i>"+
                "Activar"+
            "</button>";
            }
            var element= document.getElementById('Tr'+i).cells[4];
            $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
}

function actualizarServicio(){
    var token= $('#token').val();

    var titulo= $('#inputTitleServiceE').val();
    var descpshort= $('#inputDescShortServiceE').val();
    var descripcion = $('#summernoteE').summernote('code');
    var enlace= $('#inputLinkServiceE').val();
    var tipos= $('#tiposervicio').val();
    //console.log(enlace);

    if (titulo == "") {
        $('#inputTitleServiceE').focus();
        swal("Ingrese el Título del Servicio", "", "warning");
    } else if(titulo.length > 50){
        $('#inputTitleServiceE').focus();
        swal("Ingrese 50 caracteres como máximo", "", "warning");
    } else if (descpshort == "") {
        $("#inputDescShortServiceE").focus();
        swal("Ingrese una descripción corta para el servicio", "", "warning");
    } else if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else {
        if(tipos=='externo'){
            if(enlace==''){
                $('#inputLinkServiceE').focus();
                swal("Ingrese el Link Externo del Servicio", "", "warning");
            }else{
                var element = document.querySelector('.updateservice');
                element.setAttribute("disabled", "");
                element.style.pointerEvents = "none";

                $('#modalFullSend').modal('show');
                descripcion= descripcion.trim();

                var data = new FormData(formServicioE);
                data.append('tiposervicio', tipos);
                data.append("descripcioncorta", descpshort);
                data.append("descripcion", descripcion);

                setTimeout(() => {
                    sendUpdateService(token, data, "/update-service", element); 
                }, 700);
            }
        }else if(tipos=='interno'){
            var element = document.querySelector('.updateservice');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');
            descripcion= descripcion.trim();

            var data = new FormData(formServicioE);
            data.append('tiposervicio', tipos);
            data.append("descripcioncorta", descpshort);
            data.append("descripcion", descripcion);

            setTimeout(() => {
                sendUpdateService(token, data, "/update-service", element); 
            }, 700);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateService(token, data, url, el){
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
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Servicio Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    urlback();
                },1500);
                
            } else if (myArr.resultado == false) {
                swal("No se pudo Actualizar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Actualizar", "Documento ya se encuentra registrado.", "error");
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

/* FUNCION QUE ACTUALIZA LA IMAGEN DEL SERVICIO */
function updateimgservice(e){
    e.preventDefault();
    var token= $('#token').val();
    let fileInput = document.getElementById("fileImgEdit");
    //var idnoti= $('#idnoticiapics').val();
    var lengimg = fileInput.files.length;
    if (lengimg == 0) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite subir un archivo", "", "warning");
    } else {
        var element= document.querySelector('.btnupimgserv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSendEdit').modal('show');
        
        var data = new FormData(formUpImgService);
        data.append("num_img", lengimg);
        setTimeout(() => {
            sendUpdatePics(token, data, "/actualizar-service-img", element);
        }, 900);
    }
}

/* FUNCION QUE ACTUALIZA EL ICONO DEL SERVICIO */
function updateiconservice(e){
    e.preventDefault();
    var token= $('#token').val();
    let fileInput = document.getElementById("fileIconEdit");
    //var idnoti= $('#idnoticiapics').val();
    var lengimg = fileInput.files.length;
    if (lengimg == 0) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite subir un archivo", "", "warning");
    } else {
        var element= document.querySelector('.btnupiconserv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSendEdit').modal('show');
        
        var data = new FormData(formUpIconoService);
        data.append("num_img", lengimg);
        setTimeout(() => {
            sendUpdatePics(token, data, "/actualizar-service-icono", element);
        }, 900);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA ACTUALIZAR */
function sendUpdatePics(token, data, url, el){
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
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Archivo Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    //urlback();
                    window.location= window.location.href;
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

function downloadImgService(id){
    var url='/download-archivo-service/'+id+'/img';
    //window.open(url, '_blank');
    window.location= url;
}

function downloadIconService(id){
    var url='/download-archivo-service/'+id+'/icon';
    //window.open(url, '_blank');
    window.location= url;
}

//NO PROGRAMADO 16 SEPTIEMBRE 2024
function deleteImgService(id){
    alert(id);
    /*var url='/download-img-service/'+id;
    //window.open(url, '_blank');
    window.location= url;*/
}

//FUNCION PARA REGISTRAR SUBSERVICIOS
function registerSubService(id){
    var url='/listsubservice-services/'+utf8_to_b64(id);
    window.location= url;
}

function eliminarService(id){
    var token=$('#token').val();
    var html="";
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea eliminar este registro?",
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
            url: "/delete-oneservice",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: id
            },
            success: function (res) {
                if (res.resultado == true) {
                    swal({
                        title: "Excelente!",
                        text: "Registro Eliminado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    
                    setTimeout(function () {
                        window.location = window.location.href;
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
}

