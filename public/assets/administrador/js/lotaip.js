function openmodalAdd(){
    $('#modalAggItLot').modal('show');
}

function guardarRegistroItLot(){
    var token= $('#token').val();
    var articulo= $('#selArtLotaip :selected').val();
    var literal= $('#inputLiteral').val();
    var descripcion= $('#inputDescp').val();

    if(articulo=='0'){
        $('#selArtLotaip').focus();
        swal('Debe seleccionar un artículo','','warning');
    } else if(literal==''){
        $('#inputLiteral').focus();
        swal('Ingrese un Literal','','warning');
    }else if(descripcion==''){
        $('#inputDescp').focus();
        swal('Ingrese una descripcion','','warning');
    }else{
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("articulo", articulo);
        formData.append("literal", literal);
        formData.append("descripcion", descripcion);

        var element= document.getElementById('btnsaveitlo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/registro-item-lotaip', true);
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
                        var id= myArr.ID;
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
                        $('#modalAggItLot').modal('hide');
                        $('#inputLiteral').val("");
                        $('#inputDescp').val("");
                        window.location='/setting-lotaip';
                        //agregarFila(formData, id);
                    },1500);
                }else if(myArr.resultado==false){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo guardar el registro','','error');
                }
                else if(myArr.resultado=='existe'){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo guardar el registro','Ya se encuentra registrado el Literal digitado','error');
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

function agregarFila(formData, id){
    var html="";
    let filas = $('#tablaListadoItemLotaip').find('tbody tr').length;

    html+="<tr id='"+filas+"'>"+
        "<td>"+(filas+1)+"</td>"+
        "<td>"+formData.get('literal')+".- "+formData.get('descripcion')+"</td>"+
        "<td>Visible</td>"+
        "<td>"+
            "<div class='dropdown show'>"+
                "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
                    "<i class='fas fa-cog'></i>"+
                "</a>"+
                "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>"+
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemLiteral("+id+")'>Editar</a>"+
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarItemLiteral("+id+", "+filas+")'>Inactivar</a>"+
                "</div>"+
            "</div>"+
        "</td>"+
    "</tr>";

    document.getElementById('tablaListadoItemLotaip').insertRow(-1).innerHTML = html;
}

const eliminarFila = () => {
    const table = document.getElementById('tablaListadoItemLotaip')
    const rowCount = table.rows.length
    
    if (rowCount <= 1)
      alert('No se puede eliminar el encabezado')
    else
      table.deleteRow(rowCount -1)
}

function editarItemLiteral(id){
    $('#iditemlotaip').val(id);

    var url= "/get-item-lotaip/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                $('#inputLiteralE').val(v.literal);
                $('#inputDescpE').val(v.descripcion);
                var idart= v.id_articulo;
                $("#selArtLotaipEdit").val(idart).trigger('change');
            });
            setTimeout(() => {
                $('#modalEditItLot').modal('show');
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

function actualizarRegistroItLo(){
    var token= $('#token').val();
    var id= $('#iditemlotaip').val();
    var literal= $('#inputLiteralE').val();
    var descripcion= $('#inputDescpE').val();

    if(literal==''){
        $('#inputLiteralE').focus();
        swal('Ingrese un Literal','','warning');
    }else if(descripcion==''){
        $('#inputDescpE').focus();
        swal('Ingrese una descripcion','','warning');
    }else{
        if(puedeActualizarSM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("id",id);
        formData.append("literal", literal);
        formData.append("descripcion", descripcion);

        var element= document.getElementById('btnupitlo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-item-lotaip', true);
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
                        //var id= myArr.ID;
                        element.removeAttribute("disabled");
                        element.style.removeProperty("pointer-events");
                        $('#modalEditItLot').modal('hide');
                        $('#inputLiteral').val("");
                        $('#inputDescpE').val("");
                        window.location='/setting-lotaip';
                    },1500);
                }else if(myArr.resultado==false){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo actualizar el registro','','error');
                }else if(myArr.resultado=='existe'){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo actualizar el registro','Ya se encuentra registrado el Literal digitado','error');
                }else if(myArr.resultado=='diferente'){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo actualizar el registro','El Literal digitado es diferente al ingresado previamente','error');
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

function eliminarItemLiteral(id, i){
    var token= $('#token').val();
    var estado="0";
    var estadoItem='No Visible';
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    Swal.fire({
        title: '<strong>¡Aviso!</strong>',
        type: 'warning',
        html: '¿Está seguro que desea inactivar este registro?',
        showCloseButton: false,
        showCancelButton: true,
        allowOutsideClick: false,
        focusConfirm: false,
        focusCancel: true,
        cancelButtonColor: '#d33',
        confirmButtonText:
            '<i class="fa fa-check-circle"></i> Sí',
        confirmButtonAriaLabel: 'Thumbs up, Si',
        cancelButtonText:
            '<i class="fa fa-close"></i> No',
        cancelButtonAriaLabel: 'Thumbs down'
    }).then((result)=> {
        if(result.value)
        {
            $.ajax({
                url:'/in-activar-item-lotaip',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data:{
                    id:id, estado:estado
                },
                success: function(res){
                    if(res.resultado==true){
                        swal({
                            title:'Excelente!',
                            text:'Registro Inactivado',
                            type:'success',
                            showConfirmButton: false,
                            timer: 1700
                        });
                        setTimeout(function(){
                            window.location='/setting-lotaip';
                        },1500);
                    }else if(res.resultado==false){
                        swal('No se pudo Inactivar','','error');
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
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

function activarItemLiteral(id, i){
    var estado = "1";
    var token= $('#token').val();
    var estadoItem='Visible';
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
        url: "/in-activar-item-lotaip",
        type: "POST",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': token
        },
        data: {
            id: id,
            estado: estado,
        },
        success: function (res) {
            if (res.resultado == true) {
                swal({
                    title:'Excelente!',
                    text:'Registro Activado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });
                setTimeout(function(){
                    window.location='/setting-lotaip';
                },1500);
            } else if (res.resultado == false) {
                swal("No se pudo Activar", "", "error");
            }else if(res.resultado == 'inactivo'){
                swal('No se pudo Activar', 'Se encuentra inactiva esta red Social', 'error');
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
            },
            500: function(){
                Swal.fire({
                    title: 'Ha ocurrido un Error',
                    html: '<p>Error de conexión con el <strong>Servidor</strong>.<br>'+
                        'Intente nuevamente</p>',
                    type: 'error'
                });
            }
        }
    });
    }else{
        swal('No tiene permiso para actualizar','','error');
    }
}

/*******************************************************************************
 * LOTAIP INTERFAZ PRINCIPAL
*******************************************************************************/
var g_year='', g_mes='', g_literal='';

function showInfoLotaip(){
    $('#modalCargando').modal('hide');
    $("#tablaLOTAIP")
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
                { className: "dt-head-center", targets: [1, 2, 3, 4, 5] },
            ],
        });
}

function urlregistrarlotaip(){
    window.location='/registrar-lotaip';
}

function urlback(){
    window.location='/lotaip';
}

const removeAccents = (str) => {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
} 

function generarAlias(){
    var year= $('#selYearLotaip').select2('data')[0].text;
    var mes= $('#selMes').select2('data')[0].text;
    var val= $('#selItemLotaip').select2('data')[0].text;
    if(val!='-Seleccione una Opción-' && year!='-Seleccione una Opción-' && mes!='-Seleccione una Opción-'){
        g_year= year; g_mes= mes; g_literal=val;
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        $('#inputAliasFile').val(year+"_"+mes+"_"+cadena);
    }else{
        toastr.info("Debe elegir Año, Mes y el Ítem correspondiente...", "!Aviso!");
        $('#inputAliasFile').val('');
    }
}

function getAliasInput(){
    var year= $('#selYearLotaip').select2('data')[0].text;
    var mes= $('#selMes').select2('data')[0].text;
    var val= $('#selItemLotaip').select2('data')[0].text;
    if(val!='-Seleccione una Opción-' && year!='-Seleccione una Opción-' && mes!='-Seleccione una Opción-'){
        g_year= year; g_mes= mes; g_literal=val;
        let sinaccent= removeAccents(val);
        let minuscula= sinaccent.toLowerCase();
        //let cadenasinpoint= minuscula.replaceAll(".","");
        let cadenasinpoint= minuscula.replaceAll(/[.,/-]/g,"");
        let cadena= cadenasinpoint.replaceAll(" ","_");
        return year+"_"+mes+"_"+cadena;
    }else{
        return "";
    }
}

function guardarLotaip(){
    var token= $('#token').val();

    let fileInput = document.getElementById("file");
    var year = $("#selYearLotaip :selected").val();
    var mes = $("#selMes :selected").val();
    var literal = $("#selItemLotaip :selected").val();
    var aliasfile = $("#inputAliasFile").val();
    var lengimg = fileInput.files.length;

    if (year == "0") {
        $("#selYearLotaip").focus();
        swal("Seleccione el Año", "", "warning");
    } else if (mes == "0") {
        $("#selMes").focus();
        swal("Seleccione el Mes", "", "warning");
    } else if (literal == "0") {
        $("#selItemLotaip").focus();
        swal("Seleccione el Literal", "", "warning");
    } else if (aliasfile == "") {
        $("#inputAliasFile").focus();
        swal("No se ha generado el alias del documento", "", "warning");
    } else if (lengimg == 0 ) {
        swal("No ha seleccionado un archivo", "", "warning");
    } else if (lengimg > 1) {
        swal("Solo se permite un archivo", "", "warning");
    } else{
        if(aliasfile!=getAliasInput()){
            swal('Revise el alias del documento','','warning');
        }else{
            var element = document.querySelector('.savelotaip');
            element.setAttribute("disabled", "");
            element.style.pointerEvents = "none";

            var data = new FormData(formLOTAIP);
            data.append("anio", year);
            data.append("mes", mes);
            data.append("literal", literal);
            data.append("n_mes", g_mes);

            setTimeout(() => {
                sendNewLotaip(token, data, "/store-lotaip", element); 
            }, 700);
        }
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewLotaip(token, data, url, el){
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
                    text:'LOTAIP '+g_year+' - '+g_mes+' - '+g_literal+' Registrado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/registrar-lotaip';
                },1500);
                
            } else if (myArr.resultado == "nofile") {
                swal("Formato de Archivo no válido", "", "error");
            } else if (myArr.resultado == "nocopy") {
                swal("Error al copiar los archivos", "", "error");
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            } else if(myArr.resultado== "existe"){
                swal("No se pudo Guardar", "LOTAIP "+g_year+" - "+g_mes+" - "+g_literal+" ya se encuentra registrada.", "error");
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

function viewopenLOTAIP(id){
    var url= '/view-lotaip/'+id;
    //window.open(url, '_BLANK');
    window.location= url;
}


function interfaceupdateLOTAIP(id){
    window.location= '/edit-lotaip/'+id;
}

function generarAliasE(){
    toastr.info("No se permite generar el Alias...", "!Aviso!");
}

/* FUNCION PARA INACTIVAR LOTAIP */
function inactivarLOTAIP_original(id, i, anio, mes){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
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
            url: "/in-activar-lotaip",
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
                        console.log('Tr'+i+'-'+anio+'-'+mes);
                    var elementState= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[4];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenLOTAIP("+id+")'>"+
                        "<i class='fas fa-folder mr-3'></i>"+
                        "Ver"+
                    "</a>"+
                    "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateLOTAIP("+id+")'>"+
                        "<i class='far fa-edit mr-3'></i>"+
                        "Actualizar"+
                    "</a>";
                    if(estado=="1"){
                        html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                            "<i class='fas fa-eye-slash mr-3'></i>"+
                            "Inactivar"+
                        "</a>";
                    }else if(estado=="0"){
                            html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                                "<i class='fas fa-eye mr-3'></i>"+
                                "Activar"+
                            "</a>";
                    }
                    html+="<a class='btn btn-success btn-sm mt-2 mr-3' onclick='downloadLOTAIP("+id+")' >"+
                        "<i class='fas fa-download mr-3'></i>"+
                        "Descargar LOTAIP"+
                    "</a>"; 
                    var element= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[5];
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

/* FUNCION PARA ACTIVAR LOTAIP */
function activarLOTAIP_original(id, i, anio, mes){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
      url: "/in-activar-lotaip",
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
                console.log('Tr'+i+'-'+anio+'-'+mes);
            var elementState= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[4];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<a class='btn btn-primary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='viewopenLOTAIP("+id+")'>"+
                "<i class='fas fa-folder mr-3'></i>"+
                "Ver"+
            "</a>"+
            "<a class='btn btn-info btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='interfaceupdateLOTAIP("+id+")'>"+
                "<i class='far fa-edit mr-3'></i>"+
                "Actualizar"+
            "</a>";
            if(estado=="1"){
                html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='inactivarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                    "<i class='fas fa-eye-slash mr-3'></i>"+
                    "Inactivar"+
                "</a>";
            }else if(estado=="0"){
                    html+="<a class='btn btn-secondary btn-sm mt-2 mr-3' href='javascript:void(0)' onclick='activarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                        "<i class='fas fa-eye mr-3'></i>"+
                        "Activar"+
                    "</a>";
            }
            html+="<a class='btn btn-success btn-sm mt-2 mr-3' onclick='downloadLOTAIP("+id+")' >"+
                "<i class='fas fa-download mr-3'></i>"+
                "Descargar LOTAIP"+
            "</a>"; 
            var element= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[5];
            $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
}

function inactivarLOTAIP(id, i, anio, mes){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='No Visible';
    var classbadge="badge badge-secondary";
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
            url: "/in-activar-lotaip",
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
                    var elementState= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[1];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenLOTAIP("+id+")'>"+
                        "<i class='fas fa-folder'></i>"+
                    "</button>"+
                    "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateLOTAIP("+id+")'>"+
                        "<i class='far fa-edit'></i>"+
                    "</button>";
                    if(estado=="1"){
                        html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                            "<i class='fas fa-eye-slash'></i>"+
                        "</button>";
                    }else if(estado=="0"){
                            html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                                "<i class='fas fa-eye'></i>"+
                            "</button>";
                    }
                    html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar LOTAIP' onclick='downloadLOTAIP("+id+")' >"+
                        "<i class='fas fa-download'></i>"+
                    "</button>"; 
                    var element= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[2];
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

/* FUNCION PARA ACTIVAR LOTAIP */
function activarLOTAIP(id, i, anio, mes){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Visible';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
      url: "/in-activar-lotaip",
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
            var elementState= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[1];
            $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

            html+="<button type='button' class='btn btn-primary btn-sm mr-3 btntable' title='Ver' onclick='viewopenLOTAIP("+id+")'>"+
                "<i class='fas fa-folder'></i>"+
            "</button>"+
            "<button type='button' class='btn btn-info btn-sm mr-3 btntable' title='Actualizar' onclick='interfaceupdateLOTAIP("+id+")'>"+
                "<i class='far fa-edit'></i>"+
            "</button>";
            if(estado=="1"){
                html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Inactivar' onclick='inactivarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                    "<i class='fas fa-eye-slash'></i>"+
                "</button>";
            }else if(estado=="0"){
                html+="<button type='button' class='btn btn-secondary btn-sm mr-3 btntable' title='Activar' onclick='activarLOTAIP("+id+", "+i+", "+anio+", "+mes+")'>"+
                    "<i class='fas fa-eye'></i>"+
                "</button>";
            }
            html+="<button type='button' class='btn btn-success btn-sm mr-3 btntable' title='Descargar LOTAIP' onclick='downloadLOTAIP("+id+")' >"+
                "<i class='fas fa-download'></i>"+
            "</button>";
            var element= document.getElementById('Tr'+i+'-'+anio+'-'+mes).cells[2];
            $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
}

function downloadLOTAIP(id){
    window.location='/download-lotaip/'+id;
}

function eliminarFile(e){
    e.preventDefault();
    var element= document.getElementById('divfilelo');
    var eldivfile= document.getElementById('cardListLotaip');
    if(element.classList.contains('noshow')){
        element.classList.remove('noshow');
        eldivfile.classList.add('noshow');
        isLotaip= false;
    }
}

function actualizarLotaip(){
    var token= $('#token').val();

    var id= $('#idlotaip').val();
    let fileInput = document.getElementById("fileEdit");
    var lengimg = fileInput.files.length;

    if(isLotaip==false){
        if (lengimg == 0 ) {
            swal("No ha seleccionado un archivo", "", "warning");
        } else if (lengimg > 1) {
            swal("Solo se permite un archivo", "", "warning");
        } else {
            $('#modalFullSend').modal('show');
            var data = new FormData(formLOTAIP_E);
            data.append("islotaip", isLotaip);
            setTimeout(() => {
                sendUpdateLotaip(token, data, "/update-lotaip"); 
            }, 700);
        }
    }else{
        $('#modalFullSend').modal('show');
        var data = new FormData(formLOTAIP_E);
        data.append("islotaip", isLotaip);
        setTimeout(() => {
            sendUpdateLotaip(token, data, "/update-lotaip");
        }, 700);
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendUpdateLotaip(token, data, url){
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
                    text:'LOTAIP Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    window.location = '/lotaip';
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