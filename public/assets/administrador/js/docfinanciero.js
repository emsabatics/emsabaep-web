function showInfoFinanciero(){
    $('#modalCargando').modal('hide');
    $("#tablaDocFin")
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

function urlregistrardocfinanciero(){
    window.location='/registrar_doc_financiero';
}

function urlback(){
    window.location='/docfinanciero';
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function getAliasInput(){
    var year= $('#selYearDocFin').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputNameDocFin').val()!=''){
            var val= $('#inputNameDocFin').val();
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
    var year= $('#selYearDocFin').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputNameDocFin').val()!=''){
            var val= $('#inputNameDocFin').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            $('#inputAliasFileDocFin').val(year+"_"+cadena);
        }else{
            $('#inputNameDocFin').focus();
            toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
        }
    }else{
        $('#selYearDocFin').focus();
        toastr.info("Debe elegir Año correspondiente...", "!Aviso!");
        $('#inputAliasFileDocFin').val('');
    }
}

function guardarDocFin(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var year = $("#selYearDocFin :selected").val();
    var mes = $("#selMes :selected").val();
    var nombredoc= $('#inputNameDocFin').val();
    var aliasfile = $("#inputAliasFileDocFin").val();
    var lengimg = fileInput.files.length;
    var typefile= fileInput.files[0].type;
    //var titulo= $('#inputDocTitle').val();

    if (year == "0") {
        $("#selYearDocFin").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (nombredoc == "") {
        $('#inputNameDocFin').focus();
        swal("Ingrese el nombre del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileDocFin").focus();
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
            var element = document.querySelector('.savedocfin');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');

            var data = new FormData(formDocFin);
            data.append("anio", year);
            data.append("mes", mes);
            /*data.append("typefile", typefile);
            data.append("lengfile", lengimg);*/

            setTimeout(() => {
                sendNewDocFinanciero(token, data, "/store-doc-financiero", element); 
            }, 700);
            }else{
                swal('No tiene permiso para guardar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewDocFinanciero(token, data, url, el){
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
                    window.location='/registrar_doc_financiero';
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

function viewopenDocFin(id){
    window.location='/view-docfinanciero/'+utf8_to_b64(id);
}

/* FUNCION PARA INACTIVAR Documentación Financiera */
function inactivarDocFin(id, i){
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
            url: "/in-activar-docfinanciero",
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

                    html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenDocFin("+id+")'>"+
                        "<i class='fas fa-folder mr-2'></i>"+
                        "Ver"+
                    "</button>"+
                    "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateDocFin("+id+")'>"+
                        "<i class='far fa-edit mr-2'></i>"+
                        "Actualizar"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarDocFin("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-2'></i>"+
                            "Inactivar"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarDocFin("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-2'></i>"+
                                "Activar"+
                            "</button>";
                    }
                    html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar Rendición Cuentas' onclick='downloadDocFin("+id+")' >"+
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

/* FUNCION PARA ACTIVAR Documentación Financiera */
function activarDocFin(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-docfinanciero",
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

            html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenDocFin("+id+")'>"+
                "<i class='fas fa-folder mr-2'></i>"+
                "Ver"+
            "</button>"+
            "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateDocFin("+id+")'>"+
                "<i class='far fa-edit mr-2'></i>"+
                "Actualizar"+
            "</button>";
            if(estado=="1"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarDocFin("+id+", "+i+")'>"+
                "<i class='fas fa-eye-slash mr-2'></i>"+
                "Inactivar"+
            "</button>";
            }else if(estado=="0"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarDocFin("+id+", "+i+")'>"+
                "<i class='fas fa-eye mr-2'></i>"+
                "Activar"+
            "</button>";
            }
            html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar Rendición Cuentas' onclick='downloadDocFin("+id+")' >"+
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

function downloadDocFin(id){
    if(puedeDescargarM(nameInterfaz) === 'si'){
    window.location='/download-docfinanciero/'+id;
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function interfaceupdateDocFin(id){
    window.location= '/edit-docfinanciero/'+utf8_to_b64(id);
}

function generarAliasE(){
    //toastr.info("No se permite generar el Alias...", "!Aviso!");
    var year= $('#selYearEditDocFin').select2('data')[0].text;
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
        $('#selYearEditDocFin').focus();
        toastr.info("Debe elegir Año correspondiente...", "!Aviso!");
        $('#inputEAliasFile').val('');
    }
}

function getAliasE(){
    var year= $('#selYearEditDocFin').select2('data')[0].text;
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
    var element= document.getElementById('divfiledocfin');
    var eldivfile= document.getElementById('cardListDocFin');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isDocFinanciero= false;
    }
}

function actualizardocfin(){
    var token= $('#token').val();

    var id= $('#iddocfinanciero').val();
    let fileInput = document.getElementById("fileEdit");
    let aliasFileE= $('#inputEAliasFile').val();
    var lengimg = fileInput.files.length;

    if(isDocFinanciero==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else {
            if(puedeActualizarM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocfincancieroe);
            data.append("isDocFinanciero", isDocFinanciero);
            setTimeout(() => {
                sendUpdateDocFinanciero(token, data, "/update-docfinanciero"); 
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
            var data = new FormData(formdocfincancieroe);
            data.append("isDocFinanciero", isDocFinanciero);
            setTimeout(() => {
                sendUpdateDocFinanciero(token, data, "/update-docfinanciero");
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateDocFinanciero(token, data, url){
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

/* FUNCION PARA ELIMINAR PERMANENTEMENTE Documentación Financiera */
function eliminarpermdocfin(){
    var token=$('#token').val();
    var id= $('#iddocfinanciero').val();
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
                url: "/delete-docfinanciero",
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