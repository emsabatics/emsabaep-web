/* FUNCION CARGAR NOTIFICACIONES */
function getCountNoti(){
    var noti=0; var pendientes=0;
    var html='';
    $.ajax({
        url:'/get-notificacion',
        type:'GET',
        dataType:'json',
        success:function(res){
            //console.log("NOTI: "+noti);
            $(res).each(function(i,v){
                if(v.contador[0].total>0){
                    document.getElementById('num-noti-span').innerHTML= v.contador[0].total;
                    if(v.contador[0].total==1){
                        html+="<span class='dropdown-item dropdown-header'>"+v.contador[0].total+" Notificación</span>";
                    }else{
                        html+="<span class='dropdown-item dropdown-header'>"+v.contador[0].total+" Notificaciones</span>";
                    }
                    
                    $(v.notificacion).each(function(j, k){
                        if(j<5){
                            html+="<div class='dropdown-divider'></div>"+
                            "<a onclick='viewnotificacion("+k.id+")' class='dropdown-item'>"+
                                "<i class='fas fa-envelope mr-2'></i> Un nuevo mensaje"+
                                "<span class='float-right text-muted text-sm'>"+k.tiempo+"</span>"+
                            "</a>";
                        }
                    });
                }else{
                    html+="<span class='dropdown-item dropdown-header'>0 Notificaciones</span>"+
                        "<div class='dropdown-divider'></div>"+
                        "<a onclick='javascript:void(0)' class='dropdown-item'>"+
                            "<i class='fa fa-info mr-2'></i> Sin notificaciones"+
                            "<span class='float-right text-muted text-sm'>Justo Ahora</span>"+
                        "</a>";
                }
            });
            html+="<div class='dropdown-divider'></div>"+
            "<a onclick='viewallnoti()' class='dropdown-item dropdown-footer'>Ver todas las Notificaciones</a>";
            document.getElementById('contain-noti').innerHTML= html;
        }
    });
}

function viewallnoti(){
    window.location='/all-notifications';
}

/* FUNCION PARA CARGAR NOTIFICACIONES */
function cargarNotificaciones(){
    var html="";
    var token= $('#token').val();
    var url= "/get-all-notificacion";
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";

    $('#modalCargando').modal('show');
    btntoggle.removeAttribute("disabled");
    typeselec="recibidos";
    contarItemsAll=0;
    arrayContenidoNoti.splice(0);

    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    //xr.setRequestHeader('Content-type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                html+="<tr>"+
                    "<td colspan='3' style='text-align:center;'>No hay notificaciones</td>"+
                "</tr>";

                $('#tablaNotificaciones tbody').html(html);
            }else{

                arrayContenidoNoti= [...myArr];

                $(myArr).each(function(i,v){
                    contarItemsAll++;
                });

                $('#title-card-noti').html("Recibidos");
                
                renderTable();

            }
            if(contarItemsAll>10){
                //$('#span-lon-noti').html(startpage+"-"+endpage+"/"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').show();
            }else{
                //$('#span-lon-noti').html(startpage+"-"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').hide();
            }

            setTimeout(function(){
                getContador();
                $('#modalCargando').modal('hide');
            },1200);
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

    xr.send();
}

function getContador(){
    $.ajax({
        url:'/get-contador-notificacion',
        type:'GET',
        dataType:'json',
        success:function(res){
            if(res.length==0){
                $('#totalall').html("");
                $('#totalhoy').html("");
            }
            $(res).each(function(i,v){
                if(v.tall==0){
                    $('#totalall').html("");
                }else{
                    $('#totalall').html(v.tall);
                }

                if(v.thoy==0){
                    $('#totalhoy').html("");
                }else{
                    $('#totalhoy').html(v.thoy);
                }
            });
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
}

/* FUNCION PARA DIBUJAR DATOS EN TABLA*/
function renderTable() {
    // create html
    let result = ''; let i=0;
    arrayContenidoNoti.filter((row, index) => {
          let start = (curPage-1)*pageSize;
          let end =curPage*pageSize;
          if(index >= start && index < end) return true;
    }).forEach(c => {
        let mensaje= c.nombres;
        if(c.estado=="0"){
            result+=`<tr class='leido'>
                <td></td>`;
        }else if(c.estado=="1"){
            result+=`<tr>
            <td>
                <div class='icheck-primary'>
                    <input type='checkbox' value='${c.id}' id='check${(i+1)}' onclick='seleccionar(this,${c.id})'>
                    <label for='check${(i+1)}'></label>
                </div>
            </td>`;
        }
       result +=`<td class='mailbox-subject'>${mensaje} te dejó un mensaje</td>`;
       result +=`
       <td><span class='badge badge-info'>SOLICITUD</span><td>
       <td class='mailbox-attachment'></td>
       <td>
           <button type='button' class='btn btn-info btn-block btn-flat' onclick='viewNotiIndi(${c.id})'>
               <i class='fas fa-file-alt'></i>
           </button>
       </td>`;
        result +=`<td class='mailbox-date'>${c.tiempo}</td>
        </tr>`;
        i++;
    });

    //table.innerHTML = result;
    $('#tablaNotificaciones tbody').html(result);
    //console.log(arrayContenidoNoti);
}

/* FUNCION PARA CARGAR NOTIFICACIONES FECHA ACTUAL*/
function cargarNotiCurrent(){
    var html="";
    var url= "/get-today-notificacion";
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    $('#modalCargando').modal('show');
    btntoggle.removeAttribute("disabled");
    typeselec="hoy";
    contarItemsAll=0;
    arrayContenidoNoti.splice(0);

    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    //xr.setRequestHeader('Content-type', contentType);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                html+="<tr>"+
                    "<td colspan='3' style='text-align:center;'>No hay notificaciones</td>"+
                "</tr>";
                $('#tablaNotificaciones tbody').html(html);
            }else{

                arrayContenidoNoti= [...myArr];

                $(myArr).each(function(i,v){
                    contarItemsAll++;
                });

                renderTable();
            }

            $('#title-card-noti').html("Recibidos - Hoy "+`${day}/${arrMeses[month]}/${year}`);

            if(contarItemsAll>10){
                //$('#span-lon-noti').html("1-10/"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').show();
            }else{
                //$('#span-lon-noti').html("1-"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').hide();
            }
            

            setTimeout(function(){
                getContador();
                $('#modalCargando').modal('hide');
            },1200);
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

    xr.send();
}

/* FUNCION PARA CARGAR NOTIFICACIONES LEIDAS*/
function cargarNotiRead(){
    var html="";
    var url= "/get-read-notificacion";
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    $('#modalCargando').modal('show');
    btntoggle.setAttribute("disabled","");
    typeselec="leidas";
    contarItemsAll=0;
    arrayContenidoNoti.splice(0);

    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    //xr.setRequestHeader('Content-type', contentType);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                html+="<tr>"+
                    "<td colspan='3' style='text-align:center;'>No hay notificaciones</td>"+
                "</tr>";
                
                $('#tablaNotificaciones tbody').html(html);
            }else{
                arrayContenidoNoti= [...myArr];
                $(myArr).each(function(i,v){
                    contarItemsAll++;
                });

                renderTableRead();
            }

            $('#title-card-noti').html("Leídos");

            if(contarItemsAll>10){
                //$('#span-lon-noti').html("1-10/"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').show();
            }else{
                //$('#span-lon-noti').html("1-"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').hide();
            }

            setTimeout(function(){
                getContador();
                $('#modalCargando').modal('hide');
            },1200);
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

    xr.send();
}

function renderTableRead() {
    // create html
    let result = '';
    arrayContenidoNoti.filter((row, index) => {
          let start = (curPage-1)*pageSize;
          let end =curPage*pageSize;
          if(index >= start && index < end) return true;
    }).forEach(c => {
        let mensaje= c.nombres;
        let tipon= '"'+c.tipo+'"';
        result += `<tr>
        <td class='mailbox-subject'>${mensaje} te dejó un mensaje</td>`;
        result +=`
       <td><span class='badge badge-info'>SOLICITUD</span><td>
       <td class='mailbox-attachment'></td>
       <td>
           <button type='button' class='btn btn-info btn-block btn-flat' onclick='viewNotiIndi(${c.id})'>
               <i class='fas fa-file-alt'></i>
           </button>
       </td>`;
        result +=`<td class='mailbox-date'>${c.tiempo}</td>
        </tr>`;
    });
    //table.innerHTML = result;
    $('#tablaNotificaciones tbody').html(result);
    //console.log(arrayContenidoNoti);
}

/* FUNCION PARA RECARGAR INFO */
function reloadAll(){
    let html="<tr>"+
        "<td colspan='3' style='text-align:center;'>Cargando...</td>"+
    "</tr>";
    $('#tablaNotificaciones tbody').html(html);
    setTimeout(function(){
        if(typeselec=="recibidos"){
            refreshContentNoti(1);
        }else if(typeselec=="hoy"){
            refreshContentNoti(2);
        }else if(typeselec=="leidas"){
            refreshContentNoti(3);
        }
    }, 400);
}

/* FUNCION PARA QUE RECARGA EL CONTENIDO DE LA TABLA*/
function refreshContentNoti(opcionnoti){
    var html="";
    var url= "";

    if(opcionnoti==1 ){
        url= "/get-all-notificacion"
    } else if(opcionnoti==2 ){
        url= "/get-today-notificacion"
    } else if(opcionnoti==3 ){
        url= "/get-read-notificacion";
    }

    var contentType = "application/x-www-form-urlencoded;charset=utf-8";

    contarItemsAll=0;
    arrayContenidoNoti.splice(0);

    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    //xr.setRequestHeader('Content-type', contentType);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                html+="<tr>"+
                    "<td colspan='3' style='text-align:center;'>No hay notificaciones</td>"+
                "</tr>";
            }

            $(myArr).each(function(i,v){
                let mensaje= v.nombres;
                let tipon= '"'+v.tipo+'"';
                if(v.estado=="0"){
                    html+="<tr class='leido'>"+"<td></td>";
                }else if(v.estado=="1"){
                    html+="<tr>";
                    if(opcionnoti!=3 ){
                        html+="<td>"+
                            "<div class='icheck-primary'>"+
                                "<input type='checkbox' value='"+v.id+"' id='check"+(i+1)+"' onclick='seleccionar(this,"+v.id+")'>"+
                                "<label for='check"+(i+1)+"'></label>"+
                            "</div>"+
                        "</td>";
                    }
                }
                    html+="<td class='mailbox-subject'>"+mensaje+" te dejó un mensaje</td>";
                    html+="<td><span class='badge badge-info'>SOLICITUD</span><td>"+
                        "<td class='mailbox-attachment'></td>"+
                        "<td>"+
                            "<button type='button' class='btn btn-info btn-block btn-flat' onclick='viewNotiIndi("+v.id+")'>"+
                                "<i class='fas fa-file-alt'></i>"+
                            "</button>"+
                        "</td>";
                    html+="<td class='mailbox-date'>"+v.tiempo+"</td>"+
                "</tr>";
                contarItemsAll++;
            });

            $('#tablaNotificaciones tbody').html(html);

            if(contarItemsAll>10){
                //$('#span-lon-noti').html("1-10/"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').show();
            }else{
                //$('#span-lon-noti').html("1-"+contarItemsAll);
                $('#span-lon-noti').html("Total: "+contarItemsAll);
                $('.group-table-nav').hide();
            }

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

    xr.send();
}

/* FUNCION PARA LEER LA INFORMACIÓN RESPECTIVA */
function viewNotiIndi(id){
    window.location='/read-view-noti/'+id;
}

function regresarNoti(){
    window.location='/all-notifications';
}

function previousPage() {
    if(curPage > 1) curPage--;
    renderTable();
}
  
function nextPage() {
    if((curPage * pageSize) < contarItemsAll) curPage++;
    renderTable();
}

/*FUNCION SELECCIONAR MODULOS*/
function seleccionar(td, value) {
    var index1 = selitemnoti.indexOf(value);
    if (index1 > -1) {
        //contar1= contar1-1;
        selitemnoti.splice(index1, 1);
    }
    else
    {
        //contar1++;
        if(selitemnoti=='')
        {
            selitemnoti=[value];
        }
        else{
            selitemnoti.push(value); 
        }
    }

    if(selitemnoti.length>0){
        btnleido.removeAttribute("disabled");
    }else if(selitemnoti.length==0){
        btnleido.setAttribute("disabled","");
    }
                
    //console.log('Seleccionar: ');
    //console.log(selitemnoti);
}

/* FUNCION PARA MARCAR LEIDO LAS NOTIFICACIONES */
function marcarLeidoNoti(){
    var lonarray= selitemnoti.length;
    var token= $('#token').val();

    if(lonarray==0){
        swal('No hay ningún item seleccionado','','warning');
    }else{
        $('#modalFullSendEdit').modal('show');
        var url= "/actualizar_items_notificaciones";
        var contentType = "application/x-www-form-urlencoded;charset=utf-8";
        //var params = 'idnotis='+selitemnoti.toString();
        var formData= new FormData();
        formData.append('idnotis', selitemnoti.toString());

        setTimeout(() => {
            var xr = new XMLHttpRequest();
            xr.open('POST', url, true);
            //xr.setRequestHeader('Content-Type', contentType);
            xr.setRequestHeader('X-CSRF-TOKEN', token);

            xr.onload = function(){
                if(xr.status === 200){
                    //console.log(this.responseText);
                    var myArr = JSON.parse(this.responseText);
                    $('#modalFullSendEdit').modal('hide');
                    if(myArr.resultado==true){
                        swal({
                            title:'Excelente!',
                            text:'Cambios Efectuados',
                            type:'success',
                            showConfirmButton: false,
                            timer: 1700
                        });
        
                        setTimeout(function(){
                            window.location = '/all-notifications';
                        },1500);
                    } else if (myArr.resultado == false) {
                        swal("No se pudo Guardar", "", "error");
                    }
                }else if(xr.status === 400){
                    $('#modalFullSendEdit').modal('hide');
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            };

            xr.send(formData);
        }, 500);
    }
}

