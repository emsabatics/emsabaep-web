//FUNCION QUE ABRE INTERFAZ PARA REGISTRAR CONTACTO
function routewritecontact() {
    var url = '/interface-reg-contacto';
    window.location = url;
}

//FUNCION QUE ABRE INTERFAZ PARA REGISTRAR UBICACIONES EN EL MAPA
function addlocationmap(){
    var url = '/interface-reg-location';
    window.location = url;
}

//FUNCION PARA REGISTRAR INFORMACION DE CONTACTO
function guardarContacto(){
    var token= $('#token').val();
    var latitud= sendlat;
    var longitud= sendlong;
    var direccion= $('#inputDireccionContact').val();
    var telefono= $('#inputTelefonoContact').val();
    var telefono2= $('#inputTelefonoContact2').val();
    var email= $('#inputEmailContact').val();
    var hora_a= $('#hora_a').val();
    var hora_c= $('#hora_c').val();

    if(latitud==0){
        swal('Por favor mueva el marcador en el mapa hacia la ubicación de la Institución','','warning');
    }else if(direccion==''){
        $('#inputDireccionContact').focus();
        swal('Por favor ingrese la dirección de la Institución','','warning');
    }else if(telefono==''){
        $('#inputTelefonoContact').focus();
        swal('Por favor ingrese el teléfono de contacto de la Institución','','warning');
    }else if(email==''){
        $('#inputEmailContact').focus();
        swal('Por favor ingrese el correo de contacto de la Institución','','warning');
    }else if(hora_a==''){
        $('#hora_a').focus();
        swal('Por favor ingrese la hora de apertura','','warning');
    }else if(hora_c==''){
        $('#hora_c').focus();
        swal('Por favor ingrese la hora de cierre','','warning');
    }else{
        var isemail= validarEmail(email);
        if(isemail==0){
            $('#inputEmailContact').focus();
            swal('Dirección de correo Inválida','','error');
        }else if(isemail==1){
            if(telefono.length<10){
                $('#inputTelefonoContact').focus();
                swal('Número de Teléfono Inválido','','error');
            }else{
                getParams(latitud, longitud, direccion, telefono, telefono2, email, hora_a, hora_c, token);
            }
        }
    }
}

function getParams(latitud, longitud, direccion, telefono, telefono2, email, hora_a, hora_c, token){
    var element = document.getElementById("btnSaveContact");
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";

    var datosC= {};
    var detalleArray = [];
    datosC.tipo= "geolocalizacion";
    datosC.detalle= latitud+"&"+longitud;
    datosC.hora= null;
    datosC.horac=null;
    datosC.telefono=null;
    datosC.telefono2= null;
    datosC.detalle2= null;
    detalleArray.push({...datosC});

    datosC.tipo= "direccion";
    datosC.detalle= direccion;
    datosC.hora= null;
    datosC.horac=null;
    datosC.telefono=null;
    datosC.telefono2= null;
    datosC.detalle2= null;
    detalleArray.push({...datosC});

    datosC.tipo= "telefono";
    datosC.detalle= null;
    datosC.hora= null;
    datosC.horac=null;
    datosC.telefono= telefono;
    datosC.telefono2= telefono2;
    datosC.detalle2= null;
    detalleArray.push({...datosC});

    datosC.tipo= "email";
    datosC.detalle= email;
    datosC.hora= null;
    datosC.horac=null;
    datosC.telefono=null;
    datosC.telefono2= null;
    datosC.detalle2= null;
    detalleArray.push({...datosC});

    datosC.tipo= "houratencion";
    datosC.detalle= "Lunes - Viernes";
    datosC.hora= hora_a;
    datosC.horac=hora_c;
    datosC.telefono=null;
    datosC.telefono2= null;
    datosC.detalle2= null;
    detalleArray.push({...datosC});

    sendContact(token, JSON.stringify(detalleArray), '/registro-contacto', element);
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

//FUNCION QUE OBTIENE EL LISTADO DE LOS CONTACTOS REGISTRADOS
function getListadoContactos(contactos){
    var html="";
    var con=1;
    if(contactos.length===0){
        html+="<tr style='text-align: center;'>"+
                "<td colspan='4'>No hay registros</td>"+
            "</tr>";
    }else{
        document.getElementById('btn_insert_info').style.display='none';
    }

    $(contactos).each(function(i,v){
        let rename='';
        let redetalle='';
        let opc=0;
        if(v.tipo_contacto=="geolocalizacion"){
            rename="Geolocalización";
            if(v.detalle_2=='' || v.detalle_2==null){
                redetalle= v.detalle;
            }else{
                redetalle= v.detalle+'<br/> <strong>Dir: </strong>'+v.detalle_2;
            }
            opc=1;
        }else if(v.tipo_contacto=="direccion"){
            rename="Dirección";
            redetalle= v.detalle;
            opc=2;
        }else if(v.tipo_contacto=="telefono"){
            /*let largo= v.detalle.length;
            let index= v.detalle.indexOf("&");
            //console.log(index, largo);
            if((index+1)==largo){
                rename="Teléfono";
                redetalle= v.detalle.substring(0,index);
            }else{
                rename="Teléfonos";
                redetalle= v.detalle.replace('&','<br>');
            }
            opc=2;*/
            if(v.telefono_2==null || v.telefono_2==''){
                rename="Teléfono";
                redetalle= v.telefono;
            }else{
                rename="Teléfonos";
                redetalle= v.telefono+'<br>'+v.telefono_2;
            }
            opc=2;
        }else if(v.tipo_contacto=="houratencion"){
            rename="Horario Atención";
            redetalle= v.detalle+"<br>"+v.hora_a+" / "+v.hora_c;
            opc=3;
        }else{
            rename="Email";
            redetalle= v.detalle;
            opc=2;
        }

        //var confid= utf8_to_b64(v.id);
        //confid= '"'+confid+'"';
        var confid= v.id;

        html+="<tr id='Tr"+i +"'>"+
            "<td>"+con+"</td>"+
            "<td>"+rename+"</td>";
            if(v.latitud==null){
                html+="<td>"+redetalle+"</td>";
            }else{
                html+="<td>"+redetalle+"<br> <strong>Lat:</strong> "+v.latitud+" <br> <strong>Long:</strong> "+v.longitud+"</td>";
            }

            html+="<td>"+
                "<div class='dropdown show'>"+
                    "<a class='btn btn-secondary dropdown-toggle' href='#' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
                        "<i class='fas fa-cog'></i>"+
                    "</a>"+
                    "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>"+
                        "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemContact("+confid+", "+opc+")'>Editar</a>";
                    html+="</div>"+
                "</div>"+
            "</td>"+
        "</tr>";
        con++;
    });

    $('#tablaListadoContact > tbody').html(html);
    setTimeout(function () {
        $('#modalCargando').modal('hide');
    }, 700);
}

/* FUNCION QUE CARGA DATOS DEL ITEM SELECCIONADO EN EL MODAL */
function editarItemContact(id, op){
    if(op==1){
        window.location='/interface-update-location/'+utf8_to_b64(id)+'/vextend';
        /*toastr.info("Generando mapa....", "!Aviso!");
        var coordenadas= new Array();
        var infoLocation= new Array();
        coordenadas= getLatitudLongitud(id);
        infoLocation= getInfoLocation(id);
        setTimeout(function(){
            $('#inputELatitud').val(coordenadas[1]);
            $('#inputELongitud').val(coordenadas[0]);
            $('#inputEditNameLocation').val(infoLocation[0]);
            $('#inputEditDireccionLocation').val(infoLocation[1]);
            $('#idcontactogeo').val(id);
            var marker;
            const coordinates = document.getElementById('coordinates');
            map = new mapboxgl.Map({
                container: 'map', // container ID
                style: 'mapbox://styles/mapbox/streets-v12', // style URL
                center: coordenadas, // starting position [lng, lat]
                zoom: 15, // starting zoom
            });
        
            marker = new mapboxgl.Marker({draggable: true})
                .setLngLat(coordenadas)
                .addTo(map);
        
            function onDragEnd() {
                const lngLat = marker.getLngLat();
                coordinates.style.display = 'block';
                coordinates.innerHTML = `Longitude: ${lngLat.lng}<br />Latitude: ${lngLat.lat}`;
                sendlat=lngLat.lat; sendlong=lngLat.lng;
                $('#inputELatitud').val(lngLat.lat);
                $('#inputELongitud').val(lngLat.lng);
            }
        
            marker.on('dragend', onDragEnd);

            map.on('load', function () {
                map.resize();
            });
            setTimeout(() => {
                $('#modalEditContactGeo').modal('show');
            }, 1200);
        },900);*/

        //var coordenadas= [-79.392325, -1.707547];
    }else if(op==2){
        var url= "/get-contact-item/"+id;
        var contentType = "application/x-www-form-urlencoded;charset=utf-8";
        var xr = new XMLHttpRequest();
        xr.open('GET', url, true);

        xr.onload = function(){
            if(xr.status === 200){
                var html="";
                var myArr = JSON.parse(this.responseText);
                $(myArr).each(function(i,v){
                    if(v.tipo_contacto=="direccion"){
                        html+="<div class='form-group mb-3'>"+
                        "<label for='textContactDetalle' >Dirección:</label>"+
                        "<textarea name='textContactDetalle' id='textContactDetalle' cols='5' rows='2' class='form-control' autocomplete='off'>"+v.detalle+"</textarea>"+
                        "</div>";
                    }else if(v.tipo_contacto=="telefono"){
                        let largo= v.detalle.length;
                        let index= v.detalle.indexOf("&");
                        //console.log(index, largo);
                        if((index+1)==largo){
                            let redetalle= v.detalle.substring(0,index);
                            html+="<div class='form-group mb-3'>"+
                            "<label for='textContactDetalle' >Teléfono:</label>"+
                            "<input type='text' id='textContactDetalle' class='form-control' placeholder='Teléfono' value='"+redetalle+"' autocomplete='off' onkeypress='return solonumeros(event);'>"+
                            "<br><input type='text' id='textContactDetalle2' class='form-control' placeholder='Teléfono 2 (opcional)' autocomplete='off' onkeypress='return solonumeros(event);'>"+
                            "</div>";
                        }else{
                            let redetalle= v.detalle.substring(0,index);
                            let redetalle2= v.detalle.substring((index+1),largo);
                            html+="<div class='form-group mb-3'>"+
                            "<label for='textContactDetalle' >Teléfonos:</label>"+
                            "<input type='text' id='textContactDetalle' class='form-control' placeholder='Teléfono' value='"+redetalle+"' autocomplete='off' onkeypress='return solonumeros(event);'>"+
                            "<br><input type='text' id='textContactDetalle2' class='form-control' placeholder='Teléfono 2 (opcional)' value='"+redetalle2+"' autocomplete='off' onkeypress='return solonumeros(event);'>"+
                            "</div>";
                        }
                    }else{
                        html+="<div class='form-group mb-3'>"+
                        "<label for='textContactDetalle' >Email:</label>"+
                        "<input type='text' id='textContactDetalle' class='form-control' placeholder='Email' value='"+v.detalle+"' autocomplete='off'>"+
                        "</div>";
                    }

                    $('#idcontactoditeem').val(v.id);
                    $('#textContactDetalle').val(v.usuario);
                });
                $('#formContactEdit').html(html);
                setTimeout(() => {
                    $('#modalEditContact').modal('show');
                }, 300);
            }else if(xr.status === 400){
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        }

        xr.send(null);
    }else if(op==3){
        var url= "/get-contact-item/"+id;
        var contentType = "application/x-www-form-urlencoded;charset=utf-8";
        var xr = new XMLHttpRequest();
        xr.open('GET', url, true);

        xr.onload= function(){
            if(xr.status === 200){
                var myArr = JSON.parse(this.responseText);
                $(myArr).each(function(i,v){
                    if(v.tipo_contacto=="houratencion"){
                        $('#idcontactohour').val(v.id);

                        let indexa= v.hora_a.indexOf("A");
                        let hora= v.hora_a.substring(0, (indexa-1));
                        if(hora.length<5){
                            hora="0"+hora;
                        }
                        $('#inputHourA').val(hora);

                        let indexc= v.hora_c.indexOf("P");
                        let horc= v.hora_c.substring(0, (indexc-1));
                        if(horc.length<5){
                            horc="0"+horc;
                        }
                        $('#inputHourC').val(horc);
                    }
                });
            }else if(xr.status === 400){
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        }

        xr.send(null);
       
        setTimeout(() => {
            $('#modalEditContactHour').modal('show');
        }, 300);
    }
}

function getLatitudLongitud(id){
    var coordenadas= new Array();
    var url= "/get-contact-item/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload= function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                if(v.tipo_contacto=="geolocalizacion"){
                    coordenadas[0]= parseFloat(v.longitud);
                    coordenadas[1]= parseFloat(v.latitud);
                }
            });
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    }

    xr.send(null);
    
    return coordenadas;
}

function getInfoLocation(id){
    var coordenadas= new Array();
    var url= "/get-contact-item/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload= function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                if(v.tipo_contacto=="geolocalizacion"){
                    coordenadas[0]= v.detalle; 
                    coordenadas[1]= v.detalle_2;
                }
            });
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    }

    xr.send(null);
    
    return coordenadas;
}

/* FUNCION PARA ACTUALIZAR EL REGISTRO DE GEOLOCALIZACION */
function actualizarRegistroGeo(){
    var id= $('#idcontactogeo').val();
    var latitud= $('#inputELatitud').val();
    var longitud= $('#inputELongitud').val();
    var detalle= $('#inputEditNameLocation').val();
    var detalle2= $('#inputEditDireccionLocation').val();
    var token= $('#token').val();

    if(latitud==''){
        $('#inputELatitud').focus();
        swal('Por favor mueva el marcador en el mapa hacia la ubicación de la Institución','','warning');
    } else if(longitud==''){
        $('#inputELongitud').focus();
        swal('Por favor mueva el marcador en el mapa hacia la ubicación de la Institución','','warning');
    } else if(detalle==''){
        $('#inputEditNameLocation').focus();
        swal('Por favor ingrese el nombre de la Ubicación','','warning');
    } else if(detalle2==''){
        $('#inputEditDireccionLocation').focus();
        swal('Por favor ingrese la dirección de la Ubicación','','warning');
    } else{
        var formData= new FormData();
        formData.append("id", id);
        formData.append("latitud", latitud);
        formData.append("longitud", longitud);
        formData.append("detalle", detalle);
        formData.append("detalle2", detalle2);
        sendUpdateContacto(token, formData, '/actualizar-contacto-geo', 1);
    }
}

function cerrarModalGeo(){
    $('#modalEditContactGeo').modal('hide');
    $('#coordinates').html('');
}

/* FUNCION PARA ACTUALIZAR EL REGISTRO DE DIRECCION, TELEFONO, EMAIL */
function actualizarRegistroDiTeEm(){
    var token= $('#token').val();
    var id= $('#idcontactoditeem').val();
    var detalle= $('#textContactDetalle').val();
    var telefono2= '';
    if (document.getElementById("textContactDetalle2") !== null) {
        telefono2= $('#textContactDetalle2').val();
    }

    if(detalle==''){
        $('#textContactDetalle').focus();
        swal('Por favor rellenar el campo requerido','','warning');
    } else{
        if(isNum(detalle)){
            //es número
            if(detalle.length<10){
                swal('Número de Teléfono Inválido','','error');
            }else{
                if(telefono2!=''){
                    if(telefono2.length<=9){
                        swal('Número de Teléfono Inválido','','error');
                    }else{
                        var formData= new FormData();
                        formData.append("id", id);
                        formData.append("detalle", detalle);
                        formData.append("telefono2", telefono2);
                        formData.append("tipo","numero");
                        sendUpdateContacto(token, formData, '/actualizar-contacto-diteem', 2);
                    }
                }else{
                    var formData= new FormData();
                    formData.append("id", id);
                    formData.append("detalle", detalle);
                    formData.append("telefono2", telefono2);
                    formData.append("tipo","numero");
                    sendUpdateContacto(token, formData, '/actualizar-contacto-diteem', 2);
                }
            }
            /*if(detalle[1]!=5 && detalle.length<10){
                swal('Debe ingresar los 10 dígitos del número telefónico', '','warning');
            }else if(detalle[1]==5 && detalle.length<9){
                swal('Debe ingresar los 10 dígitos del número telefónico convencional', '','warning');
            }else{
                if(telefono2!=''){
                    if(telefono2[1]!=5 && telefono2.length<10){
                        swal('Debe ingresar los 10 dígitos del número telefónico', '','warning');
                    }else if(telefono2[1]==5 && telefono2.length<9){
                        swal('Debe ingresar los 10 dígitos del número telefónico convencional', '','warning');
                    }else{
                        var formData= new FormData();
                        formData.append("id", id);
                        formData.append("detalle", detalle);
                        formData.append("telefono2", telefono2);
                        formData.append("tipo","numero");
                        sendUpdateContacto(token, formData, '/actualizar-contacto-diteem', 2);
                    }
                }else{
                    var formData= new FormData();
                    formData.append("id", id);
                    formData.append("detalle", detalle);
                    formData.append("telefono2", telefono2);
                    formData.append("tipo","numero");
                    sendUpdateContacto(token, formData, '/actualizar-contacto-diteem', 2);
                }
            }*/
        }else{
            //es cadena
            var formData= new FormData();
            formData.append("id", id);
            formData.append("detalle", detalle);
            formData.append("telefono2", telefono2);
            formData.append("tipo","cadena");
            sendUpdateContacto(token, formData, '/actualizar-contacto-diteem', 2);
        }
    }
}

/* FUNCION PARA ACTUALIZAR EL REGISTRO DE HORARIO DE ATENCION  */
function actualizarRegistroHour(){
    var token= $('#token').val();
    var id= $('#idcontactohour').val();
    var hora= $('#inputHourA').val();
    var horac= $('#inputHourC').val();

    if(hora==''){
        $('#inputHourA').focus();
        swal('Por favor ingrese la hora de apertura','','warning');
    } else if(horac==''){
        $('#inputHourC').focus();
        swal('Por favor ingrese la hora de cierre','','warning');
    } else{
        var formData= new FormData();
        formData.append("id", id);
        formData.append("hora", hora);
        formData.append("horac", horac);
        sendUpdateContacto(token, formData, '/actualizar-contacto-hour', 3);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA ACTUALIZAR */
function sendUpdateContacto(token, data, url, op){
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
                    text:'Registro Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                if(op==1){
                    $('#modalEditContactGeo').modal('hide');
                }else if(op==2){
                    $('#modalEditContact').modal('hide');
                }else if(op==3){
                    $('#modalEditContactHour').modal('hide');
                }

                setTimeout(function(){
                    window.location='contactos';
                },1200);
            }else if(myArr.resultado==false){
                swal('No se pudo actualizar el registro','','error');
            }
        }else if(xr.status === 400){
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