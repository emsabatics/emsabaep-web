function showInfoLey(){
    $('#modalCargando').modal('hide');
    $("#tablaLEY")
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

function urlregistrarley(){
    window.location='/registrar_ley';
}

function urlback(){
    window.location='/leyes';
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function generarAlias(){
    var val= $('#inputName').val();
    let sinaccent= removeAccents(val);
    let minuscula= sinaccent.toLowerCase();
    //let cadenasinpoint= minuscula.replaceAll(".","");
    let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","_");
    $('#inputAliasFileLey').val(cadena);
}

function generarAliasE(){
    var val= $('#inputEName').val();
    let sinaccent= removeAccents(val);
    let minuscula= sinaccent.toLowerCase();
    //let cadenasinpoint= minuscula.replaceAll(".","");
    let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","_");
    $('#inputAliasFileLeyE').val(cadena);
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfileley');
    var elcardfile= document.getElementById('cardListLey');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        elcardfile.classList.add('noshow');
        isLey= false;
    }
}

function guardarLey(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var name = $("#inputName").val();
    var aliasfile = $("#inputAliasFileLey").val();
    var lengimg = fileInput.files.length;

    if (name == "") {
        $("#inputName").focus();
        swal("Ingrese un título a la noticia", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileLey").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else{
        //observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");
        var element = document.querySelector('.saveley');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formLEY);

        setTimeout(() => {
            sendNewLey(token, data, "/store-ley", element); 
        }, 700);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewLey(token, data, url, el){
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
                    //window.location = '/leyes';
                    window.location= '/registrar_ley';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "El documento ya se encuentra registrado.", "error");
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

function viewopenLey(id){
    var url= '/view-ley/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

function interfaceupdateLey(id){
    window.location= '/edit-ley/'+id;
}

function actualizarLey(){
    var token= $('#token').val();

    let fileInput = document.getElementById("fileEdit");
    var titulo = $("#inputEName").val();
    var aliasfile = $("#inputAliasFileLeyE").val();
    var lengimg = fileInput.files.length;

    if (titulo == "") {
        $("#inputEName").focus();
        swal("Ingrese un título del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileLeyE").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else {
        if(isLey==false){
            if (lengimg == 0 ) {
                swal("No ha seleccionado un archivo", "", "warning");
            } else if (lengimg > 1) {
                swal("Solo se permite un archivo", "", "warning");
            } else {
                $('#modalFullSend').modal('show');

                var data = new FormData(formLEY_e);
                data.append("isley", isLey);

                setTimeout(() => {
                    sendUpdateLey(token, data, "/update-ley"); 
                }, 700);
            }
        }else{
            $('#modalFullSend').modal('show');

            var data = new FormData(formLEY_e);
            data.append("isley", isLey);

            setTimeout(() => {
                sendUpdateLey(token, data, "/update-ley");
            }, 700);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateLey(token, data, url){
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
                    window.location = '/leyes';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "El documento ya se encuentra registrado.", "error");
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

/* FUNCION PARA INACTIVAR REGLAMENTO */
function inactivarLey(id, i){
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
            url: "/in-activar-ley",
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

                    html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenLey("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateLey("+id+")'>"+
                        "<i class='far fa-edit mr-3'></i>"+
                        "Actualizar"+
                    "</a>";
                    if(estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarLey("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-3'></i>"+
                            "Inactivar"+
                        "</a>";
                    }else if(estado=="0"){
                            html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarLey("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+="<a class='btn btn-success btn-sm mt-2 mr-3' onclick='downloadLey("+id+")' >"+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar Documento"+
                    "</a>"; 
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

/* FUNCION PARA ACTIVAR REGLAMENTO */
function activarLey(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
      url: "/in-activar-ley",
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

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenLey("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>"+
            "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateLey("+id+")'>"+
                "<i class='far fa-edit mr-3'></i>"+
                "Actualizar"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarLey("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarLey("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+="<a class='btn btn-success btn-sm mt-2 mr-3' onclick='downloadLey("+id+")' >"+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar Documento"+
            "</a>"; 
            var element= document.getElementById('Tr'+i).cells[4];
            $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
}

function downloadLey(id){
    window.location='/download-ley/'+id;
}