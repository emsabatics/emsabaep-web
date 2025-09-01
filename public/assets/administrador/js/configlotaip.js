function openmodalAddArtLotaip(){
    $('#modalAggArticuloLot').modal('show');
}

function guardarRegistroArtLot(){
    var token= $('#token').val();
    var descripcion= $('#inputDescp').val();

    if(descripcion==''){
        $('#inputDescp').focus();
        swal('Ingrese una descripcion','','warning');
    }else{
        if(puedeGuardarSM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("descripcion", descripcion);

        var element= document.getElementById('btnsaveartlo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/registro-articulo-lotaip', true);
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
                        $('#modalAggArticuloLot').modal('hide');
                        $('#inputDescp').val("");
                        window.location='/articles-lotaip';
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
    let filas = $('#tablaListadoArtLotaip').find('tbody tr').length;

    html+="<tr id='"+filas+"'>"+
        "<td>"+(filas+1)+"</td>"+
        "<td>"+formData.get('descripcion')+"</td>"+
        "<td>Visible</td>"+
        "<td>"+
            "<div class='dropdown show'>"+
                "<a class='btn btn-secondary dropdown-toggle' href='javascript:void(0)' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
                    "<i class='fas fa-cog'></i>"+
                "</a>"+
                "<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>"+
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='editarArtLiteral("+id+")'>Editar</a>"+
                    "<a class='dropdown-item' href='javascript:void(0)' onclick='eliminarArtLiteral("+id+", "+filas+")'>Inactivar</a>"+
                "</div>"+
            "</div>"+
        "</td>"+
    "</tr>";

    document.getElementById('tablaListadoArtLotaip').insertRow(-1).innerHTML = html;
}

const eliminarFila = () => {
    const table = document.getElementById('tablaListadoArtLotaip')
    const rowCount = table.rows.length
    
    if (rowCount <= 1)
      alert('No se puede eliminar el encabezado')
    else
      table.deleteRow(rowCount -1)
}

function editarArtLiteral(id){
    $('#idarticulolotaip').val(id);

    var url= "/get-articulo-lotaip/"+id;
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('GET', url, true);

    xr.onload = function(){
        if(xr.status === 200){
            var myArr = JSON.parse(this.responseText);
            $(myArr).each(function(i,v){
                $('#inputDescpEArt').val(v.descripcion);
            });
            setTimeout(() => {
                $('#modalEditArticuloLot').modal('show');
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

function actualizarRegistroArtLo(){
    var token= $('#token').val();
    var id= $('#idarticulolotaip').val();
    var descripcion= $('#inputDescpEArt').val();

    if(descripcion==''){
        $('#inputDescpEArt').focus();
        swal('Ingrese una descripcion','','warning');
    }else{
        if(puedeActualizarSM(nameInterfaz) === 'si'){
        var formData= new FormData();
        formData.append("id",id);
        formData.append("descripcion", descripcion);

        var element= document.getElementById('btnupartlo');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var xr = new XMLHttpRequest();
        xr.open('POST', '/actualizar-articulo-lotaip', true);
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
                        $('#modalEditArticuloLot').modal('hide');
                        $('#inputDescpEArt').val("");
                        window.location='/articles-lotaip';
                    },1500);
                }else if(myArr.resultado==false){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo actualizar el registro','','error');
                }else if(myArr.resultado=='existe'){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo actualizar el registro','Ya se encuentra registrado el Artículo digitado','error');
                }else if(myArr.resultado=='diferente'){
                    element.removeAttribute("disabled");
                    element.style.removeProperty("pointer-events");
                    swal('No se pudo actualizar el registro','El Artículo digitado es diferente al ingresado previamente','error');
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

function eliminarArtLiteral(id, i){
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
                url:'/in-activar-articulo-lotaip',
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
                            window.location='/articles-lotaip';
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

function activarArtLiteral(id, i){
    var estado = "1";
    var token= $('#token').val();
    var estadoItem='Visible';
    var html="";
    if(puedeActualizarSM(nameInterfaz) === 'si'){
    $.ajax({
        url: "/in-activar-articulo-lotaip",
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
                    window.location='/articles-lotaip';
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