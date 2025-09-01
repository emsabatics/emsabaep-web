var arrayImg= [];
var arrayIdImg= [];
var longitudArray=0;

var datos= [];
var objeto= {};

/* FUNCION CARGAR BANNER */
function cargar_logo(array){
    var con = 1; var estadoItem='';
    var html ="<table class='table table-striped projects' id='tableLogo'>"+
        "<thead>"+
            "<tr>"+
                "<th style='width: 1.5%'>"+
                    "#"+
                "</th>"+
                "<th style='width: 20%'>"+
                    "Archivo"+
                "</th>"+
                "<th style='width: 30%'>"+
                    "Nombre del Archivo"+
                "</th>"+
                "<th style='width: 8%' class='text-center'>"+
                    "Estado"+
                "</th>"+
            "</tr>"+
        "</thead><tbody>";

    if(array.length==0){
        html+="<tr><td colspan='4' style='text-align: center;'>No hay resultados...</td></tr>";
    }

    $(array).each(function(i,v){
            let classbadge='';
            if(v.estado=="1"){
                estadoItem="Visible";
                classbadge="badge badge-success";
            }else{
                estadoItem="No Visible";
                classbadge="badge badge-secondary";
            }
            //var urlimg= '../../assets/img/banner/'+v.imagen;
            //var fileimg= '"'+v.imagen+'"';
            html +="<tr id='Tr"+i +"'>"+
                "<td style='text-align: center;'>"+con+"</td>"+
                "<td>"+
                    "<div class='cell-img'>"+
                        `<img src="/files-img/${v.archivo}" alt="${v.archivo}">`+
                    "</div>"+
                "</td>"+
                "<td>"+v.archivo+"</td>"+
                "<td class='project-state'>"+
                    "<span class='"+classbadge+"'>"+estadoItem+"</span>"+
                "</td>"+
                "<td class='project-actions text-right'>"+
                    "<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenimg("+i+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>";
                    if(v.estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarLogo("+v.id+", "+i+")'>"+
                                "<i class='fas fa-eye-slash mr-3'></i>"+
                                "Inactivar"+
                            "</a>";
                    }else if(v.estado=="0"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarLogo("+v.id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+="<a class='btn btn-danger btn-sm mt-2  mr-3' href='javascript:void(0)' onclick='removerLogo("+v.id+", "+i+")'>"+
                        "<i class='fas fa-trash mr-3'></i>"+
                        "Eliminar"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2' onclick='downloadImg("+v.id+")' >"+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar"+
                    "</a>"+
                "</td>";
            
            con++;

            if(arrayIdImg.length==0){
                arrayIdImg[0]= v.id;
            }else{
                arrayIdImg.push(v.id);
            }

            if(arrayImg.length==0){
                arrayImg[0]= v.archivo;
            }else{
                arrayImg.push(v.archivo);
            }

            longitudArray= arrayImg.length;
    });
    html += "</tbody></table>";
    
    $('#divTablaLogo').html(html);

    setTimeout(function(){
        $('td:nth-child(6)').toggle();
        $('#modalCargando').modal('hide');
    },800);
}

/* FUNCION REDIRECCIONAMIENTO A LA PAGINA DE REGISTRO DE IMAGENES */
function urladdnewpics(){
    window.location='/registrar-logo';
}

/* FUNCION QUE GUARDA LAS IMÁGENES */
function guardarImagenes(e){
    e.preventDefault();
    let fileInput = document.getElementById("file");

    var lengimg = fileInput.files.length;
    var token= $('#token').val();
    if (lengimg == 0) {
        swal("No ha seleccionado ningún archivo", "", "warning");
    } else if(lengimg > 2){
        swal("Solo se permiten máximo 2 archivos", "", "warning");
    }else{
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        $('#modalFullSend').modal('show');
        var element= document.getElementById('btnSaveImgLogo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formLogoImg);
        setTimeout(() => {
            sendUpdatePicsLogo(token, data, lengimg, "/logo/registro-logo", element);
        }, 900);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO DE NUEVAS IMÁGENES */
function sendUpdatePicsLogo(token, data, cantidad, url, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN',token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#modalFullSend').modal('hide');
            if(myArr.resultado==true){
                if(cantidad==1){
                    swal({
                        title:'Excelente!',
                        text:'Archivo Subido',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });
                }else if(cantidad > 1){
                    swal({
                        title:'Excelente!',
                        text:'Archivos Subidos',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });
                }
                

                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");

                setTimeout(function(){
                    regresarVista();
                },1500);
            } else if (myArr.resultado == "noimagen") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("Error al copiar los archivos", "", "error");
            }else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                swal("No se pudo Guardar", "", "error");
            }
        }else if(xr.status === 400){
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
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

/* FUNCION PARA LIMPIAR EL ARRAY DE BANNER */
function limpiarArray(){
    while(arrayImg.length>0){
        arrayImg.pop();
    }

    while(arrayIdImg.length>0){
        arrayIdImg.pop();
    }

    while(arrayGetOrder.length>0){
        arrayGetOrder.pop();
    }
}

function limpiarArrayDatos(){
    objeto= {};
    while(datos.length>0){
        datos.pop();
    }

    while(arrayOrder.length>0){
        arrayOrder.pop();
    }
}

/* FUNCION CARGAR IMÁGEN SELECCIONADA */
function viewopenimg(i){
   // var urlimg= '../../assets/img/banner/'+arrayImg[i];
    //var html="<img src='"+urlimg+"' alt='"+arrayImg[i]+"' />";
    //console.log(i, arrayImg[i]);
    var html= `<img src="/files-img/${arrayImg[i]}" alt="${arrayImg[i]}">`;
    var htmlspan= "<span class='spanshowdescpimg'>"+arrayImg[i]+"</span>";
    $('#divShowImgBanner').html(html);
    $('#divShowSpanBanner').html(htmlspan);
    setTimeout(() => {
        $('#modal-view-imagen').modal('show');
    }, 300);
}

/* FUNCION PARA INACTIVAR BANNER */
function inactivarLogo(id, i) {
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-logo",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: id,
        estado: estado
      },
      success: function (res) {
        if (res.resultado == true) {
            swal({
                title: "Excelente!",
                text: "Registro Inactivado",
                type: "success",
                showConfirmButton: false,
                timer: 1600,
            });
            
            setTimeout(function () {
            var elementState= document.getElementById('Tr'+i).cells[3];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenimg("+i+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarLogo("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarLogo("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+="<a class='btn btn-danger btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='removerLogo("+id+", "+i+")'>"+
                "<i class='fas fa-trash mr-3'></i>"+
                "Eliminar"+
            "</a>"+
            "<a class='btn btn-info btn-sm mt-2' onclick='downloadImg("+id+")' >"+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar"+
            "</a>"; 
            var element= document.getElementById('Tr'+i).cells[4];
            $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR BANNER */
function activarLogo(id, i) {
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
        url: "/in-activar-logo",
        type: "POST",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': token},
        data: {
            id: id,
            estado: estado
        },
        success: function (res) {
            if (res.resultado == true) {
                swal({
                    title: "Excelente!",
                    text: "Registro Activado",
                    type: "success",
                    showConfirmButton: false,
                    timer: 1600,
                });
                
                setTimeout(function () {
                var elementState= document.getElementById('Tr'+i).cells[3];
                $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenimg("+i+")'>"+
                    "<i class='fas fa-folder mr-3'></i>"+
                    "Ver"+
                "</a>";
                if(estado=="1"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarLogo("+id+", "+i+")'>"+
                        "<i class='fas fa-eye-slash mr-3'></i>"+
                        "Inactivar"+
                    "</a>";
                }else if(estado=="0"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarLogo("+id+", "+i+")'>"+
                            "<i class='fas fa-eye mr-3'></i>"+
                            "Activar"+
                        "</a>";
                }
                html+="<a class='btn btn-danger btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='removerLogo("+id+", "+i+")'>"+
                    "<i class='fas fa-trash mr-3'></i>"+
                    "Eliminar"+
                "</a>"+
                "<a class='btn btn-info btn-sm mt-2' onclick='downloadImg("+id+")' >"+
                    "<i class='fas fa-download mr-3'></i>"+
                    "Descargar"+
                "</a>";  
                var element= document.getElementById('Tr'+i).cells[4];
                $(element).html(html);
                }, 1500);
            } else if (res.resultado == false) {
                swal("No se pudo Activar", "", "error");
            }
        },
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

function downloadImg(id){
    if(puedeDescargarSM(nameInterfaz) === 'si'){
    //var url='/download-logo/'+id;
    //window.open(url, '_blank');
    window.location='/download-logo/'+id;
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

/* FUNCION PARA ELIMINAR LOGO */
function removerLogo(id, i) {
    var estado = "0";
    var token= $('#token').val();

    var URLactual = '/logo-institucion';
    if(puedeEliminarSM(nameInterfaz) === 'si'){
    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar este registro?",
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
            url: "/delete-logo",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: id,
                estado: estado,
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
                    
                    setTimeout(function () {
                        window.location= URLactual;
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
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

/* FUNCION PARA REGRESAR A LA PAGINA PRINCIPAL LOGO*/
function regresarVista(){
    window.location='/logo-institucion';
}