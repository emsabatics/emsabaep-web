var seltipo='general';
var valueSelYear='';
var npoa='';

function showInfoRefPoa(){
    $('#modalCargando').modal('hide');
    $("#tablaRefPOA")
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

function urlback(){
    window.location='/poa';
}

function urlback_ref(id){
    var url= '/view-reforma-poa/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

function viewopenRefPOA(id){
    var url= '/view-ref-poa/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

/* FUNCION PARA INACTIVAR POA */
function inactivarRefPOA(id, i){
    var token=$('#token').val();
    var estado = "0";
    var tipo="ref";
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

                    html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenRefPOA("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateRefPOA("+id+")'>"+
                        "<i class='far fa-edit mr-3'></i>"+
                        "Actualizar"+
                    "</a>";
                    if(estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarRefPOA("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-3'></i>"+
                            "Inactivar"+
                        "</a>";
                    }else if(estado=="0"){
                            html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarRefPOA("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+="<a class='btn btn-success btn-sm mt-2 mr-3' onclick='downloadRefPOA("+id+")' >"+
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
function activarRefPOA(id, i){
    var token=$('#token').val();
    var estado = "1";
    var tipo="ref";
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

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenRefPOA("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>"+
            "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateRefPOA("+id+")'>"+
                "<i class='far fa-edit mr-3'></i>"+
                "Actualizar"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarRefPOA("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarRefPOA("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+="<a class='btn btn-success btn-sm mt-2 mr-3' onclick='downloadRefPOA("+id+")' >"+
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

function interfaceupdateRefPOA(id){
    window.location= '/edit-ref-poa/'+id;
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

function actualizarrefpoa(){
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
        if(isPoa==false){
            if (lengimg == 0 ) {
                swal("No ha seleccionado un archivo para el POA", "", "warning");
            } else if (lengimg > 1) {
                swal("Solo se permite un archivo", "", "warning");
            } else {
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');
                observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

                var data = new FormData(formE_POARef);
                data.append("anio",year);
                data.append("ispoa", isPoa);

                setTimeout(() => {
                    sendUpdateRefPoa(token, data, "/update-ref-poa"); 
                }, 700);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else{
            if(puedeActualizarSM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");

            var data = new FormData(formE_POARef);
            data.append("anio",year);
            data.append("ispoa", isPoa);

            setTimeout(() => {
                sendUpdateRefPoa(token, data, "/update-ref-poa");
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateRefPoa(token, data, url){
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
                    urlback_ref(code);
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

function downloadRefPOA(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-poa/'+id+'/ref';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}