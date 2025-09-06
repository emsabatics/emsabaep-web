//FUNCION QUE ABRE INTERFAZ PARA REGISTRAR PROCESO
function routewriteinfor() {
    $('#modalAggInfor').modal('show');
}

function guardarRegistroInfor() {
    var token = $('#token').val();

    var nombre = $('#InputNombre').val();
    var enlace = $('#Inputenlace').val();

    if (nombre == '') {
        $('#InputNombre').focus();
        swal('Ingrese un nombre', '', 'warning');
    } else if (enlace == '') {
        $('#Inputenlace').focus();
        swal('Ingrese el enlace', '', 'warning');
    } else {
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        $('#modalCargando').modal('show');
        var formData = new FormData();
        formData.append("nombre", nombre);
        formData.append("enlace", enlace);

        var element = document.getElementById("btnsaveinforpc");
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        setTimeout(function () {
            var contentType = "application/x-www-form-urlencoded;charset=utf-8";
            var xr = new XMLHttpRequest();
            xr.open('POST', '/registro-proceso', true);

            xr.setRequestHeader("X-CSRF-TOKEN", token);
            xr.onload = function () {
                if (xr.status === 200) {
                    //console.log(this.responseText);
                    var myArr = JSON.parse(this.responseText);
                    if (myArr.resultado == true) {
                        swal({
                            title: 'Excelente!',
                            text: 'Registro Guardado',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1700
                        });

                        setTimeout(function () {
                            window.location = "/proceso-contratacion";
                        }, 1500);
                    } else if (myArr.resultado == false) {
                        $('#modalCargando').modal('hide');
                        el.removeAttribute("disabled");
                        el.style.removeProperty("pointer-events");
                        swal('No se pudo guardar el registro', '', 'error');
                    }
                } else if (xr.status === 400) {
                    el.removeAttribute("disabled");
                    el.style.removeProperty("pointer-events");
                    $('#modalCargando').modal('hide');
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>' +
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            };
            xr.send(formData);
        }, 1200);

        }else{
            swal('No tiene permiso para guardar','','error');
        }
    }
}

function getListadoProceso(array) {
    var html = "";
    var con = 1;
    if (array.length === 0) {
        html += "<tr style='text-align: center;'>" +
            "<td colspan='4'>No hay registros</td>" +
            "</tr>";
    } else {
        document.getElementById('card-tools').style.display = 'none';
    }

    $(array).each(function (i, v) {
        html += "<tr id='Tr" + i + "'>" +
            "<td>" + con + "</td>" +
            "<td>" + v.nombre + "</td>" +
            "<td>" + v.enlace + "</td>" +
            "<td>" +
            "<div class='dropdown show'>" +
            "<a class='btn btn-secondary dropdown-toggle' href='#' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
            "<i class='fas fa-cog'></i>" +
            "</a>" +
            "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>" +
            "<a class='dropdown-item' href='javascript:void(0)' onclick='editarItemInfor(" + v.id + ")'>Editar</a>"+
            "<a class='dropdown-item' href='javascript:void(0)' onclick='openItemInfor(" + v.id + ")'>Ver</a>";
        html += "</div>" +
            "</div>" +
            "</td>" +
            "</tr>";
        con++;
    });

    $('#tablaListadoInfor > tbody').html(html);
    setTimeout(function () {
        $('#modalCargando').modal('hide');
    }, 700);
}

function editarItemInfor(id) {
    $('#idinfomacion').val(id);

    var url = "/get-inforproceso/" + id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function () {
        if (xr.status === 200) {
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function (i, v) {
                $('#InputENombre').val(v.nombre);
                $('#InputenlaceE').val(v.enlace);
            });
        } else if (xr.status === 400) {
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>' +
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    }

    xr.send(null);

    setTimeout(() => {
        $('#modalEditInfor').modal('show');
    }, 300);
}

function editarRegistroInfor(){
    var token = $('#token').val();

    var id= $('#idinfomacion').val();
    var nombre = $('#InputENombre').val();
    var enlace = $('#InputenlaceE').val();

    if (nombre == '') {
        $('#InputENombre').focus();
        swal('Ingrese un nombre', '', 'warning');
    } else if (enlace == '') {
        $('#InputenlaceE').focus();
        swal('Ingrese el enlace', '', 'warning');
    } else {
        if(puedeActualizarSM(nameInterfaz) === 'si'){
        //$('#modalCargando').modal('show');
        var formData = new FormData();
        formData.append("id", id);
        formData.append("nombre", nombre);
        formData.append("enlace", enlace);

        var element = document.getElementById("btneditinforpc");
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        setTimeout(function () {
            var contentType = "application/x-www-form-urlencoded;charset=utf-8";
            var xr = new XMLHttpRequest();
            xr.open('POST', '/editar-proceso', true);

            xr.setRequestHeader("X-CSRF-TOKEN", token);
            xr.onload = function () {
                if (xr.status === 200) {
                    //console.log(this.responseText);
                    var myArr = JSON.parse(this.responseText);
                    if (myArr.resultado == true) {
                        swal({
                            title: 'Excelente!',
                            text: 'Registro Actualizado',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1700
                        });

                        setTimeout(function () {
                            window.location = "/proceso-contratacion";
                        }, 1500);
                    } else if (myArr.resultado == false) {
                        $('#modalCargando').modal('hide');
                        el.removeAttribute("disabled");
                        el.style.removeProperty("pointer-events");
                        swal('No se pudo actualizar el registro', '', 'error');
                    }
                } else if (xr.status === 400) {
                    el.removeAttribute("disabled");
                    el.style.removeProperty("pointer-events");
                    $('#modalCargando').modal('hide');
                    Swal.fire({
                        title: 'Ha ocurrido un Error',
                        html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>' +
                            'Intente nuevamente</p>',
                        type: 'error'
                    });
                }
            };
            xr.send(formData);
        }, 1200);
        }else{
            swal('No tiene permiso para actualizar','','error');
        }
    }
}

function openItemInfor(id){
    var newurl="";
    toastr.info('Espere un momento...','¡Aviso!');
    var url = "/get-inforproceso/" + id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function () {
        if (xr.status === 200) {
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function (i, v) {
                newurl= v.enlace;
            });
            window.open(newurl, '_BLANK');
        } else if (xr.status === 400) {
            Swal.fire({
                title: 'Ha ocurrido un Error',
                html: '<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>' +
                    'Intente nuevamente</p>',
                type: 'error'
            });
        }
    }

    xr.send(null);
}