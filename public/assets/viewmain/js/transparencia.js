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

function downloadfileCD(id){
    window.location='/download-lotaipv2/'+id+'/cd';
}

function downloadfileMD(id){
    window.location='/download-lotaipv2/'+id+'/md';
}

function downloadfileDD(id){
    window.location='/download-lotaipv2/'+id+'/dd';
}

function downloadFile(id){
    window.location='/download-lotaipv2/'+id+'/art23';
}

function downloadOtherFile(id){
    window.location='/download-lotaipv2/'+id+'/optoth';
}

function downloadlotaipv1(id){
    window.location='/download-lotaip/'+id;
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
    window.location='/download-rendicionc/'+id;
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
    window.location='/download-docfinanciero/'+id;
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
    window.location='/download-docoperativo/'+id;
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
    window.location='/download-doclaboral/'+id;
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
    window.location='/download-ley/'+id;
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
    window.location='/download-auditoria/'+id;
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
        window.location='/biblioteca-transparencia/doc-administrativa/procesos/v1';
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
    window.location='/download-poa/'+id+'/'+tipo;
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
    if(cat=='doc'){
        window.location='/download-pac/'+id+'/'+tipo;
    }else if(cat=='resol'){
        window.location='/download-ra/'+id+'/'+tipo;
    }
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
    window.location='/download-leytransparencia/'+id;
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
    window.location='/download-pliego/'+id;
}

function downloaddocadmin(id){
    window.location='/download-docadministrativo/'+id;
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
//========================BIBLIOTECA VIRTUAL========================