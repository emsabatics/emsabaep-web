var arrayHistoria= [];

/* FUNCION CARGAR HISTORIA */
function cargar_historia(inforHistoria){
    if(inforHistoria.length==0){
        $('#divHistoria').html("<p id='p-nodata-es' class='p-nodata-yet'>Sin especificar...</p>");
    }else{
        //console.log(inforHistoria);
        var lengjson= inforHistoria.length;
        let posiimagen=0;
        let nameimg='';
        //var myArr = JSON.parse(inforHistoria);
        $(inforHistoria).each(function(i,v){
            let texthistory='';
            //$('#idEstructura').val(v.id);
            //var descp= v.descripcion.replaceAll('//','<br>');
            //$('#divHistoria').html("<p class='p-data-full'>"+descp+"</p>");
            if(v.descripcion!=null){
                texthistory= v.descripcion;
                arrayHistoria.push({
                    'texto': texthistory,
                    'imagen': 'no'
                });
            }else{
                nameimg= v.imagen;
                let posiimgtexto= v.posicion;
                if(posiimgtexto=='inicio'){
                    posiimagen= 0;
                }else if(posiimgtexto=='end'){
                    posiimagen= lengjson;
                }
            }
        });

        drawHistoria(arrayHistoria, posiimagen, nameimg);
    }

    setTimeout(() => {
        $('#modalCargando').modal('hide');
    }, 1000);
}

/* FUNCION QUE DIBUJA LA HISTORIA CORRECTAMENTE */
function drawHistoria(array, posimg, imagen){
    let html="";
    var newInsert= {
        'texto': 'no',
        'imagen': imagen
    }

    const list1 = array.splice(0, posimg);
    const list2 = array;

    const appendedList = [...list1, newInsert, ...list2];

    for (var i in appendedList) {
        if(appendedList[i].imagen!='no'){
            html+="<div class='col-lg-12 no-data'>"+
                        "<div class='imgHistoria m-4'>"+
                            `<img src="/historia-img/${appendedList[i].imagen}" alt="${appendedList[i].imagen}">`+
                        "</div>"+
                    "</div>";
        }else{
            html+=appendedList[i].texto;
        }
    }

    $('#divHistoria').html(html);
}

//
function openInterfaceEdit(){
    window.location='/update-historia';
}