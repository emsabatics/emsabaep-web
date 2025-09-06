var idSelOptLotaip=0;
var typeSelOptLotaip= '';
var g_year='', g_mes='', g_literal='';
var getShortTypeDoc='';
var textoSelArticulo='';

function urlregistrarlotaip(){
    window.location='/register-lotaip-v2';
}

function urlback(){
    window.location='/lotaip-v2';
}

/*$('#selOptLotaip').on("change", function(e) {
    var lastValue = $(this).select2('data')[0].id;
    alert(lastValue);
});*/

$('#selTypeDoc').on('select2:select', function (e) {
    var data = e.params.data;
    var getid= data.id;
    if(getid=='0'){
        getShortTypeDoc='';
    }else if(getid=='conjunto-datos'){
       getShortTypeDoc='cdatos';
    }else if(getid=='metadatos'){
        getShortTypeDoc='mdatos';
    }else if(getid=='diccionario-datos'){
        getShortTypeDoc='ddatos';
    }
});

$('#selOptLotaip').on('select2:select', function (e) {
    var data = e.params.data;
    var getid= data.id;
    var gettxt= data.text;
    typeSelOptLotaip= getid.substring(0, 3);
    idSelOptLotaip= getid.substring(4, getid.length);
    textoSelArticulo= gettxt.substring(9);
    //console.log(getid, typeSelOptLotaip, idSelOptLotaip);
    var element= document.getElementById('div-literal');
    toastr.info("Cargando Información...", "!Aviso!");
    cleanInputs();
    if(typeSelOptLotaip== "art" && textoSelArticulo=='19'){
        getLiteralFromArt(idSelOptLotaip, element);
        document.getElementById('divOpciones').style.display='none';
        document.getElementById('divCDatos').style.display='block';
        document.getElementById('divMDatos').style.display='block';
        document.getElementById('divDDatos').style.display='block';
    }else if(typeSelOptLotaip=="opt" || textoSelArticulo!='19'){
        element.style.display='none';
        document.getElementById('divOpciones').style.display='block';
        document.getElementById('divCDatos').style.display='none';
        document.getElementById('divMDatos').style.display='none';
        document.getElementById('divDDatos').style.display='none';
        $('#selItemLotaipv2').val('0').trigger('change');
    }
});

function getLiteralFromArt(id, element){
    var html="";
    var url= "/get-literal-lotaip/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            html+="<optgroup label='Seleccione una Opción'>"+
            "<option value='0'>-Seleccione una Opción-</option>";
            $(myArr).each(function(i,v){
                html+="<option value='"+v.id+"'>"+v.literal+".- "+v.descripcion+"</option>";
            });
            html+="</optgroup>";
            setTimeout(() => {
                $('#selItemLotaipv2').html(html);
                $('#selItemLotaipv2').val('0').trigger('change');
                element.style.display='block';
            }, 300);
        }else if(xr.status === 400){
            //console.log('ERROR CONEXION');
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

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function generarAliasV2(){
    var year= $('#selYearLotaip').select2('data')[0].text;
    var mes= $('#selMes').select2('data')[0].text;
    /*var tipo= $('#selOptLotaip').select2('data')[0].id;
    console.log(tipo);*/
    var tipeartopt = typeSelOptLotaip;
    if(tipeartopt=="art" && textoSelArticulo=='19'){
        var val= $('#selItemLotaipv2').select2('data')[0].text;
        if(val!='-Seleccione una Opción-' && year!='-Seleccione una Opción-' && mes!='-Seleccione una Opción-'){
            g_year= year; g_mes= mes; g_literal=val;
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            $('#inputAliasFile').val(year+"_"+mes+"_"+cadena+"_v2");
        }else{
            toastr.info("Debe elegir Año, Mes, Artículo y el Ítem correspondiente...", "!Aviso!");
            $('#inputAliasFile').val('');
        }
    }else{
        var val= $('#selOptLotaip').select2('data')[0].text;
        if(val!='-Seleccione una Opción-' && year!='-Seleccione una Opción-' && mes!='-Seleccione una Opción-'){
            g_year= year; g_mes= mes; g_literal=val;
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            $('#inputAliasFile').val(year+"_"+mes+"_"+cadena+"_v2");
        }else{
            toastr.info("Debe elegir Año, Mes y Artículo correspondiente...", "!Aviso!");
            $('#inputAliasFile').val('');
        }
    }
}

function getAliasInputV2(){
    var year= $('#selYearLotaip').select2('data')[0].text;
    var mes= $('#selMes').select2('data')[0].text;
    var tipeartopt = typeSelOptLotaip;
    if(tipeartopt=="art" && textoSelArticulo=='19'){
        var val= $('#selItemLotaipv2').select2('data')[0].text;
        if(val!='-Seleccione una Opción-' && year!='-Seleccione una Opción-' && mes!='-Seleccione una Opción-'){
            g_year= year; g_mes= mes; g_literal=val;
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            return year+"_"+mes+"_"+cadena+"_v2";
        }else{
            return "";
        }
    }else{
        var val= $('#selOptLotaip').select2('data')[0].text;
        if(val!='-Seleccione una Opción-' && year!='-Seleccione una Opción-' && mes!='-Seleccione una Opción-'){
            g_year= year; g_mes= mes; g_literal=val;
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            return year+"_"+mes+"_"+cadena+"_v2";
        }else{
            return "";
        }
    }
}

function guardarLotaipV2(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    let fInputCCD= document.getElementById("fileCCD");
    let fInputMD= document.getElementById("fileMD");
    let fInputDD= document.getElementById("fileDD");


    var year = $("#selYearLotaip :selected").val();
    var mes = $("#selMes :selected").val();
    var tipeartopt = typeSelOptLotaip;
    var idartopt = idSelOptLotaip;
    var literal = $("#selItemLotaipv2 :selected").val();
    var aliasfile = $("#inputAliasFile").val();
    var lengimg = fileInput.files.length;
    var lengimgCCD = fInputCCD.files.length;
    var lengimgDD = fInputDD.files.length;
    var lengimgMD = fInputMD.files.length;

    //console.log(lengimg, lengimgCCD, lengimgDD, lengimgMD);

    if (year == "0") {
        $("#selYearLotaip").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (mes == "0") {
        $("#selMes").focus();
        swal("Seleccione el Mes", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFile").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else {
        if(tipeartopt=="art"){
            //console.log(tipeartopt, "Ingresa ART");
            if (literal == "0"  && textoSelArticulo=='19') {
                $("#selItemLotaip").focus();
                swal("Seleccione el Literal", "", "warning");
            }else{
                if(textoSelArticulo=='19'){
                    //console.log(textoSelArticulo, "Ingresa 19");
                    if(aliasfile!=getAliasInputV2()){
                        swal('Revise el alias del documento','','warning');
                    }else if (lengimgCCD == 0 || lengimgDD == 0 || lengimgMD == 0) {
                        if(lengimgCCD==0){
                            swal("No ha seleccionado un archivo para Conjunto de Datos", "", "warning");
                        }else if(lengimgMD==0){
                            swal("No ha seleccionado un archivo para Metadatos", "", "warning");
                        }else if(lengimgDD==0){
                            swal("No ha seleccionado un archivo para Diccionario de Datos", "", "warning");
                        }
                    }else if (lengimgCCD > 1 || lengimgDD > 1 || lengimgMD > 1) {
                        swal("Solo se permite un archivo", "", "warning");
                    } else {
                        if(puedeGuardarSM(nameInterfaz) === 'si'){
                        var element = document.querySelector('.savelotaipv2');
                        element.setAttribute("disabled", "");
                        element.style.pointerEvents = "none";
            
                        var data = new FormData(formLOTAIP);
                        data.append("anio", year);
                        data.append("mes", mes);
                        data.append("tipeartopt", tipeartopt);
                        data.append("idartopt", idartopt);
                        data.append("literal", literal);
                        data.append("n_mes", g_mes);
                        data.append("num_art", textoSelArticulo);
                        //console.log(idartopt, tipeartopt);
            
                        setTimeout(() => {
                            sendNewLotaip(token, data, "/store-lotaipv2", element); 
                        }, 700);
                        }else{
                            swal('No tiene permiso para guardar','','error');
                        }
                    }
                }else if(textoSelArticulo=='23'){
                    //console.log(textoSelArticulo, "Ingresa 23");
                    if(aliasfile!=getAliasInputV2()){
                        swal('Revise el alias del documento','','warning');
                    }else if (lengimg == 0) {
                        swal("No ha seleccionado un archivo", "", "warning");
                    }else if (lengimg > 1) {
                        swal("Solo se permite un archivo", "", "warning");
                    } else {
                        if(puedeGuardarSM(nameInterfaz) === 'si'){
                        var element = document.querySelector('.savelotaipv2');
                        element.setAttribute("disabled", "");
                        element.style.pointerEvents = "none";
            
                        var data = new FormData(formLOTAIP);
                        data.append("anio", year);
                        data.append("mes", mes);
                        data.append("tipeartopt", tipeartopt);
                        data.append("idartopt", idartopt);
                        data.append("literal", literal);
                        data.append("n_mes", g_mes);
                        data.append("num_art", textoSelArticulo);
                        //console.log(idartopt, tipeartopt);
            
                        setTimeout(() => {
                            sendNewLotaip(token, data, "/store-lotaipv2", element); 
                        }, 700);
                        }else{
                            swal('No tiene permiso para guardar','','error');
                        }
                    }
                }
            }
        }else if(tipeartopt=="opt"){
            //console.log(tipeartopt, "Ingresa OPT");
            if(idartopt== "0"){
                $("#selOptLotaip").focus();
                swal("Seleccione el Artículo", "", "warning");
            }else{
                if(aliasfile!=getAliasInputV2()){
                    swal('Revise el alias del documento','','warning');
                }else if (lengimg == 0 ) {
                    swal("No ha seleccionado un archivo", "", "warning");
                }else if (lengimg > 1) {
                    swal("Solo se permite un archivo", "", "warning");
                } else {
                    if(puedeGuardarSM(nameInterfaz) === 'si'){
                    var element = document.querySelector('.savelotaipv2');
                    element.setAttribute("disabled", "");
                    element.style.pointerEvents = "none";
        
                    var data = new FormData(formLOTAIP);
                    data.append("anio", year);
                    data.append("mes", mes);
                    data.append("tipeartopt", tipeartopt);
                    data.append("idartopt", idartopt);
                    data.append("literal", literal);
                    data.append("n_mes", g_mes);
                    data.append("num_art", '0');
                    //console.log(idartopt, tipeartopt);
        
                    setTimeout(() => {
                        sendNewLotaip(token, data, "/store-lotaipv2", element); 
                    }, 700);
                    }else{
                        swal('No tiene permiso para guardar','','error');
                    }
                }
            }
        }
        
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewLotaip(token, data, url, el){
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
                    text:'LOTAIP '+g_year+' - '+g_mes+' - '+g_literal+' Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/register-lotaip-v2';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "LOTAIP "+g_year+" - "+g_mes+" - "+g_literal+" ya se encuentra registrada.", "error");
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

function cleanInputs(){
    /*$('input[type=file]').each(
        function(index){  
            var input = $(this);
            input.value = '';
            //console.log('Type: ' + input.attr('type') + 'Name: ' + input.attr('name') + 'Value: ' + input.val());
        }
    );*/
    document.getElementById('file').value= null;
    $('#num-of-files').html('- Ningún archivo seleccionado -');
    $('#images').html('');

    document.getElementById('fileCCD').value= null;
    document.getElementById('fileMD').value= null;
    document.getElementById('fileDD').value= null;

    $('#num-of-filesCD').html('- Ningún archivo seleccionado -');
    $('#imagesCD').html('');

    $('#num-of-filesMD').html('- Ningún archivo seleccionado -');
    $('#imagesMD').html('');

    $('#num-of-filesDD').html('- Ningún archivo seleccionado -');
    $('#imagesDD').html('');
}

function viewopenCD(id){
    var url= '/view-lotaipv2/'+id+'/cd';
    window.open(url, '_BLANK');
    //window.location= url;
}

function viewopenMD(id){
    var url= '/view-lotaipv2/'+id+'/md';
    window.open(url, '_BLANK');
    //window.location= url;
}

function viewopenDD(id){
    var url= '/view-lotaipv2/'+id+'/dd';
    window.open(url, '_BLANK');
    //window.location= url;
}

function viewopenFile(id){
    var url= '/view-lotaipv2/'+id+'/art23';
    window.open(url, '_BLANK');
}

function viewopenOtherFile(id){
    var url= '/view-lotaipv2/'+id+'/optoth';
    window.open(url, '_BLANK');
}

function interfaceupdateCD(id){
    //window.location= '/edit-lotaip/'+id;
    var url= '/edit-lotaipv2/'+id+'/cd';
    window.open(url, '_BLANK');
}

function interfaceupdateMD(id){
    //window.location= '/edit-lotaip/'+id;
    var url= '/edit-lotaipv2/'+id+'/md';
    window.open(url, '_BLANK');
}

function interfaceupdateDD(id){
    //window.location= '/edit-lotaip/'+id;
    var url= '/edit-lotaipv2/'+id+'/dd';
    window.open(url, '_BLANK');
}

function interfaceupdateFile(id){
    //window.location= '/edit-lotaip/'+id;
    var url= '/edit-lotaipv2/'+id+'/art23';
    window.open(url, '_BLANK');
}

function interfaceupdateOtherFile(id){
    //window.location= '/edit-lotaip/'+id;
    var url= '/edit-lotaipv2/'+id+'/optoth';
    window.open(url, '_BLANK');
}

function downloadCD(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-lotaipv2/'+id+'/cd';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function downloadMD(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-lotaipv2/'+id+'/md';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function downloadDD(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-lotaipv2/'+id+'/dd';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function downloadFile(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-lotaipv2/'+id+'/art23';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function downloadOtherFile(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    window.location='/download-lotaipv2/'+id+'/optoth';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfilelov2');
    var eldivfile= document.getElementById('cardListLotaipv2');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isLotaipv2= false;
    }
}

function generarAliasv2E(){
    toastr.info("No se permite generar el Alias...", "!Aviso!");
}

function actualizarLotaipv2(){
    var token= $('#token').val();

    var id= $('#idlotaipv2').val();
    let fileInput = document.getElementById("fileEdit");
    var lengimg = fileInput.files.length;

    if(isLotaipv2==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else {
            if(puedeActualizarSM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            var data = new FormData(formLOTAIPv2);
            data.append("islotaip", isLotaipv2);
            setTimeout(() => {
                sendUpdateLotaipv2(token, data, "/update-lotaipv2"); 
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }else{
        if(puedeActualizarSM(nameInterfaz) === 'si'){
        $('#modalFullSend').modal('show');
        var data = new FormData(formLOTAIPv2);
        data.append("islotaip", isLotaipv2);
        setTimeout(() => {
            sendUpdateLotaipv2(token, data, "/update-lotaipv2");
        }, 700);
        }else{
            swal('No tiene permiso para actualizar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateLotaipv2(token, data, url){
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
                    text:'LOTAIP Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    //window.location = '/lotaip-v2';
                    window.close();
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