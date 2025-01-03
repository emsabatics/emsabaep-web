//FUNCION PARA REGISTRAR INFORMACION DE CONTACTO
function guardarNewLocation(){
    var token= $('#token').val();
    var latitud= sendlat;
    var longitud= sendlong;
    var direccion= $('#inputDireccionLocation').val();
    var nombre= $('#inputNameLocation').val();

    if(latitud==0){
        swal('Por favor mueva el marcador en el mapa hacia la ubicación de la Institución','','warning');
    }else if(direccion==''){
        $('#inputDireccionLocation').focus();
        swal('Por favor ingrese la dirección de la nueeva Ubicación','','warning');
    }else if(nombre==''){
        $('#inputNameLocation').focus();
        swal('Por favor ingrese el nombre de la nueva Ubicación','','warning');
    }else{
        getParams(latitud, longitud, direccion, nombre, token);
    }
}

function getParams(latitud, longitud, direccion, nombre, token){
    var element = document.getElementById("btnSaveNewLocation");
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";

    var datosC= {};
    var detalleArray = [];
    datosC.tipo= "geolocalizacion";
    datosC.coordenadas= latitud+"&"+longitud;
    datosC.direccion= direccion;
    datosC.nombre= nombre;
    detalleArray.push({...datosC});

    sendContact(token, JSON.stringify(detalleArray), '/registro-location-contacto', element);
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendContact(token, data,url, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    //xr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
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
                    window.location="contactos";
                },1500);
            }else if(myArr.resultado==false){
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal('No se pudo guardar el registro','','error');
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

/* FUNCION PARA ACTUALIZAR EL REGISTRO DE GEOLOCALIZACION */
function actualizarRegistroGeo(){
    var id= $('#idcontactogeo').val();
    var latitud= $('#inputELatitud').val();
    var longitud= $('#inputELongitud').val();
    var detalle= $('#inputEditNameLocation').val();
    var detalle2= $('#inputEditDireccionLocation').val();
    var token= $('#token').val();

    var detmin= detalle.toLowerCase();
    var index= detmin.indexOf("matriz");
    //console.log(index);

    if(latitud==''){
        $('#inputELatitud').focus();
        swal('Por favor mueva el marcador en el mapa hacia la ubicación de la Institución','','warning');
    } else if(longitud==''){
        $('#inputELongitud').focus();
        swal('Por favor mueva el marcador en el mapa hacia la ubicación de la Institución','','warning');
    } else if(detalle==''){
        $('#inputEditNameLocation').focus();
        swal('Por favor ingrese el nombre de la Ubicación','','warning');
    } else if(detalle2=='' && index==-1){
        $('#inputEditDireccionLocation').focus();
        swal('Por favor ingrese la dirección de la Ubicación','','warning');
    } else{

        var element = document.querySelector('#btnUpdateNewLocation');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var formData= new FormData();
        formData.append("id", id);
        formData.append("latitud", latitud);
        formData.append("longitud", longitud);
        formData.append("detalle", detalle);
        formData.append("detalle2", detalle2);
        sendUpdateContacto(token, formData, '/actualizar-contacto-geo', 1, element);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA ACTUALIZAR */
function sendUpdateContacto(token, data, url, op, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Registro Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location='/contactos';
                },1200);
            }else if(myArr.resultado==false){
                swal('No se pudo actualizar el registro','','error');
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

function urlback(){
    window.location='/contactos';
}