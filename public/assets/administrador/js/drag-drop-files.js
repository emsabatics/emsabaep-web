let fileInput = document.getElementById("file");
let imageContainer = document.getElementById("images");
let numOfFIles = document.getElementById("num-of-files");

const findT= /pdf/;
const findV= "video/mp4";
const findSvg= "image/svg+xml";
const findCsv= "text/csv";

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

        //console.log(i.type.search(findT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findV)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-video-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findCsv)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-csv.png");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else{
            reader.onload= () =>{
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                img.setAttribute("style", "width: 90%;height: auto;");
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function checkfile(sender) {
    var validExts = new Array(".xlsx", ".xls");
    var fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
      alert("Invalid file selected, valid files are of " +
               validExts.toString() + " types.");
      return false;
    }
    else return true;
}


let fileInputra = document.getElementById("filera");
let imgContRA = document.getElementById("imagesra");
let numOfFIlesra = document.getElementById("num-of-files-ra");

function previewopcional(){
    //console.log('ejemplo');
    imgContRA.innerHTML="";
    numOfFIlesra.textContent = `${fileInputra.files.length} Archivos Seleccionados`;

    for(i of fileInputra.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findT));

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            figure.insertBefore(img, figCap);
        }else{
            reader.onload= () =>{
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imgContRA.appendChild(figure);
        reader.readAsDataURL(i);
    }
}


/*--------------------------------------------------------*/
//EDITAR PAC
/*--------------------------------------------------------*/
let fileEInput = document.getElementById("fileEdit");
let imageContainerE = document.getElementById("imagesEdit");
let numOfFIlesE = document.getElementById("num-of-files-edit");

function previewEdit(){
    imageContainerE.innerHTML="";
    if(fileEInput.files.length==1){
        numOfFIlesE.textContent = `${fileEInput.files.length} Archivo Seleccionado`;
    }else{
        numOfFIlesE.textContent = `${fileEInput.files.length} Archivos Seleccionados`;
    }

    for(i of fileEInput.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findT));

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findV)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-video-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else{
            reader.onload= () =>{
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                img.setAttribute("style", "width: 90%;height: auto;");
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imageContainerE.appendChild(figure);
        reader.readAsDataURL(i);
    }
}


let fileInputEra = document.getElementById("fileEra");
let imgContRAEd = document.getElementById("imagesraE");
let numOfFIlesraE = document.getElementById("num-of-files-ra-ed");

function previewopcionalra(){
    imgContRAEd.innerHTML="";
    numOfFIlesraE.textContent = `${fileInputEra.files.length} Archivos Seleccionados`;

    for(i of fileInputEra.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findT));

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            figure.insertBefore(img, figCap);
        }else{
            reader.onload= () =>{
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imgContRAEd.appendChild(figure);
        reader.readAsDataURL(i);
    }
}


function previewMediosV(){
    contadorHash=0;
    imageContainer.innerHTML="";
    numOfFIles.textContent = `${fileInput.files.length} Archivos Seleccionados`;

    for(i of fileInput.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
            createButton(figure);
        }else if(i.type.search(findV)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-video-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
            createButton(figure);
        }else{
            reader.onload= () =>{
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                img.setAttribute("style", "width: 90%;height: auto;");
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function createButton(contenedor){
    contadorHash++;
    var input = document.createElement('input');//creo elemento input y le creo un salto de línea
    var salto = document.createElement('br');
    var divgroup= document.createElement('div');

    input.type = 'text';
    input.className="formEdit";
    input.id = "inputHash"+contadorHash;
    input.name = 'inputMedioV[]';
    input.placeholder = 'Ingrese un Título al documento';
    //input.style.width = '150px';
    input.style.cssText= 'width: 100%;';
    //input.setAttribute('disabled',''); // propiedad disabled
    input.autocomplete= "off";

    divgroup.append(input);
    contenedor.append(salto);//todo lo agrego al div de almacenar
    contenedor.append(divgroup);
}


function previewFileCuenta(){
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

        //console.log(i.type.search(findT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findV)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-video-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else{
            reader.onload= () =>{
                let img = document.createElement("img");
                img.setAttribute("src", reader.result);
                img.setAttribute("style", "width: 30%;height: auto;");
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}