function showInfoAtencionC(){
    $('#modalCargando').modal('hide');
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

function seguimientosolicitud(idseguimientosoli){
    window.location='/atciudadana/seguimiento-solicitud/'+idseguimientosoli;
}

function urlback(){
    window.location='/atencion-ciudadana';
}

$('#btn-agregar').click(function(){
     var token= $('#token').val();

    var idregistro = $('#idregistrosoli').val();
    var observaciones= $('#observaciones_new').val();

    if(observaciones==''){
        swal('Debe ingresar una observación','','warning');
    }else{
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
        
})

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function sendNewObservacion(token, data, url){
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
                swal({
                    title:'Excelente!',
                    text:'Registro Guardado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                var contador = myArr.contador;

                setTimeout(function(){
                    $(myArr.observaciones_n).each(function(i,v){
                            $(`
                                <tr>
                                    <td>${contador}</td>
                                    <td>${v.nombre_usuario}</td>
                                    <td>${v.fecha}</td>
                                    <td>${v.estado_mensaje}</td>
                                    <td>${v.observaciones}</td>
                                </tr>
                            `).insertBefore('#fila-form');
                        });
                        // Limpiar inputs
                        $('#observaciones_new').val('');
                    },1500);
            } else if (myArr.resultado == false) {
                swal("No se pudo Guardar", "", "error");
            }
        }else if(xr.status === 400){
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