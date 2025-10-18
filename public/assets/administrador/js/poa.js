var seltipo='general';
var valueSelYear='';
var npoa='';

function showInfoPoa(){
    $('#modalCargando').modal('hide');
    $("#tablaPOA")
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
                { className: "dt-head-center", targets: [1, 2, 3, 4, 5] },
            ],
        });
}

function urlregistrarpoa(){
    window.location='/registrar_poa';
}

function urlback(){
    window.location='/poa';
}

function seleccionarCheck(e){
    console.log(e.value);
    let val= e.value;
    if(val=="area"){
        seltipo='area';
        var element = document.getElementById('divselareapoa');
        element.style.display='block';
    }else if(val=="general"){
        seltipo='general';
        var element = document.getElementById('divselareapoa');
        element.style.display='none';
    }
}

$('#selYearPOA').on("change", function(e) {
    var lastValue = $(this).select2('data')[0].text;
    if(lastValue=="-Seleccione una Opción-"){
        valueSelYear='';
        npoa= '';
    }else{
        valueSelYear= lastValue;
        npoa= lastValue;
    }
});

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
} 

function generarAlias(){
    var val= $('#inputTitulo').val();
    if(val!=''){
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        $('#inputAliasFile').val(valueSelYear+"_"+cadena);
    }else{
        $('#inputAliasFile').val('');
    }
}

function getAliasUpdate(){
    var year = $("#selYearEPoa :selected").text();
    var val= $('#inputETitulo').val();
    let sinaccent= removeAccents(val);
    let minuscula= sinaccent.toLowerCase();
    //let cadenasinpoint= minuscula.replaceAll(".","");
    let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","_");
    return year+'_'+cadena;
}


function guardarPoa_original(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var year = $("#selYearPOA :selected").val();
    var area = $("#selArea :selected").val(); //SELECCIONAR AREA
    var titulo = $("#inputTitulo").val();
    var aliasfile = $("#inputAliasFile").val();
    var observacion = $("#inputObsr").val();
    var lengimg = fileInput.files.length;
    let id_area= area.substring(4, area.length);

    if(seltipo=="general"){
        if (year == "0") {
            $("#selYearPOA").focus();
            swal("Seleccione el Año", "", "warning");
        } else if (titulo == "") {
            $("#inputTitulo").focus();
            swal("Ingrese un título al POA", "", "warning");
        } else if (aliasfile == "") {
            $("#inputAliasFile").focus();
            swal("No se ha generado el alias del documento POA", "", "warning");
        }else{
            //$('#modalFullSend').modal('show');
            observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");
            var element = document.querySelector('.savepoa');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            var data = new FormData(formPOA);
            data.append("anio", year);
            data.append("area", id_area);
            data.append("seltipo", seltipo);

            setTimeout(() => {
                sendNewPoa(token, data, "/store-poa", element); 
            }, 700);
        }
    }else if(seltipo=="area"){
        if (year == "0") {
            $("#selYearPOA").focus();
            swal("Seleccione el Año", "", "warning");
        } else if (area == "0") {
            $("#selYearPOA").focus();
            swal("Seleccione el Área", "", "warning");
        } else if (titulo == "") {
            $("#inputTitulo").focus();
            swal("Ingrese un título al POA", "", "warning");
        } else if (aliasfile == "") {
            $("#inputAliasFile").focus();
            swal("No se ha generado el alias del documento POA", "", "warning");
        }else{
            //$('#modalFullSend').modal('show');
            observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");
            var element = document.querySelector('.savepoa');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            var data = new FormData(formPOA);
            data.append("anio", year);
            data.append("area", id_area);
            data.append("seltipo", seltipo);

            setTimeout(() => {
                sendNewPoa(token, data, "/store-poa", element); 
            }, 700);
        }
    }
}

function guardarPoa(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var year = $("#selYearPOA :selected").val();
    var titulo = $("#inputTitulo").val();
    var aliasfile = $("#inputAliasFile").val();
    var observacion = $("#inputObsr").val();
    var lengimg = fileInput.files.length;

    if (year == "0") {
        $("#selYearPOA").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (titulo == "") {
        $("#inputTitulo").focus();
        swal("Ingrese un título al POA", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFile").focus();
        swal("No se ha generado el alias del documento POA", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo para el POA", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else{
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");
        var element = document.querySelector('.savepoa');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formPOA);
        data.append("anio", year);
        data.append("seltipo", seltipo);

        setTimeout(() => {
            sendNewPoa(token, data, "/store-poa", element); 
        }, 700);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewPoa(token, data, url, el){
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
                    text:'POA '+npoa+' Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/poa';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "diferente") {
                swal("La selección actual es diferente a lo registrado para el año "+npoa+" - "+seltipo.toUpperCase(), "", "error");
            } else if (myArr.resultado == "onetime") {
                swal("La selección actual "+seltipo.toUpperCase()+" ya fue registrada", "", "error");
            } else if (myArr.resultado == "areaexist") {
                swal("No se pudo Guardar", "ÁREA "+seltipo.toUpperCase()+" - "+valueSelYear+" ya se encuentra registrado.", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "POA "+npoa+" ya se encuentra registrado.", "error");
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

function viewopenPOA(id){
    var url= '/view-poa/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

function interfaceupdatePOA(id){
    window.location= '/edit-poa/'+id;
}

/* FUNCION PARA INACTIVAR POA */
function inactivarPOA(id, i){
    var token=$('#token').val();
    var estado = "0";
    var tipo="noref";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
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
            url: "/in-activar-poa",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: id,
                estado: estado,
                tipo: tipo
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
                    var elementState= document.getElementById('Tr'+i).cells[4];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenPOA("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>";
                    if(reformaPOA > 0){
                    html+="<a class='btn btn-warning btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenRefPOA("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver Reformas"+
                    "</a>";
                    }
                    html+="<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdatePOA("+id+")'>"+
                        "<i class='far fa-edit mr-3'></i>"+
                        "Actualizar"+
                    "</a>";
                    if(estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarPOA("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-3'></i>"+
                            "Inactivar"+
                        "</a>";
                    }else if(estado=="0"){
                            html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarPOA("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+='<a class="btn btn-success btn-sm mt-2 mr-3" onclick="downloadPOA('+code+')" >'+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar POA"+
                    "</a>"; 
                    var element= document.getElementById('Tr'+i).cells[5];
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

/* FUNCION PARA ACTIVAR POA */
function activarPOA(id, i){
    var token=$('#token').val();
    var estado = "1";
    var tipo="noref";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-poa",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: id,
        estado: estado,
        tipo: tipo
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
            var elementState= document.getElementById('Tr'+i).cells[4];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenPOA("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>";
            if(reformaPOA > 0){
            html+="<a class='btn btn-warning btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenRefPOA("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver Reformas"+
            "</a>";
            }
            html+="<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdatePOA("+id+")'>"+
                "<i class='far fa-edit mr-3'></i>"+
                "Actualizar"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarPOA("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarPOA("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+='<a class="btn btn-success btn-sm mt-2 mr-3" onclick="downloadPOA('+code+')" >'+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar POA"+
            "</a>"; 
            var element= document.getElementById('Tr'+i).cells[5];
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

function downloadPOA(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-poa/'+id+'/noref';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

/* FUNCION QUE LIMPIA EL INPUT FILE */
function limpiarFile(fileInput) {
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

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfilepoa');
    var eldivfile= document.getElementById('cardListPoa');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isPoa= false;
    }
}

function getChecked(){
    var checkBox = document.getElementById("checkReforma");
    if (checkBox.checked == true){
        isReforma= true;
    }else{
        isReforma= false;
    }
}

function actualizarpoa(){
    var token= $('#token').val();

    let fileInput = document.getElementById("fileEdit");
    var year = $("#selYearEPoa :selected").val();
    var titulo = $("#inputETitulo").val();
    var aliasfile = $("#inputEAliasFile").val();
    var observacion = $("#inputEObsr").val();
    var lengimg = fileInput.files.length;

    if (year == "0") {
        $("#selYearEPoa").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (titulo == "") {
        $("#inputETitulo").focus();
        swal("Ingrese un título", "", "warning");
    } else if (aliasfile == "") {
        $("#inputEAliasFile").focus();
        swal("No se ha generado el alias del documento POA", "", "warning");
    } else {
        if(aliasfile== getAliasUpdate()){

        if(isPoa==false){
            if (lengimg == 0 ) {
                swal("No ha seleccionado un archivo para el POA", "", "warning");
            } else if (lengimg > 1) {
                swal("Solo se permite un archivo", "", "warning");
            } else {
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');
                observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

                var data = new FormData(formE_POA);
                data.append("anio",year);
                data.append("ispoa", isPoa);
                data.append("isReforma", isReforma);

                setTimeout(() => {
                    sendUpdatePoa(token, data, "/update-poa"); 
                }, 700);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else{
            if(puedeActualizarSM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

            var data = new FormData(formE_POA);
            data.append("anio",year);
            data.append("ispoa", isPoa);
            data.append("isReforma", isReforma);

            setTimeout(() => {
                sendUpdatePoa(token, data, "/update-poa");
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
        }else{
            swal('Revise el alias del Documento', ' ','warning');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdatePoa(token, data, url){
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
                    text:'POA '+npoa+' Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/poa';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "POA "+npoa+" ya se encuentra registrado.", "error");
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

function generarAliasE(){
    var year = $("#selYearEPoa :selected").text();
    var val= $('#inputETitulo').val();
    let sinaccent= removeAccents(val);
    let minuscula= sinaccent.toLowerCase();
    //let cadenasinpoint= minuscula.replaceAll(".","");
    let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","_");
    $('#inputEAliasFile').val(year+'_'+cadena);
}

function viewopenRefPOA(id){
    var url= '/view-reforma-poa/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}