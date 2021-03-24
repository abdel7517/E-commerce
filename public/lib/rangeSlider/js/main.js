
  
  
  // Get Nav URL
   function getNavUrl() {
       // Get URL
       return window.location.search.replace("?", "");
};

function getParameters(url) {

    // Params obj
    let params = [];
    // To lowercase
    url = url.toLowerCase();
    // To array
    url = url.split('&');
    // url.replace(/é|è|ê/g,"e");

    // Iterate over URL parameters array
    let length = url.length;
    for(let i=0; i<length; i++) {
        // Create prop
        let prop = url[i].slice(0, url[i].search('='));
        // Create Val
        let value = url[i].slice(url[i].search('=')).replace('=', '');
        // Params New Attr
        params[prop] = value;
    }
    return params;
};

// Call of getParameters
let para = getParameters(getNavUrl());
console.log(para);

// filtre on product/products.html.twig
filtres.forEach((filtre, index) => {
      let filtreName =  (filtre.filtre.replace(/\s/g, "_")).replace(/é|è|ê/g,"e").toLowerCase();
            
      if(para[filtreName] !== undefined ){
            proritySeparate = para[filtreName].split('-');
            min = proritySeparate[0];
            max = proritySeparate[1];

            $(".filtre-"+(index + 1)).ionRangeSlider({
                type: "double",
                grid : "true",
                min: filtre.min,
                max: filtre.max,
                from: min,
                to: max,
                postfix : " "+ filtre.unit,
                onStart: updateInputs,
                onChange: updateInputs,
                onFinish: updateInputs
            });
            initFiltreActivate(filtre.filtre, filtre.unit, min, max);
        }
        else{
            $(".filtre-"+(index + 1)).ionRangeSlider({
                type: "double",
                grid : "true",
                min: filtre.min,
                max: filtre.max,
                from: filtre.min,
                to: filtre.max,
                postfix : " "+ filtre.unit,
                onStart: updateInputs,
                onChange: updateInputs,
                onFinish: updateInputs
            });
        }
});

function initFiltreActivate(filtreName, filtreUnit, min, max){
    let divFiltres = $('#filtresActivate');
    let filtreDiv = document.createElement("div");
    let deleteButton = document.createElement('button');

    filtreDiv.innerHTML= filtreName + " (" + filtreUnit + ")" + " " + min + " - " + max;
    filtreDiv.setAttribute('class','filtreActivate');
    deleteButton.setAttribute('onClick', `deleteFiltre('${filtreName}')`);
    deleteButton.innerHTML = "Supprimer";
    deleteButton.setAttribute('class', 'btn btn-lg btn-primary');
    divFiltres.append(filtreDiv);
    divFiltres.append(deleteButton);
}

function search(id, filtreName){
    let filtreNotUsed = true;
    let $inputFrom = $("#input_from_"+ id);
    let $inputTo = $("#input_to_"+id);
    let url = window.location.href;
    let urlWhitoutGetPara = window.location.origin + window.location.pathname;
    let newUrl = "";
    let para = getParameters(getNavUrl());
    let alreadySearch = (url).indexOf("?");
    let filtreNameWithoutAccentAndSpace = (filtreName.replace(/\s/g, "_")).replace(/é|è|ê/g,"e").toLowerCase();
    
    
  if(alreadySearch > 0){

    for (let key in para) {
        if(key == filtreNameWithoutAccentAndSpace){
            para[key]= $inputFrom.val() + "-" +$inputTo.val();
            filtreNotUsed = false; 
        }
    }

    if(filtreNotUsed){
        para[filtreName] =  $inputFrom.val() + "-" +$inputTo.val();
    }

    i = 0;
    for (let key in para) {
        let keyWithoutAccentAndSpace = (key.replace(/\s/g, "_")).replace(/é|è|ê/g,"e");
        proritySeparate = para[key].split('-');
        min = proritySeparate[0];
        max = proritySeparate[1];

        if( i == 0){
            newUrl = urlWhitoutGetPara +  '?'+ keyWithoutAccentAndSpace + "="+min + "-" + max;
        }else{
            newUrl = newUrl  +  '&'+ keyWithoutAccentAndSpace + "="+min + "-" + max;
        }
        i++;
    }

    window.location = newUrl;

  }
  else{
      window.location = url + "?" + filtreNameWithoutAccentAndSpace + "=" + $inputFrom.val() + "-" + $inputTo.val();
  }

}




function updateInputs (data) {
    from = data.from;
    to = data.to;
    let $inputFrom = $("#input_from_"+ data.input.context.classList[1]);
    let $inputTo = $("#input_to_"+data.input.context.classList[1]);
    $inputFrom.prop("value", from);
    $inputTo.prop("value", to);
    
    
}

function deleteFiltre(filtreName){

    let url = window.location.href;
    let urlWhitoutGetPara = window.location.origin + window.location.pathname;
    let newUrl = "";
    let para = getParameters(getNavUrl());
    console.log(para);
    let filtreNameWithoutAccentAndSpace = (filtreName.replace(/\s/g, "_")).replace(/é|è|ê/g,"e").toLowerCase();

    
    

    for (let key in para) {
        if(key == filtreNameWithoutAccentAndSpace){
            delete para[key];
        }
    }


    i = 0;
    if(Object.keys(para).length > 0 ){
        for (let key in para) {
            proritySeparate = para[key].split('-');
            min = proritySeparate[0];
            max = proritySeparate[1];
    
                newUrl = urlWhitoutGetPara +  '?'+ key + "=" + min + "-" + max;
            
            console.log(i + key);
            console.log(newUrl);
            i++;
        }
        window.location = newUrl;
    }else{
        window.location = urlWhitoutGetPara;
    }


  
 
}