var isFiltro = false;

function showInfoAtencionC() {
    $("#modalCargando").modal("hide");
    $("#tablaDocAdmin")
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
                { className: "dt-head-center", targets: [1, 2, 3, 4, 5, 6, 7] },
            ],
        });
}

function seguimientosolicitud(idseguimientosoli) {
    window.location = "/atciudadana/seguimiento-solicitud/" + idseguimientosoli;
}

function urlback() {
    window.location = "/atencion-ciudadana";
}

$("#btn-agregar").click(function () {
    var token = $("#token").val();

    var idregistro = $("#idregistrosoli").val();
    var observaciones = $("#observaciones_new").val();

    if (observaciones == "") {
        swal("Debe ingresar una observación", "", "warning");
    } else {
        observaciones = observaciones.trim();

        var data = new FormData();
        data.append("id", idregistro);
        data.append("observaciones", observaciones);

        sendNewObservacion(token, data, "/registrar-observacion-solicitud");

        /*$.ajax({
            url: "/registrar-observacion-solicitu",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': token},
            data: {
                id: idregistro,
                observaciones: observaciones
            },
            success: function (res) {
                if (res.resultado == true) {
                    swal({
                        title: "Excelente!",
                        text: "Registro Guardado",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1600,
                    });

                    var contador = res.contador;
                    
                    setTimeout(function () {
                        $(res.observaciones_n).each(function(i,v){
                            $('#fila-form').before(`
                                <tr>
                                    <td>${contador}</td>
                                    <td>${v.nombre_usuario}</td>
                                    <td>${v.fecha}</td>
                                    <td>${v.estado_mensaje}</td>
                                    <td>${v.observaciones}</td>
                                </tr>
                            `);
                        });
                        // Limpiar inputs
                        $('#observaciones_new').val('');
                    });
                } else if (res.resultado == false) {
                    swal("No se pudo Guardar", "", "error");
                }
            },
        });*/
    }
});

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewObservacion(token, data, url) {
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open("POST", url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function () {
        if (xr.status === 200) {
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);

            if (myArr.resultado == true) {
                swal({
                    title: "Excelente!",
                    text: "Registro Guardado",
                    type: "success",
                    showConfirmButton: false,
                    timer: 1700,
                });

                var contador = myArr.contador;

                setTimeout(function () {
                    $(myArr.observaciones_n).each(function (i, v) {
                        $(`
                                <tr>
                                    <td>${contador}</td>
                                    <td>${v.nombre_usuario}</td>
                                    <td>${v.fecha}</td>
                                    <td>${v.estado_mensaje}</td>
                                    <td>${v.observaciones}</td>
                                </tr>
                            `).insertBefore("#fila-form");
                    });
                    // Limpiar inputs
                    $("#observaciones_new").val("");
                }, 1500);
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            }
        } else if (xr.status === 400) {
            Swal.fire({
                title: "Ha ocurrido un Error",
                html:
                    "<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>" +
                    "Intente nuevamente</p>",
                type: "error",
            });
        }
    };
    xr.send(data);
}

function endsolicitudindividual() {
    var token = $("#token").val();

    var idregistro = $("#idregistrosoli").val();
    var estado = "end";

    var data = new FormData();
    data.append("id", idregistro);
    data.append("estado", estado);

    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea <b>Finalizar</b> esta solicitud?",
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
            sendNewObservacion(token, data, "/change-estado-solicitud");
        }
    });
}

function tramsolicitudindividual() {
    var token = $("#token").val();

    var idregistro = $("#idregistrosoli").val();
    var estado = "tram";

    var data = new FormData();
    data.append("id", idregistro);
    data.append("estado", estado);

    Swal.fire({
        title: "<strong>¡Aviso!</strong>",
        type: "warning",
        html: "¿Está seguro que desea cambiar a estado <b>En Trámite</b> esta solicitud?",
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
            sendNewObservacion(token, data, "/change-estado-solicitud");
        }
    });
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA LA ACTUALIZACION DE ESTADO */
function sendNewObservacion(token, data, url) {
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open("POST", url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function () {
        if (xr.status === 200) {
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);

            if (myArr.resultado == true) {
                swal({
                    title: "Excelente!",
                    text: "Registro Actualizado",
                    type: "success",
                    showConfirmButton: false,
                    timer: 1700,
                });

                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            }
        } else if (xr.status === 400) {
            Swal.fire({
                title: "Ha ocurrido un Error",
                html:
                    "<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>" +
                    "Intente nuevamente</p>",
                type: "error",
            });
        }
    };
    xr.send(data);
}

/* FUNCION QUE TRAZA SALTOS DE LÍNEA EN EL TEXTAREA DE LA NOTICIA */
function replaceCaracter(dato, elemento) {
    var posicion = dato.indexOf("//");
    //console.log(posicion);
    //cadena = dato.slice(0, posicion) + '\n' + dato.slice(posicion + 2);
    while (posicion >= 0) {
        // remplaza "ato" por "atito"
        dato = dato.slice(0, posicion) + "\n" + dato.slice(posicion + 2);
        // busca la siguiente ocurrencia de la palabra
        posicion = dato.indexOf("//");
        //console.log(posicion);
    }
    $(elemento).val(dato);
    /*
    var cadena="";
    for(var i=0; i<dato.length; i++){
      if(dato[i]=="/"){
        //cadena = dato[i];
        cadena += '\n'
        //$('#inputEDesc').val(cadena += '\n');
      }else{
        cadena += dato[i];
      }
    }
    $('#inputEDesc').val(cadena);*/
}

function getfiltroFechas() {
    var token = $("#token").val();
    let picker = $("#reservationtime").data("daterangepicker");

    let start = picker.startDate.format("YYYY-MM-DD");
    let end = picker.endDate.format("YYYY-MM-DD");

    var tipoestado = $("#selectEstado").val();

    if (tipoestado == "0") {
        $("#selectEstado").focus();
        swal("Debe elegir una opción", "", "warning");
    } else {
        isFiltro = true;
        $("#modalCargando").modal("show");
        $.ajax({
            url: "/atencion-ciudadana/filtrar",
            type: "POST",
            dataType: "html",
            headers: {
                "X-CSRF-TOKEN": token,
            },
            data: {
                estado: tipoestado,
                fecha_inicio: start,
                fecha_fin: end
            },
            success: function (res) {
                $("#divDocAdmin").html(res);
                setTimeout(() => {
                    showInfoAtencionC();
                    document.getElementById("btnFiltro").style.display = "none";
                    document.getElementById("btnCancelFiltro").style.display =
                        "block";
                    $("#modalCargando").modal("hide");
                }, 1500);
            },
            error: function () {
                $("#modalCargando").modal("hide");
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

function cancelFiltro() {
    document.getElementById("btnFiltro").style.display = "block";
    document.getElementById("btnCancelFiltro").style.display = "none";
    $("#selectEstado").val("0");
    const hoy = moment();

    // Actualiza las fechas del daterangepicker
    $("#reservationtime").data("daterangepicker").setStartDate(hoy);
    $("#reservationtime").data("daterangepicker").setEndDate(hoy);

    isFiltro = false;

    $("#modalCargando").modal("show");
    $.ajax({
        url: "/atencion-ciudadana/getall",
        type: "GET",
        dataType: "html",
        headers: {
            "X-CSRF-TOKEN": token,
        },
        success: function (res) {
            $("#divDocAdmin").html(res);
            setTimeout(() => {
                showInfoAtencionC();
                $("#modalCargando").modal("hide");
            }, 1500);
        },
        error: function () {
            $("#modalCargando").modal("hide");
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

function downloadExcelSolicitudes(){
    if(isFiltro==false){
        var url= '/exportar-solicitudes-all-excel';
        window.open(url, '_BLANK');
    }else if(isFiltro==true){
        var token = $("#token").val();
        let picker = $("#reservationtime").data("daterangepicker");

        let start = picker.startDate.format("YYYY-MM-DD");
        let end = picker.endDate.format("YYYY-MM-DD");

        var tipoestado = $("#selectEstado").val();

        if (tipoestado == "0") {
            $("#selectEstado").focus();
            swal("Debe elegir una opción", "", "warning");
        } else {
            fetch('/exportar-solicitudes-filter-excel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    fecha_inicio: start,
                    fecha_fin: end,
                    estado: tipoestado
                })
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'reporte_solicitudes_filtro.xlsx';
                document.body.appendChild(a);
                a.click();
                a.remove();
            });
        }
    }
}

function downloadPDFSolicitudes(){
    if(isFiltro==false){
        var url= '/exportar-solicitudes-all-pdf';
        window.open(url, '_BLANK');
    }else if(isFiltro==true){
        var token = $("#token").val();
        let picker = $("#reservationtime").data("daterangepicker");

        let start = picker.startDate.format("YYYY-MM-DD");
        let end = picker.endDate.format("YYYY-MM-DD");

        var tipoestado = $("#selectEstado").val();

        if (tipoestado == "0") {
            $("#selectEstado").focus();
            swal("Debe elegir una opción", "", "warning");
        } else {
            fetch('/exportar-solicitudes-filter-pdf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    fecha_inicio: start,
                    fecha_fin: end,
                    estado: tipoestado
                })
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'reporte_filtro_solicitudes.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
            });
        }
    }
}
