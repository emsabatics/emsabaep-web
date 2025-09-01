/* -------------------------------------------------------------------------------------------------- */
//                               CONFIGURACION LISTA DESPLEGABLE
/* -------------------------------------------------------------------------------------------------- */
function urlbackservicio(){
    window.location= '/listsubservice-services/'+utf8_to_b64(idservice);
}

function viewListItemsSubService(id){
    window.location='/subservice-view-detaillist/'+utf8_to_b64(id)+'/v1';
}

function showDetailList(){
    $('#modalCargando').modal('hide');
    $("#tablaSubServiceDetailList")
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

function urlregistrardetaillist(id){
    window.location='/subservice-register-list/'+utf8_to_b64(id)+'/v1/view';
}

function goListItemsSubService(id){
    window.location='/subservice-register-list/'+utf8_to_b64(id)+'/v1/main';
}

function urlbacktosubservice_detaillist(){
    /*if(interface=='main'){
        window.location= '/listsubservice-services/'+utf8_to_b64(idservice);
    }*/
    if(interface=='view'){
        window.location='/subservice-view-detaillist/'+utf8_to_b64(getidsubservice)+'/v1';
    }
}

function guardarListSubservicio(){
    var token= $('#token').val();

    var idsubservicio = $("#idsubservice").val();
    var titulo= $('#inputTitleListSubservice').val();
    var descripcion = $('#summernote').summernote('code');
    //console.log(descripcion);

    if (titulo == "") {
        $('#inputTitleListSubservice').focus();
        swal("Ingrese el Título de la Lista", "", "warning");
    } else if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else {
        if(puedeGuardarM(nameInterfaz) === 'si'){
        var element = document.querySelector('.savetitlesubservice');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');
        descripcion= descripcion.trim();

        var data = new FormData();
        data.append("idsubservicio", idsubservicio);
        data.append("titulo", titulo);
        data.append("descripcion", descripcion);

        setTimeout(() => {
            sendInforService(token, data, "/store_list_show_subservice", element); 
        }, 700);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

function interfaceupdateSubservicedetaillist(id){
    window.location='/subservice-updatedetail-list/'+utf8_to_b64(id)+'/v1';
}

function actualizarListSubservicio(){
    var token= $('#token').val();

    var idsubservicio = $("#idsubservice").val();
    var idlistitem= $('#idtablelist').val();
    var titulo= $('#inputEditTitleListSubservice').val();
    var descripcion = $('#summernoteEdit').summernote('code');
    //console.log(descripcion);

    if (titulo == "") {
        $('#inputEditTitleListSubservice').focus();
        swal("Ingrese el Título de la Lista", "", "warning");
    } else if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else {
        if(puedeActualizarM(nameInterfaz) === 'si'){
        var element = document.querySelector('.updatetitlesubservice');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');
        descripcion= descripcion.trim();

        var data = new FormData();
        data.append("idlistitem", idlistitem);
        data.append("idsubservicio", idsubservicio);
        data.append("titulo", titulo);
        data.append("descripcion", descripcion);

        setTimeout(() => {
            updateInforService(token, data, "/update_list_show_subservice", element); 
        }, 700);

        }else{
            swal('No tiene permiso para actualizar','','error');
        }
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

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function updateInforService(token, data, url, el){
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
                    text:'Información Actualizada',
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

/* FUNCION PARA INACTIVAR Subservicio File List */
function inactivarSubservicedetaillist(id, i){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
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
            url: "/in-activar-subserviciodetaillist",
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
                    var elementState= document.getElementById('TrSsList'+i).cells[3];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateSubservicedetaillist("+id+")'>"+
                        "<i class='far fa-edit mr-2'></i>"+
                        "Actualizar"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarSubservicedetaillist("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-2'></i>"+
                            "Inactivar"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarSubservicedetaillist("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-2'></i>"+
                                "Activar"+
                            "</button>";
                    }
                    html+="<button type='button' class='btn btn-danger btn-sm mr-3 btntable' title='Eliminar' onclick='deleteSubservicedetaillist("+id+")' >"+
                        "<i class='fas fa-trash mr-2'></i>"+
                        "Eliminar"+
                    "</button>"; 
                    var element= document.getElementById('TrSsList'+i).cells[4];
                    $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR Subservicio File List */
function activarSubservicedetaillist(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";

    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
        url: "/in-activar-subserviciodetaillist",
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
                var elementState= document.getElementById('TrSsList'+i).cells[3];
                $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateSubservicedetaillist("+id+")'>"+
                    "<i class='far fa-edit mr-2'></i>"+
                    "Actualizar"+
                "</button>";
                if(estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarSubservicedetaillist("+id+", "+i+")'>"+
                        "<i class='fas fa-eye-slash mr-2'></i>"+
                        "Inactivar"+
                    "</button>";
                }else if(estado=="0"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarSubservicedetaillist("+id+", "+i+")'>"+
                            "<i class='fas fa-eye mr-2'></i>"+
                            "Activar"+
                        "</button>";
                }
                html+="<button type='button' class='btn btn-danger btn-sm mr-3 btntable' title='Eliminar' onclick='deleteSubservicedetaillist("+id+")' >"+
                    "<i class='fas fa-trash mr-2'></i>"+
                    "Eliminar"+
                "</button>"; 
                var element= document.getElementById('TrSsList'+i).cells[4];
                $(element).html(html);
                }, 1500);
            } else if (res.resultado == false) {
                swal("No se pudo Inactivar", "", "error");
            }
        },
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ELIMINAR Subservicio Info Detail */
function deleteSubservicedetaillist(id){
    var token=$('#token').val();
    var html="";
    if(puedeEliminarM(nameInterfaz) === 'si'){
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
            url: "/delete-subserviciodetaillist",
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
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}
