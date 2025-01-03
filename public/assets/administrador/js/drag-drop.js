let fileInput = document.getElementById("file");
let imageContainer = document.getElementById("images");
let numOfFIles = document.getElementById("num-of-files");

var valAnt=0;
var valAct=0;

function limpiarBefore(){
    if(valAnt!=0){
        for(var i=0; i<valAnt; i++){
            var element = document.getElementById("liImg"+(i+1));
            if(element){
                element.remove();
            }
        }
    }
    valAnt=valAct;
}

function preview(){
    imageContainer.innerHTML="";
    if(fileInput.files.length==1){
        numOfFIles.textContent = `${fileInput.files.length} Archivo Seleccionado`;
    }else{
        numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;
    }

    for(i of fileInput.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");
        /*console.log(i); //propiedades del objeto*/
        figCap.innerHTML = i.name;
        figure.appendChild(figCap);
        reader.onload= () =>{

            //Initiate the JavaScript Image object.
            /*var image = new Image();

            //Set the Base64 string return from FileReader as source.
            image.src = reader.result;

            image.onload = function () {
                var height = this.height;
                var width = this.width;
                //console.log(height, width);
            }*/

            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            /*let span = document.createElement("span");
            span.setAttribute("class", "span-img");
            span.innerHTML="&times;";*/
            figure.insertBefore(img, figCap);
            //figure.insertBefore(span,img);
        }

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function previewBanner(){
    var contar=0;

    imageContainer.innerHTML="";
    if(fileInput.files.length==1){
        numOfFIles.textContent = `${fileInput.files.length} Archivo Seleccionado`;
    }else{
        numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;
    }

    valAct= fileInput.files.length;

    let ul_element= document.createElement("ul"); 
    //ul_element.className= "mailbox-attachments d-flex align-items-stretch clearfix";
    ul_element.className= "mailbox-attachments align-items-stretch clearfix";
    ul_element.style.cssText='padding: 15px; width: 90%;';

    limpiarBefore();

    for(i of fileInput.files){
        let reader = new FileReader();
        let li_element= document.createElement("li");
        li_element.setAttribute("id","liImg"+(contar+1));
        li_element.style.cssText='width: 270px !important;';

        /*CREATE SPAN INSIDE LI*/
        let span_element= document.createElement("span");
        span_element.className= "mailbox-attachment-icon has-img";

        var namefile= i.name;
        reader.onload= () =>{
            /*CREATE IMG INSIDE SPAN*/
            let img_element = document.createElement("img");
            img_element.style.cssText= "height: 130px !important;";
            img_element.setAttribute("src", reader.result);
            img_element.setAttribute("alt", namefile);

            span_element.appendChild(img_element); //INSERTAR ETIQUETA IMG DENTRO DEL SPAN
        }

        reader.readAsDataURL(i);

        let div_element= document.createElement("div");
        div_element.className= "mailbox-attachment-info";

        /*CREATE A INSIDE DIV*/
        let a_element= document.createElement("a");
        a_element.className= "mailbox-attachment-name";
        //a_element.innerHTML= "photo1.png";
        a_element.setAttribute("href","#");

        let i_element= document.createElement("i");
        i_element.className= "fas fa-camera mr-2";

        a_element.appendChild(i_element);
        let newText= document.createTextNode(namefile);
        a_element.appendChild(newText);

        div_element.appendChild(a_element); //INSERTAR ETIQUETA A DENTRO DEL DIV

        let span2_element= document.createElement("span");
        span2_element.className= "mailbox-attachment-size clearfix mt-1";

        /*CREATE A INSIDE DIV*/
        let a2_element= document.createElement("a");
        a2_element.className= "btn btn-default btn-sm float-right";
        a2_element.setAttribute("href","javascript:void(0)");
        a2_element.addEventListener('click', deleteLi, false);
        a2_element.myParam= contar;
        /*a2_element.addEventListener("click", function(){
            deleteLi((contar+1));
        }, false);*/

        let i2_element= document.createElement("i");
        i2_element.className= "fas fa-trash";

        a2_element.appendChild(i2_element);
        span2_element.appendChild(a2_element);

        div_element.appendChild(span2_element); //INSERTAR ETIQUETA SPAN DENTRO DEL DIV

        li_element.appendChild(span_element);
        li_element.appendChild(div_element);

        ul_element.appendChild(li_element);
        contar++;
    }

    imageContainer.appendChild(ul_element);
}

function deleteLi(event){
    //console.log(event.currentTarget.myParam);
    let i= event.currentTarget.myParam;

    let fileBuffer = Array.from(fileInput.files);
    fileBuffer.splice((i-1), 1);

    /** Code from: https://stackoverflow.com/a/47172409/8145428 */
    const dT = new ClipboardEvent('').clipboardData || // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
        new DataTransfer(); // specs compliant (as of March 2018 only Chrome)

    for (let file of fileBuffer) { dT.items.add(file); }
    fileInput.files = dT.files;
    //console.log(fileInput.files);
    if(fileInput.files.length==0){
        numOfFIles.textContent = `- Ningún archivo seleccionado -`;
    }else{
        numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;
    }
    $('#liImg'+(i+1)).remove();
}

function previewTurismo(){
    imageContainer.innerHTML="";
    if(fileInput.files.length==1){
        numOfFIles.textContent = `${fileInput.files.length} Archivo Seleccionado`;
    }else{
        numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;
    }

    for(i of fileInput.files){

        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");
        /*console.log(i); //propiedades del objeto*/
        figCap.innerHTML = i.name;
        figure.appendChild(figCap);
        reader.onload= () =>{
            /*var isValid= false;

            //Initiate the JavaScript Image object.
            var image = new Image();

            //Set the Base64 string return from FileReader as source.
            image.src = reader.result;

            image.onload = function () {
                var height = this.height;
                var width = this.width;
                console.log(height, width);
                if(height>1000 && width>1900){
                    isValid= true;
                    console.log("TRUE");
                }else{
                    isValid= false;
                    console.log("FALSE");
                }
            }*/

            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            /*let span = document.createElement("span");
            span.setAttribute("class", "span-img");
            span.innerHTML="&times;";*/
            figure.insertBefore(img, figCap);
            //figure.insertBefore(span,img);
        }

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}