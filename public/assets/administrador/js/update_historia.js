var currentValue = 0;
var isDataHistory= false;
var arrayHistoria= [];

//VARIABLES EDIT HISTORIA
var currentValueEdit = 0;
var isDeleteImg= false;
var arrayDatoImg= [];

function cargar_historia(img, texto){
    let texthistory='';
    //let lengarr= texto.length;

    $(img).each(function(i,v){
        arrayDatoImg.push(v.imagen);
        arrayDatoImg.push(v.posicion);
        drawCheck(v.posicion);
        currentValueEdit= v.posicion;
        drawImgHistoria(v.imagen, v.id);
    });

    $(texto).each(function(i,v){
        texthistory+=v.descripcion;
    });

    $('#summernote').summernote('code', texthistory);

    setTimeout(function(){
        $('#modalCargando').modal('hide');
    },1200);
}

/* FUNCION QUE DIBUJA EL CHECKBOX PARA EDITAR LA HISTORIA */
function drawCheck(selec){
    let htmlsel="";
    if(selec=="inicio"){
        htmlsel+="<div class='custom-control custom-radio'>"+
            "<input class='custom-control-input' type='radio' value='inicio' id='customRadioInicioEdit' name='customRadio' onclick='handleClickEdit(this);' checked>"+
            "<label for='customRadioInicioEdit' class='custom-control-label'>Antes del Texto</label>"+
        "</div>"+
        "<div class='custom-control custom-radio'>"+
            "<input class='custom-control-input' type='radio' value='end' id='customRadioEndEdit' name='customRadio' onclick='handleClickEdit(this);'>"+
            "<label for='customRadioEndEdit' class='custom-control-label'>Después del Texto</label>"+
        "</div>";
    }else if(selec=="end"){
        htmlsel+="<div class='custom-control custom-radio'>"+
            "<input class='custom-control-input' type='radio' value='inicio' id='customRadioInicioEdit' name='customRadio' onclick='handleClickEdit(this);'>"+
            "<label for='customRadioInicioEdit' class='custom-control-label'>Antes del Texto</label>"+
        "</div>"+
        "<div class='custom-control custom-radio'>"+
            "<input class='custom-control-input' type='radio' value='end' id='customRadioEndEdit' name='customRadio' onclick='handleClickEdit(this);' checked>"+
            "<label for='customRadioEndEdit' class='custom-control-label'>Después del Texto</label>"+
        "</div>";
    }
    $('#formgroupcheck').html(htmlsel);
}

/* FUNCION QUE DIBUJA LA IMAGEN DE LA HISTORIA SELECCIONADA */
function drawImgHistoria(imagen, idimagen){
    let imageContainer = document.getElementById("imagesBD");
    
    let ul_element= document.createElement("ul"); 
    ul_element.className= "mailbox-attachments align-items-stretch clearfix";
    ul_element.style.cssText='padding: 15px; width: 90%;';

    let li_element= document.createElement("li");
    li_element.setAttribute("id","liImg1");
    li_element.style.cssText='width: 270px !important;';

    /*CREATE SPAN INSIDE LI*/
    let span_element= document.createElement("span");
    span_element.className= "mailbox-attachment-icon has-img";
    span_element.style.cssText='max-height: 210.5px';

    /*CREATE IMG INSIDE SPAN*/
    let img_element = document.createElement("img");
    img_element.style.cssText= "height: 215px !important;padding: 10px 0px";
    img_element.setAttribute("src", "/historia-img/"+imagen);
    img_element.setAttribute("alt", imagen);

    span_element.appendChild(img_element); //INSERTAR ETIQUETA IMG DENTRO DEL SPAN

    let div_element= document.createElement("div");
    div_element.className= "mailbox-attachment-info";

    /*CREATE A INSIDE DIV*/
    let a_element= document.createElement("a");
    a_element.className= "mailbox-attachment-name";
    //a_element.innerHTML= "photo1.png";
    a_element.setAttribute("href","#");

    let i_element= document.createElement("i");
    i_element.className= "fas fa-camera mr-2";

    a_element.appendChild(i_element);
    let newText= document.createTextNode(imagen);
    a_element.appendChild(newText);

    div_element.appendChild(a_element); //INSERTAR ETIQUETA A DENTRO DEL DIV

    let span2_element= document.createElement("span");
    span2_element.className= "mailbox-attachment-size clearfix mt-1";

    /*CREATE A INSIDE DIV*/
    let a2_element= document.createElement("a");
    a2_element.className= "btn btn-default btn-sm float-right";
    a2_element.setAttribute("href","javascript:void(0)");
    a2_element.addEventListener('click', deleteLi, false);
    a2_element.myParam= idimagen;
    /*a2_element.addEventListener("click", function(){
        deleteLi((contar+1));
    }, false);*/

    let i2_element= document.createElement("i");
    i2_element.className= "fas fa-trash";

    a2_element.appendChild(i2_element);
    span2_element.appendChild(a2_element);

    div_element.appendChild(span2_element); //INSERTAR ETIQUETA SPAN DENTRO DEL DIV

    li_element.appendChild(span_element);
    li_element.appendChild(div_element);

    ul_element.appendChild(li_element);

    imageContainer.appendChild(ul_element);
}

function deleteLi(event){
    //console.log(event.currentTarget.myParam);
    let iid= event.currentTarget.myParam;
    removerImgHistoria(iid);
}

/* FUNCION PARA ELIMINAR IMG HISTORIA */
function removerImgHistoria(id) {
    var estado = "0";
    var token= $('#token').val();
    var URLactual = window.location;

    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar esta imagen?",
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
            url: "/activar-imghistoria-delete",
            type: "POST",
            dataType: "json",
            headers:{
                'X-CSRF-TOKEN': token
            },
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
                        $('#liImg1').remove();
                        $('#imagesBD').html("");
                        isDeleteImg= true;
                        var html="<div class='no-data p-4'><div class='imgadvice'>"+
                            `<img src="/assets/administrador/img/icons/no-content-img.png" alt="Construccion">`+
                        "</div>"+
                        "<span class='mensaje-noticia mt-4 mb-4'>No hay <strong>imagen</strong> disponible por el momento...</span>"+
                        "</div>";
                        $('#imagesBD').html(html);
                        document.getElementById('divUpNewImgHistory').style.display='block';
                    }, 1500);
                } else if (res.resultado == false) {
                    isDeleteImg= false;
                    swal("No se pudo Inactivar", "", "error");
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

function handleClickEdit(myRadio) {
    currentValueEdit = myRadio.value;
}

/* FUNCION PARA GUARDAR HISTORIA */
function actualizarHistoria() {
    var radioValue = currentValueEdit;
    var token= $('#token').val();
    var value=$('#summernote').summernote('code');
    value= value.trim();

    let longitud= value.length;

    if(longitud < 2700){
        arrayHistoria.push(value.trim());
    }else if(longitud >= 2700 && longitud <= 4000){
        let divArray= Math.round(longitud / 2);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            start= i;
            end= start+divArray;
            arrayHistoria.push(value.substring(start, end).trim());
        }
        /*arrayHistoria.push(value.substring(0, divArray));
        arrayHistoria.push(value.substring(divArray, longitud));*/
    }else if(longitud > 4000 && longitud <= 6000){
        let divArray= Math.round(longitud / 3);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayHistoria.push(value.substring(start, end).trim());
        }
    }else if(longitud > 6000){
        let divArray= Math.round(longitud / 4);
        let start=0;
        let end=0;
        //console.log(divArray);
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayHistoria.push(value.substring(start, end).trim());
        }
    }
    
    if(isDeleteImg==false){
        if (longitud == 0) {
            swal("Ingrese la descripción de la Historia", "", "warning");
        } else if (radioValue == '') {
            swal("No ha seleccionado la posición de la imagen", "", "warning");
        }else{
            $('#modalFullSend').modal('show');
            //descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
            var data = new FormData(formEditHistoria);
            data.append("posicion", radioValue);
            data.append("descripcion", arrayHistoria.join("//"));
            data.append("longitud", arrayHistoria.length);
            data.append("imagenbd", arrayDatoImg.toString());
            data.append("tipo","texto");
            setTimeout(() => {
                sendUpdateHistoria(token, data, "/actualizar-historia");  
            }, 900);
        }
    }else if(isDeleteImg==true){
        let fileInput = document.getElementById("file");
        var lengimg = fileInput.files.length;

        if (longitud == 0) {
            swal("Ingrese la descripción de la Historia", "", "warning");
        } else if (radioValue == '') {
            swal("No ha seleccionado la posición de la imagen", "", "warning");
        } else if (lengimg == 0) {
            swal("No ha seleccionado alguna imagen para la historia", "", "warning");
        } else if (lengimg > 1) {
            swal("Debe elegir solo una fotografía", "", "warning");
        } else {
            $('#modalFullSend').modal('show');
            //descripcion = descripcion.replace(/(\r\n|\n|\r)/gm, "//");
            var data = new FormData(formEditHistoria);
            data.append("posicion", radioValue);
            data.append("descripcion", arrayHistoria.join("//"));
            data.append("longitud", arrayHistoria.length);
            data.append("imagenbd", arrayDatoImg.toString());
            data.append("tipo","imagen");
            setTimeout(() => {
                sendUpdateHistoria(token, data, "/actualizar-historia");  
            }, 900);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateHistoria(token, data, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#modalFullSend').modal('hide');
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Historia Registrada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/historia';
                },1500);
            } else if (myArr.resultado == "noimagen") {
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            }
        }else if(xr.status === 400){
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