/*
===================================LOTAIP==========================================================
*/
function viewdetail(id){
    window.location='/view-desc-lotaip/v1/'+id;
}

function viewdetailv2(id){
    window.location='/view-desc-lotaip/v2/'+id;
}

function comeback(){
    window.location='/biblioteca-transparencia';
}

function comeback_list(opcion){
    if(opcion==1){
        window.location='/transp-lotaip';
    }else if(opcion==2){
        window.location='/transp-lotaip2';
    }
}

function utf8_to_b64( str ) {
    return window.btoa(unescape(encodeURIComponent( str )));
}

$("#accordion").on("show.bs.collapse hide.bs.collapse", e => {
    $(e.target)
    .prev()
    .find("i:last-child")
    .toggleClass("fa-plus fa-minus");
});

function showToastInfo(){
    toastr.info('Preparando archivo.','Por favor, espere...',{
            "positionClass": "toast-top-right",
            "closeButton": false,
            "timeOut": "2500"
    });
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DEL INCREMENTO */
function sendIncrementLotaip(data, token, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //ok
        }else if(xr.status === 400){
            //error
        }
    };
    xr.send(data);
}

function downloadfileCD(id){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idcd", id);
    sendIncrementLotaip(data, token, "/lotaipv2-increment-cd");
    setTimeout(() => {
        window.location='/download-lotaipv2/'+id+'/cd';
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function downloadfileMD(id){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idmd", id);
    sendIncrementLotaip(data, token, "/lotaipv2-increment-md");
    setTimeout(() => {
        window.location='/download-lotaipv2/'+id+'/md';
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function downloadfileDD(id){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("iddd", id);
    sendIncrementLotaip(data, token, "/lotaipv2-increment-dd");
    setTimeout(() => {
        window.location='/download-lotaipv2/'+id+'/dd';
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function downloadFile(id){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idff", id);
    sendIncrementLotaip(data, token, "/lotaipv2-increment");
    setTimeout(() => {
        window.location='/download-lotaipv2/'+id+'/art23';
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function downloadOtherFile(id){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idff", id);
    sendIncrementLotaip(data, token, "/lotaipv2-increment");
    setTimeout(() => {
        window.location='/download-lotaipv2/'+id+'/optoth';
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function downloadlotaipv1(id){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/lotaipv1-increment");
    setTimeout(() => {
        window.location='/download-lotaip/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

/*
===================================LOTAIP==========================================================
*/

/*
================================RENDICIÓN DE CUENTAS================================================
*/
function comeback_rc(){
    window.location='/biblioteca-transparencia';
}

function view_rc(idanio){
    window.location='/view-desc-rc/v1/'+idanio;
}

function comeback_listrc(){
    window.location='/transparencia/rendicion-cuenta';
}

function viewopenrendicionc(idanio, id, idrc){
    window.location='/view/view-rendicion-cuenta/'+idanio+'/'+id+'/'+idrc;
}

function comeback_playrc(idanio){
    window.location='/view-desc-rc/v1/'+idanio;
}

function downloadrendicionc(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/rendicionc-increment");
    setTimeout(() => {
        window.location='/download-rendicionc/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}
/*
================================RENDICIÓN DE CUENTAS================================================
*/

/*
================================DOCUMENTACIÓN FINANCIERA================================================
*/
function comeback_docfin(){
    window.location='/biblioteca-transparencia';
}

function view_docfin(idanio){
    window.location='/view-desc-docfin/v1/'+idanio;
}

function comeback_listdocfin(){
    window.location='/transparencia/doc-financiera';
}

function downloaddocfin(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/docfin-increment");
    setTimeout(() => {
         window.location='/download-docfinanciero/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}
/*
================================DOCUMENTACIÓN FINANCIERA================================================
*/
/*
================================DOCUMENTACIÓN OPERATIVA================================================
*/
function comeback_docopt(){
    window.location='/biblioteca-transparencia';
}

function view_docopt(idanio){
    window.location='/view-desc-docopt/v1/'+idanio;
}

function comeback_listopt(){
    window.location='/transparencia/doc-operativa';
}

function downloaddocopt(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/docoperativo-increment");
    setTimeout(() => {
        window.location='/download-docoperativo/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}
/*
================================DOCUMENTACIÓN OPERATIVA================================================
*/

/*
================================DOCUMENTACIÓN LABORAL================================================
*/
function view_doclab(idanio){
    window.location='/view-desc-doclab/v1/'+idanio;
}

function comeback_doclab(){
    window.location='/biblioteca-transparencia';
}

function comeback_listlab(){
    window.location='/transparencia/doc-laboral';
}

function downloaddoclab(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/doclaboral-increment");
    setTimeout(() => {
        window.location='/download-doclaboral/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}
/*
================================DOCUMENTACIÓN LABORAL================================================
*/

/*
================================DOCUMENTACIÓN LEGAL================================================
*/
function comeback_listreglamento(){
    window.location='/biblioteca-transparencia';
}

function downloadReglamento(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idrr", id);

    sendIncrementReglamento(data, token, "/reglamento-increment");
    
    setTimeout(() => {
        window.location='/download-ley/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DEL INCREMENTO */
function sendIncrementReglamento(data, token, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //ok
        }else if(xr.status === 400){
            //error
        }
    };
    xr.send(data);
}
/*
================================DOCUMENTACIÓN LEGAL================================================
*/

/*
================================DOCUMENTACIÓN AUDITORIA================================================
*/
function comeback_docauditoria(){
    window.location='/biblioteca-transparencia';
}

function view_docauditoria(idanio){
    window.location='/view-desc-docaud/v1/'+idanio;
}

function comeback_listdocaud(){
    window.location='/transparencia/auditoria';
}

function downloaddocaud(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);

    sendIncrementReglamento(data, token, "/auditoria-increment");
    
    setTimeout(() => {
        window.location='/download-auditoria/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}
/*
================================DOCUMENTACIÓN AUDITORIA================================================
*/
/*
================================DOCUMENTACIÓN ADMINISTRATIVA================================================
*/
function comeback_docadmin(){
    window.location='/biblioteca-transparencia';
}

function view_docadmin(option){
    if(option=='ley_t'){
        window.location='/biblioteca-transparencia/doc-administrativa/view-ley-tr/v1';
    }else if(option=='pac'){
        window.location='/biblioteca-transparencia/doc-administrativa/pac/v1';
    }else if(option=='poa'){
        window.location='/biblioteca-transparencia/doc-administrativa/poa/v1';
    }else if(option=='medios_v'){
        window.location='/biblioteca-transparencia/doc-administrativa/mediosv/v1';
    }else if(option=='pliego_t'){
        window.location='/biblioteca-transparencia/doc-administrativa/pliegot/v1';
    }else if(option=='proceso_s'){
        //window.location='/biblioteca-transparencia/doc-administrativa/procesos/v1';
        window.open(url_sercop, '_blank');
    }else if(option=='other_d'){
        window.location='/biblioteca-transparencia/doc-administrativa/other_d/v1';
    }
}

//========================POA========================
function view_docpoa(idanio){
    window.location='/biblioteca-transparencia/doc-administrativa/view-desc-docpoa/v1/'+idanio;
}

function comeback_listyearpoa(){
    window.location='/biblioteca-transparencia/doc-administrativa/poa/v1';
}

function downloadpoaT(id, tipo){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idpp", id);
    data.append("tipo", tipo);

    sendIncrementPoa(data, token, "/poa-increment");
    
    setTimeout(() => {
        window.location='/download-poa/'+id+'/'+tipo;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DEL INCREMENTO */
function sendIncrementPoa(data, token, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //ok
        }else if(xr.status === 400){
            //error
        }
    };
    xr.send(data);
}
//========================POA========================

//========================PAC========================
function view_docpac(idanio){
    window.location='/biblioteca-transparencia/doc-administrativa/view-desc-docpac/v1/'+idanio;
}

function comeback_listyearpac(){
    window.location='/biblioteca-transparencia/doc-administrativa/pac/v1';
}

function downloadpacT(id, cat, tipo){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idpp", id);
    data.append("tipo", tipo);

     if(cat=='doc'){
        sendIncrementPac(data, token, "/pac-increment");
    }else if(cat=='resol'){
        sendIncrementPac(data, token, "/pac-increment-resol");
    }
    
    setTimeout(() => {
        if(cat=='doc'){
            window.location='/download-pac/'+id+'/'+tipo;
        }else if(cat=='resol'){
            window.location='/download-ra/'+id+'/'+tipo;
        }
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DEL INCREMENTO */
function sendIncrementPac(data, token, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //ok
        }else if(xr.status === 400){
            //error
        }
    };
    xr.send(data);
}
//========================PAC========================

function view_docmediosv(idanio){
    window.location='/biblioteca-transparencia/doc-administrativa/mediosv/view-desc-docmv/v1/'+idanio;
}

function view_list_docadmin(idanio){
    window.location='/biblioteca-transparencia/doc-administrativa/view-desc-docadmin/v1/'+idanio;
}

/* funcion para descargar la ley de transparencia en la vista usuario */
function downloadLeyT(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/leyt-increment");
    setTimeout(() => {
        window.location='/download-leytransparencia/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

//========================MEDIOS DE VERIFICACION========================
function comeback_listyearmediosv(){
    window.location='/biblioteca-transparencia/doc-administrativa/mediosv/v1';
}

/* funcion para descargar los medios de verificacion en la vista usuario */
function downloadMediosV(id){
    window.location='/download-mediosverificacion/'+id;
}
//========================MEDIOS DE VERIFICACION========================

function downloadPliegoT(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/pliegot-increment");
    setTimeout(() => {
        window.location='/download-pliego/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function downloaddocadmin(id){
    var element = document.querySelector('.btntable');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", id);
    sendIncrementGeneral(data, token, "/docadmin-increment");
    setTimeout(() => {
        window.location='/download-docadministrativo/'+id;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function comeback_listdocadmin(){
    window.location='/biblioteca-transparencia/doc-administrativa/other_d/v1';
}

function comeback_administrativo(){
    window.location='/transparencia/doc-administrativa';
}
/*
================================DOCUMENTACIÓN ADMINISTRATIVA================================================
*/

//========================BIBLIOTECA VIRTUAL========================
function view_subcatgallery(idcat){
    window.location='/biblioteca-virtual-gallery-subcat/'+idcat;
}

function view_open_gallery(idsubcat){
    window.location='/biblioteca-virtual/gallery/'+idcat+'/'+idsubcat;
}

function comeback_subcat_gallery(){
    window.location='/biblioteca-virtual-gallery-subcat/'+idcat;
}


function view_subcatother(idcat){
    window.location='/biblioteca-virtual-subcat/'+idcat;
}

function view_open_archivos(idsubcat){
    window.location='/biblioteca-virtual/archivos/'+idcat+'/'+idsubcat;
}

function downloadFileBv(idfile){
    var element = document.querySelector('.btnlistop');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";
    showToastInfo();
    var token = $('#token').val();
    var data = new FormData();
    data.append("idfile", idfile);
    sendIncrementLotaip(data, token, "/filebibliovir-increment");
    setTimeout(() => {
        window.location='/download-filebibliovirtual/'+idfile;
        element.removeAttribute("disabled");
        element.style.removeProperty("pointer-events"); 
    }, 2000);
}

function comeback_subcat_files(){
    window.location= '/biblioteca-virtual-subcat/'+idcat
}

function comeback_cat_biblioteca(){
    window.location='/biblioteca-virtual';
}

function comeback_listyearmediosv(){
    window.location='/biblioteca-transparencia/doc-administrativa/mediosv/v1';
}

/* funcion para descargar los documentos en la vista usuario */
function downloadfilevirtual(idf, opcion){
    if(opcion==1){
        window.location='/download-docvirtual/'+idf+'/withsc';
    }else if(opcion==2){
        window.location='/download-docvirtual/'+idf+'/nosc';
    }
}

function view_subcatvideo(idcat){
    window.location='/biblioteca-virtual-video-subcat/'+idcat;
}

function view_open_video(idsubcat){
    window.location='/biblioteca-virtual/video/'+idcat+'/'+idsubcat;
}
//========================BIBLIOTECA VIRTUAL========================

function utf8_to_b64( str ) {
    return window.btoa(unescape(encodeURIComponent( str )));
}


/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DEL INCREMENTO */
function sendIncrementGeneral(data, token, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //ok
        }else if(xr.status === 400){
            //error
        }
    };
    xr.send(data);
}

function openmodalvideo(i){
    const container = document.getElementById('div_videoinfo');
    container.innerHTML= '';

    // URL del video (puedes pasarla desde Blade)
    const videoUrl = "/videos-bibliotecavirtual/"+arrayVideo[i];
    // Crear el elemento <video>
    const video = document.createElement('video');

    // Asignar atributos
    video.src = videoUrl;
    /*video.width = 640;   // opcional
    video.height = 360;  // opcional*/
    video.controls = true; // muestra los controles
    video.controlsList = "nodownload"; // oculta el botón de descarga
    video.preload = "metadata";
    video.classList.add('responsive-video', 'rounded-lg', 'shadow-md')
    // También puedes evitar clic derecho (opcional)
    video.addEventListener('contextmenu', e => e.preventDefault());

    // Crear el contenedor donde irá el video
    container.appendChild(video);

    setTimeout(() => {
        $('#modal_info_video').modal('show');
    }, 900);
}

function cerrarModalVideo(){
    $('#modal_info_video').modal('hide');
    const container = document.getElementById('div_videoinfo');
    container.innerHTML= '';
}

function comeback_subcat_video(){
    window.location='/biblioteca-virtual-video-subcat/'+idcat;
}