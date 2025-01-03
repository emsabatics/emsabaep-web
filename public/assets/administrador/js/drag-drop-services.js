let fileInput = document.getElementById("file");
let imageContainer = document.getElementById("images");
let numOfFIles = document.getElementById("num-of-files");

let fileInputIcon = document.getElementById("fileIcon");
let imageContainerIcon = document.getElementById("images-icon");
let numOfFIlesIcon = document.getElementById("num-of-files-icon");

const findT= /pdf/;
const findV= "video/mp4";
const findSvg= "image/svg+xml";

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
        figCap.style.cssText="font-size: 17px;";
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

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function previewIcon(){
    imageContainerIcon.innerHTML="";

    if(fileInputIcon.files.length==1){
        numOfFIlesIcon.textContent = `${fileInputIcon.files.length} Archivo Seleccionado`;
    }else{
        numOfFIlesIcon.textContent = `${fileInputIcon.files.length} Archivos Seleccionados`;
    }

    for(i of fileInputIcon.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figCap.style.cssText="font-size: 17px;";
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
                img.setAttribute("style", "width: 50%;height: auto;");
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imageContainerIcon.appendChild(figure);
        reader.readAsDataURL(i);
    }
}


let fileInputEdit = document.getElementById("fileImgEdit");
let imageContainerEdit = document.getElementById("imagesService");
let numOfFIlesEdit = document.getElementById("num-of-files-service");

let fileInputIconEdit = document.getElementById("fileIconEdit");
let imageContainerIconEdit = document.getElementById("images-icon-edit");
let numOfFIlesIconEdit = document.getElementById("num-of-files-icon-edit");

function previewImgEdit(){
    imageContainerEdit.innerHTML="";

    if(fileInputEdit.files.length==1){
        numOfFIlesEdit.textContent = `${fileInputEdit.files.length} Archivo Seleccionado`;
    }else{
        numOfFIlesEdit.textContent = `${fileInputEdit.files.length} Archivos Seleccionados`;
    }

    for(i of fileInputEdit.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figCap.style.cssText="font-size: 17px;";
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

        imageContainerEdit.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function previewIconEdit(){
    imageContainerIconEdit.innerHTML="";

    if(fileInputIconEdit.files.length==1){
        numOfFIlesIconEdit.textContent = `${fileInputIconEdit.files.length} Archivo Seleccionado`;
    }else{
        numOfFIlesIconEdit.textContent = `${fileInputIconEdit.files.length} Archivos Seleccionados`;
    }

    for(i of fileInputIconEdit.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figCap.style.cssText="font-size: 17px;";
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
                img.setAttribute("style", "width: 50%;height: auto;");
                /*let span = document.createElement("span");
                span.setAttribute("class", "span-img");
                span.innerHTML="&times;";*/
                figure.insertBefore(img, figCap);
                //figure.insertBefore(span,img);
            }
        }

        imageContainerIconEdit.appendChild(figure);
        reader.readAsDataURL(i);
    }
}