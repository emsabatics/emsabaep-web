var datos= [];
var objeto= {};
var contarCampos=0;

function urlregistrarcate(){
    $('#modalAggCatDocOp').modal('show');
}

function guardarCategoriaDocOp(){
    var token= $('#token').val();
    var categoria= $('#inputCategoria').val();

    if(categoria==''){
        $('#inputCategoria').focus();
        swal('Ingrese una categoría','','warning');
    }else{
        if(puedeGuardarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("categoria", categoria);

        var element = document.querySelector('.savecatdo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/registro-categoria-operativo', true);
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
                        $('#modalAggCatDocOp').modal('hide');
                        $('#inputCategoria').val("");
                        window.location='/docoperativo';
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
    xr.open('GET', '/get-name-categoria-docop/'+id, true);
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
                $('#modalAggSubCatDocOp').modal('show');
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

function guardarSubCategoriaDocOp(){
    var token= $('#token').val();
    var idcategoria= $('#idcategoria').val();
    var subcategoria= $('#inputSubcategoria').val();
    var tipocat = $('#tipocategoria').val();
    var itemselection= $('#indexselsubcat').val();
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
        xr.open('POST', '/registro-subcategoria-operativo', true);
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
                        /*html+="<tr id='TrSub"+myArr.ID+"Cat"+filas+"'>"+
                        "<td>"+subcategoria+"</td>"+
                        "<td>Sin Archivos</td>"+
                        "<td class='text-right py-0 align-middle'>"+
                            "<div class='btn-group btn-group-sm'>"+
                                '<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar SubCategoría" onclick="deleteFileSubCat("'+myArr.codecat.toString()+'", '+myArr.codesubcat.toString()+', '+filas+')"><i class="fas fa-trash"></i></a>'+
                                '<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarSubCat('+myArr.codesubcat.toString()+', '+myArr.codecat.toString()+', '+filas+')"><i class="fas fa-eye-slash"></i></a>'+
                                '<a href="javascript:void(0)" class="btn btn-success" title="Documentos" onclick="registerFileSubCat('+myArr.codecat.toString()+', '+myArr.codesubcat.toString()+')"><i class="fas fa-folder-plus"></i></a>'+
                                '<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editSubCat('+myArr.codesubcat.toString()+', '+filas+')"><i class="fas fa-edit"></i></a>'+
                                '<a href="javascript:void(0)" class="btn btn-info" title="Editar Documentos SubCategoría" onclick="viewListFilesSubCat('+myArr.codecat.toString()+', '+myArr.codesubcat.toString()+')"><i class="fas fa-file-signature"></i></a>'+
                                "</div>"+
                            "</td>"+
                        "</tr>";*/

                        $('#BodyTableSubCat'+itemselection).load('/get-data-table-docop/'+myArr.codecat+'/'+myArr.codesubcat);

                        if ( $("#nodatacat"+idcategoria)[0] ) {
                            // hacer algo aquí si el elemento existe
                            $("#nodatacat"+idcategoria)[0].remove();
                            $('#TableSubCat'+itemselection+' > tbody:last-child').append(html);
                        }else{
                            $('#TableSubCat'+itemselection+' > tbody:last-child').append(html);
                        }
                        
                        $('#modalAggSubCatDocOp').modal('hide');
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

function editarCat(id, index){
    $('#idgetcategoria').val(id);
    $('#indexselection').val(index);
    var xr = new XMLHttpRequest();
    xr.open('GET', '/get-name-categoria-docop/'+id, true);
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
                $('#modalUpdateCatDocOp').modal('show');
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

function actualizarCategoriaDocOp(){
    var token= $('#token').val();
    var itemselection= $('#indexselection').val();
    var idcategoria= $('#idgetcategoria').val();
    var categoria= $('#inputUpCategoria').val();
    var estadocategoria= getSelectEstadoCheck();

    if(categoria==''){
        $('#inputUpCategoria').focus();
        swal('Ingrese una Categoría','','warning');
    }else{
        if(puedeActualizarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("idcategoria", idcategoria);
        formData.append("categoria", categoria);
        formData.append("estadocategoria", estadocategoria);

        var element = document.querySelector('.updatecatdo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-categoria-operativo', true);
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
                        $('#modalUpdateCatDocOp').modal('hide');
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

function getSelectEstadoCheck(){
    if( $('#customSwitchCat').prop('checked') ) {
        return 1;
    }else{
        return 0;
    }
}

function registerFileSubCat(idcat, idsubcat){
    window.location='/registrar_docs_operativo/'+idcat+'/'+idsubcat+'/op1';
}

function guardarDocOper(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var year = $("#selYearDocOper :selected").val();
    var mes = $("#selMes :selected").val();
    var nombredoc= $('#inputNameDocOper').val();
    var aliasfile = $("#inputAliasFileDocOper").val();
    var lengimg = fileInput.files.length;
    var typefile= fileInput.files[0].type;
    //var titulo= $('#inputDocTitle').val();

    if (year == "0") {
        $("#selYearDocOper").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (nombredoc == "") {
        $('#inputNameDocOper').focus();
        swal("Ingrese el nombre del documento", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFileDocOper").focus();
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
            if(puedeGuardarM(nameInterfaz) === 'si'){
            var element = document.querySelector('.savedocoper');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            $('#modalFullSend').modal('show');

            var data = new FormData(formDocOper);
            data.append("anio", year);
            data.append("mes", mes);
            data.append("idcat", idcat);
            data.append("idsubcat", idsubcat);
            /*data.append("typefile", typefile);
            data.append("lengfile", lengimg);*/

            setTimeout(() => {
                sendNewDocOperativo(token, data, "/store-doc-operativo", element); 
            }, 700);
            }else{
                swal('No tiene permiso para guardar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewDocOperativo(token, data, url, el){
    let currentUrl = window.location.href;

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
                    //urlback();
                    //window.location='/registrar_doc_operativo';
                    window.location = currentUrl;
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

/* FUNCION PARA INACTIVAR SUBCATEGORIA */
function inactivarSubCat(idsubcat, idcat, index){
    var token=$('#token').val();
    var estado = "0";
    var html="";
    var codecat = $('#idcat_encriptado_item'+index).val();
    var codesubcat = $('#idsubcat_encriptado_item'+index).val();

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
            url: "/in-activar-subcat-docop",
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
                        '<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar SubCategoría" onclick="deleteFileSubCat('+codecat+', '+codesubcat+', '+index+')"><i class="fas fa-trash"></i></a>';
                    if(estado=="1"){
                        html+='<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarSubCat('+codesubcat+', '+codecat+', '+index+')"><i class="fas fa-eye-slash"></i></a>';
                    }else if(estado=="0"){
                        html+='<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarSubCat('+codesubcat+', '+codecat+', '+index+')"><i class="fas fa-eye"></i></a>';
                    }
                    html+='<a href="javascript:void(0)" class="btn btn-success" title="Documentos" onclick="registerFileSubCat('+codecat+', '+codesubcat+')"><i class="fas fa-folder-plus"></i></a>'+
                    '<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editSubCat('+codesubcat+', '+index+')"><i class="fas fa-edit"></i></a>'+
                    '<a href="javascript:void(0)" class="btn btn-info" title="Editar Documentos SubCategoría" onclick="viewListFilesSubCat('+codecat+', '+codesubcat+')"><i class="fas fa-file-signature"></i></a>';
                    html+="</div>";
                    var element= document.getElementById('TrSub'+(index+1)+'Cat'+index).cells[2];
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
    var codecat = $('#idcat_encriptado_item'+index).val();
    var codesubcat = $('#idsubcat_encriptado_item'+index).val();
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-subcat-docop",
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
                    '<a href="javascript:void(0)" class="btn btn-danger" title="Eliminar SubCategoría" onclick="deleteFileSubCat('+codecat+', '+codesubcat+', '+index+')"><i class="fas fa-trash"></i></a>';
                if(estado=="1"){
                    html+='<a href="javascript:void(0)" class="btn btn-secondary" title="Inactivar" onclick="inactivarSubCat('+codesubcat+', '+codecat+', '+index+')"><i class="fas fa-eye-slash"></i></a>';
                }else if(estado=="0"){
                    html+='<a href="javascript:void(0)" class="btn btn-secondary" title="Activar" onclick="activarSubCat('+codesubcat+', '+codecat+', '+index+')"><i class="fas fa-eye"></i></a>';
                }
                html+='<a href="javascript:void(0)" class="btn btn-success" title="Documentos" onclick="registerFileSubCat('+codecat+', '+codesubcat+')"><i class="fas fa-folder-plus"></i></a>'+
                    '<a href="javascript:void(0)" class="btn btn-primary" title="Editar" onclick="editSubCat('+codesubcat+', '+index+')"><i class="fas fa-edit"></i></a>'+
                    '<a href="javascript:void(0)" class="btn btn-info" title="Editar Documentos SubCategoría" onclick="viewListFilesSubCat('+codecat+', '+codesubcat+')"><i class="fas fa-file-signature"></i></a>';
                html+="</div>";
                var element= document.getElementById('TrSub'+(index+1)+'Cat'+index).cells[2];
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
    xr.open('GET', '/get-docop-name-subcategoria/'+idsubcat, true);
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
                $('#modalUpdateSubCatDocOp').modal('show');
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

function actualizarSubCategoriaDocOp(){
    var token= $('#token').val();
    var idsubcategoria= $('#idsubcategoria').val();
    var subcategoria= $('#inputviewsubcategoria').val();
    var itemselection= $('#indexselsubcattable').val();
    itemselection = parseInt(itemselection);

    if(subcategoria==''){
        $('#inputviewsubcategoria').focus();
        swal('Ingrese una Subcategoría','','warning');
    }else{
        if(puedeActualizarM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("idsubcategoria", idsubcategoria);
        formData.append("subcategoria", subcategoria);

        var element = document.querySelector('.updatesubcatdo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-subcategoria-operativo', true);
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
                    var elementtb= document.getElementById('TrSub'+(itemselection+1)+'Cat'+itemselection).cells[0];
                    setTimeout(function(){
                        //var elementtb= document.getElementById('TrSub'+(itemselection+1)+'Cat'+itemselection).cells[0];
                        $(elementtb).html(subcategoria);
                        $('#modalUpdateSubCatDocOp').modal('hide');
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

function urlback(){
    window.location='/docoperativo';
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
            url: "/delete-filedocop-sure-subcategoria",
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
                        $('#TrSub'+(index+1)+'Cat'+index).remove();
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

//FUNCION QUE DIRIGE A LA INTERFAZ QUE ENLISTA LOS DOCUMENTOS DE LA SUBCATEGORIA
function viewListFilesSubCat(idcat, idsubcat){
    window.location='/view_listdocsop_subcat/'+idcat+'/'+idsubcat+'/v1';
}

function urlregistrardocoperativo(idcat, idsubcat){
    window.location='/registrar_docs_operativo/'+idcat+'/'+idsubcat+'/op2';
}

function viewopenDocOper(id){
    window.location='/view-docoperativo/'+id;
}

function urlbacklistdocs(idcat, idsubcat){
    window.location='/view_listdocsop_subcat/'+idcat+'/'+idsubcat+'/v1';
}

function interfaceupdateDocOper(id){
    window.location= '/edit-docoperativo/'+id;
}

/* FUNCION PARA ELIMINAR PERMANENTEMENTE Documentación Operativa */
function eliminarpermdocoper(){
    var token=$('#token').val();
    var id= $('#iddocoperativo').val();
    var idcat = $('#idcategoria').val();
    var idsubcat = $('#idsubcategoria').val();
    if(puedeEliminarM(nameInterfaz) === 'si'){
    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea eliminar permanentemente este registro?",
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
                url: "/delete-docoperativo",
                type: "POST",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    id: id
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
                            urlbacklistdocs(idcat, idsubcat);
                        }, 1500);
                    } else if (res.resultado == false) {
                        swal("No se pudo Inactivar", "", "error");
                    }
                },
            });
        }
    });
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

function actualizardocoper(){
    var token= $('#token').val();

    var id= $('#iddocoperativo').val();
    let fileInput = document.getElementById("fileEdit");
    let aliasFileE= $('#inputEAliasFile').val();
    var lengimg = fileInput.files.length;

    var idcat = $('#idcategoria').val();
    var idsubcat = $('#idsubcategoria').val();

    if(isDocOperativo==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else {
            if(puedeActualizarM(nameInterfaz) === 'si'){
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocoperativoe);
            data.append("isDocOperativo", isDocOperativo);
            setTimeout(() => {
                sendUpdateDocOperativo(token, data, "/update-docoperativo", idcat, idsubcat); 
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
            $('#modalFullSend').modal('show');
            var data = new FormData(formdocoperativoe);
            data.append("isDocOperativo", isDocOperativo);
            setTimeout(() => {
                sendUpdateDocOperativo(token, data, "/update-docoperativo", idcat, idsubcat);
            }, 700);
            }else{
                swal('No tiene permiso para actualizar','','error');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateDocOperativo(token, data, url, idcat, idsubcat){
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
                    urlbacklistdocs(idcat, idsubcat);
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

function generarAliasE(){
    //toastr.info("No se permite generar el Alias...", "!Aviso!");
    var year= $('#selYearEditDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputEDocTitle').val()!=''){
            var val= $('#inputEDocTitle').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            $('#inputEAliasFile').val(year+"_"+cadena);
        }else{
            $('#inputEDocTitle').focus();
            toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
        }
    }else{
        $('#selYearEditDocOper').focus();
        toastr.info("Debe elegir Año correspondiente...", "!Aviso!");
        $('#inputEAliasFile').val('');
    }
}

function getAliasE(){
    var year= $('#selYearEditDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputEDocTitle').val()!=''){
            var val= $('#inputEDocTitle').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            return year+"_"+cadena;
        }else{
            return ' ';
        }
    }else{
        return ' ';
    }
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfiledocoper');
    var eldivfile= document.getElementById('cardListDocOper');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isDocOperativo= false;
    }
}

/* FUNCION PARA INACTIVAR Documentación Operativa */
function inactivarDocOper(id, i){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
    var html="";
    var codedoc = $('#iddocumento'+i).val();
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
            url: "/in-activar-docoperativo",
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

                    html+='<button type="button" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenDocOper('+codedoc+', '+i+')">'+
                        "<i class='fas fa-folder mr-2'></i>"+
                        "Ver"+
                    "</button>"+
                    '<button type="button" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateDocOper('+codedoc+')">'+
                        "<i class='far fa-edit mr-2'></i>"+
                        "Actualizar"+
                    "</button>";
                    if(estado=="1"){
                        html+='<button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Inactivar" onclick="inactivarDocOper('+codedoc+', '+i+')">'+
                            "<i class='fas fa-eye-slash mr-2'></i>"+
                            "Inactivar"+
                        "</button>";
                    }else if(estado=="0"){
                            html+='<button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Activar" onclick="activarDocOper('+codedoc+', '+i+')">'+
                                "<i class='fas fa-eye mr-2'></i>"+
                                "Activar"+
                            "</button>";
                    }
                    html+='<button type="button" class="btn btn-success btn-sm mr-3 btntable" title="Descargar" onclick="downloadDocOper('+codedoc+')">'+
                        "<i class='fas fa-download mr-2'></i>"+
                        "Descargar Documento"+
                    "</button>";
                    var element= document.getElementById('Tr'+i).cells[4];
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

/* FUNCION PARA ACTIVAR Documentación Operativa */
function activarDocOper(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    var codedoc = $('#iddocumento'+i).val();
    if(puedeActualizarM(nameInterfaz) === 'si'){
    $.ajax({
      url: "/in-activar-docoperativo",
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

            html+='<button type="button" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenDocOper('+codedoc+', '+i+')">'+
                "<i class='fas fa-folder mr-2'></i>"+
                "Ver"+
            "</button>"+
            '<button type="button" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdateDocOper('+codedoc+')">'+
                "<i class='far fa-edit mr-2'></i>"+
                "Actualizar"+
            "</button>";
            if(estado=="1"){
                html+='<button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Inactivar" onclick="inactivarDocOper('+codedoc+', '+i+')">'+
                    "<i class='fas fa-eye-slash mr-2'></i>"+
                    "Inactivar"+
                "</button>";
            }else if(estado=="0"){
                html+='<button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Activar" onclick="activarDocOper('+codedoc+', '+i+')">'+
                    "<i class='fas fa-eye mr-2'></i>"+
                    "Activar"+
                "</button>";
            }
            html+='<button type="button" class="btn btn-success btn-sm mr-3 btntable" title="Descargar" onclick="downloadDocOper('+codedoc+')">'+
                "<i class='fas fa-download mr-2'></i>"+
                "Descargar Documento"+
            "</button>";
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

function downloadDocOper(id){
    if(puedeDescargarM(nameInterfaz) === 'si'){
    window.location='/download-docoperativo/'+id;
    }else{
        swal('No tiene permiso para realizar esta acción','','error');
    }
}

//-------------------------------->
const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function getAliasInput(){
    var year= $('#selYearDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputNameDocOper').val()!=''){
            var val= $('#inputNameDocOper').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            return year+"_"+cadena;
        }else{
            return "";
        }
    }else{
        return "";
    }
}

function generarAlias(){
    var year= $('#selYearDocOper').select2('data')[0].text;
    if(year!='-Seleccione una Opción-'){
        if($('#inputNameDocOper').val()!=''){
            var val= $('#inputNameDocOper').val();
            let sinaccent= removeAccents(val);
            let minuscula= sinaccent.toLowerCase();
            //let cadenasinpoint= minuscula.replaceAll(".","");
            let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
            let cadena= cadenasinpoint.replaceAll(" ","_");
            $('#inputAliasFileDocOper').val(year+"_"+cadena);
        }else{
            $('#inputNameDocOper').focus();
            toastr.info("Debe ingresar el título correspondiente...", "!Aviso!");
        }
    }else{
        $('#selYearDocOper').focus();
        toastr.info("Debe elegir Año correspondiente...", "!Aviso!");
        $('#inputAliasFileDocOper').val('');
    }
}

function showInfoOperativo(){
    $('#modalCargando').modal('hide');
    $("#tablaDocOper")
        .removeAttr("width")
        .DataTable({
            autoWidth: true,
            lengthMenu: [
                [8, 16, 32, 64, -1],
                [8, 16, 32, 64, "Todo"],
            ],
            //para cambiar el lenguaje a español
            language: {
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty:
                    "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                sSearch: "Buscar:",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior",
                },
                sProcessing: "Procesando...",
            },
            columnDefs: [
                { width: 40, targets: 0, className: "text-center" },
                { className: "dt-head-center", targets: [1, 2, 3, 4] },
            ],
        });
}
