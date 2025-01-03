var currentValue = 0;
var isDataHistory= false;
var arrayHistoria= [];

/* FUNCION QUE GUARDA LA INFORMACIÓN */
function guardarHistoria(){
    var value=$('#summernote').summernote('code');
    value= value.trim();

    let longitud= value.length;
    //console.log(longitud);

    if(longitud < 2700){
        arrayHistoria.push(value.trim());
    }else if(longitud >= 2700 && longitud <= 4000){
        let divArray= Math.round(longitud / 2);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            start= i;
            end= start+divArray;
            arrayHistoria.push(value.substring(start, end).trim());
        }
        /*arrayHistoria.push(value.substring(0, divArray));
        arrayHistoria.push(value.substring(divArray, longitud));*/
    }else if(longitud > 4000 && longitud <= 6000){
        let divArray= Math.round(longitud / 3);
        let start=0;
        let end=0;
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayHistoria.push(value.substring(start, end).trim());
        }
    }else if(longitud > 6000){
        let divArray= Math.round(longitud / 4);
        let start=0;
        let end=0;
        //console.log(divArray);
        for(let i=0; i< longitud; i= i+divArray){
            //console.log(i, cont);
            start= i;
            end= start+divArray;
            arrayHistoria.push(value.substring(start, end).trim());
        }
    }
	//var x = value.find("font").remove();		
	/*console.log(arrayHistoria[0]);
    console.log(arrayHistoria[1]);
    console.log(arrayHistoria[2]);
    console.log(arrayHistoria[3]);
    console.log(arrayHistoria[4]);*/
    //console.log(arrayHistoria);
    
    let fileInput = document.getElementById("file");
    var radioValue = currentValue;
    var lengimg = fileInput.files.length;
    var token= $('#token').val();


    if (arrayHistoria.length==0) {
        $("#summernote").focus();
        swal("Ingrese la descripción de la Historia", "", "warning");
    } else if (radioValue == '') {
        swal("No ha seleccionado la posición de la imagen", "", "warning");
    } else if (lengimg == 0) {
        swal("No ha seleccionado alguna imagen para la historia", "", "warning");
    } else if (lengimg > 1) {
        swal("Debe elegir solo una fotografía", "", "warning");
    } else {
        //descripcion = descripcion.replace(/\n/g, "\n");

        /*console.log(arrayHistoria.length);
        console.log(radioValue);
        console.log(lengimg);*/

        $('#modalCargando').modal('show');

        var element = document.querySelector('.button-save-historia');
        element.setAttribute("disabled", "");
        element.style.pointerEvents = "none";

        var data = new FormData(formInHistoria);
        data.append("posicion", radioValue);
        data.append("descripcion", arrayHistoria.join("//"));
        data.append("longitud", arrayHistoria.length);
        setTimeout(() => {
            sendNuevaHistoria(data, token, "/registrar-historia", element);  
        }, 900);
    }

}

/* FUNCION QUE ENVIA LOS DATOS AL SERVIDOR PARA EL REGISTRO O ACTUALIZACION DE ESTRUCTURA */
function sendNuevaHistoria(data, token, url, el){
    var contentType = "application/x-www-form-urlencoded;charset=utf-8";
    var xr = new XMLHttpRequest();
    xr.open('POST', url, true);
    //xr.setRequestHeader('Content-Type', contentType);
    xr.setRequestHeader("X-CSRF-TOKEN", token);
    xr.onload = function(){
        $('#modalCargando').modal('hide');
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
                    el.removeAttribute("disabled");
                    el.style.removeProperty("pointer-events");
                    window.location='/historia';
                    
                },1500);
            } else if (myArr.resultado == false) {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "No se pudo Guardar",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if(myArr.file==false){
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "Ha ocurrido un error con la Imagen",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if (myArr.resultado == "noimagen") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "Formato de Imagen no válido",
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else if (myArr.resultado == "nocopy") {
                el.removeAttribute("disabled");
                el.style.removeProperty("pointer-events");
                Swal.fire({
                    title: "Error al copiar los archivos",
                    icon: "error",
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

/* FUNCION QUE OBTIENE EL VALOR ELEGIDO DE LOS RADIO BUTTON */
function handleClick(myRadio) {
    currentValue = myRadio.value;
}

function dividirCadena(cadenaADividir, separador) {
    var arrayDeCadenas = cadenaADividir.split(separador);
    /*document.write('<p>La cadena original es: "' + cadenaADividir + '"');
    document.write('<br>El separador es: "' + separador + '"');
    document.write(
      "<br>El array tiene " + arrayDeCadenas.length + " elementos: ",
    );
  
    for (var i = 0; i < arrayDeCadenas.length; i++) {
      document.write(arrayDeCadenas[i] + " / ");
    }*/

    console.log("El array tiene "+ arrayDeCadenas.length+" elementos.");
    for (var i = 0; i < arrayDeCadenas.length; i++) {
        console.log(" ["+[i]+"]: "+arrayDeCadenas[i] + " / ");
    }
}