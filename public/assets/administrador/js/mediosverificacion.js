var valueYearMV= '';
var datos= [];
var objeto= {};
var contarCampos=0;

function cargar_mediosv(name){
    var con = 1; var estadoItem='';
    var html =
        "<table class='table datatables' id='tablaMediosV'>" +
            "<thead class='thead-dark'>" +
                "<tr style='pointer-events:none;'>" +
                    "<th>N°</th>" +
                    "<th>Título</th>" +
                    "<th>N° Archivos</th>" +
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

        html +="<tr id='Tr"+i +"'>"+
            "<td style='text-align: center;'>"+con+"</td>"+
            "<td><span class='lugarNews'>"+v.titulo+"</td>"+
            "<td>"+v.num_files+"</td>" +
            "<td>"+estadoItem+"</td>" +
            "<td style='display: flex;'>"+
                "<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editMediosV("+v.id+")'>"+
                    "<i class='fas fa-pencil-alt'></i>"+
                "</button>";
                if(v.estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerMediosV("+v.id+", "+i+")'>"+
                        "<i class='fas fa-eye-slash'></i>"+
                    "</button>";
                }else if(v.estado=="0"){
                    html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarMediosV("+v.id+", "+i+")'>"+
                        "<i class='fas fa-eye'></i>"+
                    "</button>";
                }
            html+="</td>" +
        "</tr>";
        con++;
    });
    html += "</tbody></table>";
    $("#divMediosV").html(html);
    setTimeout(function(){
        $('#modalCargando').modal('hide');
        $("#tablaMediosV")
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

function urlregistrarmediov(){
    window.location= '/registrar_mediosv';
}

function urlback(){
    window.location='/medios-verificacion';
}

function removerMediosV(id, pos){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-success";
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
            url: "/in-activar-mediosv",
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
                    var elementState= document.getElementById('Tr'+pos).cells[3];
                    //$(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");
                    $(elementState).html(estadoItem);

                    html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editMediosV("+id+")'>"+
                        "<i class='fas fa-pencil-alt'></i>"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerMediosV("+id+", "+pos+")'>"+
                            "<i class='fas fa-eye-slash'></i>"+
                        "</button>";
                    }else if(estado=="0"){
                        html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarMediosV("+id+", "+pos+")'>"+
                            "<i class='fas fa-eye'></i>"+
                        "</button>";
                    }
                    var element= document.getElementById('Tr'+pos).cells[4];
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

function activarMediosV(id, pos){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-mediosv",
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
            var elementState= document.getElementById('Tr'+pos).cells[3];
            //$(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");
            $(elementState).html(estadoItem);

            html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editMediosV("+id+")'>"+
                "<i class='fas fa-pencil-alt'></i>"+
            "</button>";
            if(estado=="1"){
                html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerMediosV("+id+", "+pos+")'>"+
                    "<i class='fas fa-eye-slash'></i>"+
                "</button>";
            }else if(estado=="0"){
                html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarMediosV("+id+", "+pos+")'>"+
                    "<i class='fas fa-eye'></i>"+
                "</button>";
            }
            var element= document.getElementById('Tr'+pos).cells[4];
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

$('#selYearMediosV').on("change", function(e) {
    var lastValue = $(this).select2('data')[0].text;
    if(lastValue=="-Seleccione una Opción-"){
        valueYearMV='';
        $('#inputMTitle').val('');
    }else{
        valueYearMV= lastValue;
        $('#inputMTitle').val('AÑO '+valueYearMV);
    }
});

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
} 

function getvalues(el){
    var inps = document.getElementsByName('inputMedioV[]');
    var year= $('#selYearMediosV').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        for (var i = 0; i <inps.length; i++) {
            var inp=inps[i];
            if(inp.value!=''){
                let sinaccent= removeAccents(inp.value);
                let minuscula= sinaccent.toLowerCase();
                let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
                let cadena= cadenasinpoint.replaceAll(" ","_");
                datos.push({
                    "value" : inp.value,
                    "alias" : year+"_"+cadena
                })
                contarCampos++;
            }else{
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal('Por favor ingrese el título del documento','','warning');
                return;
            }
        }

        if(contarCampos==inps.length){
            objeto= datos;
            return true;
        }else{
            return false;
        }
    }else{
        el.removeAttribute("disabled");
        el.style.removeProperty("pointer-events");
        swal("Debe elegir el Año correspondiente...", "", "warning");
    }
}

function guardarMediosV(){
    var token= $('#token').val();
    let fileInput = document.getElementById("file");
    var year = $("#selYearMediosV :selected").val();
    var titulo = $("#inputMTitle").val();
    var lengimg = fileInput.files.length;

    if (year == "0") {
        $("#selYearMediosV").focus();
        swal("Seleccione el Año", "", "warning");
    }if (titulo == "") {
        $("#inputMTitle").focus();
        swal("No ha ingresado un título", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else{
        toastr.info("Generando alias a los documentos...", "!Aviso!");
        var element = document.querySelector('.savemediosv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";
        setTimeout(() => {
            var getresult= getvalues(element);
            if(getresult){
                if(puedeGuardarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');
                var data = new FormData(formMediosV);
                data.append("anio", year);
                data.append("objeto", JSON.stringify(objeto));
                setTimeout(() => {
                    sendNewMediosV(token, data, "/store-mediosv", element, '#modalFullSend'); 
                }, 900);
                }else{
                    swal('No tiene permiso para guardar','','error');
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                }
            }else{
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                swal('Ha ocurrido un error inesperado, recargue la página nuevamente','','error');
                return;
            }
        }, 900);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewMediosV(token, data, url, el, modalname){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    //xr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $(modalname).modal('show');
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Registro Guardado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    el.removeAttribute("disabled");
                    el.style.removeProperty("pointer-events");
                    //urlback();
                    window.location='/registrar_mediosv';
                },1500);
            }else if(myArr.resultado==false){
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                cleanObject();
                swal('No se pudo guardar el registro','','error');
            }else if (myArr.resultado == "nofile") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                cleanObject();
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                cleanObject();
                swal("Error al copiar los archivos", "", "error");
            }else if(myArr.resultado== "existe"){
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                cleanObject();
                swal("No se pudo Guardar", "El Registro ya se encuentra registrado.", "error");
            }
        }else if(xr.status === 400){
            $(modalname).modal('show');
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            cleanObject();
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

function cleanObject(){
    objeto= {};
    while(datos.length>0){
        datos.pop();
    }
}

function editMediosV(id){
    window.location='/edit-mediosv/'+id;
}

function eliminarFile(id, pos){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";
    if(puedeEliminarSM(nameInterfaz) === 'si'){
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
            url: "/in-activar-file-mediosv",
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
                        text: "Registro Eliminado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    var contador= res.count;
                    
                    setTimeout(function () {
                        if(contador==0){
                            drawNoData();
                        }else{
                            var eldivfile= document.getElementById('divpics'+pos);
                            if(eldivfile.classList.contains('noshow')){
                                eldivfile.classList.remove('noshow');
                            }else{
                                eldivfile.classList.add('noshow');
                            }
                        }
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
}

function verFile(id){
    var url= '/view-mediosv/'+id;
    window.open(url, '_BLANK');
    //window.location= url;
}

function updatefilesmediosv(e){
    e.preventDefault();
    contarCampos=0;
    cleanObject();
    var token= $('#token').val();
    let fileInput = document.getElementById("file");
    var idmv = $("#idmediosv").val();
    var lengimg = fileInput.files.length;

    if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else{
        toastr.info("Generando alias a los documentos...", "!Aviso!");
        var element = document.querySelector('.updatemediosv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";
        setTimeout(() => {
            var getresult= getvaluesEdit(element);
            if(getresult){
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSendEdit').modal('show');
                var data = new FormData(formEMediosV);
                data.append("idmv", idmv);
                data.append("objeto", JSON.stringify(objeto));
                setTimeout(() => {
                    sendNewMediosV(token, data, "/update-mediosv", element, '#modalFullSendEdit'); 
                }, 900);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                }
            }else{
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                swal('Ha ocurrido un error inesperado, recargue la página nuevamente','','error');
                return;
            }
        }, 900);
    }
}

function getvaluesEdit(el){
    var inps = document.getElementsByName('inputMedioV[]');
    var year= $('#selYearEditMediosv').select2('data')[0].text;

    for (var i = 0; i <inps.length; i++) {
        var inp=inps[i];
        if(inp.value!=''){
            let sinaccent= removeAccents(inp.value);
            let minuscula= sinaccent.toLowerCase();
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            datos.push({
                "value" : inp.value,
                "alias" : year+"_"+cadena
            })
            contarCampos++;
        }else{
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            swal('Por favor ingrese el título del documento','','warning');
            return;
        }
    }

    if(contarCampos==inps.length){
        objeto= datos;
        return true;
    }else{
        return false;
    }
}