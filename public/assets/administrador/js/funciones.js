function solonumeros(e){
    key=e.keyCode || e.which;
    teclado=String.fromCharCode(key);
    numeros="0123456789";
    especiales="8-37-38-40-41-45-46";//array
    teclado_especial=false;
    for(var i in especiales)
    {
        if(key==especiales[i])
        {
          teclado_especial=true;
        }
    }
    if(numeros.indexOf(teclado)==-1 &&!teclado_especial){
        return false;
    }
}

function validarEmail(valor) {
    emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    //Se muestra un texto a modo de ejemplo, luego va a ser un icono
    if (emailRegex.test(valor)) {
        //console.log("DIRECCION VALIDA");
        return 1;
    } else {
        //console.log("DIRECCION INVALIDA");
        return 0;
    }
}

function valideKey(evt){	
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code == 40 || code==41 || code==45 ){
        return true;
    }else if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}

function soloLetras(e){
  var key = e.keyCode || e.which,
  tecla = String.fromCharCode(key).toLowerCase(),
  letras= "áéíóúabcdefghijklmnñopqrstuvwxyz",
  especiales = [8, 32, 37, 38, 39, 46, 164],
  tecla_especial = false;

  for(var i in especiales){
      if(key == especiales[i]){
          tecla_especial= true;
          break;
      }
  }

  if(letras.indexOf(tecla) == -1 && !tecla_especial){
      return false;
  }
}

function utf8_to_b64( str ) {
  return window.btoa(unescape(encodeURIComponent( str )));
}

function base64ToUtf8Numbers(base64) {
    // Decodificar de base64
    const decoded = atob(base64);
    
    // Convertir el resultado a un arreglo de bytes (UTF-8)
    const bytes = Array.from(decoded).map(char => char.charCodeAt(0));
    return bytes;
}

function base64ToNumber(base64) {
    const decoded = atob(base64);  // decodifica a carácter
    return parseInt(decoded, 10);  // lo convierte en número entero
}


function fechaActual(){
  var hoy= new Date();
  var dia= hoy.getDate();
  var mes= hoy.getMonth()+1;
  var anio= hoy.getFullYear();

  var ndia='', nmes='';
  if(dia.toString().length==1){
     ndia= "0"+dia;
  }else{
     ndia= dia;
  }

  if(mes.toString().length==1){
     nmes= "0"+mes;
  }else{
     nmes= mes;
  }

  return anio+"-"+nmes+"-"+ndia;
}

function isNum(val){
  return !isNaN(val)
}

function utf8ToBase64_moderno(str) {
    let encoder = new TextEncoder();
    let bytes = encoder.encode(str);
    let binary = String.fromCharCode(...bytes);
    return btoa(binary);
}

function scrollTablaAlFinal(idTabla) {
  const tabla = document.getElementById(idTabla);
  if (tabla) {
    const tbody = tabla.querySelector('tbody');
    if (tbody) {
      tbody.scrollTop = tbody.scrollHeight;
    }
  }
}

function scrollToUltimaFila(tablaId) {
  const tabla = document.getElementById(tablaId);
  if (!tabla) {
    //console.error("Tabla no encontrada con ID: " + tablaId);
    return;
  }

  const filas = tabla.getElementsByTagName('tr');
  if (filas.length === 0) {
      console.warn("La tabla no contiene filas.");
      return;
  }
  const ultimaFila = filas[filas.length - 1];
  const contenedor = tabla.parentElement; // Asume que el contenedor es el padre de la tabla

    //Scroll suave
    ultimaFila.scrollIntoView({behavior: "smooth", block: "end"});



  // Opcional, desplazamiento directo
  //ultimaFila.scrollIntoView(); // Desplaza la fila visible
  //window.scrollTo(0, tabla.scrollHeight); // Desplaza toda la ventana
}