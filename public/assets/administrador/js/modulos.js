function showInfoModulos(){
    $('#modalCargando').modal('hide');
    $("#tablaModulos")
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

function openmodalAdd() {
    $('#modalAggModulo').modal('show');
}

function guardarRegistroModulo(){
    var token= $('#token').val();

    var nombre = $('#inputNombreModulo').val();
    var icono = $('#inputIconoModulo').val();
    var prioridad = $('#selNivelPriori :selected').val();

    if(nombre==''){
        $('#inputNombreModulo').focus();
        swal('Debe ingresar un nombre de módulo','','warning');
    }else if(icono=="0"){
        $('#inputIconoModulo').focus();
        swal('Debe ingresar el detalle de un ícono','','warning');
    }if(prioridad=='0'){
        $('#selNivelPriori').focus();
        swal('Debe seleccionar un nivel de prioridad','','warning');
    }else{
         var element = document.querySelector('.saveregistromodulo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var formData= new FormData();
        formData.append("nombre", nombre);
        formData.append("icono", icono);
        formData.append("prioridad", prioridad);
        guardarModulo(token, formData, element, '/registro-modulo');
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function guardarModulo(token, data, el, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");

            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Registro Guardado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                setTimeout(function(){
                    $('#modalAggModulo').modal('hide');
                    $('#selNivelPriori').select2('val','0');
                    $('#inputNombreModulo').val("");
                    $('#inputIconoModulo').val("");
                    window.location='/modulos';
                },1500);
            }else if(myArr.resultado=="existe"){
                swal('Ya existe un registro con el nombre ingreado. Por favor, le recomendamos actualizar el registro','error');
            }else if(myArr.resultado==false){
                swal('No se pudo guardar el registro','','error');
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

function editarModulo(id, item){
    var html="";
    var token= $('#token').val();
    var url= "/get-modulo/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";

    toastr.info('Cargando Datos!','',{
        "positionClass": "toast-top-right",
        "closeButton": false,
        "timeOut": "2500"
    });

    //$('#modalCargando').modal('show');
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    //xr.setRequestHeader('Content-type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(xr.responseText);

            if(myArr.length==0){
                swal('Sin Datos para mostrar','','error');
            }else{
                $('#idmodulo').val(id);
                $('#itemselection').val(item);
                $(myArr).each(function(i,v){
                    $('#inputNombreModuloEdit').val(v.nombre);
                    $('#inputIconoModuloEdit').val(v.icono);
                    $('#selNivelPrioriEdit').val(v.nivel_prioridad);
                });
            }
            setTimeout(function(){
                $('#selNivelPrioriEdit').trigger('change');
                $('#modalEditModulo').modal('show');
            },1200);
        }else if(xr.status === 400){
            //console.log('ERROR CONEXION');
            //$('#modalCargando').modal('hide');
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

    xr.send();
}

function updateModulo(){
    var token= $('#token').val();

    var id= $('#idmodulo').val();
    var posicion= $('#itemselection').val();
    var nombre = $('#inputNombreModuloEdit').val();
    var icono = $('#inputIconoModuloEdit').val();
    var prioridad = $('#selNivelPrioriEdit :selected').val();

    if(nombre==''){
        $('#inputNombreModuloEdit').focus();
        swal('Debe ingresar un nombre de módulo','','warning');
    }else if(icono=="0"){
        $('#inputIconoModuloEdit').focus();
        swal('Debe ingresar el detalle de un ícono','','warning');
    }if(prioridad=='0'){
        $('#selNivelPrioriEdit').focus();
        swal('Debe seleccionar un nivel de prioridad','','warning');
    }else{
         var element = document.querySelector('.updateregistromodulo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var formData= new FormData();
        formData.append("id", id);
        formData.append("nombre", nombre);
        formData.append("icono", icono);
        formData.append("prioridad", prioridad);
        actualizarModulo(token, formData, element, posicion, '/actualizar-modulo');
    }
}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO */
function actualizarModulo(token, data, el, pos, url){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader('X-CSRF-TOKEN', token);
    xr.onload = function(){
        if(xr.status === 200){
            //console.log(this.responseText);
            var myArr = JSON.parse(this.responseText);
            el.removeAttribute("disabled");
            el.style.removeProperty("pointer-events");

            if(myArr.resultado==true){
                swal({
                    title:'Excelente!',
                    text:'Registro Actualizado',
                    type:'success',
                    showConfirmButton: false,
                    timer: 1700
                });

                var elementState= document.getElementById('Tr'+pos).cells[1];
                $(elementState).html(data.get('nombre'));

                var elementState2= document.getElementById('Tr'+pos).cells[2];
                $(elementState2).html(data.get('icono'));

                var elementState2= document.getElementById('Tr'+pos).cells[3];
                $(elementState2).html(data.get('prioridad'));

                setTimeout(function(){
                    $('#modalEditModulo').modal('hide');
                },1500);
            }else if(myArr.resultado==false){
                swal('No se pudo guardar el registro','','error');
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

/* FUNCION PARA INACTIVAR Modulo */
function inactivarModulo(id, i){
    var token=$('#token').val();
    var estado = "0";
    var estadoItem='Inactivo';
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
            url: "/in-activar-modulo",
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
                    var elementState= document.getElementById('Tr'+i).cells[4];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+=`<a class='btn btn-primary btn-sm mt-2 mr-3' title='Editar' href='javascript:void(0)'`+
                            `onclick='editarModulo("`+id+`", `+i+`)'>`+
                            `<i class='far fa-edit ml-2 mr-2'></i>`+
                        `</a>`;
                    if(estado=="1"){
                        html+=`<a class='btn btn-secondary btn-sm mt-2 mr-3' title='Inactivar' href='javascript:void(0)' onclick='inactivarModulo("`+id+`", `+i+`)'>`+
                              `<i class='fas fa-eye-slash ml-2 mr-2'></i>`+
                            `</a>`;
                    }else if(estado=="0"){
                        html+=`<a class='btn btn-secondary btn-sm mt-2 mr-3' title='Activar' href='javascript:void(0)' onclick='activarModulo("`+id+`", `+i+`)'>`+
                              `<i class='fas fa-eye ml-2 mr-2'></i>`+
                            `</a>`;
                    }

                    var element= document.getElementById('Tr'+i).cells[5];
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

/* FUNCION PARA ACTIVAR Documentación Financiera */
function activarModulo(id, i){
    var token=$('#token').val();
    var estado = "1";
    var estadoItem='Activo';
    var classbadge="badge badge-success";
    var html="";
    $.ajax({
      url: "/in-activar-modulo",
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
                    var elementState= document.getElementById('Tr'+i).cells[4];
                    $(elementState).html("<span class='"+classbadge+"'>"+estadoItem+"</span>");

                    html+=`<a class='btn btn-primary btn-sm mt-2 mr-3' title='Editar' href='javascript:void(0)'`+
                            `onclick='editarModulo("`+id+`", `+i+`)'>`+
                            `<i class='far fa-edit ml-2 mr-2'></i>`+
                        `</a>`;
                    if(estado=="1"){
                        html+=`<a class='btn btn-secondary btn-sm mt-2 mr-3' title='Inactivar' href='javascript:void(0)' onclick='inactivarModulo("`+id+`", `+i+`)'>`+
                              `<i class='fas fa-eye-slash ml-2 mr-2'></i>`+
                            `</a>`;
                    }else if(estado=="0"){
                        html+=`<a class='btn btn-secondary btn-sm mt-2 mr-3' title='Activar' href='javascript:void(0)' onclick='activarModulo("`+id+`", `+i+`)'>`+
                              `<i class='fas fa-eye ml-2 mr-2'></i>`+
                            `</a>`;
                    }

                    var element= document.getElementById('Tr'+i).cells[5];
                    $(element).html(html);
            }, 1500);
        } else if (res.resultado == false) {
            swal("No se pudo Inactivar", "", "error");
        }
      },
    });
}

