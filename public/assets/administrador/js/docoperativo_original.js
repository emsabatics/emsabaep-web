function showInfoOperativo(){
    $('#modalCargando').modal('hide');
    $("#tablaDocOper")
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

function urlregistrardocoperativo(){
    window.location='/registrar_doc_operativo';
}

function urlback(){
    window.location='/docoperativo';
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function getAliasInput(){
    var year= $('#selYearDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputNameDocOper').val()!=''){
            var val= $('#inputNameDocOper').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            return year+"_"+cadena;
        }else{
            return "";
        }
    }else{
        return "";
    }
}

function generarAlias(){
    var year= $('#selYearDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputNameDocOper').val()!=''){
            var val= $('#inputNameDocOper').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            $('#inputAliasFileDocOper').val(year+"_"+cadena);
        }else{
            $('#inputNameDocOper').focus();
            toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
        }
    }else{
        $('#selYearDocOper').focus();
        toastr.info("Debe elegir Año correspondiente...", "!Aviso!");
        $('#inputAliasFileDocOper').val('');
    }
}

function guardarDocOper(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var year = $("#selYearDocOper :selected").val();
    var mes = $("#selMes :selected").val();
    var nombredoc= $('#inputNameDocOper').val();
    var aliasfile = $("#inputAliasFileDocOper").val();
    var lengimg = fileInput.files.length;
    var typefile= fileInput.files[0].type;
    //var titulo= $('#inputDocTitle').val();

    if (year == "0") {
        $("#selYearDocOper").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (nombredoc == "") {
        $('#inputNameDocOper').focus();
        swal("Ingrese el nombre del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileDocOper").focus();
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
            if(puedeGuardarM(nameInterfaz) === 'si'){
            var element = document.querySelector('.savedocoper');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');

            var data = new FormData(formDocOper);
            data.append("anio", year);
            data.append("mes", mes);
            /*data.append("typefile", typefile);
            data.append("lengfile", lengimg);*/

            setTimeout(() => {
                sendNewDocOperativo(token, data, "/store-doc-operativo", element); 
            }, 700);
            }else{
                swal('No tiene permiso para guardar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewDocOperativo(token, data, url, el){
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
                    window.location='/registrar_doc_operativo';
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

function viewopenDocOper(id){
    window.location='/view-docoperativo/'+utf8_to_b64(id);
}

/* FUNCION PARA INACTIVAR Documentación Operativa */
function inactivarDocOper(id, i){
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
            url: "/in-activar-docoperativo",
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

                    html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenDocOper("+id+")'>"+
                        "<i class='fas fa-folder mr-2'></i>"+
                        "Ver"+
                    "</button>"+
                    "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateDocOper("+id+")'>"+
                        "<i class='far fa-edit mr-2'></i>"+
                        "Actualizar"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarDocOper("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-2'></i>"+
                            "Inactivar"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarDocOper("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-2'></i>"+
                                "Activar"+
                            "</button>";
                    }
                    html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar Rendición Cuentas' onclick='downloadDocOper("+id+")' >"+
                        "<i class='fas fa-download mr-2'></i>"+
                        "Descargar Documento"+
                    "</button>"; 
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
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR Documentación Operativa */
function activarDocOper(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-docoperativo",
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

            html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenDocOper("+id+")'>"+
                "<i class='fas fa-folder mr-2'></i>"+
                "Ver"+
            "</button>"+
            "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateDocOper("+id+")'>"+
                "<i class='far fa-edit mr-2'></i>"+
                "Actualizar"+
            "</button>";
            if(estado=="1"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarDocOper("+id+", "+i+")'>"+
                "<i class='fas fa-eye-slash mr-2'></i>"+
                "Inactivar"+
            "</button>";
            }else if(estado=="0"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarDocOper("+id+", "+i+")'>"+
                "<i class='fas fa-eye mr-2'></i>"+
                "Activar"+
            "</button>";
            }
            html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar Rendición Cuentas' onclick='downloadDocOper("+id+")' >"+
                "<i class='fas fa-download mr-2'></i>"+
                "Descargar Documento"+
            "</button>";
            var element= document.getElementById('Tr'+i).cells[4];
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

function downloadDocOper(id){
    if(puedeDescargarM(nameInterfaz) === 'si'){
    window.location='/download-docoperativo/'+id;
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function interfaceupdateDocOper(id){
    window.location= '/edit-docoperativo/'+utf8_to_b64(id);
}

function generarAliasE(){
    //toastr.info("No se permite generar el Alias...", "!Aviso!");
    var year= $('#selYearEditDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputEDocTitle').val()!=''){
            var val= $('#inputEDocTitle').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            $('#inputEAliasFile').val(year+"_"+cadena);
        }else{
            $('#inputEDocTitle').focus();
            toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
        }
    }else{
        $('#selYearEditDocOper').focus();
        toastr.info("Debe elegir Año correspondiente...", "!Aviso!");
        $('#inputEAliasFile').val('');
    }
}

function getAliasE(){
    var year= $('#selYearEditDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputEDocTitle').val()!=''){
            var val= $('#inputEDocTitle').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            return year+"_"+cadena;
        }else{
            return ' ';
        }
    }else{
        return ' ';
    }
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfiledocoper');
    var eldivfile= document.getElementById('cardListDocOper');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isDocOperativo= false;
    }
}

function actualizardocoper(){
    var token= $('#token').val();

    var id= $('#iddocoperativo').val();
    let fileInput = document.getElementById("fileEdit");
    let aliasFileE= $('#inputEAliasFile').val();
    var lengimg = fileInput.files.length;

    if(isDocOperativo==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else {
            if(puedeActualizarM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocoperativoe);
            data.append("isDocOperativo", isDocOperativo);
            setTimeout(() => {
                sendUpdateDocOperativo(token, data, "/update-docoperativo"); 
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }else{
        if(aliasFileE!=getAliasE()){
            swal("Revise el alias del documento", "", "warning");
        }else{
            if(puedeActualizarM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocoperativoe);
            data.append("isDocOperativo", isDocOperativo);
            setTimeout(() => {
                sendUpdateDocOperativo(token, data, "/update-docoperativo");
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateDocOperativo(token, data, url){
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
                    text:'Documento Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    urlback();
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
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

/* FUNCION PARA ELIMINAR PERMANENTEMENTE Documentación Operativa */
function eliminarpermdocoper(){
    var token=$('#token').val();
    var id= $('#iddocoperativo').val();
    if(puedeEliminarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea eliminar permanentemente este registro?",
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
                url: "/delete-docoperativo",
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
                            urlback();
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