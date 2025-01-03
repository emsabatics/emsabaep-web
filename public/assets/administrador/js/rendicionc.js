var g_year='', sel_tipo='';
var num_files_sel=0;

function urlregistrarrendicionc(){
    window.location='/registrar-rendicionc';
}

function urlback(){
    window.location='/rendicion-cuentas';
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
} 

function getAliasInput(){
    var year= $('#selYearRendicionc').select2('data')[0].text;
    if(sel_tipo!='-Seleccione una Opción-' && year!='-Seleccione una Opción-'){
        g_year= year;
        if(sel_tipo=='video'){
            return "video_rendicion_cuentas_"+year;
        }else if(sel_tipo=='medio'){
            if($('#inputDocTitle').val()!=''){
                var val= $('#inputDocTitle').val();
                let sinaccent= removeAccents(val);
                let minuscula= sinaccent.toLowerCase();
                //let cadenasinpoint= minuscula.replaceAll(".","");
                let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
                let cadena= cadenasinpoint.replaceAll(" ","_");
                return "rendicion_cuentas_"+year+"_"+cadena;
            }else{
                return "";
            }
        }
    }else{
        return "";
    }
}

function generarAlias(){
    var year= $('#selYearRendicionc').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        g_year= year;
        if(sel_tipo=='video'){
            $('#inputAliasFile').val("video_rendicion_cuentas_"+year);
        }else if(sel_tipo=='medio'){
            if($('#inputDocTitle').val()!=''){
                var val= $('#inputDocTitle').val();
                let sinaccent= removeAccents(val);
                let minuscula= sinaccent.toLowerCase();
                //let cadenasinpoint= minuscula.replaceAll(".","");
                let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
                let cadena= cadenasinpoint.replaceAll(" ","_");
                $('#inputAliasFile').val("rendicion_cuentas_"+year+"_"+cadena);
            }else{
                toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
            }
        }
        
    }else{
        toastr.info("Debe elegir Año correspondiente...", "!Aviso!");
        $('#inputAliasFile').val('');
    }
}

$('#selTipo').on("change", function(e) {
    var lastValue = $(this).select2('data')[0].text;
    if(lastValue=="-Seleccione una Opción-"){
        sel_tipo='';
        num_files_sel=0;
        $('#span-info-file').html('Seleccione solo un archivo');
        $('#inputDocTitle').val("");
    }else if(lastValue=="Medio de Verificación"){
        sel_tipo='medio';
        num_files_sel=2;
        $('#span-info-file').html('Seleccione los archivos');
        $('#inputDocTitle').val("");
    }else if(lastValue=="Vídeo"){
        sel_tipo='video';
        num_files_sel=1;
        $('#span-info-file').html('Seleccione solo un archivo');
        var year= $('#selYearRendicionc').select2('data')[0].text;
        $('#inputDocTitle').val("Vídeo Rendición Cuentas "+year);
    }
});

function guardarRendicionc(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var year = $("#selYearRendicionc :selected").val();
    var tipo = $("#selTipo :selected").val();
    var aliasfile = $("#inputAliasFile").val();
    var lengimg = fileInput.files.length;
    var typefile= fileInput.files[0].type;
    var titulo= $('#inputDocTitle').val();

    if (year == "0") {
        $("#selYearRendicionc").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (tipo == "0") {
        $("#selTipo").focus();
        swal("Seleccione el Tipo", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFile").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (titulo == "") {
        $("#inputDocTitle").focus();
        swal("Ingrese el Título del Documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (num_files_sel==1 && lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else if(num_files_sel==0){
        swal("No ha seleccionado un archivo", "", "warning");
    }else{
        if(aliasfile!=getAliasInput()){
            swal('Revise el alias del documento','','warning');
        }else{
            var element = document.querySelector('.saverendicionc');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            var data = new FormData(formRendicionc);
            data.append("anio", year);
            data.append("titulo", titulo);
            data.append("sel_tipo", sel_tipo);
            data.append("typefile", typefile);
            data.append("lengfile", lengimg);

            setTimeout(() => {
                sendNewRendicionc(token, data, "/store-rendicionc", element); 
            }, 700);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewRendicionc(token, data, url, el){
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
                    text:'Rendición de Cuenta '+g_year+' Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/registrar-rendicionc';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "Rendición de Cuenta "+g_year+" ya se encuentra registrada.", "error");
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


function viewopenrendicionc(id){
    var url= '/view-rendicionc/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}

/* FUNCION PARA INACTIVAR Rendicion Cuentas */
function inactivarrendicionc(id, i, anio, tipo){
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
            url: "/in-activar-rendicionc",
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
                    var elementState= document.getElementById('Tr'+i+'-'+anio+'-'+tipo).cells[2];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenrendicionc("+id+")'>"+
                        "<i class='fas fa-folder'></i>"+
                    "</button>"+
                    "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdaterendicionc("+id+")'>"+
                        "<i class='far fa-edit'></i>"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarrendicionc("+id+", "+i+", "+anio+", "+tipo+")'>"+
                            "<i class='fas fa-eye-slash'></i>"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarrendicionc("+id+", "+i+", "+anio+", "+tipo+")'>"+
                                "<i class='fas fa-eye'></i>"+
                            "</button>";
                    }
                    html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar Rendición Cuentas' onclick='downloadrendicionc("+id+")' >"+
                        "<i class='fas fa-download'></i>"+
                    "</button>"; 
                    var element= document.getElementById('Tr'+i+'-'+anio+'-'+tipo).cells[3];
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

/* FUNCION PARA ACTIVAR Rendicion Cuentas */
function activarrendicionc(id, i, anio, tipo){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
      url: "/in-activar-rendicionc",
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
            var elementState= document.getElementById('Tr'+i+'-'+anio+'-'+tipo).cells[2];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenrendicionc("+id+")'>"+
                "<i class='fas fa-folder'></i>"+
            "</button>"+
            "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdaterendicionc("+id+")'>"+
                "<i class='far fa-edit'></i>"+
            "</button>";
            if(estado=="1"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarrendicionc("+id+", "+i+", "+anio+", "+tipo+")'>"+
                "<i class='fas fa-eye-slash'></i>"+
            "</button>";
            }else if(estado=="0"){
            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarrendicionc("+id+", "+i+", "+anio+", "+tipo+")'>"+
                "<i class='fas fa-eye'></i>"+
            "</button>";
            }
            html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar Rendición Cuentas' onclick='downloadrendicionc("+id+")' >"+
                "<i class='fas fa-download'></i>"+
            "</button>";
            var element= document.getElementById('Tr'+i+'-'+anio+'-'+tipo).cells[3];
            $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
}

function downloadrendicionc(id){
    window.location='/download-rendicionc/'+id;
}

function interfaceupdaterendicionc(id){
    window.location= '/edit-rendicionc/'+id;
}

function generarAliasE(){
    toastr.info("No se permite generar el Alias...", "!Aviso!");
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfilerc');
    var eldivfile= document.getElementById('cardListRendicionc');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isRendicionc= false;
    }
}

function actualizarrendicionc(){
    var token= $('#token').val();

    let fileInput = document.getElementById("fileEdit");
    var lengimg = fileInput.files.length;

    if(isRendicionc==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else {
            $('#modalFullSend').modal('show');
            var data = new FormData(formrendicionc_e);
            data.append("isRendicionc", isRendicionc);
            setTimeout(() => {
                sendUpdateRendicionc(token, data, "/update-rendicionc"); 
            }, 700);
        }
    }else{
        $('#modalFullSend').modal('show');
        var data = new FormData(formrendicionc_e);
        data.append("isRendicionc", isRendicionc);
        setTimeout(() => {
            sendUpdateRendicionc(token, data, "/update-rendicionc");
        }, 700);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateRendicionc(token, data, url){
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
                    text:'Rendición de Cuentas Actualizado',
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