function viewnew(id){
    window.location='/vistamain-noticia/'+utf8_to_b64(id)+'/main';
}

function utf8_to_b64( str ) {
    return window.btoa(unescape(encodeURIComponent( str )));
}