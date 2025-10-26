
function showInfoPliego(){
    $('#modalCargando').modal('hide');
    $("#tablaPliego")
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
                { width: 40, targets: 1, className: "text-center" },
                { className: "dt-head-center", targets: [1, 2, 3, 4] },
                { width: 1, targets: 0 },
            ],
        });
}

function urlregistrarpliego(){
    window.location='/registrar_pliego';
}

function urlback(){
    window.location='/pliego-tarifario';
}

function guardarPliego(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var descripcion = $("#inputObsr").val();
    var aliasfile = $("#inputAliasFile").val();
    var lengimg = fileInput.files.length;
    var typefilesel= fileInput.files[0].type;
    let typepdf= 'application/pdf';
    let typeimg= 'image/';
    var tipofyle='';

    if(typefilesel.includes(typepdf)){
        tipofyle='pdf';
    }else if(typefilesel.includes(typeimg)){
        tipofyle='image';
    }

    if (descripcion == "") {
        $("#inputObsr").focus();
        swal("Ingrese una Descripción al Documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFile").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo para el Pliego Tarifario", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else{
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
        var element = document.querySelector('.savepliego');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formPliego);
        data.append("tipo_file", tipofyle);

        setTimeout(() => {
            sendNewPliego(token, data, "/store-pliego", element); 
        }, 700);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewPliego(token, data, url, el){
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
                    text:'Pliego Tarifario Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/pliego-tarifario';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else  if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "Pliego ya se encuentra registrado.", "error");
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

function viewopenPliego(id){
    var url= '/view-pliego/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

function interfaceupdatePliego(id){
    window.location= '/edit-pliego/'+id;
}

/* FUNCION PARA INACTIVAR PLIEGO */
function inactivarPliego(id, i){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";
    var code = $('#iddocumento'+i).val();
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
            url: "/in-activar-pliego",
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
                    var elementState= document.getElementById('Tr'+i).cells[4];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenPliego("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdatePliego("+id+")'>"+
                        "<i class='far fa-edit mr-3'></i>"+
                        "Actualizar"+
                    "</a>";
                    if(estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarPliego("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-3'></i>"+
                            "Inactivar"+
                        "</a>";
                    }else if(estado=="0"){
                            html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarPliego("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+='<a class="btn btn-success btn-sm mt-2 mr-3" title="Descargar Documento" onclick="downloadPliego('+code+')" >'+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar Pliego"+
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

/* FUNCION PARA ACTIVAR PLIEGO */
function activarPliego(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    var code = $('#iddocumento'+i).val();
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-pliego",
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
            var elementState= document.getElementById('Tr'+i).cells[4];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenPliego("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>"+
            "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdatePliego("+id+")'>"+
                "<i class='far fa-edit mr-3'></i>"+
                "Actualizar"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarPliego("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarPliego("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+='<a class="btn btn-success btn-sm mt-2 mr-3" title="Descargar Documento" onclick="downloadPliego('+code+')" >'+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar Pliego"+
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

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfilepliego');
    var eldivfile= document.getElementById('cardListPliego');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isPliego= false;
    }
}

function actualizarpliego(){
    var token= $('#token').val();

    let fileInput = document.getElementById("fileEdit");
    var descripcion = $("#inputEObsr").val();
    var aliasfile = $("#inputAliasFileE").val();
    var lengimg = fileInput.files.length;

    if (descripcion == "") {
        $("#inputEObsr").focus();
        swal("Ingrese una Descripción", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileE").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else {
        if(isPliego==false){
            if (lengimg == 0 ) {
                swal("No ha seleccionado un archivo", "", "warning");
            } else if (lengimg > 1) {
                swal("Solo se permite un archivo", "", "warning");
            } else {
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');
                var typefilesel= fileInput.files[0].type;
                let typepdf= 'application/pdf';
                let typeimg= 'image/';
                var tipofyle='';

                if(typefilesel.includes(typepdf)){
                    tipofyle='pdf';
                }else if(typefilesel.includes(typeimg)){
                    tipofyle='image';
                }

                var data = new FormData(formE_Pliego);
                data.append("tipo_file", tipofyle);
                data.append("ispliego", isPliego);

                setTimeout(() => {
                    sendUpdatePliego(token, data, "/update-pliego"); 
                }, 700);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else if(isPliego==true){
            if(puedeActualizarSM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            var data = new FormData(formE_Pliego);
            data.append("tipo_file", tipofyle);
            data.append("ispliego", isPliego);

            setTimeout(() => {
                sendUpdatePliego(token, data, "/update-pliego"); 
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdatePliego(token, data, url){
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
                    text:'Pliego Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/pliego-tarifario';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "Pliego Tarifario ya se encuentra registrado.", "error");
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

function downloadPliego(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-pliego/'+id;
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}