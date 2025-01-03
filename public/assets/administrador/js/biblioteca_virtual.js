function urlregistrarcate(){
    $('#modalAggCatBiV').modal('show');
}

function guardarCategoriaBiV(){
    var token= $('#token').val();
    var categoria= $('#inputCategoria').val();

    if(categoria==''){
        $('#inputCategoria').focus();
        swal('Ingrese una categoría','','warning');
    }else{
        var formData= new FormData();
        formData.append("categoria", categoria);

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
    var html="";
    let filas = $('#TableSubCat'+itemselection).find('tbody tr').length;

    if(subcategoria==''){
        $('#inputSubcategoria').focus();
        swal('Ingrese una Subcategoría','','warning');
    }else{
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
                        html+="<tr id='TrSub"+myArr.ID+"Cat"+filas+"'>"+
                            "<td>"+subcategoria+"</td>"+
                            "<td>"+myArr.totalfile+"</td>"+
                            "<td class='text-right py-0 align-middle'>"+
                                "<div class='btn-group btn-group-sm'>"+
                                    "<a href='javascript:void(0)' class='btn btn-secondary' title='Inactivar Subcategoría' onclick='inactivarSubCat("+myArr.ID+","+idcategoria+","+filas+")'><i class='fas fa-eye-slash'></i></a>"+
                                    "<a href='javascript:void(0)' onclick='registerFileSubCat("+idcategoria+", "+myArr.ID+")' class='btn btn-success' title='Agregar Documentos'><i class='fas fa-folder-plus'></i></a>"+
                                    "<a href='javascript:void(0)' class='btn btn-primary' title='Editar Subcategoría' onclick='editSubCat("+myArr.ID+","+filas+")'><i class='fas fa-edit'></i></a>"+
                                    "<a href='javascript:void(0)' class='btn btn-info' title='Editar Documentos SubCategoría' onclick='viewListFilesSubCat("+idcategoria+","+myArr.ID+")'><i class='fas fa-file-signature'></i></a>"+
                                "</div>"+
                            "</td>"+
                        "</tr>";
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
    var lengimg = fileInput.files.length;
    var typefile= fileInput.files[0].type;

    if (nombredoc == "") {
        $('#inputNameDocBiVir').focus();
        swal("Ingrese el nombre del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileDocBiVir").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else {
        //console.log(aliasfile, getAliasInput());
        if(aliasfile!=getAliasInput()){
            swal('Revise el alias del documento','','warning');
        }else{
            var element = document.querySelector('.savedocvirtual');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');

            var data = new FormData(formDocBiVirtual);
            data.append("subcategoria", subcategoria);
            /*data.append("typefile", typefile);
            data.append("lengfile", lengimg);*/

            setTimeout(() => {
                sendNewDocBibliotecaVirtual(token, data, "/store-doc-bibliovirtual", element); 
            }, 700);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewDocBibliotecaVirtual(token, data, url, el){
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
                swal({
                    title:'Excelente!',
                    text:'Documento Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location='/registrar_doc_virtual/'+currLoc+'/'+currSubc+'/v1';
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

    if(categoria==''){
        $('#inputUpCategoria').focus();
        swal('Ingrese una Categoría','','warning');
    }else{
        var formData= new FormData();
        formData.append("idcategoria", idcategoria);
        formData.append("categoria", categoria);
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
    }
}

/* FUNCION PARA INACTIVAR SUBCATEGORIA */
function inactivarSubCat(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "0";
    var html="";
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

                    html+="<div class='btn-group btn-group-sm'>";
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
}

/* FUNCION PARA ACTIVAR SUBCATEGORIA */
function activarSubCat(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "1";
    var html="";
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
                html+="<div class='btn-group btn-group-sm'>";
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
                if(myArr.resultado==true){
                    swal({
                        title:'Excelente!',
                        text:'Registro Actualizado',
                        type:'success',
                        showConfirmButton: false,
                        timer: 1700
                    });
    
                    setTimeout(function(){
                        var element= document.getElementById('TrSub'+idsubcategoria+'Cat'+itemselection).cells[0];
                        $(element).html(subcategoria);
                        $('#modalUpdateSubCatBiV').modal('hide');
                        $('#idsubcategoria').val("");
                        $('#inputviewsubcategoria').val("");
                        $('#indexselsubcattable').val("");
                        //window.location='/library-externo';
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
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
    }
}

function registerFileSubCat(idcat, idsubcat){
    window.location='/registrar_doc_virtual/'+idcat+'/'+idsubcat+'/v1';
}

//FUNCION QUE DIRIGE A LA INTERFAZ QUE ENLISTA LOS DOCUMENTOS DE LA SUBCATEGORIA
function viewListFilesSubCat(idcat, idsubcat){
    window.location='/view_listdocs_subcatvirtual/'+idcat+'/'+idsubcat+'/v1';
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
                                html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarFileSubCat(${id}, ${index}, 'withsc')"><i class="fas fa-eye-slash"></i></a>`;
                            }else if(estado=="0"){
                                html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarFileSubCat(${id}, ${index}, 'withsc')"><i class="fas fa-eye"></i></a>`;
                            }
                            html+=`<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editFileSubCat(${id},'withsc')"><i class="fas fa-edit"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick="vistaFileSubCat(${id})"><i class="fas fa-folder"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick="downloadFileSubCat(${id})"><i class="fas fas fa-download"></i></a>`+
                            `<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick="eliminarFileSubCat(${id}, ${index}, 'withsc')"><i class="fas fa-trash"></i></a>`;
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
}

function activarFileSubCat(id, index, opcion){
    var token=$('#token').val();
    var estado = "1";
    var html="";
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
                        html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarFileSubCat(${id}, ${index}, 'withsc')"><i class="fas fa-eye-slash"></i></a>`;
                    }else if(estado=="0"){
                        html+=`<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarFileSubCat(${id}, ${index}, 'withsc')"><i class="fas fa-eye"></i></a>`;
                    }
                    html+=`<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editFileSubCat(${id},'withsc')"><i class="fas fa-edit"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-secondary" title="Ver Documento" onclick="vistaFileSubCat(${id})"><i class="fas fa-folder"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-success" title="Descargar Documento" onclick="downloadFileSubCat(${id})"><i class="fas fas fa-download"></i></a>`+
                    `<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar" onclick="eliminarFileSubCat(${id}, ${index}, 'withsc')"><i class="fas fa-trash"></i></a>`;
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
    var lengimg = fileInput.files.length;

    if(isDocVirtual==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else {
            //alert('TODO EN ORDEN');
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocvirtuale);
            data.append("isDocVirtual", isDocVirtual);
            setTimeout(() => {
                sendUpdateDocVirtual(token, data, "/update-docvirtual"); 
            }, 700);
        }
    }else{
        if(aliasFileE!=getAliasE()){
            swal("Revise el alias del documento", "", "warning");
        }else{
            //alert('TODO EN ORDEN');
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocvirtuale);
            data.append("isDocVirtual", isDocVirtual);
            setTimeout(() => {
                sendUpdateDocVirtual(token, data, "/update-docvirtual");
            }, 700);
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
}

function downloadFileSubCat(idf){
    window.location='/download-docvirtual/'+idf+'/withsc';
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
}

function activarFileOnlyCat(id, idcat, index, opcion){
    var token=$('#token').val();
    var estado = "1";
    var html="";
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
}

function vistaFileOnlyCat(idf){
    window.location='/view-docfilevirtual/'+idf+'/nosc';
}

function editFileOnlyCat(id, opcion){
    window.location='/edit_doc_subcatvirtual/'+id+'/'+opcion+'/v1';
}

function downloadFileOnlyCat(idf){
    window.location='/download-docvirtual/'+idf+'/nosc';
}

function eliminarFileOnlyCat(idf, index){
    var estado = "0";
    var token= $('#token').val();

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
}