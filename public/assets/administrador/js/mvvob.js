var arrayMision= [];
var arrayVision= [];
var arrayValores= [];
var arrayObjetivos= [];

var contadorValor = 0;
var arrayValorIndi= [];
var contadorObjetivo = 0;
var arrayObjetivoIndi= [];

/* FUNCION CARGAR MVVOB */
function cargar_mvvob(){
    var contar=1, contarVal=1; 
    var html="", htmlVal="";
    var url= "/get_mivivaob";
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                $('#divMision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                $('#divVision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                $('#divValores').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                $('#divObjetivo').html("<p class='p-nodata-yet'>Sin especificar...</p>");
            }

            $(myArr).each(function(i,v){
                /* -----------MISION-------------- */
                if(myArr.hasOwnProperty('mision')){
                    if(myArr.mision.length==0){
                        $('#divMision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                    }
                    $(myArr.mision).each(function(j,k){
                        arrayMision.push({
                            "id" : k.id,
                            "descripcion" : k.descripcion,
                            "tipo": k.tipo
                        });
                        var descp= k.descripcion.replaceAll('//','<br>');
                        $('#divMision').html("<p class='p-data-full'>"+descp+"</p>");
                    });
                }else{
                    $('#divMision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }

                /* -----------VISION-------------- */
                if(myArr.hasOwnProperty('vision')){
                    if(myArr.vision.length==0){
                        $('#divVision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                    }
                    $(myArr.vision).each(function(j,k){
                        arrayVision.push({
                            "id" : k.id,
                            "descripcion" : k.descripcion,
                            "tipo": k.tipo
                        });
                        var descp= k.descripcion.replaceAll('//','<br>');
                        $('#divVision').html("<p class='p-data-full'>"+descp+"</p>");
                    });
                }else{
                    $('#divVision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }

                /* -----------VALORES-------------- */
                if(myArr.hasOwnProperty('valores')){
                    if(myArr.valores.length==0){
                        $('#divValores').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                    }
                    $(myArr.valores).each(function(j,k){
                        arrayValores.push({
                            "id" : k.id,
                            "descripcion" : k.descripcion,
                            "tipo": k.tipo
                        });
                        var descp= k.descripcion.replaceAll('//','<br>');
                        
                        htmlVal+="<div class='card card-info card-outline'>"+
                            "<div class='card-header'>"+
                            "<h5 class='card-title'>Valor #"+contarVal+"</h5>"+
                                "<div class='card-tools' id='cardToolsValor"+contarVal+"'>"+
                                    "<a href='javascript:void(0)' class='btn btn-tool' onclick='editarValor("+j+")' title='Actualizar'>"+
                                        "<i class='fas fa-pen'></i>"+
                                    "</a>";
                                    if(k.estado=="1"){
                                        htmlVal+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarValorInd("+k.id+", "+contarVal+")' title='Inactivar'>"+
                                                "<i class='fas fa-eye-slash'></i>"+
                                            "</a>";
                                    }else if(k.estado=="0"){
                                        htmlVal+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarValorInd("+k.id+", "+contarVal+")' title='Activar'>"+
                                                "<i class='fas fa-eye'></i>"+
                                            "</a>";
                                    }
                                htmlVal+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarValor("+k.id+")' title='Eliminar'>"+
                                        "<i class='fas fa-trash'></i>"+
                                    "</a>"+
                                "</div>"+
                            "</div>"+
                            "<div class='card-body'>"+
                                "<p class='text-justify' id='pdescvalorindi"+contarVal+"'>"+descp+"</p>"+
                            "</div>"+
                        "</div>";
                        
                        contarVal++;

                        $('#divValores').html(htmlVal);
                    });
                }else{
                    $('#divValores').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }

                /* -----------OBJETIVOS-------------- */
                if(myArr.hasOwnProperty('objetivos')){
                    if(myArr.objetivos.length==0){
                        $('#divValores').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                    }
                    $(myArr.objetivos).each(function(j,k){
                        arrayObjetivos.push({
                            "id" : k.id,
                            "descripcion" : k.descripcion,
                            "tipo": k.tipo
                        });
                        var descp= k.descripcion.replaceAll('//','<br>');
                        html+="<div class='card card-info card-outline'>"+
                                "<div class='card-header'>"+
                                "<h5 class='card-title'>Objetivo #"+contar+"</h5>"+
                                    "<div class='card-tools' id='cardToolsObj"+contar+"'>"+
                                        "<a href='javascript:void(0)' class='btn btn-tool' onclick='editarObjetivo("+j+")' title='Actualizar'>"+
                                            "<i class='fas fa-pen'></i>"+
                                        "</a>";
                                        if(k.estado=="1"){
                                            html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarObjecInd("+k.id+", "+contar+")' title='Inactivar'>"+
                                                    "<i class='fas fa-eye-slash'></i>"+
                                                "</a>";
                                        }else if(k.estado=="0"){
                                            html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarObjecInd("+k.id+", "+contar+")' title='Activar'>"+
                                                    "<i class='fas fa-eye'></i>"+
                                                "</a>";
                                        }
                                    html+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarObjetivo("+k.id+")' title='Eliminar'>"+
                                            "<i class='fas fa-trash'></i>"+
                                        "</a>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='card-body'>"+
                                    "<p class='text-justify' id='pdescobjindi"+contar+"'>"+descp+"</p>"+
                                "</div>"+
                            "</div>";
                            
                        contar++;

                        $('#divObjetivo').html(html);
                    });
                }else{
                    $('#divObjetivo').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }
            });

            if(myArr.length>0){
                if(arrayMision.length==0){
                    $('#divMision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }
                if(arrayVision.length==0){
                    $('#divVision').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }

                if(arrayValores.length==0){
                    $('#divValores').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }

                if(arrayObjetivos.length==0){
                    $('#divObjetivo').html("<p class='p-nodata-yet'>Sin especificar...</p>");
                }
            }

            setTimeout(function(){
                $('#modalCargando').modal('hide');
            },800);
        }else if(xr.status === 400){
            //console.log('ERROR CONEXION');
            $('#modalCargando').modal('hide');
            setTimeout(function () {
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }, 500);
        }
    }

    xr.send(null);
}

/* FUNCION ABRIR MODAL EDITAR MISION */
function openmodalMision(){
    if(arrayMision.length>0){
        $('#idmision').val(arrayMision[0].id);
        replaceCaracter(arrayMision[0].descripcion, '#inputMision');
    }else{
        $('#inputMision').html("");
        $('#inputMision').focus();
    }
    
    $('#modal-edit-mision').modal('show');
}

/* FUNCION ABRIR MODAL EDITAR VISION */
function openmodalVision(){
    if(arrayVision.length>0){
        $('#idvision').val(arrayVision[0].id);
        replaceCaracter(arrayVision[0].descripcion, '#inputVision');
    }else{
        $('#inputVision').html("");
        $('#inputVision').focus();
    }

    $('#modal-edit-vision').modal('show');
}

/* FUNCION ABRIR MODAL EDITAR VALORES */
function openmodalValores(){
    $('#inputValor').focus();
    //document.getElementById('btnGuardarObj').setAttribute("disabled","").style.pointerEvents='none';
    $('#modal-edit-valores').modal('show');
}

/* FUNCION ABRIR MODAL EDITAR OBJETIVOS */
function openmodalObjetivos(){
    $('#inputObjetivo').focus();
    //document.getElementById('btnGuardarObj').setAttribute("disabled","").style.pointerEvents='none';
    $('#modal-edit-objetivos').modal('show');
}

/* FUNCION ABRIR MODAL EDITAR OBJETIVO INDIVIDUAL */
function editarValor(pos){
    if(arrayValores.length>0){
        $('#idvalorindi').val(arrayValores[pos].id);
        replaceCaracter(arrayValores[pos].descripcion, '#inputValorIndividual');
        //$('#inputValorIndividual').val(arrayValores[pos].descripcion);
    }else{
        $('#inputValorIndividual').html("");
        $('#inputValorIndividual').focus();
    }
    $('#posvalor').val(pos);
    $('#cardTitleValorInd').html("Valor #"+(pos+1))

    $('#modal-edit-valorind').modal('show');
}

/* FUNCION ABRIR MODAL EDITAR OBJETIVO INDIVIDUAL */
function editarObjetivo(pos){
    if(arrayObjetivos.length>0){
        $('#idobjetivoindi').val(arrayObjetivos[pos].id);
        replaceCaracter(arrayObjetivos[pos].descripcion, '#inputObjetivoIndividual');
        //$('#inputObjetivoIndividual').val(arrayObjetivos[pos].descripcion);
    }else{
        $('#inputObjetivoIndividual').html("");
        $('#inputObjetivoIndividual').focus();
    }
    $('#posobjetivo').val(pos);
    $('#cardTitleObjInd').html("Objetivo #"+(pos+1))

    $('#modal-edit-objind').modal('show');
}


/* FUNCION QUE GUARDA LA MISION */
function guardarRegistroMision(){
    var idmision= $('#idmision').val();
    var mision= $('#inputMision').val();
    var tipo = "mision";
    var tiporegistro='';

    var token= $('#token').val();

    if(mision==''){
        $('#inputMision').focus();
        swal('Por favor ingrese la Misión','','warning');
    }else{
        var element = document.querySelector('.btn-save-mision');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        mision = mision.replace(/(\r\n|\n|\r)/gm, "//");
        mision= mision.trim();

        if(idmision!=''){
            tiporegistro='update';
            arrayMision[0].descripcion= mision;
        }else{
            tiporegistro='insert';
        }

        var formData = new FormData();
        formData.append("id", idmision);
        formData.append("descripcion", mision);
        formData.append("tipo", tipo);
        sendUpdateMiViObjIndi(formData, token, '/registrar-mivivaob', element, '#modal-edit-mision', tipo, 0, tiporegistro);
    }
}

/* FUNCION QUE GUARDA LA VISION */
function guardarRegistroVision(){
    var idvision= $('#idvision').val();
    var vision= $('#inputVision').val();
    var tipo = "vision";
    var tiporegistro='';

    var token= $('#token').val();

    if(vision==''){
        $('#inputVision').focus();
        swal('Por favor ingrese la Visión','','warning');
    }else{
        var element = document.querySelector('.btn-save-vision');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        vision = vision.replace(/(\r\n|\n|\r)/gm, "//");
        vision= vision.trim();

        if(idvision!=''){
            tiporegistro='update';
            arrayVision[0].descripcion= vision;
        }else{
            tiporegistro='insert';
        }

        var formData = new FormData();
        formData.append("id", idvision);
        formData.append("descripcion", vision);
        formData.append("tipo", tipo);
        sendUpdateMiViObjIndi(formData, token, '/registrar-mivivaob', element, '#modal-edit-vision', tipo, 0, tiporegistro);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DE MISION/VISION */
function sendUpdateMiViObjIndi(data, token, url, el, modal, tipo, posi, registro){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
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
                    if(registro=='update'){
                        if(tipo=="objetivos"){
                            $('#idobjetivoindi').val('');
                            $('#posobjetivo').val('');
                        }else{
                            $('#id'+tipo).val('');
                        }
                        reDrawInfo(tipo, posi);
                        $(modal).modal('hide');
                    }else if(registro=='insert'){
                        window.location='/mi-vi-va-ob';
                    }
                    
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

/* FUNCION QUE ACTUALIZA LA MISION/VISION/VALORES/OBJETIVOS EN EL TAB-PANEL*/
function reDrawInfo(tipo, posi){
    if(tipo=="mision"){
        //console.log(arrayMision[posi], posi, tipo);
        var descp= arrayMision[posi].descripcion.replaceAll('//','<br>');
        //console.log(descp);
        $('#divMision').html("<p class='p-data-full'>"+descp+"</p>");
    }else if(tipo=="vision"){
        var descp= arrayVision[posi].descripcion.replaceAll('//','<br>');
        $('#divVision').html("<p class='p-data-full'>"+descp+"</p>");
    }else if(tipo=="valores"){
        var descp= arrayValores[posi].descripcion.replaceAll('//','<br>');
        $('#pdescvalorindi'+(posi+1)).html(descp);
    }else if(tipo=="objetivos"){
        //var descp= arrayObjetivos[posi].descripcion;
        var descp= arrayObjetivos[posi].descripcion.replaceAll('//','<br>');
        $('#pdescobjindi'+(posi+1)).html(descp);
    }
}

/* FUNCION QUE GUARDA EL OBJETIVO INDIVIDUAL SELECCIONADO EN EL TAB-PANEL*/
function guardarObjIndividual(){
    var idobjetivo= $('#idobjetivoindi').val();
    var objetivo= $('#inputObjetivoIndividual').val();
    var tipo = "objetivos";
    var posi= $('#posobjetivo').val();
    posi= parseInt(posi);
    var tiporegistro='';

    var token= $('#token').val();

    if(objetivo==''){
        $('#inputObjetivoIndividual').focus();
        swal('Por favor ingrese el contenido del Objetivo #'+(posi+1),'','warning');
    }else{
        var element = document.querySelector('.btn-updt-obj');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        objetivo = objetivo.replaceAll(/(\r\n|\n|\r)/gm, "//");
        objetivo= objetivo.trim();

        if(idobjetivo!=''){
            tiporegistro='update';
            arrayObjetivos[posi].descripcion= objetivo;
        }else{
            tiporegistro='insert';
        }

        var formData = new FormData();
        formData.append("id", idobjetivo);
        formData.append("descripcion", objetivo);
        formData.append("tipo", tipo);
        sendUpdateMiViObjIndi(formData, token, '/registrar-mivivaob', element, '#modal-edit-objind', tipo, posi, tiporegistro);
    }
}

/* FUNCION PARA INACTIVAR OBJETIVO INDIVIDUAL */
function eliminarObjetivo(id) {
    var token= $('#token').val();

    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar este objetivo?",
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
            url: "/eliminar-objindi",
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
                    
                    limpiarArray();

                    setTimeout(function () {
                        window.location='/mi-vi-va-ob';
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Eliminar", "", "error");
                }
            },
            statusCode:{
                400: function(){
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
}

/* FUNCION PARA INACTIVAR OBJETIVO INDIVIDUAL */
function inactivarObjecInd(id, i) {
    var token= $('#token').val();
    var estado = "0";
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
            url: "/in-activar-objindi",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: id,
                estado: estado,
            },
            success: function (res) {
                //console.log(res);
                if (res.resultado == true) {
                    swal({
                        title: "Excelente!",
                        text: "Registro Inactivado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    
                    setTimeout(function () {
                        var html="<a href='javascript:void(0)' class='btn btn-tool' onclick='editarObjetivo("+id+")' title='Editar'>"+
                            "<i class='fas fa-pen'></i>"+
                        "</a>";
                        if(estado=="1"){
                            html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarObjecInd("+id+", "+i+")' title='Inactivar'>"+
                                "<i class='fas fa-eye-slash'></i>"+
                            "</a>";
                        }else if(estado=="0"){
                            html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarObjecInd("+id+", "+i+")' title='Activar'>"+
                                "<i class='fas fa-eye'></i>"+
                            "</a>";
                        }
                        html+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarObjetivo("+id+")' title='Eliminar'>"+
                            "<i class='fas fa-trash'></i>"+
                        "</a>";
                        var element= document.getElementById('cardToolsObj'+i);
                        $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            statusCode:{
                400: function(){
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
}
  
/* FUNCION PARA ACTIVAR OBJETIVO INDIVIDUAL */
function activarObjecInd(id, i) {
    var token= $('#token').val();
    var estado = "1";
    $.ajax({
      url: "/in-activar-objindi",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: id,
        estado: estado,
      },
      success: function (res) {
        if (res.resultado == true) {
            var html="<a href='javascript:void(0)' class='btn btn-tool' onclick='editarObjetivo("+id+")' title='Activar'>"+
                "<i class='fas fa-pen'></i>"+
            "</a>";
            if(estado=="1"){
                html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarObjecInd("+id+", "+i+")' title='Inactivar'>"+
                    "<i class='fas fa-eye-slash'></i>"+
                "</a>";
            }else if(estado=="0"){
                html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarObjecInd("+id+", "+i+")' title='Activar'>"+
                    "<i class='fas fa-eye'></i>"+
                "</a>";
            }
            html+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarObjetivo("+id+")' title='Eliminar'>"+
                "<i class='fas fa-trash'></i>"+
            "</a>";
            var element= document.getElementById('cardToolsObj'+i);
            $(element).html(html);
            swal({
                title: "Excelente!",
                text: "Registro Activado",
                type: "success",
                showConfirmButton: false,
                timer: 1600,
            });
        } else if (res.resultado == false) {
            swal("No se pudo Activar", "", "error");
        }
      },
    });
}

/* FUNCION QUE TRAZA SALTOS DE LÍNEA EN EL TEXTAREA DE LA MISION/VISION */
function replaceCaracter(dato, elemento){
    var posicion = dato.indexOf("//");
    //console.log(posicion);
    //cadena = dato.slice(0, posicion) + '\n' + dato.slice(posicion + 2);
    while (posicion >= 0)
    {
        // remplaza "ato" por "atito"
        dato = dato.slice(0, posicion) + '\n' + dato.slice(posicion + 2);
        // busca la siguiente ocurrencia de la palabra
        posicion = dato.indexOf("//");
        //console.log(posicion);
    }
    $(elemento).val(dato);
}

function replaceCaractertoText(dato){
    var posicion = dato.indexOf("//");
    //console.log(posicion);
    //cadena = dato.slice(0, posicion) + '\n' + dato.slice(posicion + 2);
    while (posicion >= 0)
    {
        // remplaza "ato" por "atito"
        dato = dato.slice(0, posicion) + '\n' + dato.slice(posicion + 2);
        // busca la siguiente ocurrencia de la palabra
        posicion = dato.indexOf("//");
        //console.log(posicion);
    }
    //$(elemento).val(dato);
    return dato;
}

/* FUNCION PARA LIMPIAR LOS ARRAY */
function limpiarArray(){
    while(arrayMision.length>0){
        arrayMision.pop();
    }

    while(arrayVision.length>0){
        arrayVision.pop();
    }

    while(arrayObjetivos.length>0){
        arrayObjetivos.pop();
    }

    while(arrayObjetivoIndi.length>0){
        arrayObjetivoIndi.pop();
    }

    while(arrayValorIndi.length>0){
        arrayValorIndi.pop();
    }
    
    contadorObjetivo = 0;
    contadorValor = 0;
}

/* FUNCION QUE DIBUJA OBJETIVOS INDIVIDUALES*/
$('#agregarobjetive').click(function(){
    var txtobjetivo = document.getElementById('inputObjetivo');
    var contenedor = document.getElementById('almacenar');
    if(txtobjetivo.value == ""){ //si no ingresa nada en el input le manda mensaje de que ingrese un nombre
        swal('Ingresa un objetivo','','warning');
        return false;
    }else{
        let caracterespecial= "<>";
        //let replacechar= txtobjetivo.value.replace(/\s+/g, '');
        let replacechar= txtobjetivo.value.replace(/(\r\n|\n|\r)/gm, "//");
        replacechar= replacechar.trim();
        //let replacechar= txtobjetivo.value.trim();
        contadorObjetivo++;

        var textarea= document.createElement("textarea");
        var btn_eliminar = document.createElement('button');
        var divgroup= document.createElement('div');
        
        textarea.id = "inputObj"+contadorObjetivo;
        textarea.className= "form-control text-justify";
        textarea.cols="5";
        textarea.rows= "7";
        textarea.value = replaceCaractertoText(replacechar);
        //textarea.style.width = '150px';
        textarea.style.cssText= 'width: 76%;';
        textarea.setAttribute('disabled',''); // propiedad disabled

        divgroup.id="divGroup"+contadorObjetivo;
        divgroup.className="d-flex flex-row mt-3";

        btn_eliminar.innerHTML='<i class="fas fa-trash mr-2"></i> Eliminar';
        btn_eliminar.type = 'button';
        btn_eliminar.className="btn btn-danger ml-2";
        btn_eliminar.id = "btn"+contadorObjetivo;
        btn_eliminar.style.height= '40px';

        divgroup.append(textarea);
        divgroup.append(btn_eliminar);
        //contenedor.append(salto);//todo lo agrego al div de almacenar
        contenedor.append(divgroup);

        arrayObjetivoIndi.push(replacechar+caracterespecial);

        txtobjetivo.value="";

        var botones = document.getElementById('btn'+contadorObjetivo);
    
          botones.addEventListener('click', function(){
            //console.log("El texto que tiene es: ", this.id);
            var posi= this.id.substr(3, this.id.length);
            var divactual= document.getElementById("divGroup"+posi);

            var texta_name = divactual.querySelector("textarea");

            while (divactual.firstChild) {
              divactual.removeChild(divactual.firstChild);
            }

            let replacetxt= texta_name.value.replace(/(\r\n|\n|\r)/gm, "//");
            var newvalue= arrayObjetivoIndi.indexOf(replacetxt+caracterespecial);
            //let newvalue= texta_name.value.replace(/(\r\n|\n|\r)/gm, "//");
            //var index1= arrayObjetivoIndi.indexOf(texta_name.value+caracterespecial);
            if(newvalue > -1){
              arrayObjetivoIndi.splice(newvalue,1);
            }
            contenedor.removeChild(divactual);
        });
    }
});

/* FUNCION QUE GUARDA LOS OBJETIVOS */
function guardarRegistroObjetivos(){
    var longObj= arrayObjetivoIndi.length;
    var tipo = "objetivos";

    var token= $('#token').val();

    if(longObj==0){
        $('#inputObjetivo').focus();
        swal('Por favor ingrese un objetivo','','warning');
    }else{
        var element = document.querySelector('.btn-save-obj');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var formData = new FormData();
        formData.append("descripcion", arrayObjetivoIndi.toString());
        formData.append("tipo", tipo);
        sendUpdateObjetivos(formData, token, '/registro-objetivo', element, '#modal-edit-objetivos');
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO DE LOS OBJETIVOS */
function sendUpdateObjetivos(data, token, url, el, modal){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
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
                    $('#almacenar').html("");
                    $(modal).modal('hide');
                    let div= document.getElementById('divObjetivo');
                    if(div.querySelector(".p-nodata-yet")!== null){
                        div.querySelector(".p-nodata-yet").remove();
                    }
                    drawNewObjIndi(myArr.objetivos);
                    //window.location='/mi-vi-va-ob';
                },1500);
            } else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("No se pudo Guardar", "", "error");
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

/* FUNCION QUE DIBUJA LOS NUEVOS OBJETIVOS INGRESADOS*/
function drawNewObjIndi(arrobj){
    var longarr= arrayObjetivos.length;
    var html=""; 
    var contar= longarr;
    //console.log(longarr, arrobj);

    const divObjetivo = document.getElementById("divObjetivo");

    $(arrobj).each(function(j, k){
        let nid= parseInt(k.id);
        arrayObjetivos.push({
            "id" : nid,
            "descripcion" : k.descripcion,
            "tipo": "objetivos"
        });

        var descp= k.descripcion.replaceAll('//','<br>');

        html+="<div class='card card-info card-outline'>"+
            "<div class='card-header'>"+
                "<h5 class='card-title'>Objetivo #"+(contar+1)+"</h5>"+
                "<div class='card-tools' id='cardToolsObj"+(contar+1)+"'>"+
                    "<a href='javascript:void(0)' class='btn btn-tool' onclick='editarObjetivo("+contar+")' title='Actualizar'>"+
                        "<i class='fas fa-pen'></i>"+
                    "</a>";
                    if(k.estado=="1"){
                        html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarObjecInd("+k.id+", "+(contar+1)+")' title='Inactivar'>"+
                            "<i class='fas fa-eye-slash'></i>"+
                        "</a>";
                    }else if(k.estado=="0"){
                        html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarObjecInd("+k.id+", "+(contar+1)+")' title='Activar'>"+
                            "<i class='fas fa-eye'></i>"+
                        "</a>";
                    }
                    html+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarObjetivo("+k.id+")' title='Eliminar'>"+
                        "<i class='fas fa-trash'></i>"+
                    "</a>"+
                "</div>"+
            "</div>"+
            "<div class='card-body'>"+
                "<p class='text-justify' id='pdescobjindi"+(contar+1)+"'>"+descp+"</p>"+
            "</div>"+
        "</div>";
        
        contar++;
        divObjetivo.insertAdjacentHTML("beforeend", html);
        html="";
    });

    //console.log(arrayObjetivos);
}


/* FUNCION QUE DIBUJA OBJETIVOS INDIVIDUALES*/
$('#agregarvalore').click(function(){
    var txtvalor = document.getElementById('inputValor');
    var contenedor = document.getElementById('almacenarvalor');
    if(txtvalor.value == ""){ //si no ingresa nada en el input le manda mensaje de que ingrese un nombre
        swal('Ingresa un Valor','','warning');
        return false;
    }else{
        let caracterespecial= "<>";
        //let replacechar= txtvalor.value.replace(/\s+/g, '');
        let replacechar= txtvalor.value.replace(/(\r\n|\n|\r)/gm, "//");
        replacechar= replacechar.trim();
        //let replacechar= txtvalor.value.trim();
        contadorValor++;

        var textarea= document.createElement("textarea");
        var btn_eliminar = document.createElement('button');
        var divgroup= document.createElement('div');
        
        textarea.id = "inputObj"+contadorValor;
        textarea.className= "form-control text-justify";
        textarea.cols="5";
        textarea.rows= "7";
        textarea.value = replaceCaractertoText(replacechar);
        //textarea.style.width = '150px';
        textarea.style.cssText= 'width: 76%;';
        textarea.setAttribute('disabled',''); // propiedad disabled

        divgroup.id="divGroup"+contadorValor;
        divgroup.className="d-flex flex-row mt-3";

        btn_eliminar.innerHTML='<i class="fas fa-trash mr-2"></i> Eliminar';
        btn_eliminar.type = 'button';
        btn_eliminar.className="btn btn-danger ml-2";
        btn_eliminar.id = "btn"+contadorValor;
        btn_eliminar.style.height= '40px';

        divgroup.append(textarea);
        divgroup.append(btn_eliminar);
        //contenedor.append(salto);//todo lo agrego al div de almacenar
        contenedor.append(divgroup);

        arrayValorIndi.push(replacechar+caracterespecial);

        txtvalor.value="";

        var botones = document.getElementById('btn'+contadorValor);
    
          botones.addEventListener('click', function(){
            //console.log("El texto que tiene es: ", this.id);
            var posi= this.id.substr(3, this.id.length);
            var divactual= document.getElementById("divGroup"+posi);

            var texta_name = divactual.querySelector("textarea");

            while (divactual.firstChild) {
              divactual.removeChild(divactual.firstChild);
            }

            let replacetxt= texta_name.value.replace(/(\r\n|\n|\r)/gm, "//");
            var newvalue= arrayValorIndi.indexOf(replacetxt+caracterespecial);
            if(newvalue > -1){
              arrayValorIndi.splice(newvalue,1);
            }

            contenedor.removeChild(divactual);
        });
    }
});

/* FUNCION QUE GUARDA LOS VALORES */
function guardarRegistroValores(){
    var longObj= arrayValorIndi.length;
    var tipo = "valores";

    var token= $('#token').val();

    if(longObj==0){
        $('#inputObjetivo').focus();
        swal('Por favor ingrese un valor','','warning');
    }else{
        var element = document.querySelector('.btn-save-valor');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var formData = new FormData();
        formData.append("descripcion", arrayValorIndi.toString());
        formData.append("tipo", tipo);
        sendUpdateValores(formData, token, '/registro-valor', element, '#modal-edit-valores');
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO DE LOS VALORES */
function sendUpdateValores(data, token, url, el, modal){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
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
                    $('#almacenarvalor').html("");
                    $(modal).modal('hide');
                    let div= document.getElementById('divValores');
                    if(div.querySelector(".p-nodata-yet")!== null){
                        div.querySelector(".p-nodata-yet").remove();
                    }
                    drawNewValorIndi(myArr.valores);
                    //window.location='/mi-vi-va-ob';
                },1500);
            } else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("No se pudo Guardar", "", "error");
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

/* FUNCION QUE DIBUJA LOS NUEVOS VALORES INGRESADOS*/
function drawNewValorIndi(arrobj){
    var longarr= arrayValores.length;
    var html=""; 
    var contar= longarr;
    //console.log(longarr, arrobj);
    //console.log(longarr);

    const divValores = document.getElementById("divValores");

    $(arrobj).each(function(j, k){
        let nid= parseInt(k.id);
        arrayValores.push({
            "id" : nid,
            "descripcion" : k.descripcion,
            "tipo": "valores"
        });

        console.log(arrayValores);

        var descp= k.descripcion.replaceAll('//','<br>');

        html+="<div class='card card-info card-outline'>"+
            "<div class='card-header'>"+
                "<h5 class='card-title'>Valor #"+(contar+1)+"</h5>"+
                "<div class='card-tools' id='cardToolsValor"+(contar+1)+"'>"+
                    "<a href='javascript:void(0)' class='btn btn-tool' onclick='editarValor("+contar+")' title='Actualizar'>"+
                        "<i class='fas fa-pen'></i>"+
                    "</a>";
                    if(k.estado=="1"){
                        html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarValorInd("+k.id+", "+(contar+1)+")' title='Inactivar'>"+
                            "<i class='fas fa-eye-slash'></i>"+
                        "</a>";
                    }else if(k.estado=="0"){
                        html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarValorInd("+k.id+", "+(contar+1)+")' title='Activar'>"+
                            "<i class='fas fa-eye'></i>"+
                        "</a>";
                    }
                    html+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarValor("+k.id+")' title='Eliminar'>"+
                        "<i class='fas fa-trash'></i>"+
                    "</a>"+
                "</div>"+
            "</div>"+
            "<div class='card-body'>"+
                "<p class='text-justify' id='pdescvalorindi"+(contar+1)+"'>"+descp+"</p>"+
            "</div>"+
        "</div>";
        
        contar++;
        divValores.insertAdjacentHTML("beforeend", html);
        html="";
    });

    //console.log(arrayValores);
}

/* FUNCION QUE GUARDA EL OBJETIVO INDIVIDUAL SELECCIONADO EN EL TAB-PANEL*/
function guardarValorIndividual(){
    var idvalor= $('#idvalorindi').val();
    var valor= $('#inputValorIndividual').val();
    var tipo = "valores";
    var posi= $('#posvalor').val();
    posi= parseInt(posi);
    var tiporegistro='';

    var token= $('#token').val();

    if(valor==''){
        $('#inputValorIndividual').focus();
        swal('Por favor ingrese el contenido del Valor #'+(posi+1),'','warning');
    }else{
        var element = document.querySelector('.btn-updt-valor');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        valor = valor.replaceAll(/(\r\n|\n|\r)/gm, "//");

        if(idvalor!=''){
            tiporegistro='update';
            arrayValores[posi].descripcion= valor;
        }else{
            tiporegistro='insert';
        }

        var formData = new FormData();
        formData.append("id", idvalor);
        formData.append("descripcion", valor);
        formData.append("tipo", tipo);
        sendUpdateMiViObjIndi(formData, token, '/registrar-mivivaob', element, '#modal-edit-valorind', tipo, posi, tiporegistro);
    }
}

/* FUNCION PARA INACTIVAR VALOR INDIVIDUAL */
function inactivarValorInd(id, i) {
    var token= $('#token').val();
    var estado = "0";
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
            url: "/in-activar-valindi",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: id,
                estado: estado,
            },
            success: function (res) {
                //console.log(res);
                if (res.resultado == true) {
                    swal({
                        title: "Excelente!",
                        text: "Registro Inactivado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    
                    setTimeout(function () {
                        var html="<a href='javascript:void(0)' class='btn btn-tool' onclick='editarValor("+id+")' title='Editar'>"+
                            "<i class='fas fa-pen'></i>"+
                        "</a>";
                        if(estado=="1"){
                            html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarValorInd("+id+", "+i+")' title='Inactivar'>"+
                                "<i class='fas fa-eye-slash'></i>"+
                            "</a>";
                        }else if(estado=="0"){
                            html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarValorInd("+id+", "+i+")' title='Activar'>"+
                                "<i class='fas fa-eye'></i>"+
                            "</a>";
                        }
                        html+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarValor("+id+")' title='Eliminar'>"+
                            "<i class='fas fa-trash'></i>"+
                        "</a>";
                        var element= document.getElementById('cardToolsValor'+i);
                        $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            statusCode:{
                400: function(){
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
}
  
/* FUNCION PARA ACTIVAR VALOR INDIVIDUAL */
function activarValorInd(id, i) {
    var token= $('#token').val();
    var estado = "1";
    $.ajax({
      url: "/in-activar-valindi",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: id,
        estado: estado,
      },
      success: function (res) {
        if (res.resultado == true) {
            var html="<a href='javascript:void(0)' class='btn btn-tool' onclick='editarValor("+id+")' title='Activar'>"+
                "<i class='fas fa-pen'></i>"+
            "</a>";
            if(estado=="1"){
                html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='inactivarValorInd("+id+", "+i+")' title='Inactivar'>"+
                    "<i class='fas fa-eye-slash'></i>"+
                "</a>";
            }else if(estado=="0"){
                html+= "<a href='javascript:void(0)' class='btn btn-tool' onclick='activarValorInd("+id+", "+i+")' title='Activar'>"+
                    "<i class='fas fa-eye'></i>"+
                "</a>";
            }
            html+="<a href='javascript:void(0)' class='btn btn-tool' onclick='eliminarValor("+id+")' title='Eliminar'>"+
                "<i class='fas fa-trash'></i>"+
            "</a>";
            var element= document.getElementById('cardToolsValor'+i);
            $(element).html(html);

            swal({
                title: "Excelente!",
                text: "Registro Activado",
                type: "success",
                showConfirmButton: false,
                timer: 1600,
            });
        } else if (res.resultado == false) {
            swal("No se pudo Activar", "", "error");
        }
      },
    });
}

/* FUNCION PARA INACTIVAR VALOR INDIVIDUAL */
function eliminarValor(id) {
    var token= $('#token').val();

    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar este Valor?",
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
            url: "/eliminar-valorindi",
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
                    
                    limpiarArray();

                    setTimeout(function () {
                        window.location='/mi-vi-va-ob';
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Eliminar", "", "error");
                }
            },
            statusCode:{
                400: function(){
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
}