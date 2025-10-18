function viewnew(id){
    window.location='/vistamain-noticia/'+id+'/main';
}

function verVideo(url){
    window.open(url, '_blank');
}

function utf8_to_b64( str ) {
    return window.btoa(unescape(encodeURIComponent( str )));
}