var arrayGuardar= [];
var arrayUpdate= [];
var arrayDelete= [];
var arraySelOp= [];
var selmodulo=[];
var contar1=0;

function showInfoPermisos(){
    $('#modalCargando').modal('hide');
    $("#tablaPermisos")
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

/*FUNCION LIMPIA ARRAY*/
function limpiarArray(){
    while(selmodulo.length>0){
        selmodulo.pop();
    }

    while(arraySelOp.length>0){
        arraySelOp.pop();
    }
}

function editarPermiso(id, usuario, index){
    $('#id_usuariop').val(id);
    $('#inputUsuariop').val(usuario);
    llenarTabla("vacio");
    setTimeout(() => {
        $('#modalSettings').modal('show');
    }, 1500);
}

/*$('#selModulo').on('select2:select', function (e) {
    var data = e.params.data;
    var x= data.id;
    if(x!='0'){
        console.log("IDSEL: "+x);
        llenarTabla(x);
    }else{
        llenarTabla("vacio");
    }
});*/

$(document).on('select2:select', '#selModulo', function (e) {
    var data = e.params.data;
    var x = data.id;
    if(x != '0'){
        //console.log("IDSEL: " + x);
        llenarTabla(x);
    } else {
        llenarTabla("vacio");
    }
});


function llenarTabla(opcion){
    //console.log(opcion);
    limpiarArray();
    var html=""; var i=1;var con=1, numcheck=1; var j=0;
    var letraG='"guardar"';
    var letraU='"actualizar"';
    var letraD='"eliminar"';
    var letraDo='"descargar"';
    var letraS='"configurar"';
    var token=$('#token').val();

    if(opcion!="vacio"){
        var idu= $('#id_usuariop').val();
        //opcion = utf8_to_b64(opcion);
        var idmodulo= opcion;
        html+="<label class='control-label'>Tabla de Opciones</label><br>";
        $.ajax({
            url:'/get-permisos-usuario',
            type:'POST',
            dataType:'json',
            headers: {'X-CSRF-TOKEN': token},
            data:{idm:idmodulo, idu:idu},
            success:function(res){
                $(res).each(function(i,v){
                    //console.log("DATOMOD: "+v.datomod);
                    if(v.datomod=="vacio"){
                        //console.log(v.numsubm);
                        if(v.numsubm=="0"){
                            html+="<table id='datosPermiso' class='table datatables'><thead class='thead-dark'>"+
                                "<tr style='pointer-events:none;'><th>Módulo</th><th>Guardar</th>"+
                                "<th>Actualizar</th><th>Eliminar</th><th>Descargar</th><th>Configurar</th><th><i class='fa fa-check-square'></i></th>"+
                                "</tr></thead><tbody>";
                            html+="<tr><td>"+v.modulo+"</td>"+
                            "<td>"+
                                "<div class='toggle-flip'>"+
                                  "<label>"+
                                    "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                  "</label>"+
                                "</div>"+
                            "</td>";
                            numcheck++;
                            html+="<td>"+
                                "<div class='toggle-flip'>"+
                                  "<label>"+
                                    "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                  "</label>"+
                                "</div>"+
                            "</td>";
                            numcheck++;
                            html+="<td>"+
                                "<div class='toggle-flip'>"+
                                  "<label>"+
                                    "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                  "</label>"+
                                "</div>"+
                            "</td>";
                            numcheck++;
                            html+="<td>"+
                                "<div class='toggle-flip'>"+
                                  "<label>"+
                                    "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                  "</label>"+
                                "</div>"+
                            "</td>";
                            numcheck++;
                            html+="<td>"+
                                "<div class='toggle-flip'>"+
                                  "<label>"+
                                    "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                  "</label>"+
                                "</div>"+
                            "</td>";
                            html+="<td class='tdcheck'>";
                            var nomMod='"'+v.titulo+'"';
                            if(v.seleccionado=="no"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input inputcheck' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")'>"+
                                "</label>"+
                                "</div>";
                            }else if(v.seleccionado=="si"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input inputcheck' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")' checked>"+
                                "</label>"+
                                "</div>";
                                selmodulo.push(parseInt(idmodulo)); 
                            }

                            html+="</td></tr>";
                            i++;
                        }else if(v.numsubm=="1"){
                            //console.log(v.seleccionado);
                            if(v.seleccionado=="no"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")'>"+
                                "<span id='spanInfo' style='font-size: .9rem;font-weight: 700;font-family: sans-serif;'>"+
                                    "Habilitar Permiso Módulo</span></label>"+
                                "</div>";
                            }else if(v.seleccionado=="si"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")' checked>"+
                                "<span id='spanInfo' style='font-size: .9rem;font-weight: 700;font-family: sans-serif;'>"+
                                    "Deshabilitar Permiso Módulo</span></label>"+
                                "</div>";
                                selmodulo.push(parseInt(idmodulo)); 
                            }
                            html+="<br>";
                            html+="<table id='datosPermiso' class='table datatables'><thead class='thead-dark'>"+
                                "<tr style='pointer-events:none;'><th>Submódulos</th><th>Guardar</th>"+
                                "<th>Actualizar</th><th>Eliminar</th><th>Descargar</th><th>Configurar</th>"+
                                "</tr></thead><tbody>";
                            $.each(v.submodulos, function(j,w){
                                html+="<tr>";
                                html+="<td>"+w.submodulo+"</td>";
                                $.each(v.opciones, function(k,a){
                                    if(a.guardar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",guardar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }

                                    numcheck++;

                                    if(a.actualizar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",actualizar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }

                                    numcheck++;

                                    if(a.eliminar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",eliminar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;

                                    if(a.descargar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",descargar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;

                                    if(a.configurar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",configurar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;
                                });
                                
                                html+="</tr>";
                            });  
                        }
                    }else if(v.datomod=="lleno"){
                        //console.log("NUMSUB: "+v.numsubm);
                        if(v.numsubm=="0"){
                            html+="<table id='datosPermiso' class='table datatables'><thead class='thead-dark'>"+
                                "<tr style='pointer-events:none;'><th>Módulo</th><th>Guardar</th>"+
                                "<th>Actualizar</th><th>Eliminar</th><th>Descargar</th><th>Configurar</th><th><i class='fa fa-check-square'></i></th>"+
                                "</tr></thead><tbody>";
                            html+="<tr><td>"+v.modulo+"</td>";

                            $.each(v.opciones, function(j,w){
                                if(w.guardar=="si"){
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                    arraySelOp.push("guardar");
                                }else{
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                }

                                numcheck++;

                                if(w.actualizar=="si"){
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                    arraySelOp.push("actualizar");
                                }else{
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                }

                                numcheck++;

                                if(w.eliminar=="si"){
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                    arraySelOp.push("eliminar");
                                }else{
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                }

                                numcheck++;

                                if(w.descargar=="si"){
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                    arraySelOp.push("descargar");
                                }else{
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                }

                                numcheck++;

                                if(w.configurar=="si"){
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                    arraySelOp.push("configurar");
                                }else{
                                    html+="<td>"+
                                        "<div class='toggle-flip'>"+
                                          "<label>"+
                                            "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDato(this,"+numcheck+","+idmodulo+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                          "</label>"+
                                        "</div>"+
                                    "</td>";
                                }
                            });

                            html+="<td class='tdcheck'>";
                            var nomMod='"'+v.titulo+'"';
                            if(v.seleccionado=="no"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input inputcheck' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")'>"+
                                "</label>"+
                                "</div>";
                            }else if(v.seleccionado=="si"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input inputcheck' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")' checked>"+
                                "</label>"+
                                "</div>";
                                selmodulo.push(parseInt(idmodulo)); 
                            }

                            html+="</td></tr>";
                            i++;
                        }else if(v.numsubm=="1"){
                            if(v.seleccionado=="no"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")'>"+
                                "<span id='spanInfo' style='font-size: .9rem;font-weight: 700;font-family: sans-serif;'>"+
                                    "Habilitar Permiso Módulo</span></label>"+
                                "</div>";
                            }else if(v.seleccionado=="si"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")' checked>"+
                                "<span id='spanInfo' style='font-size: .9rem;font-weight: 700;font-family: sans-serif;'>"+
                                    "Deshabilitar Permiso Módulo</span></label>"+
                                "</div>";
                                selmodulo.push(parseInt(idmodulo)); 
                            }
                            html+="<br>";
                            html+="<table id='datosPermiso' class='table datatables'><thead class='thead-dark'>"+
                                "<tr style='pointer-events:none;'><th>Submódulos</th><th>Guardar</th>"+
                                "<th>Actualizar</th><th>Eliminar</th><th>Descargar</th><th>Configurar</th>"+
                                "</tr></thead><tbody>";
                            $.each(v.submodulos, function(j,w){
                                html+="<tr>";
                                html+="<td>"+w.submodulo+"</td>";
                                $.each(v.opciones, function(k,a){
                                    if(a.guardar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",guardar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }

                                    numcheck++;

                                    if(a.actualizar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",actualizar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }

                                    numcheck++;

                                    if(a.eliminar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",eliminar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;

                                    if(a.descargar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",descargar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;

                                    if(a.configurar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",configurar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;
                                });
                                
                                html+="</tr>";
                            });
                        }else if(v.numsubm=="2"){
                            if(v.seleccionado=="no"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")'>"+
                                "<span id='spanInfo' style='font-size: .9rem;font-weight: 700;font-family: sans-serif;'>"+
                                    "Habilitar Permiso</span></label>"+
                                "</div>";
                            }else if(v.seleccionado=="si"){
                                html+="<div class='form-check'>"+
                                "<label class='form-check-label'>"+
                                   "<input class='form-check-input' type='checkbox' id='cCheck"+i+"' onclick='seleccionar(this,"+idmodulo+")' checked>"+
                                "<span id='spanInfo' style='font-size: .9rem;font-weight: 700;font-family: sans-serif;'>"+
                                    "Deshabilitar Permiso Módulo</span></label>"+
                                "</div>";
                                selmodulo.push(parseInt(idmodulo)); 
                            }
                            html+="<br>";
                            html+="<table id='datosPermiso' class='table datatables'><thead class='thead-dark'>"+
                                "<tr style='pointer-events:none;'><th>Submódulos</th><th>Guardar</th>"+
                                "<th>Actualizar</th><th>Eliminar</th><th>Descargar</th><th>Configurar</th>"+
                                "</tr></thead><tbody>";
                            $.each(v.opciones,function(j,w){
                                html+="<tr>";
                                html+="<td>"+w.submodulo+"</td>";
                                if(w.seleccionSM=="no"){
                                    html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";

                                    numcheck++;

                                    html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";

                                    numcheck++;

                                    html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";

                                    numcheck++;

                                    html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";

                                    numcheck++;

                                    html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";

                                    numcheck++;

                                }else if(w.seleccionSM=="si"){
                                    if(w.guardar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",guardar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraG+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }

                                    numcheck++;

                                    if(w.actualizar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",actualizar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraU+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }

                                    numcheck++;

                                    if(w.eliminar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",eliminar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraD+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;

                                    if(w.descargar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",descargar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraDo+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;

                                    if(w.configurar=="si"){
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' checked onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                        arraySelOp.push(w.idsm+",configurar");
                                    }else{
                                        html+="<td>"+
                                            "<div class='toggle-flip'>"+
                                              "<label>"+
                                                "<input id='checkD"+numcheck+"' type='checkbox' onclick='seleccionarDatoSM(this,"+numcheck+","+idmodulo+","+w.idsm+","+letraS+")'><span class='flip-indecator' data-toggle-on='SI' data-toggle-off='NO'></span>"+
                                              "</label>"+
                                            "</div>"+
                                        "</td>";
                                    }
                                    numcheck++;
                                }
                                html+="</tr>";
                            });
                        }
                    }
                });
                html+="</tbody></table>";
                $('#tabla_permiso').html(html);
                
                setTimeout(() => {
                    $('#datosPermiso').DataTable({
                        //para cambiar el lenguaje a español
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros",
                            "zeroRecords": "No se encontraron resultados",
                            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sSearch": "Buscar:",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast":"Último",
                                "sNext":"Siguiente",
                                "sPrevious": "Anterior"
                                },
                            "sProcessing":"Procesando...",
                        }
                    });
                }, 500);
            },
            error:function(response){
                swal('Ha ocurrido un error en la comunicación con el servidor','','error');
            }
        });

    }else if(opcion=="vacio"){
       // html+="<label class='control-label'>Tabla de Opciones</label><br>";
       var idusuario = $('#id_usuariop').val();
       $("#divDocSelectPermisos").html("");
       toastr.info('Cargando Datos.','Por favor, espere...',{
            "positionClass": "toast-top-right",
            "closeButton": false,
            "timeOut": "2500"
        });
       $.ajax({
            url: "/get-permiso-by-usuario",
            type: "POST",
            dataType: "html",
            headers: {
                "X-CSRF-TOKEN": token,
            },
            data: {
                idusuario: idusuario
            },
            success: function (res) {
                $("#divDocSelectPermisos").html(res);
                setTimeout(() => {
                    refrescarSelect();
                }, 500);
            },
            error: function () {
                Swal.fire({
                    title: "Ha ocurrido un Error",
                    html: "<p>Error al Filtrar los Datos",
                    type: "error",
                });
            },
            statusCode: {
                400: function () {
                    Swal.fire({
                        title: "Ha ocurrido un Error",
                        html:
                            "<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>" +
                            "Intente nuevamente</p>",
                        type: "error",
                    });
                },
            },
        });
    }
}

/*FUNCION SELECCIONAR MODULOS*/
function seleccionar(td, value) {
    //console.log(value);
    value = utf8_to_b64(value);
    var idu= $('#id_usuariop').val();
    var token= $('#token').val();
    var estado="";
    var html="";

    var html_loader= "<div class='txt-center' style='display: flex;justify-content: center;'>"+
            "<img src='/assets/administrador/img/gif/load.gif' alt=''>"+
        "</div>";

    var index1 = selmodulo.indexOf(value);
    if (index1 > -1) {
        contar1= contar1-1;
        selmodulo.splice(index1, 1);
        estado="0";
    }
    else
    {
        contar1++;
        estado="1";
        if(selmodulo=='')
        {
            selmodulo=[value];
        }
        else{
            selmodulo.push(value); 
        }
    }
    $.ajax({
        url:'/permisos/registro_p_modulo',
        type:'POST',
        dataType:'json',
        headers: {'X-CSRF-TOKEN': token},
        data:{
            id:idu, idmodulo:value, estado:estado
        },
        success:function(res){
            if(res.resultado==true){
                if(estado=="1"){
                    html+="<div class='alert alert-success' role='alert'>"+
                        "<h4 class='alert-heading'>Selección Guardada!</h4>"+
                      "</div>";
                    $('#spanInfo').html("Deshabilitar Permiso Módulo");
                    toastr.success('Selección Guardada!','',{
                        "positionClass": "toast-top-right",
                        "closeButton": false,
                        "timeOut": "2500"
                    });
                }else if(estado=="0"){
                    html+="<div class='alert alert-success' role='alert'>"+
                        "<h4 class='alert-heading'>Selección Quitada!</h4>"+
                      "</div>";
                    $('#spanInfo').html("Habilitar Permiso Módulo");
                    toastr.success('Selección Quitada!','',{
                        "positionClass": "toast-top-right",
                        "closeButton": false,
                        "timeOut": "2500"
                    });
                }
                
                $('#div_respuesta').html(html);
                $('#tabla_permiso').html(html_loader);
                let valorn =  base64ToNumber(value);
                setTimeout(function(){
                    $('#div_respuesta').html("");
                    $('#tabla_permiso').html("");
                    llenarTabla(valorn);
                },3500);
            }else if(res.resultado==false){
                html+="<div class='alert alert-danger' role='alert'>"+
                    "<h4 class='alert-heading'>No se pudo Guardar!</h4>"+
                  "</div>";
                $('#div_respuesta').html(html);
                toastr.error('No se pudo Guardar!','',{
                    "positionClass": "toast-top-right",
                    "closeButton": false,
                    "timeOut": "2500"
                });
                setTimeout(function(){
                    $('#div_respuesta').html("");
                },3500);
            }
        },
        error:function(response){
            swal('Ha ocurrido un error en la comunicación con el servidor','','error');
        }
    });
                
    /*console.log('Seleccionar: ');
    console.log(selmodulo);*/
}

/*FUNCION SELECCIONAR OPCION INDIVIDUAL MODULO SIN SUBMODULO*/
function seleccionarDato(td, numcheck, value, nombre) {
    value = utf8_to_b64(value);
    var idu= $('#id_usuariop').val();
    var token= $('#token').val();
    var estado="";
    var html="";

    var index1 = arraySelOp.indexOf(nombre);
    //console.log(index1);
    if (index1 > -1) {
        contar1= contar1-1;
        arraySelOp.splice(index1, 1);
        estado="0";
    }
    else
    {
        contar1++;
        estado="1";
        if(arraySelOp=='')
        {
            arraySelOp=[nombre];
        }
        else{
            arraySelOp.push(nombre); 
        }
    }

    $.ajax({
        url:'/permisos/registro_ps_modulo',
        type:'POST',
        dataType:'json',
        headers: {'X-CSRF-TOKEN': token},
        data:{
            id:idu, idmodulo:value, opcion:nombre, estado: estado
        },
        success:function(res){
            if(res.resultado==true){
                if(estado=="1"){
                    html+="<div class='alert alert-success' role='alert'>"+
                        "<h4 class='alert-heading'>Permiso Asignado!</h4>"+
                      "</div>";
                    toastr.success('Permiso Asignado!','',{
                        "positionClass": "toast-top-right",
                        "closeButton": false,
                        "timeOut": "2500"
                    });
                }else if(estado=="0"){
                    html+="<div class='alert alert-success' role='alert'>"+
                        "<h4 class='alert-heading'>Permiso Quitado!</h4>"+
                      "</div>";
                    toastr.success('Permiso Quitado!','',{
                        "positionClass": "toast-top-right",
                        "closeButton": false,
                        "timeOut": "2500"
                    });
                }
                
                $('#div_respuesta').html(html);
                setTimeout(function(){
                    $('#div_respuesta').html("");
                },3500);
            }else if(res.resultado==false){
                html+="<div class='alert alert-danger' role='alert'>"+
                    "<h4 class='alert-heading'>No se pudo Guardar!</h4>"+
                  "</div>";
                $('#div_respuesta').html(html);
                toastr.error('No se pudo Guardar!','',{
                    "positionClass": "toast-top-right",
                    "closeButton": false,
                    "timeOut": "2500"
                });
                setTimeout(function(){
                    $('#div_respuesta').html("");
                },3500);
            }else if(res.resultado=="no_exist"){
                html+="<div class='alert alert-danger' role='alert'>"+
                    "<h4 class='alert-heading'>No se pudo Asignar!</h4>"+
                    "<p>Primero debe dar permiso a este módulo</p>"+
                  "</div>";
                $('#div_respuesta').html(html);
                toastr.error('Primero debe dar permiso a este módulo','No se pudo Asignar!',{
                    "positionClass": "toast-top-right",
                    "closeButton": false,
                    "timeOut": "2500"
                });
                $('#checkD'+numcheck).prop('checked', false);
                contar1= contar1-1;
                arraySelOp.splice(contar1, 1);
                setTimeout(function(){
                    $('#div_respuesta').html("");
                },3500);
            }
        },
        error:function(response){
            swal('Ha ocurrido un error en la comunicación con el servidor','','error');
        }
    });
}

/*FUNCION SELECCIONAR OPCION INDIVIDUAL MODULO CON SUBMODULO*/
function seleccionarDatoSM(td, numcheck, value, idsm, nombre) {
    value = utf8_to_b64(value);
    var idu= $('#id_usuariop').val();
    var token= $('#token').val();
    var estado="";
    var html="";
    var dato= idsm+","+nombre;
    var index1 = arraySelOp.indexOf(dato);
    if (index1 > -1) {
        contar1= contar1-1;
        arraySelOp.splice(index1, 1);
        estado="0";
    }
    else
    {
        contar1++;
        estado="1";
        if(arraySelOp=='')
        {
            arraySelOp=[dato];
        }
        else{
            arraySelOp.push(dato); 
        }
    }

    idsm = utf8_to_b64(idsm);

    $.ajax({
        url:'/permisos/registro_ps_submodulo',
        type:'POST',
        dataType:'json',
        headers: {'X-CSRF-TOKEN': token},
        data:{
            id:idu, idmodulo:value, idsubmodulo: idsm, opcion:nombre, estado: estado
        },
        success:function(res){
            if(res.resultado==true){
                if(estado=="1"){
                    html+="<div class='alert alert-success' role='alert'>"+
                        "<h4 class='alert-heading'>Permiso Asignado!</h4>"+
                      "</div>";
                    toastr.success('Permiso Asignado!','',{
                        "positionClass": "toast-top-right",
                        "closeButton": false,
                        "timeOut": "2500"
                    });
                }else if(estado=="0"){
                    html+="<div class='alert alert-success' role='alert'>"+
                        "<h4 class='alert-heading'>Permiso Quitado!</h4>"+
                      "</div>";
                    toastr.success('Permiso Quitado!','',{
                        "positionClass": "toast-top-right",
                        "closeButton": false,
                        "timeOut": "2500"
                    });
                }
                
                $('#div_respuesta').html(html);
                setTimeout(function(){
                    $('#div_respuesta').html("");
                },3500);
            }else if(res.resultado==false){
                html+="<div class='alert alert-danger' role='alert'>"+
                    "<h4 class='alert-heading'>No se pudo Guardar!</h4>"+
                  "</div>";
                $('#div_respuesta').html(html);
                toastr.error('No se pudo Guardar!','',{
                    "positionClass": "toast-top-right",
                    "closeButton": false,
                    "timeOut": "2500"
                });
                setTimeout(function(){
                    $('#div_respuesta').html("");
                },3500);
            }else if(res.resultado=="no_exist"){
                html+="<div class='alert alert-danger' role='alert'>"+
                    "<h4 class='alert-heading'>No se pudo Asignar!</h4>"+
                    "<p>Primero debe dar permiso a este módulo</p>"+
                  "</div>";
                $('#div_respuesta').html(html);
                toastr.error('Primero debe dar permiso a este módulo','No se pudo Asignar!',{
                    "positionClass": "toast-top-right",
                    "closeButton": false,
                    "timeOut": "2500"
                });
                $('#checkD'+numcheck).prop('checked', false);
                contar1= contar1-1;
                arraySelOp.splice(contar1, 1);
                setTimeout(function(){
                    $('#div_respuesta').html("");
                },3500);
            }
        },
        error:function(response){
            swal('Ha ocurrido un error en la comunicación con el servidor','','error');
        }
    });
}

function cerrarModal(){
    cargar_datos();
}

function cargar_datos(){
    var token= $('#token').val();
    $.ajax({
        url: "/get-all-permisos-usuario",
        type: "GET",
        dataType: "html",
        headers: {
            "X-CSRF-TOKEN": token,
        },
        success: function (res) {
            $("#divDocPermisos").html(res);
            setTimeout(() => {
                showInfoPermisos();
                $('#modalSettings').modal('hide');
                $('#selModulo').val("0");
                $('#selModulo').select2().trigger('change');
                $('#tabla_permiso').html("");
                $('#div_respuesta').html("");
            }, 900);
        },
        error: function () {
            Swal.fire({
                title: "Ha ocurrido un Error",
                html: "<p>Error inesperado",
                type: "error",
            });
        },
        statusCode: {
            400: function () {
                Swal.fire({
                    title: "Ha ocurrido un Error",
                    html:
                        "<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>" +
                        "Intente nuevamente</p>",
                    type: "error",
                });
            },
        },
    });
}

function refrescarSelect(){
    $('.select2').select2({
        theme: 'bootstrap4',
    });
}