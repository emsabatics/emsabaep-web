
function getLogos(){
    var html="";
    var token= $('#token').val();
    var estado= '1';
    
    var data = new FormData();
    data.append("estado", estado);

    var url= "/logo/get-logos";
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);
            //console.log(myArr);
            $(myArr).each(function(i,v){
               if(v.archivo.includes('blanco')){
                imglogoblanco= v.archivo;
               }else{
                imglogonormal= v.archivo;
               }
            });
            //console.log(imglogoblanco, imglogonormal);
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    }

    xr.send();
}