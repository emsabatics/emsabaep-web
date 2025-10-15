var datos= [];
var objeto= {};
var contarCampos=0;

function urlregistrarcate(){
    $('#modalAggCatBiV').modal('show');
}

function guardarCategoriaBiV(){
    var token= $('#token').val();
    var categoria= $('#inputCategoria').val();
    var tipocat= $('#seltipocategoria').val();

    if(categoria==''){
        $('#inputCategoria').focus();
        swal('Ingrese una categoría','','warning');
    }else if(tipocat=='0'){
        $('#seltipocategoria').focus();
        swal('Seleccione un tipo de categoría','','warning');
    }else{
        if(puedeGuardarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("categoria", categoria);
        formData.append("tipocat", tipocat);

        var element = document.querySelector('.savecatbv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/registro-categoria', true);
        xr.setRequestHeader('X-CSRF-TOKEN', token);

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
                        $('#modalAggCatBiV').modal('hide');
                        $('#inputCategoria').val("");
                        window.location='/library-externo';
                    },1500);
                }else if(myArr.resultado==false){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo guardar el registro','','error');
                }
            }else if(xr.status === 400){
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        };
        xr.send(formData);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

function openmodalSubCat(id, index){
    var xr = new XMLHttpRequest();
    xr.open('GET', '/get-name-categoria/'+id, true);
    xr.setRequestHeader('X-CSRF-TOKEN', token);

    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#indexselsubcat').val(index);
            $('#idcategoria').val(id);
            $(myArr).each(function(i,v){
                $('#inputviewcategoria').val(v.descripcion);
                $('#tipocategoria').val(v.tipo);
            })
            setTimeout(() => {
                $('#modalAggSubCatBiV').modal('show');
            }, 450);
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    };
    xr.send();
}

function guardarSubCategoriaBiV(){
    var token= $('#token').val();
    var idcategoria= $('#idcategoria').val();
    var subcategoria= $('#inputSubcategoria').val();
    var itemselection= $('#indexselsubcat').val();
    var tipocat = $('#tipocategoria').val();
    var html="";
    let filas = $('#TableSubCat'+itemselection).find('tbody tr').length;

    if(subcategoria==''){
        $('#inputSubcategoria').focus();
        swal('Ingrese una Subcategoría','','warning');
    }else{
        if(puedeGuardarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("idcategoria", idcategoria);
        formData.append("subcategoria", subcategoria);

        var element = document.querySelector('.savesubcatbv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/registro-subcategoria', true);
        xr.setRequestHeader('X-CSRF-TOKEN', token);

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
                        if(tipocat=='galeria'){
                            html+="<tr id='TrSub"+myArr.ID+"Cat"+filas+"'>"+
                                "<td>"+subcategoria+"</td>"+
                                "<td>Sin Archivos</td>"+
                                "<td class='text-right py-0 align-middle'>"+
                                    "<div class='btn-group btn-group-sm'>"+
                                        "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteGallerySubCat("+idcategoria+", "+myArr.ID+", "+filas+")'><i class='fas fa-trash'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar Subcategoría' onclick='inactivarSubCatGallery("+myArr.ID+","+idcategoria+","+filas+")'><i class='fas fa-eye-slash'></i></a>"+
                                        "<a href='javascript:void(0)' onclick='registerFileGallerySubCat("+idcategoria+", "+myArr.ID+")' class='btn btn-success' title='Agregar Imágenes'><i class='fas fa-folder-plus'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-primary' title='Editar Subcategoría' onclick='editSubCat("+myArr.ID+","+filas+")'><i class='fas fa-edit'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesGallerySubCat("+idcategoria+","+myArr.ID+")'><i class='fas fa-file-signature'></i></a>"+
                                    "</div>"+
                                "</td>"+
                            "</tr>";
                        }else if(tipocat=='video'){
                            html+="<tr id='TrSub"+myArr.ID+"Cat"+filas+"'>"+
                                "<td>"+subcategoria+"</td>"+
                                "<td>Sin Archivos</td>"+
                                "<td class='text-right py-0 align-middle'>"+
                                    "<div class='btn-group btn-group-sm'>"+
                                        "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteVideoSubCat("+idcategoria+", "+myArr.ID+", "+filas+")'><i class='fas fa-trash'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar Subcategoría' onclick='inactivarSubCatVideo("+myArr.ID+","+idcategoria+","+filas+")'><i class='fas fa-eye-slash'></i></a>"+
                                        "<a href='javascript:void(0)' onclick='registerFileVideoSubCat("+idcategoria+", "+myArr.ID+")' class='btn btn-success' title='Agregar Imágenes'><i class='fas fa-folder-plus'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-primary' title='Editar Subcategoría' onclick='editSubCat("+myArr.ID+","+filas+")'><i class='fas fa-edit'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesVideoSubCat("+idcategoria+","+myArr.ID+")'><i class='fas fa-file-signature'></i></a>"+
                                    "</div>"+
                                "</td>"+
                            "</tr>";
                        }else{
                            html+="<tr id='TrSub"+myArr.ID+"Cat"+filas+"'>"+
                                "<td>"+subcategoria+"</td>"+
                                "<td>Sin Archivos</td>"+
                                "<td class='text-right py-0 align-middle'>"+
                                    "<div class='btn-group btn-group-sm'>"+
                                        "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteFileSubCat("+idcategoria+", "+myArr.ID+", "+filas+")'><i class='fas fa-trash'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar Subcategoría' onclick='inactivarSubCat("+myArr.ID+","+idcategoria+","+filas+")'><i class='fas fa-eye-slash'></i></a>"+
                                        "<a href='javascript:void(0)' onclick='registerFileSubCat("+idcategoria+", "+myArr.ID+")' class='btn btn-success' title='Agregar Documentos'><i class='fas fa-folder-plus'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-primary' title='Editar Subcategoría' onclick='editSubCat("+myArr.ID+","+filas+")'><i class='fas fa-edit'></i></a>"+
                                        "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesSubCat("+idcategoria+","+myArr.ID+")'><i class='fas fa-file-signature'></i></a>"+
                                    "</div>"+
                                "</td>"+
                            "</tr>";
                        }
                        
                        if ( $("#nodatacat"+idcategoria)[0] ) {
                            // hacer algo aquí si el elemento existe
                            $("#nodatacat"+idcategoria)[0].remove();
                            $('#TableSubCat'+itemselection+' > tbody:last-child').append(html);
                        }else{
                            $('#TableSubCat'+itemselection+' > tbody:last-child').append(html);
                        }
                        
                        $('#modalAggSubCatBiV').modal('hide');
                        $('#inputSubcategoria').val("");
                        $('#h3ContSubCat').html(myArr.contsubcatgeneral);
                        $('#spanContSubCat'+itemselection).html(myArr.totalsubcat);
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
                        //window.location='/library-externo';
                    },1500);
                }else if(myArr.resultado==false){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo guardar el registro','','error');
                }
            }else if(xr.status === 400){
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        };
        xr.send(formData);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

function registerFileCat(id){
    window.location='/registrar_doc_virtual/'+id+'/0/v1';
}

//FUNCION QUE RETORNA A LA INTERFAZ PRINCIPAL library-externo
function urlback(){
    window.location='/library-externo';
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function getAliasInput(){
    var  namecat= $('#txtnamecat').val();
    namecat= namecat.toLowerCase();
    var newnamecat= namecat.substring(0,3);
    if($('#inputNameDocBiVir').val()!=''){
        var val= $('#inputNameDocBiVir').val();
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        return newnamecat+"_"+cadena;
    }else{
        return "";
    }
}

function generarAlias(){
    var  namecat= $('#txtnamecat').val();
    namecat= namecat.toLowerCase();
    var newnamecat= namecat.substring(0,3);
    if($('#inputNameDocBiVir').val()!=''){
        var val= $('#inputNameDocBiVir').val();
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        $('#inputAliasFileDocBiVir').val(newnamecat+"_"+cadena);
    }else{
        $('#inputNameDocBiVir').focus();
        toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
    }
}

function generarAliasE(){
    var  namecat= $('#txtnamecatedit').val();
    namecat= namecat.toLowerCase();
    var newnamecat= namecat.substring(0,3);
    if($('#inputNameDocBiVirEdit').val()!=''){
        var val= $('#inputNameDocBiVirEdit').val();
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        $('#inputAliasFileDocBiVirEdit').val(newnamecat+"_"+cadena);
    }else{
        $('#inputNameDocBiVirEdit').focus();
        toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
    }
}

function setDatetoDoc(){
    const date = new Date();
    var year= date.getFullYear();
    var mont= date.getMonth();
    var nmont='';
    //var day= date.getDate();
    if(mont.toString().length==1){
        nmont= "0"+mont.toString();
    }

    return year+"_"+nmont;
}

function guardarDocVirtual(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var subcategoria = $("#selSubCategoria :selected").val();
    var nombredoc= $('#inputNameDocBiVir').val();
    var aliasfile = $("#inputAliasFileDocBiVir").val();
    var descp = $('#inputDescpDocBiVir').val();
    var lengimg = fileInput.files.length;
    var typefile= fileInput.files[0].type;

    if (nombredoc == "") {
        $('#inputNameDocBiVir').focus();
        swal("Ingrese el nombre del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileDocBiVir").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (descp.length == 0) {
        $("#inputDescpDocBiVir").focus();
        swal("No se ha ingresado una descripción del documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else {
        //console.log(aliasfile, getAliasInput());
        if(aliasfile!=getAliasInput()){
            swal('Revise el alias del documento','','warning');
        }else{
            if(puedeGuardarM(nameInterfaz) === 'si'){
            descp = descp.replace(/(\r\n|\n|\r)/gm, "//");
            var element = document.querySelector('.savedocvirtual');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');

            var data = new FormData(formDocBiVirtual);
            data.append("subcategoria", subcategoria);
            data.append("descipcionfile", descp);
            /*data.append("typefile", typefile);
            data.append("lengfile", lengimg);*/

            setTimeout(() => {
                sendNewDocBibliotecaVirtual(token, data, "/store-doc-bibliovirtual", element,'otro'); 
            }, 700);
            }else{
                swal('No tiene permiso para guardar','','error');
            }
        }
    }
}

function guardarImagenesDocvi(){
    //e.preventDefault();
    let fileInput = document.getElementById("file");
    var idsubcat = $("#selSubCategoria :selected").val();

    var lengimg = fileInput.files.length;
    var token= $('#token').val();
    if (lengimg == 0) {
        swal("No ha seleccionado imágenes", "", "warning");
    } else {
        if(puedeGuardarM(nameInterfaz) === 'si'){
        $('#modalFullSend').modal('show');
        var element = document.querySelector('.savedocvirtual');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        setTimeout(() => {
            var getresult= getvalues(element);
            if(getresult){
                var data = new FormData(formDocBiVirtual);
                data.append("idsubcat", idsubcat);
                data.append("objeto", JSON.stringify(objeto));
                setTimeout(() => {
                    sendNewDocBibliotecaVirtual(token, data, "/store-files-bibliovirtual", element, 'galeria');
                }, 900);
            }else{
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                $('#modalFullSend').modal('hide');
                swal('Por favor, verifique que todos los campos se encuentren con datos','','warning');
                return;
            }
        }, 900);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

function getvalues(el){
    var inps = document.getElementsByName('inputFilebv[]');
    var txts = document.getElementsByName('textFilebv[]');
    
    for (var i = 0; i <inps.length; i++) {
        var inp=inps[i];
        if(inp.value!='' && txts[i].value.length > 0){
            /*let sinaccent= removeAccents(inp.value);
            let minuscula= sinaccent.toLowerCase();
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");*/
            let cadena = inp.value;
            let cadenatxt = txts[i].value;
            cadenatxt = cadenatxt.replace(/(\r\n|\n|\r)/gm, "//");
            cadena= cadena.trim();
            cadenatxt= cadenatxt.trim();
            
            datos.push({
                "titulo" : cadena,
                "descripcion" : cadenatxt
            })
            contarCampos++;
        }else{
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            swal('Por favor ingrese el título del documento','','warning');
            return;
        }
    }

    if(contarCampos==inps.length){
        objeto= datos;
        return true;
    }else{
        return false;
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewDocBibliotecaVirtual(token, data, url, el, tipo){
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
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");
            if(myArr.resultado==true){
                if(tipo=='galeria'){
                    swal({
                        title:'Excelente!',
                        text:'Imágenes Registradas',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });
                }else{
                    swal({
                        title:'Excelente!',
                        text:'Documento Registrado',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });
                }

                setTimeout(function(){
                    if(tipo=='galeria'){
                        window.location='/registrar_gallery_virtual/'+currLoc+'/'+currSubc+'/v1';
                    }else{
                        window.location='/registrar_docs_virtual/'+currLoc+'/'+currSubc+'/v1';
                    }
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "Documento ya se encuentra registrado.", "error");
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

function editarCat(id, index){
    $('#idgetcategoria').val(id);
    $('#indexselection').val(index);
    var xr = new XMLHttpRequest();
    xr.open('GET', '/get-name-categoria/'+id, true);
    xr.setRequestHeader('X-CSRF-TOKEN', token);

    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                $('#inputUpCategoria').val(v.descripcion);
                $('#seltipocategoriaedit').val(v.tipo);
                if(v.estado=='0'){
                    $("#customSwitchCat").prop('checked',false);
                    $('#estadoCategoria').html('Inactivo');
                }else if(v.estado=='1'){
                    $("#customSwitchCat").prop('checked',true);
                    $('#estadoCategoria').html('Activo');
                }
            })
            setTimeout(() => {
                $('#modalUpdateCatBiV').modal('show');
            }, 450);
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    };
    xr.send();
}

$("#customSwitchCat").on('change', function() {
    if ($(this).is(':checked')) {
        $(this).attr('value', 'activo');
        $('#estadoCategoria').html('Activo');
    }
    else {
       $(this).attr('value', 'inactivo');
       $('#estadoCategoria').html('Inactivo');
    }
});

function getSelectEstadoCheck(){
    if( $('#customSwitchCat').prop('checked') ) {
        return 1;
    }else{
        return 0;
    }
}

function actualizarCategoriaBiV(){
    var token= $('#token').val();
    var itemselection= $('#indexselection').val();
    var idcategoria= $('#idgetcategoria').val();
    var categoria= $('#inputUpCategoria').val();
    var estadocategoria= getSelectEstadoCheck();
    var tipocat= $('#seltipocategoriaedit').val();

    if(categoria==''){
        $('#inputUpCategoria').focus();
        swal('Ingrese una Categoría','','warning');
    }else if(tipocat=='0'){
        $('#seltipocategoriaedit').focus();
        swal('Seleccione un tipo de categoría','','warning');
    }else{
        if(puedeActualizarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("idcategoria", idcategoria);
        formData.append("categoria", categoria);
        formData.append("tipocat", tipocat);
        formData.append("estadocategoria", estadocategoria);

        var element = document.querySelector('.updatecatbv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-categoria', true);
        xr.setRequestHeader('X-CSRF-TOKEN', token);

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
    
                    setTimeout(function(){
                        $('#tituloCatnro'+itemselection).html(categoria);
                        if(estadocategoria==0){
                            $('#spanHeaderCategoriaStatus-'+itemselection).html('Inactivo');
                        }else if(estadocategoria==1){
                            $('#spanHeaderCategoriaStatus-'+itemselection).html('Activo');
                        }
                        $('#modalUpdateCatBiV').modal('hide');
                        $('#inputUpCategoria').val("");
                        $('#idgetcategoria').val("");
                        $('#indexselection').val("");
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
                    },1500);
                }else if(myArr.resultado==false){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo guardar el registro','','error');
                }else if(myArr.resultado=='con_data'){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo guardar el registro ya que contiene archivos almacenados en esta categoría','','error');
                }
            }else if(xr.status === 400){
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        };
        xr.send(formData);
        }else{
            swal('No tiene permiso para actualizar','','error');
        }
    }
}

/* FUNCION PARA INACTIVAR SUBCATEGORIA */
function inactivarSubCat(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "0";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea inactivar este registro?",
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
            url: "/in-activar-subcategoria",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: idsubcat,
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

                    html+="<div class='btn-group btn-group-sm'>"+
                        "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteFileSubCat("+idcat+", "+idsubcat+", "+index+")'><i class='fas fa-trash'></i></a>";
                    if(estado=="1"){
                        html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar' onclick='inactivarSubCat("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye-slash'></i></a>";
                    }else if(estado=="0"){
                        html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Activar' onclick='activarSubCat("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye'></i></a>";
                    }
                    html+="<a href='javascript:void(0)' onclick='registerFileSubCat("+idcat+", "+idsubcat+")' class='btn btn-success' title='Documentos'><i class='fas fa-folder-plus'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-primary' title='Editar' onclick='editSubCat("+idsubcat+","+index+")'><i class='fas fa-edit'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesSubCat("+idcat+","+idsubcat+")'><i class='fas fa-file-signature'></i></a>";
                    html+="</div>";
                    var element= document.getElementById('TrSub'+idsubcat+'Cat'+index).cells[2];
                    $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR SUBCATEGORIA */
function activarSubCat(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "1";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-subcategoria",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: idsubcat,
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
                html+="<div class='btn-group btn-group-sm'>"+
                        "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteFileSubCat("+idcat+", "+idsubcat+", "+index+")'><i class='fas fa-trash'></i></a>";
                if(estado=="1"){
                    html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar' onclick='inactivarSubCat("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye-slash'></i></a>";
                }else if(estado=="0"){
                    html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Activar' onclick='activarSubCat("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye'></i></a>";
                }
                html+="<a href='javascript:void(0)' onclick='registerFileSubCat("+idcat+", "+idsubcat+")' class='btn btn-success' title='Documentos'><i class='fas fa-folder-plus'></i></a>"+
                "<a href='javascript:void(0)' class='btn btn-primary' title='Editar' onclick='editSubCat("+idsubcat+","+index+")'><i class='fas fa-edit'></i></a>"+
                "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesSubCat("+idcat+","+idsubcat+")'><i class='fas fa-file-signature'></i></a>";
                html+="</div>";
                var element= document.getElementById('TrSub'+idsubcat+'Cat'+index).cells[2];
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

/* FUNCION PARA INACTIVAR SUBCATEGORIA GALERIA*/
function inactivarSubCatGallery(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "0";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea inactivar este registro?",
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
            url: "/in-activar-subcategoria",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: idsubcat,
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

                    html+="<div class='btn-group btn-group-sm'>"+
                    "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteFileSubCat("+idcat+", "+idsubcat+", "+index+")'><i class='fas fa-trash'></i></a>";
                    if(estado=="1"){
                        html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar' onclick='inactivarSubCatGallery("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye-slash'></i></a>";
                    }else if(estado=="0"){
                        html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Activar' onclick='activarSubCatGallery("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye'></i></a>";
                    }
                    html+="<a href='javascript:void(0)' onclick='registerFileGallerySubCat("+idcat+", "+idsubcat+")' class='btn btn-success' title='Agregar Imágenes'><i class='fas fa-folder-plus'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-primary' title='Editar' onclick='editSubCat("+idsubcat+","+index+")'><i class='fas fa-edit'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesSubCat("+idcat+","+idsubcat+")'><i class='fas fa-file-signature'></i></a>";
                    html+="</div>";
                    var element= document.getElementById('TrSub'+idsubcat+'Cat'+index).cells[2];
                    $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR SUBCATEGORIA GALERIA*/
function activarSubCatGallery(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "1";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-subcategoria",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: idsubcat,
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
                html+="<div class='btn-group btn-group-sm'>"+
                    "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteFileSubCat("+idcat+", "+idsubcat+", "+index+")'><i class='fas fa-trash'></i></a>";
                if(estado=="1"){
                    html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar' onclick='inactivarSubCatGallery("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye-slash'></i></a>";
                }else if(estado=="0"){
                    html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Activar' onclick='activarSubCatGallery("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye'></i></a>";
                }
                html+="<a href='javascript:void(0)' onclick='registerFileGallerySubCat("+idcat+", "+idsubcat+")' class='btn btn-success' title='Agregar Imágenes'><i class='fas fa-folder-plus'></i></a>"+
                "<a href='javascript:void(0)' class='btn btn-primary' title='Editar' onclick='editSubCat("+idsubcat+","+index+")'><i class='fas fa-edit'></i></a>"+
                "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesSubCat("+idcat+","+idsubcat+")'><i class='fas fa-file-signature'></i></a>";
                html+="</div>";
                var element= document.getElementById('TrSub'+idsubcat+'Cat'+index).cells[2];
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

function editSubCat(idsubcat, index){
    var xr = new XMLHttpRequest();
    xr.open('GET', '/get-name-subcategoria/'+idsubcat, true);
    xr.setRequestHeader('X-CSRF-TOKEN', token);

    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#indexselsubcattable').val(index);
            $('#idsubcategoria').val(idsubcat);
            $(myArr).each(function(i,v){
                $('#inputviewsubcategoria').val(v.descripcion);
            })
            setTimeout(() => {
                $('#modalUpdateSubCatBiV').modal('show');
            }, 450);
        }else if(xr.status === 400){
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    };
    xr.send();
}

function actualizarSubCategoriaBiV(){
    var token= $('#token').val();
    var idsubcategoria= $('#idsubcategoria').val();
    var subcategoria= $('#inputviewsubcategoria').val();
    var itemselection= $('#indexselsubcattable').val();

    if(subcategoria==''){
        $('#inputviewsubcategoria').focus();
        swal('Ingrese una Subcategoría','','warning');
    }else{
        if(puedeActualizarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("idsubcategoria", idsubcategoria);
        formData.append("subcategoria", subcategoria);

        var element = document.querySelector('.updatesubcatbv');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-subcategoria', true);
        xr.setRequestHeader('X-CSRF-TOKEN', token);

        xr.onload = function(){
            if(xr.status === 200){
                //console.log(this.responseText);
                var myArr = JSON.parse(this.responseText);
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                if(myArr.resultado==true){
                    swal({
                        title:'Excelente!',
                        text:'Registro Actualizado',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });
    
                    setTimeout(function(){
                        var elementtb= document.getElementById('TrSub'+idsubcategoria+'Cat'+itemselection).cells[0];
                        $(elementtb).html(subcategoria);
                        $('#modalUpdateSubCatBiV').modal('hide');
                        $('#idsubcategoria').val("");
                        $('#inputviewsubcategoria').val("");
                        $('#indexselsubcattable').val("");
                        //window.location='/library-externo';
                    },1500);
                }else if(myArr.resultado==false){
                    swal('No se pudo guardar el registro','','error');
                }
            }else if(xr.status === 400){
                element.removeAttribute("disabled");
                element.style.removeProperty("pointer-events");
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        };
        xr.send(formData);
        }else{
            swal('No tiene permiso para actualizar','','error');
        }
    }
}

function registerFileSubCat(idcat, idsubcat){
    window.location='/registrar_docs_virtual/'+idcat+'/'+idsubcat+'/v1';
}

function registerFileGallerySubCat(idcat, idsubcat){
    window.location='/registrar_gallery_virtual/'+idcat+'/'+idsubcat+'/v1';
}

//FUNCION QUE DIRIGE A LA INTERFAZ QUE ENLISTA LOS DOCUMENTOS DE LA SUBCATEGORIA
function viewListFilesSubCat(idcat, idsubcat){
    window.location='/view_listdocs_subcatvirtual/'+idcat+'/'+idsubcat+'/v1';
}

function viewListFilesGallerySubCat(idcat, idsubcat){
    window.location='/view_galleryfiles_subcatvirtual/'+idcat+'/'+idsubcat+'/v1';
}

function urlbacktosubc(){
    var idcat= currIdCat;
    var idsubcat= currIdSubc;
    if(idsubcat==0){
        window.location='/library-externo';
    }else{
        window.location='/view_listdocs_subcatvirtual/'+idcat+'/'+idsubcat+'/v1';
    }
    
}

/* ================================================================================================================== */
/*                                     ARCHIVOS SUBCATEGORÍA                                                          */
/* ================================================================================================================== */
function inactivarFileSubCat(id, index, opcion){
    var token=$('#token').val();
    var estado = "0";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea inactivar este registro?",
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
            url: "/in-activar-filesubcategoria",
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
                        if(opcion=='withsc'){
                            html+=`<div class="btn-group btn-group-sm">`;
                            if(estado=="1"){
                                html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick='inactivarFileSubCat("${id}", ${index}, "withsc")'><i class="fas fa-eye-slash"></i></a>`;
                            }else if(estado=="0"){
                                html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick='activarFileSubCat("${id}", ${index}, "withsc")'><i class="fas fa-eye"></i></a>`;
                            }
                            html+=`<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick='editFileSubCat("${id}","withsc")'><i class="fas fa-edit"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick='vistaFileSubCat("${id}")'><i class="fas fa-folder"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick='downloadFileSubCat("${id}")'><i class="fas fas fa-download"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick='eliminarFileSubCat("${id}", ${index}, "withsc")'><i class="fas fa-trash"></i></a>`;
                            html+=`</div>`;
                            var element= document.getElementById('TrFile'+index).cells[2];
                            $(element).html(html);
                        }
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

function activarFileSubCat(id, index, opcion){
    var token=$('#token').val();
    var estado = "1";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-filesubcategoria",
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
                if(opcion=='withsc'){
                    html+=`<div class="btn-group btn-group-sm">`;
                    if(estado=="1"){
                        html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick='inactivarFileSubCat("${id}", ${index}, "withsc")'><i class="fas fa-eye-slash"></i></a>`;
                    }else if(estado=="0"){
                        html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick='activarFileSubCat("${id}", ${index}, "withsc")'><i class="fas fa-eye"></i></a>`;
                    }
                    html+=`<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick='editFileSubCat("${id}","withsc")'><i class="fas fa-edit"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick='vistaFileSubCat("${id}")'><i class="fas fa-folder"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick='downloadFileSubCat("${id}")'><i class="fas fas fa-download"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick='eliminarFileSubCat("${id}", ${index}, "withsc")'><i class="fas fa-trash"></i></a>`;
                    html+=`</div>`;
                    var element= document.getElementById('TrFile'+index).cells[2];
                    $(element).html(html);
                }
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

//FUNCION QUE REDIRIGE A LA INTERFAZ PARA EDITAR EL DOCUMENTO DE LA SUBCATEGORIA
function editFileSubCat(id, opcion){
    window.location='/edit_doc_subcatvirtual/'+id+'/'+opcion+'/v1';
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfiledocvirtual');
    var eldivfile= document.getElementById('cardListDocVirtual');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isDocVirtual= false;
    }
}

function getAliasE(){
    var  namecat= $('#txtnamecatedit').val();
    namecat= namecat.toLowerCase();
    var newnamecat= namecat.substring(0,3);
    if($('#inputNameDocBiVirEdit').val()!=''){
        var val= $('#inputNameDocBiVirEdit').val();
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        return newnamecat+"_"+cadena;
    }else{
        return "";
    }
}

function actualizardocvirtual(){
    var token= $('#token').val();

    var id= $('#idfilevirtual').val();
    let fileInput = document.getElementById("fileEdit");
    let aliasFileE= $('#inputAliasFileDocBiVirEdit').val();
    var descp = $('#inputDescpDocBiViredit').val();
    var lengimg = fileInput.files.length;

    if(isDocVirtual==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else if (descp.length == 0) {
            $("#inputDescpDocBiViredit").focus();
            swal("No se ha ingresado una descripción del documento", "", "warning");
        } else {
            if(puedeActualizarM(nameInterfaz) === 'si'){
            descp = descp.replace(/(\r\n|\n|\r)/gm, "//");
            //alert('TODO EN ORDEN');
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocvirtuale);
            data.append("isDocVirtual", isDocVirtual);
            data.append("descipcionfile", descp);
            setTimeout(() => {
                sendUpdateDocVirtual(token, data, "/update-docvirtual"); 
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }else{
        if(aliasFileE!=getAliasE()){
            swal("Revise el alias del documento", "", "warning");
        }else{
            if(puedeActualizarM(nameInterfaz) === 'si'){
            descp = descp.replace(/(\r\n|\n|\r)/gm, "//");
            //alert('TODO EN ORDEN');
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocvirtuale);
            data.append("isDocVirtual", isDocVirtual);
            data.append("descipcionfile", descp);
            setTimeout(() => {
                sendUpdateDocVirtual(token, data, "/update-docvirtual");
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateDocVirtual(token, data, url){
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
                    text:'Documento Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    if(currIdSubc=='0'){
                        window.location='/library-externo';
                    }else{
                        viewListFilesSubCat(currIdCat, currIdSubc);
                    }
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
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

function eliminarFileSubCat(idf, index){
    var estado = "0";
    var token= $('#token').val();
    if(puedeEliminarM(nameInterfaz) === 'si'){
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
                url: "/delete-file-oncat",
                type: "POST",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    id: idf,
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
                            viewListFilesSubCat(getIdCat, getIdSubc);
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

function downloadFileSubCat(idf){
    if(puedeDescargarM(nameInterfaz) === 'si'){
    window.location='/download-docvirtual/'+idf+'/withsc';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function vistaFileSubCat(idf){
    window.location='/view-docfilevirtual/'+idf+'/withsc';
}
/* ================================================================================================================== */
/*                                     ARCHIVOS SUBCATEGORÍA                                                          */
/* ================================================================================================================== */


/* ================================================================================================================== */
/*                                     ARCHIVOS ONLY CATEGORÍA                                                          */
/* ================================================================================================================== */
function inactivarFileOnlyCat(id, idcat, index, opcion){
    var token=$('#token').val();
    var estado = "0";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea inactivar este registro?",
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
            url: "/in-activar-filesubcategoria",
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
                        if(opcion=='nosc'){
                            html+=`<div class="btn-group btn-group-sm">`;
                            if(estado=="1"){
                                html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarFileOnlyCat(${id}, ${idcat}, ${index}, 'nosc')"><i class="fas fa-eye-slash"></i></a>`;
                            }else if(estado=="0"){
                                html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarFileOnlyCat(${id}, ${idcat}, ${index}, 'nosc')"><i class="fas fa-eye"></i></a>`;
                            }
                            html+=`<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editFileOnlyCat(${id},'nosc')"><i class="fas fa-edit"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick="vistaFileOnlyCat(${id})"><i class="fas fa-folder"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick="downloadFileOnlyCat(${id})"><i class="fas fas fa-download"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick="eliminarFileOnlyCat(${id}, ${index}, 'nosc')"><i class="fas fa-trash"></i></a>`;
                            html+=`</div>`;
                            var element= document.getElementById('TrFile'+index+'OnCat'+idcat).cells[1];
                            $(element).html(html);
                        }
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

function activarFileOnlyCat(id, idcat, index, opcion){
    var token=$('#token').val();
    var estado = "1";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-filesubcategoria",
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
                if(opcion=='nosc'){
                    html+=`<div class="btn-group btn-group-sm">`;
                    if(estado=="1"){
                        html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarFileOnlyCat(${id}, ${idcat}, ${index}, 'nosc')"><i class="fas fa-eye-slash"></i></a>`;
                    }else if(estado=="0"){
                        html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarFileOnlyCat(${id}, ${idcat}, ${index}, 'nosc')"><i class="fas fa-eye"></i></a>`;
                    }
                    html+=`<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editFileOnlyCat(${id},'nosc')"><i class="fas fa-edit"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick="vistaFileOnlyCat(${id})"><i class="fas fa-folder"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick="downloadFileOnlyCat(${id})"><i class="fas fas fa-download"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick="eliminarFileOnlyCat(${id}, ${index}, 'nosc')"><i class="fas fa-trash"></i></a>`;
                    html+=`</div>`;
                    var element= document.getElementById('TrFile'+index+'OnCat'+idcat).cells[1];
                    $(element).html(html);
                }
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

function vistaFileOnlyCat(idf){
    window.location='/view-docfilevirtual/'+idf+'/nosc';
}

function editFileOnlyCat(id, opcion){
    window.location='/edit_doc_subcatvirtual/'+id+'/'+opcion+'/v1';
}

function downloadFileOnlyCat(idf){
    if(puedeDescargarM(nameInterfaz) === 'si'){
    window.location='/download-docvirtual/'+idf+'/nosc';
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function eliminarFileOnlyCat(idf, index){
    var estado = "0";
    var token= $('#token').val();
    if(puedeEliminarM(nameInterfaz) === 'si'){
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
                url: "/delete-file-oncat",
                type: "POST",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    id: idf,
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
                            window.location='/library-externo';
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

/* ================================================================================================================== */
/*                                     ARCHIVOS GALERIA                                                               */
/* ================================================================================================================== */

/* FUNCION CARGAR IMÁGEN SELECCIONADA */
function viewopenimg(i){
   // var urlimg= '../../assets/img/banner/'+arrayImg[i];
    //var html="<img src='"+urlimg+"' alt='"+arrayImg[i]+"' />";
    var html= `<img src="/galeria-bibliotecavirtual/${arrayImg[i]}" alt="${arrayImg[i]}">`;
    var htmlspan= "<span class='spanshowdescpimg'>"+arrayImg[i]+"</span>";
    $('#divShowImgBanner').html(html);
    $('#divShowSpanBanner').html(htmlspan);
    setTimeout(() => {
        $('#modal-view-imagen').modal('show');
    }, 300);
}

function updateimggallery(index, idfile){
    $('#idindex').val(index);
    $('#idfile').val(idfile);
    $('#modal-update-imagen').modal('show');
}

function updatetxtgallery(index, idfile){
    $('#idindex').val(index);
    $('#idfile').val(idfile);

    var url= "/get-txt-img/"+idfile;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                $('#inputtituloimg').val(v.titulo);
                $('#inputdescripcionimg').val(v.descripcion);
            });
            setTimeout(() => {
                $('#modal-update-txtimagen').modal('show');
            }, 300);
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

    xr.send(null);
}

//FUNCION CERRAR MODAL UPDATE IMAGEN
function cerrarModalImg(){
    $('#modal-update-imagen').modal('hide');
    $('#images').html('');
    const file = document.querySelector('#file');
    file.value = '';

    numOfFIles.textContent = `- Ningún archivo seleccionado -`;
}

function guardarImgGaleria(){
    var token = $('#token').val()
    var pos = $('#idindex').val();
    var idimg = $('#idfile').val();
    let fileInput = document.getElementById("file");

    var lengimg = fileInput.files.length;

    if (lengimg == 0) {
        swal("No ha seleccionado una imágen", "", "warning");
    } else if(lengimg>=2){
        swal("Sólo debe seleccionar una imágen", "", "warning");
    } else {
        if(puedeGuardarM(nameInterfaz) === 'si'){
            var formData = new FormData(formImgGaleria);
            $('#btnAgendar').addClass('btndisable');
            guardarNewImgGallery(token, formData, '/update-files-bibliovirtual', pos);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function guardarNewImgGallery(token, data, url, pos){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            if(myArr.resultado==true){
                $('#btnAgendar').removeClass('btndisable');
                swal({
                    title:'Excelente!',
                    text:'Imagen Actualizada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    $('#images').html('');
                    $('#idindex').val('');
                    $('#idfile').val('');
                    $('#modal-update-imagen').modal('hide');
                    arrayImg[pos] = myArr.nombreimg;
                     // Actualiza el src del <img> en el DOM
                    let img = document.getElementById('imggaleria' + pos);
                    img.src = '/galeria-bibliotecavirtual/' + myArr.nombreimg;
                    limpiarFile();
                },1500);
            } else if (myArr.resultado == "noimagen") {
                $('#btnAgendar').removeClass('btndisable');
                swal("Formato de Imagen no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                $('#btnAgendar').removeClass('btndisable');
                swal("Error al copiar los archivos", "", "error");
            } else if(myArr.resultado==false){
                $('#btnAgendar').removeClass('btndisable');
                swal('No se pudo guardar la imagen','','error');
            }
        }else if(xr.status === 400){
            $('#btnAgendar').removeClass('btndisable');
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

/* FUNCION QUE LIMPIA EL INPUT FILE */
function limpiarFile() {
    var lengimg = fileInput.files.length;
    if (lengimg > 0) {
        let fileBuffer = Array.from(fileInput.files);
        fileBuffer.splice((i-1), 1);

        /** Code from: https://stackoverflow.com/a/47172409/8145428 */
        const dT = new ClipboardEvent('').clipboardData || // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
            new DataTransfer(); // specs compliant (as of March 2018 only Chrome)

        for (let file of fileBuffer) { dT.items.add(file); }
        fileInput.files = dT.files;
        //console.log(fileInput.files);
        if(fileInput.files.length==0){
            numOfFIles.textContent = `- Ningún archivo seleccionado -`;
        }else{
            numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;
        }
    }
}

function actualizarRegistroImg(){
    var token = $('#token').val()
    var pos = $('#idindex').val();
    var idimg = $('#idfile').val();
    var titulo = $('#inputtituloimg').val();
    var descp = $('#inputdescripcionimg').val();

    if(titulo.length == 0){
        $('#inputtituloimg').focus();
        swal("No ha ingresado un título", "", "warning");
    }else if(descp.length == 0){
        $('#inputdescripcionimg').focus();
        swal("No ha ingresado una descripción", "", "warning");
    }else{
        if(puedeGuardarM(nameInterfaz) === 'si'){
            $('#btnuptxtimg').addClass('btndisable');
            descp = descp.replace(/(\r\n|\n|\r)/gm, "//");

            var formData = new FormData();
            formData.append('idfile', idimg);
            formData.append('titulo', titulo);
            formData.append('descripcion', descp);

            guardarTxtGallery(token, formData, '/update-txtfiles-bibliovirtual', pos, titulo, descp);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function guardarTxtGallery(token, data, url, pos, titulo, desc){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            if(myArr.resultado==true){
                $('#btnuptxtimg').removeClass('btndisable');
                swal({
                    title:'Excelente!',
                    text:'Información Actualizada',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    $('#idindex').val('');
                    $('#idfile').val('');
                    $('#modal-update-txtimagen').modal('hide');
                    let ptitulo = document.getElementById('ptitulo' + pos);
                    ptitulo.innerHTML = titulo;

                    let pdesc = document.getElementById('pdescp' + pos);
                    pdesc.innerHTML = desc.replace('//', "<br>");
                },1500);
            } else if(myArr.resultado==false){
                $('#btnuptxtimg').removeClass('btndisable');
                swal('No se pudo guardar los registros','','error');
            }
        }else if(xr.status === 400){
            $('#btnuptxtimg').removeClass('btndisable');
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

/* FUNCION PARA INACTIVAR SUBCATEGORIA ARCHIVO GALERIA*/
function inactivarfilegaleria(idfile, index){
    var token=$('#token').val();
    var estado = "0";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea inactivar este registro?",
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
            url: "/in-activar-filegaleria",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: idfile,
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
                        let idEncriptado = "'"+idfile+"'";
                        if(estado=="1"){
                            html = $('<a>', {
                                href: 'javascript:void(0)',
                                class: 'btn btn-secondary btn-sm',
                                html: '<i class="fas fa-eye-slash"></i>', // contenido HTML del <a>
                                click: function() {
                                    inactivarfilegaleria(idEncriptado, index);
                                }
                            });
                            $('#pestado'+index).html("Activo");
                        }else if(estado=="0"){
                            html = $('<a>', {
                                href: 'javascript:void(0)',
                                class: 'btn btn-secondary btn-sm',
                                html: '<i class="fas fa-eye"></i>', // contenido HTML del <a>
                                click: function() {
                                    activarfilegaleria(idEncriptado, index);
                                }
                            });
                            $('#pestado'+index).html("Inactivo");
                        }
                        // Insertar al inicio del contenedor con clase text-right
                        $('#footerCard'+index).prepend(html);
                        // Eliminar el enlace que estaba primero antes de agregar el nuevo
                        $('#footerCard'+index+' a').eq(1).remove(); // eq(1) porque ahora el nuevo es eq(0)
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR SUBCATEGORIA GALERIA*/
function activarfilegaleria(idfile, index){
    var token=$('#token').val();
    var estado = "1";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-filegaleria",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: idfile,
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
                let idEncriptado = "'"+idfile+"'";
                if(estado=="1"){
                    html = $('<a>', {
                        href: 'javascript:void(0)',
                        class: 'btn btn-secondary btn-sm',
                        html: '<i class="fas fa-eye-slash"></i>', // contenido HTML del <a>
                        click: function() {
                            inactivarfilegaleria(idEncriptado, index);
                        }
                    });
                    $('#pestado'+index).html("Activo");
                }else if(estado=="0"){
                    html = $('<a>', {
                        href: 'javascript:void(0)',
                        class: 'btn btn-secondary btn-sm',
                        html: '<i class="fas fa-eye"></i>', // contenido HTML del <a>
                        click: function() {
                            activarfilegaleria(idEncriptado, index);
                        }
                    });
                    $('#pestado'+index).html("Inactivo");
                }
                // Insertar al inicio del contenedor con clase text-right
                $('#footerCard'+index).prepend(html);
                // Eliminar el enlace que estaba primero antes de agregar el nuevo
                $('#footerCard'+index+' a').eq(1).remove(); // eq(1) porque ahora el nuevo es eq(0)
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

/* FUNCION PARA ELIMINAR BANNER */
function eliminarfileongaleria(id, i) {
    var estado = "0";
    var token= $('#token').val();

    let urlActual = window.location.href;

    if(puedeEliminarM(nameInterfaz) === 'si'){
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
            url: "/delete-file-galeria",
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
                        window.location= urlActual;
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

function urlbacktosubcgallery(){
    window.location= '/library-externo';
}

function deleteGallerySubCat(idcat, idsubcat, index){
    var token= $('#token').val();

    let urlActual = window.location.href;

    if(puedeEliminarM(nameInterfaz) === 'si'){
    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar esta subcategoría y sus archivos?",
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
            url: "/delete-gallery-sure-subcategoria",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                idcat: idcat,
                idsubcat: idsubcat
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
                        $('#TrSub'+idsubcat+'Cat'+index).remove();
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Eliminar", "", "error");
                } else if (res.resultado == 'no_all_delete') {
                    swal("No se pudo Eliminar todos los registros", "", "error");
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

function deleteFileSubCat(idcat, idsubcat, index){
    var token= $('#token').val();

    let urlActual = window.location.href;

    if(puedeEliminarM(nameInterfaz) === 'si'){
    Swal.fire({
      title: "<strong>¡Aviso!</strong>",
      type: "warning",
      html: "¿Está seguro que desea eliminar esta subcategoría y sus archivos?",
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
            url: "/delete-file-sure-subcategoria",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                idcat: idcat,
                idsubcat: idsubcat
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
                        $('#TrSub'+idsubcat+'Cat'+index).remove();
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Eliminar", "", "error");
                } else if (res.resultado == 'no_all_delete') {
                    swal("No se pudo Eliminar todos los registros", "", "error");
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

/* ================================================================================================================== */
/*                                     ARCHIVOS VIDEOS                                                               */
/* ================================================================================================================== */

/* FUNCION PARA INACTIVAR SUBCATEGORIA GALERIA*/
function inactivarSubCatVideo(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "0";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea inactivar este registro?",
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
            url: "/in-activar-subcategoria",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: idsubcat,
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

                    html+="<div class='btn-group btn-group-sm'>"+
                    "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteVideoSubCat("+idcat+", "+idsubcat+", "+index+")'><i class='fas fa-trash'></i></a>";
                    if(estado=="1"){
                        html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar' onclick='inactivarSubCatVideo("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye-slash'></i></a>";
                    }else if(estado=="0"){
                        html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Activar' onclick='activarSubCatVideo("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye'></i></a>";
                    }
                    html+="<a href='javascript:void(0)' onclick='registerFileVideoSubCat("+idcat+", "+idsubcat+")' class='btn btn-success' title='Agregar Imágenes'><i class='fas fa-folder-plus'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-primary' title='Editar' onclick='editSubCat("+idsubcat+","+index+")'><i class='fas fa-edit'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesVideoSubCat("+idcat+","+idsubcat+")'><i class='fas fa-file-signature'></i></a>";
                    html+="</div>";
                    var element= document.getElementById('TrSub'+idsubcat+'Cat'+index).cells[2];
                    $(element).html(html);
                    }, 1500);
                } else if (res.resultado == false) {
                    swal("No se pudo Inactivar", "", "error");
                }
            },
            });
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/* FUNCION PARA ACTIVAR SUBCATEGORIA GALERIA*/
function activarSubCatVideo(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "1";
    var html="";
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-subcategoria",
      type: "POST",
      dataType: "json",
      headers: {'X-CSRF-TOKEN': token},
      data: {
        id: idsubcat,
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
                html+="<div class='btn-group btn-group-sm'>"+
                    "<a href='javascript:void(0)' class='btn btn-danger' title='Eliminar SubCategoría' onclick='deleteVideoSubCat("+idcat+", "+idsubcat+", "+index+")'><i class='fas fa-trash'></i></a>";
                if(estado=="1"){
                    html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar' onclick='inactivarSubCatVideo("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye-slash'></i></a>";
                }else if(estado=="0"){
                    html+="<a href='javascript:void(0)' class='btn btn-secondary' title='Activar' onclick='activarSubCatVideo("+idsubcat+", "+idcat+", "+index+")'><i class='fas fa-eye'></i></a>";
                }
                html+="<a href='javascript:void(0)' onclick='registerFileVideoSubCat("+idcat+", "+idsubcat+")' class='btn btn-success' title='Agregar Imágenes'><i class='fas fa-folder-plus'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-primary' title='Editar' onclick='editSubCat("+idsubcat+","+index+")'><i class='fas fa-edit'></i></a>"+
                    "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesVideoSubCat("+idcat+","+idsubcat+")'><i class='fas fa-file-signature'></i></a>";
                html+="</div>";
                var element= document.getElementById('TrSub'+idsubcat+'Cat'+index).cells[2];
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

function registerFileVideoSubCat(idcat, idsubcat){
    window.location='/registrar_videos_virtual/'+idcat+'/'+idsubcat+'/v1';
}

function guardarVideosDocvi(){
    let fileInput = document.getElementById("file");
    var idsubcat = $("#selSubCategoria :selected").val();

    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('uploadProgress');
    const progressText = document.getElementById('progressText');
    const resultDiv = document.getElementById('uploadResult');

    var lengimg = fileInput.files.length;
    var token= $('#token').val();
    if (lengimg == 0) {
        swal("No ha seleccionado imágenes", "", "warning");
    } else {
        if(puedeGuardarM(nameInterfaz) === 'si'){
            //$('#modalFullSend').modal('show');
            var element = document.querySelector('.savedocvirtual');
            /*element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";*/

            progressContainer.style.display = 'block';
            progressBar.value = 0;
            progressText.textContent = '0%';

            setTimeout(() => {
                var data = new FormData(formVideoBiVirtual);
                data.append('idsubcat', currSubc);
                setTimeout(() => {
                    sendNewVideoBibliotecaBv(token, data, "/store-videos-bibliovirtual", element);
                }, 900);
            }, 900);
        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewVideoBibliotecaBv(token, data, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);

    xr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressBar.value = percent;
            progressText.textContent = percent + '%';
        }
    });

    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            $('#modalFullSend').modal('hide');
            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Video Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location='/registrar_videos_virtual/'+currLoc+'/'+currSubc+'/v1';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
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