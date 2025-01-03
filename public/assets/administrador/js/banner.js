var arrayImg= [];
var arrayIdImg= [];
var arrayGetOrder= [];
var longitudArray=0;
var arrayOrder= [];

var datos= [];
var objeto= {};

/* FUNCION CARGAR BANNER */
function cargar_banner(array){
    var con = 1; var estadoItem='';
    var html ="<table class='table table-striped projects' id='tableBanner'>"+
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
                "<th style='width: 20%'>"+
                "</th>"+
            "</tr>"+
        "</thead><tbody>";

    if(array.length==0){
        html+="<tr><td colspan='5' style='text-align: center;'>No hay resultados...</td></tr>";
    }else{
        document.getElementById('btnOpenModalOrder').style.display='block';
    }

    $(array).each(function(i,v){
        if(v.observacion=='no_eliminado'){
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
                        `<img src="/banner-img/${v.imagen}" alt="${v.imagen}">`+
                    "</div>"+
                "</td>"+
                "<td>"+v.imagen+"</td>"+
                "<td class='project-state'>"+
                    "<span class='"+classbadge+"'>"+estadoItem+"</span>"+
                "</td>"+
                "<td class='project-actions text-right'>"+
                    "<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenimg("+i+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>";
                    if(v.estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarBanner("+v.id+", "+i+")'>"+
                                "<i class='fas fa-eye-slash mr-3'></i>"+
                                "Inactivar"+
                            "</a>";
                    }else if(v.estado=="0"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarBanner("+v.id+", "+i+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+="<a class='btn btn-danger btn-sm mt-2  mr-3' href='javascript:void(0)' onclick='removerBanner("+v.id+", "+i+")'>"+
                        "<i class='fas fa-trash mr-3'></i>"+
                        "Eliminar"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2' onclick='downloadImg("+v.id+")' >"+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar"+
                    "</a>"+
                "</td>"+
                "<td>"+v.orden+"</td>";
            
            con++;

            if(arrayIdImg.length==0){
                arrayIdImg[0]= v.id;
            }else{
                arrayIdImg.push(v.id);
            }

            if(arrayImg.length==0){
                arrayImg[0]= v.imagen;
            }else{
                arrayImg.push(v.imagen);
            }

            if(arrayGetOrder.length==0){
                arrayGetOrder[0] = v.orden;
            }else{
                arrayGetOrder.push(v.orden);
            }

            longitudArray= arrayImg.length;
        }
    });
    html += "</tbody></table>";
    
    $('#divTablaBanner').html(html);

    setTimeout(function(){
        $('td:nth-child(6)').toggle();
        $('#modalCargando').modal('hide');
    },800);
}

/* FUNCION REDIRECCIONAMIENTO A LA PAGINA DE REGISTRO DE IMAGENES */
function urladdnewpics(){
    window.location='/registro-banner';
}

/* FUNCION QUE GUARDA LAS IMÁGENES */
function guardarImagenes(e){
    e.preventDefault();
    let fileInput = document.getElementById("file");

    var lengimg = fileInput.files.length;
    var token= $('#token').val();
    if (lengimg == 0) {
        swal("No ha seleccionado imágenes", "", "warning");
    } else {
        $('#modalFullSend').modal('show');
        var element= document.getElementById('btnSaveImgBanner');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formBannerImg);
        setTimeout(() => {
            sendUpdatePicsBanner(token, data, "/banner/registro-banner", element);
        }, 900);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO DE NUEVAS IMÁGENES */
function sendUpdatePicsBanner(token, data, url, el){
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
                swal({
                    title:'Excelente!',
                    text:'Fotografías Subidas',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");

                setTimeout(function(){
                    window.location = "/banner";
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

/* FUNCION QUE ABRE EL MODAL PARA ACTUALIZAR EL ORDEN DE VISUALIZACIÓN */
function openModalOrder(){
    var html="";
    toastr.info("Espere un momento...", "Graficando Tabla", "!Aviso!");
    for(var i=0; i<longitudArray; i++){
        //var urlimg= '../../assets/img/banner/'+arrayImg[i];
        html+="<tr>"+
            "<td>"+(i+1)+"</td>"+
            "<td>"+
                "<div class='cell-img'>"+
                    `<img src="/banner-img/${arrayImg[i]}" alt="${arrayImg[i]}">`+
                 "</div>"+
            "</td>"+
            "<td>"+
                "<select class='form-control' id='mySelectOrder"+(i+1)+"' onchange='dropdown("+(i+1)+")'>"+
                    drawSelectOption()+
                "</select>"+
            "</td>"+
        "</tr>";
    }

    $('#tableEditOrder tbody').html(html);

    setTimeout(() => {
        setOptionValue();
        $('#modal-changeorden').modal('show'); 
    }, 1500);
}

//FUNCION QUE DIBUJA LAS OPTION DEL SELECT
function drawSelectOption(){
    var html="";
    for(var i=0; i<longitudArray; i++){
        /*if((i+1)==x){
            html+="<option value='"+(i+1)+"' selected>"+(i+1)+"</option>";
        }else{
            html+="<option value='"+(i+1)+"'>"+(i+1)+"</option>";
        }*/
        html+="<option value='"+(i+1)+"'>"+(i+1)+"</option>";
    }
    return html;
}

//FUNCION QUE APLICA EL SELECTED AL OPTION VALUE
function setOptionValue(){
    for(var i=0; i<longitudArray; i++){
        $('#mySelectOrder'+(i+1)).val(arrayGetOrder[i]);
    }
}

//FUNCION ONCHANGE SELECT
function dropdown(pos) {
    var select = document.getElementById("mySelectOrder"+pos);
    var option = select.options[select.selectedIndex];
    var val = option.value;
    //console.log("SELECT "+pos, val);
    //arrayOrder[pos-1]= val;
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

//FUNCION ACTUALIZAR ORDEN DE VISUALIZACION DE LAS IMAGENES
function actualizarOrden(){
    limpiarArrayDatos();
    var token= $('#token').val();

    for(var i=0; i<longitudArray; i++){
        var value= $('#mySelectOrder'+(i+1)).val();
        arrayOrder.push(value);
    }

    const findDuplicates = arrayOrder => arrayOrder.filter((item, index) => arrayOrder.indexOf(item) !== index)
    var duplicates = findDuplicates(arrayOrder);

    if(duplicates.length>0){
        swal('Ha ocurrido un error','Se repite el orden # '+duplicates.toString(), 'error');
    }else{
        var element = document.getElementById("btnActionUpBan");
        //element.setAttribute("disabled", "");
        //element.style.pointerEvents = "none";

        for(var i=0; i<longitudArray; i++){
            var posi= $('#mySelectOrder'+(i+1)).val();
            datos.push({
                "id" : arrayIdImg[i],
                "orden" : posi
            })
            
        }
        //objeto.datos= datos;
        objeto= datos;

        sendOrderBanner(token, JSON.stringify(objeto), '/banner/registro-orden-banner', element);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendOrderBanner(token, data, url, el){
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
                    window.location="/banner";
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

/* FUNCION CARGAR IMÁGEN SELECCIONADA */
function viewopenimg(i){
   // var urlimg= '../../assets/img/banner/'+arrayImg[i];
    //var html="<img src='"+urlimg+"' alt='"+arrayImg[i]+"' />";
    var html= `<img src="/banner-img/${arrayImg[i]}" alt="${arrayImg[i]}">`;
    var htmlspan= "<span class='spanshowdescpimg'>"+arrayImg[i]+"</span>";
    $('#divShowImgBanner').html(html);
    $('#divShowSpanBanner').html(htmlspan);
    setTimeout(() => {
        $('#modal-view-imagen').modal('show');
    }, 300);
}

/* FUNCION PARA INACTIVAR BANNER */
function inactivarBanner(id, i) {
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";
    $.ajax({
      url: "/in-activar-banner",
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
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarBanner("+id+", "+i+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarBanner("+id+", "+i+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+="<a class='btn btn-danger btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='removerBanner("+id+", "+i+")'>"+
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
}

/* FUNCION PARA ACTIVAR BANNER */
function activarBanner(id, i) {
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
        url: "/in-activar-banner",
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
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarBanner("+id+", "+i+")'>"+
                        "<i class='fas fa-eye-slash mr-3'></i>"+
                        "Inactivar"+
                    "</a>";
                }else if(estado=="0"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarBanner("+id+", "+i+")'>"+
                            "<i class='fas fa-eye mr-3'></i>"+
                            "Activar"+
                        "</a>";
                }
                html+="<a class='btn btn-danger btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='removerBanner("+id+", "+i+")'>"+
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
}

function downloadImg(id){
    var url='/download-banner/'+id;
    //window.open(url, '_blank');
    window.location='/download-banner/'+id;
}

/* FUNCION PARA ELIMINAR BANNER */
function removerBanner(id, i) {
    var estado = "0";
    var token= $('#token').val();

    var URLactual = '/banner';

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
            url: "/delete-banner",
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
}

/* FUNCION PARA REGRESAR A LA PAGINA PRINCIPAL BANNER*/
function regresarVista(){
    window.location='/banner';
}