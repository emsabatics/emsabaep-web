var npac='';

function showInfoRefPac(){
    $('#modalCargando').modal('hide');
    $("#tablaRefPAC")
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
                { className: "dt-head-center", targets: [1, 2, 3, 4, 5,6] },
                { width: 1, targets: 0 },
            ],
        });
}

function urlback(){
    window.location='/pac';
}

function viewopenRefPAC(id){
    var url= '/view-ref-pac/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

function urlback_ref(id){
    var url= '/view-reforma-pac/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

/* FUNCION PARA INACTIVAR PAC */
function inactivarRefPAC(id, i){
    var token=$('#token').val();
    var estado = "0";
    var tipo="ref";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";

    var code = $('#id_encriptado_item'+i).val();
    //code = "'"+code+"'";

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
            url: "/in-activar-pac",
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
                    var elementState= document.getElementById('Tr'+i).cells[6];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenRefPAC("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateRefPAC("+id+")'>"+
                        "<i class='far fa-edit mr-3'></i>"+
                        "Actualizar"+
                    "</a>";
                    if(estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarRefPAC("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-3'></i>"+
                            "Inactivar"+
                        "</a>";
                    }else if(estado=="0"){
                            html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarRefPAC("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+='<a class="btn btn-success btn-sm mt-2 mr-3" onclick="downloadRefPAC('+code+')" >'+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar PAC"+
                    "</a>"+
                    '<a class="btn btn-danger btn-sm mt-2 mr-3" onclick="downloadRefRA('+code+')" >'+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar Resolución"+
                    "</a>"; 
                    var element= document.getElementById('Tr'+i).cells[7];
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

/* FUNCION PARA ACTIVAR PAC */
function activarRefPAC(id, i){
    var token=$('#token').val();
    var estado = "1";
    var tipo="ref";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";

    var code = $('#id_encriptado_item'+i).val();
    //code = "'"+code+"'";

    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-pac",
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
            var elementState= document.getElementById('Tr'+i).cells[6];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenRefPAC("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>"+
            "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateRefPAC("+id+")'>"+
                "<i class='far fa-edit mr-3'></i>"+
                "Actualizar"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarRefPAC("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarRefPAC("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+='<a class="btn btn-success btn-sm mt-2 mr-3" onclick="downloadRefPAC('+code+')" >'+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar PAC"+
            "</a>"+
            '<a class="btn btn-danger btn-sm mt-2 mr-3" onclick="downloadRefRA('+code+')" >'+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar Resolución"+
            "</a>"; 
            var element= document.getElementById('Tr'+i).cells[7];
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

function downloadRefPAC(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-pac/'+id+'/ref';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function downloadRefRA(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-ra/'+id+'/ref';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function interfaceupdateRefPAC(id){
    window.location= '/edit-ref-pac/'+id;
}

function eliminarFile(e, option){
    e.preventDefault();
    if(option=="pac"){
        var element= document.getElementById('divfilepac');
        var eldivfile= document.getElementById('cardListPac');
        if(element.classList.contains('noshow')){
            element.classList.remove('noshow');
            eldivfile.classList.add('noshow');
            isPac= false;
        }
    }else if(option=="ra"){
        var element= document.getElementById('divfilera');
        var eldivfile= document.getElementById('cardListRa');
        if(element.classList.contains('noshow')){
            element.classList.remove('noshow');
            eldivfile.classList.add('noshow');
            isRa= false;
        }
    }
}

function generarAliasE(){
    var val= $('#inputEResolucion').val();
    let sinaccent= removeAccents(val);
    let minuscula= sinaccent.toLowerCase();
    //let cadenasinpoint= minuscula.replaceAll(".","");
    let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","_");
    $('#inputEAliasFileRA').val(cadena);
}

function actualizarPacRef(){
    var token= $('#token').val();

    let fileInput = document.getElementById("fileEdit");
    var year = $("#selYearEPac :selected").val();
    var titulo = $("#inputETitulo").val();
    var aliasfile = $("#inputEAliasFile").val();
    var observacion = $("#inputEObsr").val();
    var lengimg = fileInput.files.length;

    let fileInputR = document.getElementById("fileEra");
    var resolucion = $("#inputEResolucion").val();
    var aliasfilera= $('#inputEAliasFileRA').val();
    var lengimgr = fileInputR.files.length;

    if (year == "0") {
        $("#selYearEPac").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (titulo == "") {
        $("#inputETitulo").focus();
        swal("Ingrese un título a la noticia", "", "warning");
    } else if (aliasfile == "") {
        $("#inputEAliasFile").focus();
        swal("No se ha generado el alias del documento PAC", "", "warning");
    } else if (resolucion == "") {
        $("#inputEResolucion").focus();
        swal("Ingrese el número de la Resolución Administrativa", "", "warning");
    } else if (aliasfilera == "") {
        $("#inputEAliasFileRA").focus();
        swal("No se ha generado el alias del documento Resolución Administrativa", "", "warning");
    }else{
        if(isPac==false && isRa==false){
            if (lengimg == 0 ) {
                swal("No ha seleccionado un archivo para el PAC", "", "warning");
            } else if (lengimg > 1) {
                swal("Solo se permite un archivo", "", "warning");
            } else if (lengimgr == 0 ) {
                swal("No ha seleccionado un archivo para la RESOLUCIÓN ADMINISTRATIVA", "", "warning");
            } else if (lengimgr > 1) {
                swal("Solo se permite un archivo", "", "warning");
            }else{
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');
                observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

                var data = new FormData(formE_PAC);
                data.append("anio",year);
                data.append("ispac", isPac);
                data.append("isra", isRa);

                setTimeout(() => {
                    sendUpdatePac(token, data, "/update-ref-pac"); 
                }, 700);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else if(isPac==false && isRa==true){
            if (lengimg == 0 ) {
                swal("No ha seleccionado un archivo para el PAC", "", "warning");
            } else if (lengimg > 1) {
                swal("Solo se permite un archivo", "", "warning");
            }else{
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');
                observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

                var data = new FormData(formE_PAC);
                data.append("anio",year);
                data.append("ispac", isPac);
                data.append("isra", isRa);

                setTimeout(() => {
                    sendUpdatePac(token, data, "/update-ref-pac"); 
                }, 700);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else if(isPac==true && isRa==false){
            if (lengimgr == 0 ) {
                swal("No ha seleccionado un archivo para la RESOLUCIÓN ADMINISTRATIVA", "", "warning");
            } else if (lengimgr > 1) {
                swal("Solo se permite un archivo", "", "warning");
            }else{
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');
                observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

                var data = new FormData(formE_PAC);
                data.append("anio",year);
                data.append("ispac", isPac);
                data.append("isra", isRa);

                setTimeout(() => {
                    sendUpdatePac(token, data, "/update-ref-pac"); 
                }, 700);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else if(isPac==true && isRa==true){
            if(puedeActualizarSM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

            var data = new FormData(formE_PAC);
            data.append("anio",year);
            data.append("ispac", isPac);
            data.append("isra", isRa);

            setTimeout(() => {
                sendUpdatePac(token, data, "/update-ref-pac"); 
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdatePac(token, data, url){
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
                    text:'PAC '+npac+' Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    urlback_ref(code);
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "PAC "+npac+" ya se encuentra registrado.", "error");
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