var seltypedep='';
var seldependencia='';
var arrayDepEdit= [];
var sel_dep_edit='';

function addDepartamento(){
    window.location='/registrar-departamento';
}

function backInterfaceDep(){
    window.location='/departamentos';
}

/* FUNCION QUE MUESTRA INFORMACION DE LOS DEPARTAMENTOS CREADOS */
function cargar_departamento(gerencia, direccion, coordinacion){
    var con = 1; var estadoItem=''; var posi=0; var tipoop;
    var html =
        "<table class='table datatables' id='tablaDepartamentos'>" +
            "<thead class='thead-dark'>" +
                "<tr style='pointer-events:none;'>" +
                    "<th>N°</th>" +
                    "<th>Nombre</th>" +
                    "<th>Detalle</th>"+
                    "<th>Estado</th>" +
                    "<th>Opciones</th>" +
                "</tr>" +
            "</thead>" +
        "<tbody>";

    if(gerencia.length>0 || direccion.length>0 || coordinacion.length>0){
        $(gerencia).each(function(i, v){
            tipoop= '"'+"gerencia"+'"';
            if(v.estado=="1"){
                estadoItem="Visible";
            }else{
                estadoItem="No Visible";
            }

            html +="<tr id='Tr"+con +"'>"+
                "<td style='text-align: center;'>"+con+"</td>"+
                "<td>"+v.nombre+"</td>"+
                "<td> Gerencia </td>"+
                "<td>"+estadoItem+"</td>" +
                "<td style='display: flex;'>"+
                "<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarGerencia("+v.id+")'>"+
                    "<i class='fas fa-pencil-alt'></i>"+
                "</button>";
                if(v.estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerItem("+v.id+", "+posi+", "+tipoop+")'>"+
                        "<i class='fas fa-eye-slash'></i>"+
                    "</button>";
                }else if(v.estado=="0"){
                    html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarItem("+v.id+", "+posi+", "+tipoop+")'>"+
                        "<i class='fas fa-eye'></i>"+
                    "</button>";
                }
                html+="</td>" +
            "</tr>";
            con++;
            posi++;
        });

        $(direccion).each(function(i,v){
            tipoop= '"'+"direccion"+'"';
            if(v.estado=="1"){
                estadoItem="Visible";
            }else{
                estadoItem="No Visible";
            }

            html +="<tr id='Tr"+con +"'>"+
                "<td style='text-align: center;'>"+con+"</td>"+
                "<td>"+v.nombre+"</td>"+
                "<td>"+v.dependencia+"</td>"+
                "<td>"+estadoItem+"</td>" +
                "<td style='display: flex;'>"+
                "<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarDireccion("+v.id+")'>"+
                    "<i class='fas fa-pencil-alt'></i>"+
                "</button>";
                if(v.estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerItem("+v.id+", "+posi+", "+tipoop+")'>"+
                        "<i class='fas fa-eye-slash'></i>"+
                    "</button>";
                }else if(v.estado=="0"){
                    html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarItem("+v.id+", "+posi+", "+tipoop+")'>"+
                        "<i class='fas fa-eye'></i>"+
                    "</button>";
                }
                html+="</td>" +
            "</tr>";
            con++;
            posi++;
        });

        $(coordinacion).each(function(i,v){
            tipoop= '"'+"coordinacion"+'"';

            if(v.estado=="1"){
                estadoItem="Visible";
            }else{
                estadoItem="No Visible";
            }

            html +="<tr id='Tr"+con +"'>"+
                "<td style='text-align: center;'>"+con+"</td>"+
                "<td>"+v.nombre+"</td>"+
                "<td>"+v.dependencia+"</td>"+
                "<td>"+estadoItem+"</td>" +
                "<td style='display: flex;'>"+
                "<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarCoordinacion("+v.id+")'>"+
                    "<i class='fas fa-pencil-alt'></i>"+
                "</button>";
                if(v.estado=="1"){
                    html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerItem("+v.id+", "+posi+", "+tipoop+")'>"+
                        "<i class='fas fa-eye-slash'></i>"+
                    "</button>";
                }else if(v.estado=="0"){
                    html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarItem("+v.id+", "+posi+", "+tipoop+")'>"+
                        "<i class='fas fa-eye'></i>"+
                    "</button>";
                }
                html+="</td>" +
            "</tr>";
            con++;
            posi++;
        });
    }

    //Object.values(resultado).forEach(val => {});

    html += "</tbody></table>";
    $("#divtablaDep").html(html);
    setTimeout(function () {
        $('#modalCargando').modal('hide');
        $("#tablaDepartamentos")
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
    }, 600);
}

//FUNCION QUE ABRE MODAL PARA REGISTRAR UN NUEVO DEPARTAMENTO
function openModalAggDep(){
    document.getElementById('divupimggerdir').style.display='none';
    setTimeout(() => {
        $('#modalAggDep').modal('show');
    }, 450);
    
}

//FUNCION QUE TRANSFORMA EL PRIMER CARACTER DEL SELECT2 EN MAYÚSCULA
function getval(sel, op){
    if(sel.value!='0'){
        //let firstv = sel.value[0].toUpperCase();
        //cadena= firstv+ (sel.value.substring(1, sel.value.length));
        //$('#span-socialmedia').html(cadena);
        seltypedep= sel.value;
        /*if(seltypedep!='gerencia'){
            elementRowDep.style.display='block';
            $('#selDependencia').html("<option>Cargando...</option>");
            cargar_dependencia(seltypedep);
        }else{
            elementRowDep.style.display='none';
            $('#selDependencia').html("");
        }*/

        if(seltypedep=='gerencia'){
            elementRowDep.style.display='none';
            $('#selDependencia').html("");
            document.getElementById('divupimggerdir').style.display='block';
        }else if(seltypedep=='direccion'){
            elementRowDep.style.display='block';
            $('#selDependencia').html("<option>Cargando...</option>");
            document.getElementById('divupimggerdir').style.display='block';
            cargar_dependencia(seltypedep);
        }else if(seltypedep=='coordinacion'){
            elementRowDep.style.display='block';
            $('#selDependencia').html("<option>Cargando...</option>");
            document.getElementById('divupimggerdir').style.display='none';
            let fileIn= document.getElementById('file');
            limpiarFile(fileIn);
            $('#images').html('');
            cargar_dependencia(seltypedep);
        }
    }else{
        seltypedep='';
        elementRowDep.style.display='none';
        $('#selDependencia').html("");
    }
}

//FUNCION QUE TRANSFORMA EL PRIMER CARACTER DEL SELECT2 EN MAYÚSCULA
function getvalEdit(sel, op){
    if(sel.value!='0'){
        //seltypedep= sel.value;
        /*if(seltypedep!='gerencia'){
            elementRowDep.style.display='block';
            $('#selDependencia').html("<option>Cargando...</option>");
            cargar_dependencia(seltypedep);
        }else{
            elementRowDep.style.display='none';
            $('#selDependencia').html("");
        }*/
    }else{
        //seltypedep='';
        //elementRowDep.style.display='none';
        //$('#selDependencia').html("");
    }
}

//FUNCION QUE TRANSFORMA EL PRIMER CARACTER DEL SELECT2 EN MAYÚSCULA
function getvalDep(sel){
    if(sel.value!=''){
        seldependencia= sel.value;
    }else{
        seldependencia='';
    }
}

function getvalDepEdit(sel){
    if(sel.value!='0'){
        sel_dep_edit= sel.value;
    }else{
        sel_dep_edit='';
    }
}

/* FUNCION QUE GUARDA EL REGISTRO DE DEPARTAMENTO */
function guardarRegistroDep(){
    var token= $('#token').val();
    var nombre= $('#inputNombreDep').val();
    var tipo= seltypedep;
    let fileInput = document.getElementById("file");
    var lengimg = fileInput.files.length;

    if(tipo==''){
        Swal.fire({
            title: "Por favor, seleccione el tipo de registro",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else if(nombre==''){
        $('#inputNombreDep').focus();
        Swal.fire({
            title: "Por favor, ingrese un nombre para el registro",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else{
        var element = document.querySelector('.btn-add-dep');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";
        if(tipo=='gerencia'){
            if (lengimg == 0) {
                swal("No ha seleccionado imagen para la Gerencia", "", "warning");
            } else if(lengimg>=2){
                swal("Sólo debe seleccionar una imagen para la Gerencia", "", "warning");
            } else{
                var formData = new FormData(formDept);
                formData.append("nombre", nombre);
                formData.append("tipo", tipo);
                formData.append("imagen","si");
                sendUpdateDepartamento(formData, token, '/registrar-dept', element, '#modalAggDep');
            }
        }else if(tipo=='direccion'){
            var idDep= seldependencia;
            if(idDep==''){
                Swal.fire({
                    title: "Por favor, seleccione la Dependencia",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } if (lengimg == 0) {
                swal("No ha seleccionado imagen para la Coordinación", "", "warning");
            } else if(lengimg>=2){
                swal("Sólo debe seleccionar una imagen para la Coordinación", "", "warning");
            } else{
                let id_dep= idDep.substring(4, seldependencia.length);
                /*let tipo_dep=  idDep.substring(0, 3);
                if(tipo_dep=='grc'){
                    tipo_dep='gerencia';
                }else if(tipo_dep=='dir'){
                    tipo_dep='direccion';
                }*/
                //console.log('NOMBRE: '+nombre, 'TIPO: '+tipo, 'IDD: '+idDep, 'ID_DEP: '+id_dep, 'TIPO_DEP: '+tipo_dep);
                var formData = new FormData(formDept);
                formData.append("nombre", nombre);
                formData.append("tipo", tipo);
                formData.append("iddependencia", id_dep);
                formData.append("imagen","si");
                sendUpdateDepartamento(formData, token, '/registrar-dept', element, '#modalAggDep');
            }
        }else if(tipo=='coordinacion'){
            var idDep= seldependencia;
            if(idDep==''){
                Swal.fire({
                    title: "Por favor, seleccione la Dependencia",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            }else{
                let id_dep= idDep.substring(4, seldependencia.length);
                let tipo_dep=  idDep.substring(0, 3);
                if(tipo_dep=='grc'){
                    tipo_dep='gerencia';
                }else if(tipo_dep=='dir'){
                    tipo_dep='direccion';
                }
                //console.log(id_dep, tipo_dep);
                var formData = new FormData(formDept);
                formData.append("nombre", nombre);
                formData.append("tipo", tipo);
                formData.append("id_dep", id_dep);
                formData.append("tipo_dep", tipo_dep);
                formData.append("imagen","no");
                sendUpdateDepartamento(formData, token, '/registrar-dept', element, '#modalAggDep');
            }
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO DE DEPARTAMENTO */
function sendUpdateDepartamento(data, token, url, el, modal){
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
                Swal.fire({
                    title:'Excelente!',
                    text:'Registro Guardado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    seldependencia='0';
                    el.removeAttribute("disabled");
                    el.style.removeProperty("pointer-events");
                    $(modal).modal('hide');
                    window.location='/registrar-departamento';
                    /*if(registro=='update'){
                        if(tipo=="objetivos"){
                            $('#idobjetivoindi').val('');
                            $('#posobjetivo').val('');
                        }else{
                            $('#id'+tipo).val('');
                        }
                        reDrawInfo(tipo, posi);
                        $(modal).modal('hide');
                    }else if(registro=='insert'){
                        
                    }*/
                },1500);
            } else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "No se pudo Guardar",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if (myArr.resultado == 'existe'){
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "No se pudo Guardar",
                    html: '<p>Ya existe un registro ingresado <strong>anteriormente</strong></p>',
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if (myArr.resultado == "noimagen") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "No se pudo Guardar",
                    html: '<p>Formato de Imagen no válido</p>',
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if (myArr.resultado == "nocopy") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "No se pudo Guardar",
                    html: '<p>Error al copiar la imagen</p>',
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
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

/* FUNCION CARGAR DEPENDENCIA EN EL SELECT */
function cargar_dependencia(tipo){
    var html="<option value=''> -Seleccione una Opción- </option>";
    var url= "/get-departamento/"+tipo;
    //var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                html+="<option value=''> -Sin Registro- </option>";
            }


            //arrayDependencias= [...myArr.array];

            $(myArr.array).each(function(i,v){
                //console.log(v.nombre);
                if(v.tipo=='gerencia'){
                    html+="<option value='grc_"+v.id+"'>"+v.nombre+"</option>";
                }else if(v.tipo=='direccion'){
                    html+="<option value='dir_"+v.id+"'>"+v.nombre+"</option>";
                }
            });

            setTimeout(function(){
                $('#selDependencia').html(html);
            },800);
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

/* FUNCION CARGAR DEPENDENCIA EN EL SELECT EDIT INFO*/
function cargar_dependencia_edit(tipo){
    var html="<optgroup label='Seleccione una Opción'>";
    var url= "/get-departamento/"+tipo;
    //var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                html+="<option value=''> -Sin Registro- </option>";
            }else{
                html+="<option value='0'>-Seleccione una Opción-</option>";
            }


            //arrayDependencias= [...myArr.array];

            $(myArr.array).each(function(i,v){
                //console.log(v.id, v.nombre, v.tipo);
                if(v.tipo=='gerencia'){
                    html+="<option value='grc_"+v.id+"'>"+v.nombre+"</option>";
                }else if(v.tipo=='direccion'){
                    html+="<option value='dir_"+v.id+"'>"+v.nombre+"</option>";
                }
            });

            html+="</optgroup>";
            $('#selDependenciaEdit').append(html);
            setTimeout(function(){
                $('#selDependenciaEdit').val('0').trigger('change');
            },150);
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

/* FUNCION INACTIVAR EL ITEM DE REGISTRO*/
function removerItem(id, i, tipo){
    var token= $('#token').val();
    var estado = "0";
    var html="";
    var tip_sel="";
    var estadoItem="";

    if(estado=="1"){
        estadoItem="Visible";
    }else{
        estadoItem="No Visible";
    }

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
                url: "/in-activar-dept",
                type: "POST",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    id: id,
                    estado: estado,
                    tipo: tipo
                },
                success: function (res) {
                    //console.log(res);
                    if (res.resultado == true) {
                        swal({
                            title: "Excelente!",
                            text: "Registro Inactivado",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1600,
                        });
                        
                        setTimeout(function () {
                            if(tipo=="gerencia"){
                                tip_sel='"'+"gerencia"+'"';
                                html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarGerencia("+id+")'>"+
                                    "<i class='fas fa-pencil-alt'></i>"+
                                "</button>";
                            }else if(tipo=="direccion"){
                                tip_sel='"'+"direccion"+'"';
                                html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarDireccion("+id+")'>"+
                                    "<i class='fas fa-pencil-alt'></i>"+
                                "</button>";
                            }else if(tipo=="coordinacion"){
                                tip_sel='"'+"coordinacion"+'"';
                                html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarCoordinacion("+id+")'>"+
                                    "<i class='fas fa-pencil-alt'></i>"+
                                "</button>";
                            }

                            if(estado=="1"){
                                html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerItem("+id+", "+i+", "+tip_sel+")'>"+
                                    "<i class='fas fa-eye-slash'></i>"+
                                "</button>";
                            }else if(estado=="0"){
                                html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarItem("+id+", "+i+", "+tip_sel+")'>"+
                                    "<i class='fas fa-eye'></i>"+
                                "</button>";
                            }

                            var elementState= document.getElementById('Tr'+(i+1)).cells[3];
                            $(elementState).html(estadoItem);

                            var elementOption= document.getElementById('Tr'+(i+1)).cells[4];
                            $(elementOption).html(html);
                            tip_sel="";
                            estadoItem="";
                        }, 1500);
                    } else if (res.resultado == false) {
                        swal("No se pudo Inactivar", "", "error");
                    } else if(res.resultado == 'enuso'){
                        swal("No se pudo Inactivar porque el registro se encuentra en uso", "", "error");
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

/* FUNCION ACTIVAR EL ITEM DE REGISTRO*/
function activarItem(id, i, tipo){
    var token= $('#token').val();
    var estado = "1";
    var html="";
    let tip_sel= '';
    var estadoItem="";

    if(estado=="1"){
        estadoItem="Visible";
    }else{
        estadoItem="No Visible";
    }

    $.ajax({
        url: "/in-activar-dept",
        type: "POST",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': token},
        data: {
            id: id,
            estado: estado,
            tipo: tipo
        },
        success: function (res) {
            //console.log(res);
            if (res.resultado == true) {
                swal({
                    title: "Excelente!",
                    text: "Registro Activado",
                    type: "success",
                    showConfirmButton: false,
                    timer: 1600,
                });
                
                setTimeout(function () {
                    if(tipo=="gerencia"){
                        tip_sel='"'+"gerencia"+'"';
                        html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarGerencia("+id+")'>"+
                            "<i class='fas fa-pencil-alt'></i>"+
                        "</button>";
                    }else if(tipo=="direccion"){
                        tip_sel='"'+"direccion"+'"';
                        html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarDireccion("+id+")'>"+
                            "<i class='fas fa-pencil-alt'></i>"+
                        "</button>";
                    }else if(tipo=="coordinacion"){
                        tip_sel='"'+"coordinacion"+'"';
                        html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarCoordinacion("+id+")'>"+
                            "<i class='fas fa-pencil-alt'></i>"+
                        "</button>";
                    }

                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerItem("+id+", "+i+", "+tip_sel+")'>"+
                            "<i class='fas fa-eye-slash'></i>"+
                        "</button>";
                    }else if(estado=="0"){
                        html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarItem("+id+", "+i+", "+tip_sel+")'>"+
                            "<i class='fas fa-eye'></i>"+
                        "</button>";
                    }

                    var elementState= document.getElementById('Tr'+(i+1)).cells[3];
                    $(elementState).html(estadoItem);

                    var elementOption= document.getElementById('Tr'+(i+1)).cells[4];
                    $(elementOption).html(html);

                    tip_sel="";
                    estadoItem="";
                }, 1500);
            } else if (res.resultado == false) {
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
}

/* FUNCION EDITAR REGISTRO GERENCIA*/
function editarGerencia(id){
    $('#iddepartamentoedit').val(id);
    $('#tipo_seleccion_edit').val("gerencia");
    limpiar_array();
    elementSpanModal.style.display='block';
    toastr.info("Cargando información...", "¡Aviso!");
    document.getElementById('divImgToGerDir').style.display='block';
    get_info_depart("gerencia", id);
}

/* FUNCION EDITAR REGISTRO DIRECCION*/
function editarDireccion(id){
    $('#iddepartamentoedit').val(id);
    $('#tipo_seleccion_edit').val("direccion");
    limpiar_array();
    elementSpanModal.style.display='none';
    tipoChangeDep='direccion';
    toastr.info("Cargando información...", "¡Aviso!");
    document.getElementById('divImgToGerDir').style.display='block';
    setTimeout(() => {
        get_info_depart("direccion", id);
    }, 300);
    
}

/* FUNCION EDITAR REGISTRO COORDINACION*/
function editarCoordinacion(id){
    $('#iddepartamentoedit').val(id);
    $('#tipo_seleccion_edit').val("coordinacion");
    limpiar_array();
    elementSpanModal.style.display='none';
    tipoChangeDep='coordinacion';
    toastr.info("Cargando información...", "¡Aviso!");
    document.getElementById('divImgToGerDir').style.display='none';
    setTimeout(() => {
        get_info_depart("coordinacion", id);
    }, 300);
}

/* FUNCION OBTENER INFORMACIÓN DEL DEPARTAMENTO SELECCIONADO*/
function get_info_depart(tipo, id){
    var url= "/get-departamento-indi/"+tipo+'/'+id;
    //var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            document.getElementById('divcontainerUp').style.display='none';
            var myArr = JSON.parse(xr.responseText);

            $(myArr).each(function(i,v){
                if(v.tipo=='gerencia'){
                    $('#selDepartamentoEdit').val(v.tipo).trigger('change');
                    $('#inputNombreEditDep').val(v.nombre);
                    $('#nameSelSpanModal').html(v.nombre);
                    if(v.imagen.length>0){
                        imprimirImgGerDir(v.id, v.imagen);
                    }else{
                        isImage= false;
                        document.getElementById('divcontainerUp').style.display='block';
                        document.getElementById('rowPicsInd').style.display='none';
                    }
                }else if(v.tipo=="direccion"){
                    //console.log(v.tipo, v.id_dependencia);
                    $('#inputNombreEditDep').val(v.nombre);
                    $('#selDepartamentoEdit').val(v.tipo).trigger('change');
                    $('#inputNameDependencia').val(v.nombre_dependencia);
                    $('#inputIdDependencia').val(v.id_dependencia);
                    if(v.imagen.length>0){
                        imprimirImgGerDir(v.id, v.imagen);
                    }else{
                        isImage= false;
                        document.getElementById('divcontainerUp').style.display='block';
                        document.getElementById('rowPicsInd').style.display='none';
                    }
                    //$('#selDependenciaEdit').val('grc_'+v.id_dependencia);
                    //$('#selDependenciaEdit').trigger('change');
                }else if(v.tipo=="coordinacion"){
                    $('#inputNombreEditDep').val(v.nombre);
                    $('#selDepartamentoEdit').val(v.tipo).trigger('change');
                    $('#inputNameDependencia').val(v.nombre_dependencia);
                    $('#inputIdDependencia').val(v.id_dependencia);
                    sel_dep_edit= v.tipo_dependencia;
                }
            });

            if(tipo=="gerencia"){
                //$('.select2-selection').css('pointer-events', 'none');
                $('.form-sel-dep').css('pointer-events', 'none');
                document.getElementById('rowDependenciaEdit').style.display='none';
                divNameDep.style.display='none';
            }else if(tipo=="direccion"){
                $('.form-sel-dep').css('pointer-events', 'none');
                divNameDep.style.display='block';
            }else if(tipo=="coordinacion"){
                $('.form-sel-dep').css('pointer-events', 'none');
                divNameDep.style.display='block';
            }

            setTimeout(function(){
                //$('#selDependencia').html(html);
                $('#modalEditDep').modal('show');
            },800);
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

/* FUNCION QUE MUESTRA EL SELECT DE LA NUEVA DEPENDENCIA */
function changeDependencia(){
    divNameDep.style.display='none';
    divProgess.style.display= 'block';
    isActiveDep= true;
    move();
    setTimeout(() => {
        cargar_dependencia_edit(tipoChangeDep);
        divProgess.style.display= 'none';
        setTimeout(()=>{
            document.getElementById('rowDependenciaEdit').style.display='block';
        }, 200);
    }, 700);
}

/* FUNCION QUE OCULTA EL SELECT DE LA NUEVA DEPENDENCIA */
function cancelDependencia(){
    sel_dep_edit='';
    isActiveDep= false;
    divNameDep.style.display='block';
    document.getElementById('rowDependenciaEdit').style.display='none';
    tipoChangeDep='';
    $('#selDependenciaEdit').html('');
}

function limpiar_array(){
    while(arrayDepEdit.length > 0){
        arrayDepEdit.pop();
    }
}

/* FUNCION QUE GUARDA EL REGISTRO DE DEPARTAMENTO */
function guardarRegistroEditDep(){
    var id= $('#iddepartamentoedit').val();
    var nombre= $('#inputNombreEditDep').val();
    var tipo= $('#tipo_seleccion_edit').val();
    nombre= nombre.trim();
    let fileInput = document.getElementById("fileedit");
    var lengimg = fileInput.files.length;

    var token= $('#token').val();

    //console.log(id, nombre, tipo);

    if(nombre==''){
        $('#inputNombreEditDep').focus();
        Swal.fire({
            title: "Por favor, ingrese un nombre para el registro",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else{
        var element = document.querySelector('.btn-edit-dep');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        if(tipo=='gerencia'){
            var formData = new FormData(formeditDept);
            formData.append('id', id);
            formData.append("nombre", nombre);
            formData.append("tipo", tipo);
            formData.append("isImage", isImage);
            sendUpdateDepartamento(formData, token, '/actualizar-dept', element, '#modalEditDep');
            
        }else if(tipo=='direccion'){
            var idDependenciaGer= '';
            //console.log(id, nombre, tipo, $('#inputIdDependencia').val());
            if(isActiveDep==true){
                if(sel_dep_edit=='0'){
                    Swal.fire({
                        title: "Por favor, seleccione el tipo de registro",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Ok"
                    });
                }else{
                    idDependenciaGer= sel_dep_edit.substring(4, sel_dep_edit.length);
                }
            }else if(isActiveDep==false){
                idDependenciaGer= $('#inputIdDependencia').val();
            }
            //console.log(id, nombre, tipo, idDependenciaGer);
            var formData = new FormData(formeditDept);
            formData.append('id', id);
            formData.append("nombre", nombre);
            formData.append("tipo", tipo);
            formData.append("iddependencia", idDependenciaGer);
            formData.append("isImage", isImage);
            sendUpdateDepartamento(formData, token, '/actualizar-dept', element, '#modalEditDep');
        }else if(tipo=='coordinacion'){
            var idDependenciaGer= '';
            let tipo_dep='';
            //console.log(id, nombre, tipo, $('#inputIdDependencia').val());
            if(isActiveDep==true){
                if(sel_dep_edit=='0'){
                    Swal.fire({
                        title: "Por favor, seleccione el tipo de registro",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Ok"
                    });
                }else{
                    idDependenciaGer= sel_dep_edit.substring(4, sel_dep_edit.length);
                    tipo_dep=  sel_dep_edit.substring(0, 3);
                    if(tipo_dep=='grc'){
                        tipo_dep='gerencia';
                    }else if(tipo_dep=='dir'){
                        tipo_dep='direccion';
                    }
                }
            }else if(isActiveDep==false){
                idDependenciaGer= $('#inputIdDependencia').val();
                tipo_dep=  sel_dep_edit.substring(0, 3);
                if(tipo_dep=='grc'){
                    tipo_dep='gerencia';
                }else if(tipo_dep=='dir'){
                    tipo_dep='direccion';
                }
            }
            //console.log(id, nombre, tipo, idDependenciaGer, tipo_dep);
            var formData = new FormData(formeditDept);
            formData.append('id', id);
            formData.append("nombre", nombre);
            formData.append("tipo", tipo);
            formData.append("iddependencia", idDependenciaGer);
            formData.append("tipo_dep", tipo_dep);
            sendUpdateDepartamento(formData, token, '/actualizar-dept', element, '#modalEditDep');
        }
    }
}

/*---------- REGISTRAR INFORMACION DE DEPARTAMENTOS DE LA INSTITUCION --------------*/
var SelTypeValueDep='0';

/* FUNCION QUE ABRE LA INTERFAZ DE REGISTRO DE INFORMACIÓN */
function openUrlInfoDep(){
    window.location='/registrar-info-departamento';
}

/* FUNCION QUE ABRE LA INTERFAZ DE ACTUALIZACION DE INFORMACIÓN */
function editarInforDep(id){
    window.location='/actualizar-info-departamento/'+id;
}

//FUNCION QUE TRANSFORMA EL PRIMER CARACTER DEL SELECT2 EN MAYÚSCULA
function getvalTypeD(sel, op){
    var html="<option value='0'>Seleccione una Opción</option>";
    if(sel.value!='0'){
        //let firstv = sel.value[0].toUpperCase();
        //cadena= firstv+ (sel.value.substring(1, sel.value.length));
        //$('#span-socialmedia').html(cadena);
        SelTypeValueDep= sel.value;
        $('#selGetDepartReg').html("<option value='0'>Cargando...</option>");
        cargar_dependencia_type(SelTypeValueDep, html);
    }else{
        SelTypeValueDep='0';
        $('#selGetDepartReg').html(html);
    }
}

/* FUNCION CARGAR DEPENDENCIA EN EL SELECT */
function cargar_dependencia_type(tipo, html){
    var url= "/get-info-departamento/"+tipo;
    //var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            //arrayDependencias= [...myArr.array];

            $(myArr.array).each(function(i,v){
                //console.log(v.nombre);
                if(v.tipo=='gerencia'){
                    html+="<option value='grc_"+v.id+"'>"+v.nombre+"</option>";
                }else if(v.tipo=='direccion'){
                    html+="<option value='dir_"+v.id+"'>"+v.nombre+"</option>";
                }else if(v.tipo=='coordinacion'){
                    html+="<option value='cor_"+v.id+"'>"+v.nombre+"</option>";
                }
            });
            
            setTimeout(function(){
                $('#selGetDepartReg').html(html);
            },400);
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

/* FUNCION GUARDAR REGISTRO INFO DEPARTAMENTO*/
function saveinfordep(){
    var token= $('#token').val();
    var tipo= SelTypeValueDep;
    var idType= $('#selGetDepartReg').val();
    let id_dep= '';
    var nombre= $('#inputUsuarioEncargado').val();
    var email= $('#emailUsuarioEncargado').val();
    var telefono= $('#telefonoUsuarioEncargado').val();
    var extension= $('#extUsuarioEncargado').val();

    if(idType.length> 1){
        id_dep= idType.substring(4, idType.length);
    }else{
        id_dep= idType;
    }

    if(tipo=='0'){
        Swal.fire({
            title: "Por favor, seleccione el tipo de registro",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else if(id_dep=='0'){
        Swal.fire({
            title: "Por favor, seleccione el Departamento",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else if(nombre==''){
        $('#inputUsuarioEncargado').focus();
        Swal.fire({
            title: "Por favor, ingrese el nombre del encargado del Área",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else if(email==''){
        $('#emailUsuarioEncargado').focus();
        Swal.fire({
            title: "Por favor, ingrese el email del encargado del Área",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else if(telefono==''){
        $('#telefonoUsuarioEncargado').focus();
        Swal.fire({
            title: "Por favor, ingrese el teléfono del Área",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else{
        var isemail= validarEmail(email);
        if(isemail==0){
            $('#emailUsuarioEncargado').focus();
            swal('Dirección de correo Inválida','','error');
        }else if(isemail==1){
            if(telefono.length<10){
                $('#telefonoUsuarioEncargado').focus();
                swal('Número de Teléfono Inválido','','error');
            }else{
                var formData = new FormData();
                formData.append("tipo", tipo);
                formData.append("iddepartamento", id_dep);
                formData.append("nombre", nombre);
                formData.append("email", email);
                formData.append("telefono", telefono);
                formData.append("extension", extension);

                $.ajax({
                    url:'/registro-info-depart',
                    type: 'POST',
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': token},
                    data: formData,
                    success: function(res){
                        if(res.resultado==true){
                            swal({
                                title:'Excelente!',
                                text:'Registro Guardado',
                                type:'success',
                                showConfirmButton: false,
                                timer: 1600
                            });
                            setTimeout(function(){
                                window.location= '/departamentos';
                            },1500);
                        }else if(res.resultado==false){
                            swal('No se pudo Guardar','','error');
                        }else if(res.resultado=='existe'){
                            swal('No se pudo Guardar','Ya existe un registro con el Departamento seleccionado','error');
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
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        }
    }
}

/* FUNCION ACTUALIZAR REGISTRO INFO DEPARTAMENTO*/
function updateinfordep(){
    var token= $('#token').val();
    var id= $('#inputIdRegistro').val();
    var categoria= $('#selCatDepart').val();
    var departamento= $('#selEditDepart').val();
    var nombre= $('#inputEditUsuarioEncargado').val();
    var email= $('#emailEditUsuarioEncargado').val();
    var telefono= $('#telefonoEditUsuarioEncargado').val();
    var extension= $('#extEditUsuarioEncargado').val();

    if(nombre==''){
        $('#inputEditUsuarioEncargado').focus();
        Swal.fire({
            title: "Por favor, ingrese el nombre del encargado del Área",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else if(email==''){
        $('#emailEditUsuarioEncargado').focus();
        Swal.fire({
            title: "Por favor, ingrese el email del encargado del Área",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else if(telefono==''){
        $('#telefonoEditUsuarioEncargado').focus();
        Swal.fire({
            title: "Por favor, ingrese el teléfono del Área",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
        });
    }else{
        var isemail= validarEmail(email);
        if(isemail==0){
            $('#emailUsuarioEncargado').focus();
            swal('Dirección de correo Inválida','','error');
        }else if(isemail==1){
            if(telefono.length<10){
                $('#telefonoUsuarioEncargado').focus();
                swal('Número de Teléfono Inválido','','error');
            }else{
                var formData = new FormData();
                formData.append("id", id);
                formData.append("tipo", categoria);
                formData.append("iddepartamento", departamento);
                formData.append("nombre", nombre);
                formData.append("email", email);
                formData.append("telefono", telefono);
                formData.append("extension", extension);
                //console.log(id,categoria, departamento, nombre, email, telefono, extension);
                $.ajax({
                    url:'/update-info-depart',
                    type: 'POST',
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': token},
                    data: formData,
                    success: function(res){
                        if(res.resultado==true){
                            swal({
                                title:'Excelente!',
                                text:'Registro Actualizado',
                                type:'success',
                                showConfirmButton: false,
                                timer: 1600
                            });
                            setTimeout(function(){
                                window.location= '/departamentos';
                            },1500);
                        }else if(res.resultado==false){
                            swal('No se pudo Guardar','','error');
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
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        }
    }
}

/* FUNCION INACTIVAR EL ITEM DE REGISTRO*/
function removerInforDep(id, pos){
    var token= $('#token').val();
    var estado = "0";
    var html="";
    var estadoItem="";

    if(estado=="1"){
        estadoItem="Visible";
    }else{
        estadoItem="No Visible";
    }

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
                url: "/in-activar-info-dept",
                type: "POST",
                dataType: "json",
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    id: id,
                    estado: estado
                },
                success: function (res) {
                    //console.log(res);
                    if (res.resultado == true) {
                        swal({
                            title: "Excelente!",
                            text: "Registro Inactivado",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1600,
                        });
                        
                        setTimeout(function () {
                            html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarInforDep("+id+")'>"+
                                    "<i class='fas fa-pencil-alt'></i>"+
                                "</button>";

                            if(estado=="1"){
                                html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerInforDep("+id+", "+pos+")'>"+
                                    "<i class='fas fa-eye-slash'></i>"+
                                "</button>";
                            }else if(estado=="0"){
                                html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarInforDep("+id+", "+pos+")'>"+
                                    "<i class='fas fa-eye'></i>"+
                                "</button>";
                            }

                            var elementState= document.getElementById('Tr'+pos).cells[5];
                            $(elementState).html(estadoItem);

                            var elementOption= document.getElementById('Tr'+pos).cells[6];
                            $(elementOption).html(html);
                            estadoItem="";
                        }, 1500);
                    } else if (res.resultado == false) {
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
        }else if(result.dismiss === Swal.DismissReason.cancel){

        }
    });
}

/* FUNCION ACTIVAR EL ITEM DE REGISTRO*/
function activarInforDep(id, pos){
    var token= $('#token').val();
    var estado = "1";
    var html="";
    var estadoItem="";

    if(estado=="1"){
        estadoItem="Visible";
    }else{
        estadoItem="No Visible";
    }

    $.ajax({
        url: "/in-activar-info-dept",
        type: "POST",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': token},
        data: {
            id: id,
            estado: estado
        },
        success: function (res) {
            //console.log(res);
            if (res.resultado == true) {
                swal({
                    title: "Excelente!",
                    text: "Registro Activado",
                    type: "success",
                    showConfirmButton: false,
                    timer: 1600,
                });
                
                setTimeout(function () {
                    html+="<button type='button' class='btn btn-primary btn-sm mr-3' title='Editar' onclick='editarInforDep("+id+")'>"+
                            "<i class='fas fa-pencil-alt'></i>"+
                        "</button>";

                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3' title='Inactivar' onclick='removerInforDep("+id+", "+pos+")'>"+
                            "<i class='fas fa-eye-slash'></i>"+
                        "</button>";
                    }else if(estado=="0"){
                        html+="<button type='button' class='btn btn-info btn-sm mr-3' title='Activar' onclick='activarInforDep("+id+", "+pos+")'>"+
                            "<i class='fas fa-eye'></i>"+
                        "</button>";
                    }

                    var elementState= document.getElementById('Tr'+pos).cells[5];
                    $(elementState).html(estadoItem);

                    var elementOption= document.getElementById('Tr'+pos).cells[6];
                    $(elementOption).html(html);
                    estadoItem="";
                }, 1500);
            } else if (res.resultado == false) {
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
}

/* FUNCION QUE DIBUJA LA IMAGEN DEL EVENTO */
function imprimirImgGerDir(id, imagen){
    //`${fileInput.files.length} Archivos Seleccionados`
    isImage= true;
    var html="";
    html+="<div class='card shadow mt-4 mb-4' id='divpics"+id+"' style='width: 336px;'>"+
        "<div class='card-body text-center'>"+
          "<div class='mt-2' style='height: 160px;width: 295px;'>"+
            "<a href='javascript:void(0)'>"+
              `<img src="/files-img/${imagen}" alt="Evento" class="avatar-img divgetimg">`+
            "</a>"+
          "</div>"+
        "</div>"+
        "<div class='card-footer card-footer-event-edit'>"+
          "<div class='row align-items-center justify-content-between'>"+
            "<div class='col-auto'>"+
              "<small class='btnSpanDel' onclick='eliminarPic("+id+")'>"+
                "<span class='far fa-trash-alt'></span> "+
                "&nbsp;Eliminar"+
              "</small>"+
            "</div>"+
          "</div>"+
        "</div>"+
    "</div>";
    $('#rowPicsInd').html(html);
}

/* FUNCION QUE LIMPIA EL INPUT FILE */
function limpiarFile(fileInput) {
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

function previewpicsEdit(){
    //imageContainer.innerHTML="";
    let fileInput = document.getElementById("fileedit");
    let numOfFIles = document.getElementById("num-of-files-edit");
    let imageContainer = document.getElementById("images-edit");

    imageContainer.innerHTML="";
    numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;

    for(i of fileInput.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        figCap.innerHTML = i.name;
        figure.appendChild(figCap);
        reader.onload= () =>{
            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            /*let span = document.createElement("span");
            span.setAttribute("class", "span-img");
            span.innerHTML="&times;";*/
            figure.insertBefore(img, figCap);
            //figure.insertBefore(span,img);
        }

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

/* FUNCION QUE ELIMINA LA IMAGEN ACTUAL DEL EVENTO PERO SIN ACCEDER A BD */
function eliminarPic(id){
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
            isImage= false;
            document.getElementById('divcontainerUp').style.display='block';
            document.getElementById('rowPicsInd').style.display='none';
            //$('#rowPicsInd').html("");
        } else if (result.dismiss === Swal.DismissReason.cancel) {
        }
    });
}