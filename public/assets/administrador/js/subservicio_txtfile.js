/* -------------------------------------------------------------------------------------------------- */
//                               CONFIGURACION TEXTO Y ARCHIVOS
/* -------------------------------------------------------------------------------------------------- */
var currentValueFiles = 0;
var currentValueEditFiles = 0;

function urlbackservicio(){
    window.location= '/listsubservice-services/'+utf8_to_b64(idservice);
}

function viewFileSubService(id){
    window.location='/subservice-view-filelist/'+utf8_to_b64(id)+'/v1';
}

function goFileSubService(id){
    window.location='/subservice-file-list/'+utf8_to_b64(id)+'/v1/main';
}

function urlregistrarfilelist(id){
    window.location='/subservice-file-list/'+utf8_to_b64(id)+'/v1/view';
}

function showDetailTxtFile(){
    $('#modalCargando').modal('hide');
    $("#tablaSubServiceTxtFile")
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

/* FUNCION QUE OBTIENE EL VALOR ELEGIDO DE LOS RADIO BUTTON */
function handleClick(myRadio) {
    currentValueFiles = myRadio.value;
}

function handleClickEdit(myRadio) {
    currentValueEditFiles = myRadio.value;
}

function guardarTextWithFileSubservicio(){
    var token= $('#token').val();

    var idsubservicio = $("#idsubservice").val();
    var descripcion = $('#summernote').summernote('code');
    var radioValue = currentValueFiles;
    //console.log(descripcion);

    let fileInput = document.getElementById("file");
    var lengimg = fileInput.files.length;

    if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else if (radioValue == '' || radioValue == '0') {
        swal("No ha seleccionado la posición del archivo", "", "warning");
    } else if (lengimg == 0) {
        swal("No ha seleccionado algún archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Debe elegir solo un archivo", "", "warning");
    } else {
        var typefilesel= fileInput.files[0].type;
        let typepdf= 'application/pdf';
        let typeimg= 'image/';
        var tipofile='';

        if(typefilesel.includes(typepdf)){
            tipofile='pdf';
        }else if(typefilesel.includes(typeimg)){
            tipofile='image';
        }

        if(puedeGuardarM(nameInterfaz) === 'si'){
        var element = document.querySelector('.savetextsubservice');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');
        descripcion= descripcion.trim();

        var data = new FormData(formTextFileSubServicio);
        data.append("idsubservicio", idsubservicio);
        data.append("descripcion", descripcion);
        data.append("posicion", radioValue);
        data.append("tipo_file", tipofile);

        setTimeout(() => {
            sendTextFileService(token, data, "/store_text_file_subservice", element); 
        }, 700);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendTextFileService(token, data, url, el){
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

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function getAliasInput(){
    if($('#inputNameDocAdj').val()!=''){
        var val= $('#inputNameDocAdj').val();
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        return "service_"+cadena;
    }else{
        return "";
    }
}

function generarAlias(){
    if($('#inputNameDocAdj').val()!=''){
        var val= $('#inputNameDocAdj').val();
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        $('#inputAliasFileDocAdj').val("service_"+cadena);
    }else{
        $('#inputNameDocAdj').focus();
        toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
    }
}

function guardarFilesSubservicio(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var idsubservicio= $('#idsubservice').val();
    var nombredoc= $('#inputNameDocAdj').val();
    var aliasfile = $("#inputAliasFileDocAdj").val();
    var lengimg = fileInput.files.length;
    var typefile= fileInput.files[0].type;
    //var titulo= $('#inputDocTitle').val();

    if (nombredoc == "") {
        $('#inputNameDocAdj').focus();
        swal("Ingrese el nombre del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileDocAdj").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else {
        //console.log(aliasfile, getAliasInput());
        if(aliasfile!=getAliasInput()){
            swal('Revise el alias del documento','','warning');
        }else{
            var element = document.querySelector('.savedocsadj');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');

            var data = new FormData(formDocAdmin);
            data.append("idsubservicio", idsubservicio);
            /*data.append("typefile", typefile);
            data.append("lengfile", lengimg);*/

            setTimeout(() => {
                sendNewDocSubservice(token, data, "/store-doc-subservice", element); 
            }, 700);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewDocSubservice(token, data, url, el){
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
                    text:'Documento Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    //urlback();
                    window.location= window.location.href;
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "Documento ya se encuentra registrado.", "error");
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

function urlbacktosubservice_filelist(){
    /*if(interface=='main'){
        window.location= '/listsubservice-services/'+utf8_to_b64(idservice);
    }*/
    if(interface=='view'){
        window.location='/subservice-view-filelist/'+utf8_to_b64(getidsubservice)+'/v1';
    }
}

/* FUNCION PARA INACTIVAR Subservicio File List */
function inactivarSubservicefilelist(id, i){
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
            url: "/in-activar-subserviciofilelist",
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

                    html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateSubservicefilelist("+id+")'>"+
                        "<i class='far fa-edit mr-2'></i>"+
                        "Actualizar"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarSubservicefilelist("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-2'></i>"+
                            "Inactivar"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarSubservicefilelist("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-2'></i>"+
                                "Activar"+
                            "</button>";
                    }
                    html+="<button type='button' class='btn btn-danger btn-sm mr-3 btntable' title='Eliminar' onclick='deleteSubservicefilelist("+id+")' >"+
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
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR Subservicio File List */
function activarSubservicefilelist(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
        url: "/in-activar-subserviciofilelist",
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

                html+="<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateSubservicefilelist("+id+")'>"+
                    "<i class='far fa-edit mr-2'></i>"+
                    "Actualizar"+
                "</button>";
                if(estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarSubservicefilelist("+id+", "+i+")'>"+
                        "<i class='fas fa-eye-slash mr-2'></i>"+
                        "Inactivar"+
                    "</button>";
                }else if(estado=="0"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarSubservicefilelist("+id+", "+i+")'>"+
                            "<i class='fas fa-eye mr-2'></i>"+
                            "Activar"+
                        "</button>";
                }
                html+="<button type='button' class='btn btn-danger btn-sm mr-3 btntable' title='Eliminar' onclick='deleteSubservicefilelist("+id+")' >"+
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
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ELIMINAR Subservicio Info Detail */
function deleteSubservicefilelist(id){
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
            url: "/delete-subserviciofilelist",
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

function interfaceupdateSubservicefilelist(id){
    window.location='/subservice-updatedetail-filelist/'+utf8_to_b64(id)+'/v1';
}

function downloadFileListSubService(id){
    if(puedeDescargarM(nameInterfaz) === 'si'){
    var url='/download-archivo-subservice/'+id+'/filetext';
    //window.open(url, '_blank');
    window.location= url;
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

/* FUNCION QUE ACTUALIZA EL ARCHIVO DEL SUBSERVICIO */
function updatefilesubservicefilelist(e){
    e.preventDefault();
    var token= $('#token').val();
    let fileInput = document.getElementById("fileImgEdit");
    //var idnoti= $('#idnoticiapics').val();
    var lengimg = fileInput.files.length;
    if (lengimg > 0) {
        //var radioValue = currentValueEditFiles;
        var radioValue= $('input[name="customRadio"]:checked').val();
        var typefilesel= fileInput.files[0].type;
        let typepdf= 'application/pdf';
        let typeimg= 'image/';
        var tipofile='';

        if(typefilesel.includes(typepdf)){
            tipofile='pdf';
        }else if(typefilesel.includes(typeimg)){
            tipofile='image';
        }

        if (radioValue == '' || radioValue == '0') {
            swal("No ha seleccionado la posición del archivo", "", "warning");
        } else if (lengimg == 0) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite subir un archivo", "", "warning");
        } else {
            if(puedeActualizarM(nameInterfaz) === 'si'){
            var element= document.querySelector('.btnupfilelisT');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');
            
            var data = new FormData(formUpFileSubServiceFileList);
            data.append("num_img", lengimg);
            data.append("posicion", radioValue);
            data.append("tipo_file", tipofile);
            setTimeout(() => {
                sendUpdatePics(token, data, "/actualizar-subservice-file-filelist", element);
            }, 900);
            }else{
                swal('No tiene permiso para realizar esta acción','','error');
            }
        }
    }else{
        var idsubserviciofile= $('#idsubserviciofile').val();
        var radioValue= $('input[name="customRadio"]:checked').val();
        if (radioValue == '' || radioValue == '0') {
            swal("No ha seleccionado la posición del archivo", "", "warning");
        }else{
            if(puedeActualizarM(nameInterfaz) === 'si'){
            var element= document.querySelector('.btnupfilelisT');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');
            
            var data = new FormData();
            data.append("idsubserviciofile", idsubserviciofile);
            data.append("posicion", radioValue);
            setTimeout(() => {
                sendUpdatePics(token, data, "/actualizar-subservice-positionfile-filelist", element);
            }, 900);
            }else{
                swal('No tiene permiso para realizar esta acción','','error');
            }
        }
    }
}

function actualizarDetalleFileListSubservicio(){
    var token= $('#token').val();
    var descripcion=$('#summernoteeditfilelist').summernote('code');
    descripcion= descripcion.trim();

    if(descripcion=='<p><br></p>'){
        swal('Por favor ingrese la Información','','warning');
    } else {
        if(puedeActualizarM(nameInterfaz) === 'si'){
        var element= document.querySelector('.updatetextfilelist');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');
            
        var data = new FormData(formUpdateInforFileListSubServicio);
        data.append("descripcion", descripcion);
        setTimeout(() => {
            sendUpdatePics(token, data, "/actualizar-subservice-textfilelist", element);
        }, 900);
        }else{
            swal('No tiene permiso para realizar esta acción','','error');
        }
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