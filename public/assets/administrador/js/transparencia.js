var $summernote = $('#summernote');
var isCodeView;
var currentValue = 0;
var isDataLey= false;
var arrayLey= [];

$summernote.on('summernote.codeview.toggled', () => {
    isCodeView = $('.note-editor').hasClass('codeview');
});

$("#summernote").on('summernote.blur', function () {
    $('#summernote').html($('#summernote').summernote('code'));
});
  

function NoguardarTransparencia(){
    /*if (isCodeView == true) {
        $summernote.summernote('codeview.toggle');
    }
    var body = $summernote.summernote('code');*/
    var token= $('#token').val();
    var value=$('#summernote').summernote('code');
    value= value.trim();

    let longitud= value.length;

    if(longitud < 9000){
        arrayLey.push(value.trim());
    }else if(longitud >= 9000 && longitud <= 19000){
        let divArray= Math.round(longitud / 2);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            start= i;
            end= start+divArray;
            arrayLey.push(value.substring(start, end).trim());
        }
    }else if(longitud >= 19000 && longitud <= 36000){
        let divArray= Math.round(longitud / 3);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayLey.push(value.substring(start, end).trim());
        }
    }else if(longitud>=36000 && longitud <= 50000){
        let divArray= Math.round(longitud / 4);
        let start=0;
        let end=0;
        //console.log(divArray);
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayLey.push(value.substring(start, end).trim());
        }
    }else if(longitud>50000){
        let divArray= Math.round(longitud / 5);
        let start=0;
        let end=0;
        //console.log(divArray);
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayLey.push(value.substring(start, end).trim());
        }
    }

    if (arrayLey.length==0) {
        $("#summernote").focus();
        swal("Ingrese la descripción de la Ley de Transparencia", "", "warning");
    } else {
        $('#modalCargando').modal('show');

        var element = document.querySelector('.button-save-transparencia');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formInTRANSPARENCIA);
        data.append("descripcion", arrayLey.join("//"));
        data.append("longitud", arrayLey.length);
        setTimeout(() => {
            sendNuevaLey(data, token, "/registrar-ley-transparencia", element);  
        }, 900);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DE LEY TRANSPARENCIA */
function NosendNuevaLey(data, token, url, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        $('#modalCargando').modal('hide');
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            if(myArr.resultado==true){
                Swal.fire({
                    title:'Excelente!',
                    text:'Registro Guardado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    el.removeAttribute("disabled");
                    el.style.removeProperty("pointer-events");
                    window.location='/ley-transparencia';
                },1500);
            } else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "No se pudo Guardar",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if(myArr.file==false){
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "Ha ocurrido un error con la Imagen",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if (myArr.resultado == "noimagen") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "Formato de Imagen no válido",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if (myArr.resultado == "nocopy") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "Error al copiar los archivos",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }
        }else if(xr.status === 400){
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

var arrayTransparencia= [];
var arrayEdTransparencia= [];

/* FUNCION CARGAR TRANSPARENCIA */
function Nocargar_transparencia(inforTransparencia){
    if(inforTransparencia.length==0){
        $('#divTransparencia').html("<p id='p-nodata-es' class='p-nodata-yet'>Sin especificar...</p>");
    }else{
        $(inforTransparencia).each(function(i,v){
            let texthistory='';
            if(v.descripcion!=null){
                texthistory= v.descripcion;
                arrayTransparencia.push({
                    'texto': texthistory,
                    'imagen': 'no'
                });
            }
        });

        drawTransparencia(arrayTransparencia);
    }

    setTimeout(() => {
        $('#modalCargando').modal('hide');
    }, 1000);
}

/* FUNCION QUE DIBUJA LA HISTORIA CORRECTAMENTE */
function NodrawTransparencia(array){
    let html="";

    $(array).each(function(i,v){
        html+=v.texto;
    })

    $('#divTransparencia').html(html);
}

function NoopenInterfaceEdit(){
    window.location='/update-ley-transparencia';
}

function NoactualizarTransparencia(){
    var token= $('#token').val();
    var value=$('#summernote').summernote('code');
    value= value.trim();

    let longitud= value.length;

    if(longitud < 9000){
        arrayEdTransparencia.push(value.trim());
    }else if(longitud >= 9000 && longitud <= 19000){
        let divArray= Math.round(longitud / 2);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            start= i;
            end= start+divArray;
            arrayEdTransparencia.push(value.substring(start, end).trim());
        }
    }else if(longitud >= 19000 && longitud <= 36000){
        let divArray= Math.round(longitud / 3);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayEdTransparencia.push(value.substring(start, end).trim());
        }
    }else if(longitud>=36000 && longitud <= 50000){
        let divArray= Math.round(longitud / 4);
        let start=0;
        let end=0;
        //console.log(divArray);
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayEdTransparencia.push(value.substring(start, end).trim());
        }
    }else if(longitud>50000){
        let divArray= Math.round(longitud / 5);
        let start=0;
        let end=0;
        //console.log(divArray);
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayEdTransparencia.push(value.substring(start, end).trim());
        }
    }

    if (arrayEdTransparencia.length==0) {
        $("#summernote").focus();
        swal("Ingrese la descripción de la Ley de Transparencia", "", "warning");
    } else {
        $('#modalCargando').modal('show');

        var element = document.querySelector('.button-update-transparencia');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formEditTransparencia);
        data.append("descripcion", arrayEdTransparencia.join("//"));
        data.append("longitud", arrayEdTransparencia.length);
        setTimeout(() => {
            sendUpdateLey(token, data, "/actualizar-ley-transparencia", element);  
        }, 900);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function NosendUpdateLey(token, data, url, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#modalCargando').modal('hide');
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Historia Registrada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                setTimeout(function(){
                    //window.location = '/ley-transparencia';
                },1500);
            } else if (myArr.resultado == "noimagen") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("No se pudo Guardar", "", "error");
            }
        }else if(xr.status === 400){
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            $('#modalCargando').modal('hide');
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


/******************************************************************************************************/
/*                                 NUEVO PROCESO TRANSPARENCIA                                        */
/******************************************************************************************************/
function showInfoLey(){
    $('#modalCargando').modal('hide');
    $("#tablaTrans")
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

function urlregistrartransparencia(){
    window.location='/add-ley-transparencia';
}

function urlback(){
    window.location='/ley-transparencia';
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function generarAlias(){
    var val= $('#inputNameTransparencia').val();
    let sinaccent= removeAccents(val);
    let minuscula= sinaccent.toLowerCase();
    //let cadenasinpoint= minuscula.replaceAll(".","");
    let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","_");
    $('#inputAliasFileTransparencia').val(cadena);
}

function generarAliasE(){
    var val= $('#inputENameTransparencia').val();
    let sinaccent= removeAccents(val);
    let minuscula= sinaccent.toLowerCase();
    //let cadenasinpoint= minuscula.replaceAll(".","");
    let cadenasinpoint= minuscula.replaceAll(/[.,/]/g,"");
    let cadena= cadenasinpoint.replaceAll(" ","_");
    $('#inputAliasFileTranspE').val(cadena);
}

function guardarTransparencia(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var name = $("#inputNameTransparencia").val();
    var aliasfile = $("#inputAliasFileTransparencia").val();
    var lengimg = fileInput.files.length;

    if (name == "") {
        $("#inputNameTransparencia").focus();
        swal("Ingrese un título a la noticia", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileTransparencia").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else{
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        //observacion = observacion.replace(/(\r\n|\n|\r)/gm, "//");
        var element = document.querySelector('.savetransparencia');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        $('#modalFullSend').modal('show');

        var data = new FormData(formTrans);

        setTimeout(() => {
            sendNewTransparencia(token, data, "/registrar-ley-transparencia", element); 
        }, 700);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewTransparencia(token, data, url, el){
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
                    window.location = '/ley-transparencia';
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

function viewopenTransparencia(id){
    var url= '/view-transparencia/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

function interfaceupdateTransparencia(id){
    window.location= '/edit-leytransparencia/'+id;
}

function actualizarTransparencia(){
    var token= $('#token').val();

    let fileInput = document.getElementById("fileEdit");
    var titulo = $("#inputENameTransparencia").val();
    var aliasfile = $("#inputAliasFileTranspE").val();
    var lengimg = fileInput.files.length;

    if (titulo == "") {
        $("#inputENameTransparencia").focus();
        swal("Ingrese un título del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileTranspE").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else {
        if(isLeyT==false){
            if (lengimg == 0 ) {
                swal("No ha seleccionado un archivo", "", "warning");
            } else if (lengimg > 1) {
                swal("Solo se permite un archivo", "", "warning");
            } else {
                if(puedeActualizarSM(nameInterfaz) === 'si'){
                $('#modalFullSend').modal('show');

                var data = new FormData(formTransparenciaE);
                data.append("isley", isLeyT);

                setTimeout(() => {
                    sendUpdateLeyT(token, data, "/update-leytransparencia"); 
                }, 700);
                }else{
                    swal('No tiene permiso para actualizar','','error');
                }
            }
        }else{
            if(puedeActualizarSM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');

            var data = new FormData(formTransparenciaE);
            data.append("isley", isLeyT);

            setTimeout(() => {
                sendUpdateLeyT(token, data, "/update-leytransparencia");
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateLeyT(token, data, url){
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
function inactivarTransparencia(id, i){
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
            url: "/in-activar-leytransparencia",
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

                    html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenTransparencia("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateTransparencia("+id+")'>"+
                        "<i class='far fa-edit mr-3'></i>"+
                        "Actualizar"+
                    "</a>";
                    if(estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarTransparencia("+id+", "+i+")'>"+
                            "<i class='fas fa-eye-slash mr-3'></i>"+
                            "Inactivar"+
                        "</a>";
                    }else if(estado=="0"){
                            html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarTransparencia("+id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+='<a class="btn btn-success btn-sm mt-2 mr-3" title="Descargar Documento" onclick="downloadTransparencia('+code+')" >'+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar Documento"+
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

/* FUNCION PARA ACTIVAR REGLAMENTO */
function activarTransparencia(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    var code = $('#iddocumento'+i).val();
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-leytransparencia",
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

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenTransparencia("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>"+
            "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateTransparencia("+id+")'>"+
                "<i class='far fa-edit mr-3'></i>"+
                "Actualizar"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarTransparencia("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarTransparencia("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+='<a class="btn btn-success btn-sm mt-2 mr-3" title="Descargar Documento" onclick="downloadTransparencia('+code+')" >'+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar Documento"+
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

function downloadTransparencia(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-leytransparencia/'+id;
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfiletransp');
    var elcardfile= document.getElementById('cardListTransp');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        elcardfile.classList.add('noshow');
        isLeyT= false;
    }
}