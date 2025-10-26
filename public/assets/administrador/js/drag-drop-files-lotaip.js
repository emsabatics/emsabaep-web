let fileInputCCD = document.getElementById("fileCCD");
let imageContainerCD = document.getElementById("imagesCD");
let numOfFIlesCD = document.getElementById("num-of-filesCD");


let fileInputMD = document.getElementById("fileMD");
let imageContainerMD = document.getElementById("imagesMD");
let numOfFIlesMD = document.getElementById("num-of-filesMD");


let fileInputDD = document.getElementById("fileDD");
let imageContainerDD = document.getElementById("imagesDD");
let numOfFIlesDD = document.getElementById("num-of-filesDD");

const findCT= /pdf/;
const findformatCsv= "text/csv";

function viewpCCD(){
    imageContainerCD.innerHTML="";
    numOfFIlesCD.textContent = `${fileInputCCD.files.length} Archivo Seleccionado`;

    for(i of fileInputCCD.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findCT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findCT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findV)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-video-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findformatCsv)!='-1'){
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

        imageContainerCD.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function viewpMD(){
    imageContainerMD.innerHTML="";
    numOfFIlesMD.textContent = `${fileInputMD.files.length} Archivo Seleccionado`;

    for(i of fileInputMD.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findCT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findCT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findV)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-video-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findformatCsv)!='-1'){
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

        imageContainerMD.appendChild(figure);
        reader.readAsDataURL(i);
    }
}

function viewpDD(){
    imageContainerDD.innerHTML="";
    numOfFIlesDD.textContent = `${fileInputDD.files.length} Archivo Seleccionado`;

    for(i of fileInputDD.files){
        let reader = new FileReader();
        let figure = document.createElement("figure");
        let figCap = document.createElement("figcaption");

        //console.log(i.type.search(findCT));
        //console.log(i);

        figCap.innerHTML = i.name;
        figure.style.cssText="text-align: center;";
        figure.appendChild(figCap);
        if(i.type.search(findCT)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-pdf-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findV)!='-1'){
            let img = document.createElement("img");
            img.setAttribute("src", "/assets/administrador/img/icons/icon-video-color.svg");
            img.setAttribute("style", "width: 40%;");
            figure.insertBefore(img, figCap);
        }else if(i.type.search(findformatCsv)!='-1'){
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

        imageContainerDD.appendChild(figure);
        reader.readAsDataURL(i);
    }
}