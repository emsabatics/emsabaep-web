/* -------------------------------------------------------------------------------------------------- */
//                               CONFIGURACION DETALLE INFORMATIVO
/* -------------------------------------------------------------------------------------------------- */
function urlbackservicio(){
    window.location= '/listsubservice-services/'+utf8_to_b64(idservice);
}

function viewListItemsSubService(id){
    window.location='/subservice-view-detaillist/'+utf8_to_b64(id)+'/v1';
}

function goInfoSubService(id){
    window.location='/subservice-detail-infor/'+utf8_to_b64(id)+'/v1/main';
}

function guardarInforSubservicio(){
    var token= $('#token').val();

    var idsubservicio = $("#idsubservice").val();
    var descripcion = $('#summernote').summernote('code');
    //console.log(descripcion);

    let fileInput = document.getElementById("file");
    var lengimg = fileInput.files.length;

    if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else {
        var element = document.querySelector('.saveinfosubservice');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');
        descripcion= descripcion.trim();

        var data = new FormData(formInforSubServicio);
        data.append("idsubservicio", idsubservicio);
        data.append("descripcion", descripcion);

        setTimeout(() => {
            sendInforService(token, data, "/store_detail_infor_subservice", element); 
        }, 700);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendInforService(token, data, url, el){
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
                    text:'Información Registrada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location= window.location.href;
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

function showDetailInfo(){
    $('#modalCargando').modal('hide');
    $("#tablaSubServiceInfoDetail")
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
}

function urlregistrarinfodetail(id){
    window.location='/subservice-detail-infor/'+utf8_to_b64(id)+'/v1/view';
}

/* FUNCION PARA INACTIVAR Subservicio Info Detail */
function inactivarSubserviceinfodetail(id, i){
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
            url: "/in-activar-subservicioinfodetail",
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
                    var elementState= document.getElementById('TrSsInfo'+i).cells[3];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateSubserviceinfodetail("+id+")'>"+
                        "<i class='far fa-edit mr-2'></i>"+
                        "Actualizar"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarSubserviceinfodetail("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-2'></i>"+
                            "Inactivar"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarSubserviceinfodetail("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-2'></i>"+
                                "Activar"+
                            "</button>";
                    }
                    html+="<button type='button' class='btn btn-danger btn-sm mr-3 btntable' title='Eliminar' onclick='deleteSubserviceinfodetail("+id+")' >"+
                        "<i class='fas fa-trash mr-2'></i>"+
                        "Eliminar"+
                    "</button>"; 
                    var element= document.getElementById('TrSsInfo'+i).cells[4];
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

/* FUNCION PARA ACTIVAR Subservicio Info Detail */
function activarSubserviceinfodetail(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";

    $.ajax({
        url: "/in-activar-subservicioinfodetail",
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
                var elementState= document.getElementById('TrSsInfo'+i).cells[3];
                $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateSubserviceinfodetail("+id+")'>"+
                    "<i class='far fa-edit mr-2'></i>"+
                    "Actualizar"+
                "</button>";
                if(estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarSubserviceinfodetail("+id+", "+i+")'>"+
                        "<i class='fas fa-eye-slash mr-2'></i>"+
                        "Inactivar"+
                    "</button>";
                }else if(estado=="0"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarSubserviceinfodetail("+id+", "+i+")'>"+
                            "<i class='fas fa-eye mr-2'></i>"+
                            "Activar"+
                        "</button>";
                }
                html+="<button type='button' class='btn btn-danger btn-sm mr-3 btntable' title='Eliminar' onclick='deleteSubserviceinfodetail("+id+")' >"+
                    "<i class='fas fa-trash mr-2'></i>"+
                    "Eliminar"+
                "</button>"; 
                var element= document.getElementById('TrSsInfo'+i).cells[4];
                $(element).html(html);
                }, 1500);
            } else if (res.resultado == false) {
                swal("No se pudo Inactivar", "", "error");
            }
        },
    });
}

/* FUNCION PARA ELIMINAR Subservicio Info Detail */
function deleteSubserviceinfodetail(id){
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
            url: "/delete-subservicioinfodetail",
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

function interfaceupdateSubserviceinfodetail(id){
    window.location='/subservice-updatedetail-infor/'+utf8_to_b64(id)+'/v1';
}

function actualizarInforSubservicio(){
    var token= $('#token').val();

    var descripcion = $('#summernoteeditinfodetail').summernote('code');
    //console.log(enlace);

    if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else {
        var element = document.querySelector('.updateinfosubservice');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');
        descripcion= descripcion.trim();

        var data = new FormData(formUpdateInforSubServicio);
        data.append("descripcion", descripcion);

        setTimeout(() => {
            sendUpdateSubService(token, data, "/update_subservice_infodetail", element); 
        }, 700);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateSubService(token, data, url, el){
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
                    text:'Registro Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    //window.location='/subservice-view-detail-infor/'+utf8_to_b64(getidsubservice)+'/v1';
                    window.location= window.location.href;
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
function updateimgsubserviceinfodetail(e){
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
        var element= document.querySelector('.btnupimgsubservid');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');
        
        var data = new FormData(formUpImgSubServiceInfoDetail);
        data.append("num_img", lengimg);
        setTimeout(() => {
            sendUpdatePics(token, data, "/actualizar-subservice-img-infodet", element);
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
            $('#modalFullSend').modal('hide');
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Registro Actualizado',
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

function downloadImgSubServiceInfo(id){
    var url='/download-archivo-subservice/'+id+'/infodetail';
    //window.open(url, '_blank');
    window.location= url;
}

function urlbacktosubservice_detailinfo(){
    /*if(interface=='main'){
        window.location= '/listsubservice-services/'+utf8_to_b64(idservice);
    }*/
    if(interface=='view'){
        window.location='/subservice-view-detail-infor/'+utf8_to_b64(getidsubservice)+'/v1';
    }
}
