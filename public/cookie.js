// --------------- cookie ------------------- 
// --- Config --- //
var purecookieTitle = "Cookies."; // Title
var purecookieDesc = "En continuant la navigation, vous acceptez l'utilisation des cookies."; // Description
var purecookieLink = '<a href="https://www.staffdeco.fr/mentionsLegales.html" target="_blank">En savoir plus</a>'; // Cookiepolicy link
var purecookieButton = "Je comprends"; // Button text
// ---        --- //


function pureFadeIn(elem, display){
  var el = document.getElementById(elem);
  el.style.opacity = 0;
  el.style.display = display || "block";

  (function fade() {
    var val = parseFloat(el.style.opacity);
    if (!((val += .02) > 1)) {
      el.style.opacity = val;
      requestAnimationFrame(fade);
    }
  })();
};
function pureFadeOut(elem){
  var el = document.getElementById(elem);
  el.style.opacity = 1;

  (function fade() {
    if ((el.style.opacity -= .02) < 0) {
      el.style.display = "none";
    } else {
      requestAnimationFrame(fade);
    }
  })();
};

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}

function cookieConsent() {
  if (!getCookie('purecookieDismiss')) {
    document.body.innerHTML += '<div class="cookieConsentContainer" id="cookieConsentContainer"><div class="cookieTitle"><a>' + purecookieTitle + '</a></div><div class="cookieDesc"><p>' + purecookieDesc + ' ' + purecookieLink + '</p></div><div class="cookieButton"><a onClick="purecookieDismiss();">' + purecookieButton + '</a></div></div>';
	pureFadeIn("cookieConsentContainer");
  }
}

function purecookieDismiss() {
  setCookie('purecookieDismiss','1',7);
  pureFadeOut("cookieConsentContainer");
}

window.onload = function() { cookieConsent(); };


// --- search --- 

function searchToggle(obj, evt){
  var container = $(obj).closest('.search-wrapper');
  var search = $('#search');
  var nav = $('.nav-item');
  var logo = $('.logo');
  const mediaQuery = window.matchMedia('(max-width: 993px)');
  const mediaQuery2 = window.matchMedia('(max-width: 373px)');


      if(!container.hasClass('active')){

            if (!mediaQuery.matches) {
              nav.addClass('none');
            }
            if (mediaQuery2.matches) {
              search.css('position', 'relative');
              search.css('right', '1.5em');
            }
            container.addClass('active');
            evt.preventDefault();
          }

        else if(container.hasClass('active') && $(obj).closest('.input-holder').length == 0){

          if (!mediaQuery.matches) {
            nav.removeClass('none');
          }
          if (mediaQuery2.matches) {
            search.css('right', 'inherit');
          }
          container.removeClass('active');
          // clear input
          container.find('.search-input').val("");

      }
       
          if(container.find('.search-input').val() !== ""){

              window.location = "https://boutique.staffdeco.fr/product/search/"+ container.find('.search-input').val();
          }
     
      

}

// add caracteristic for product on new.html.twig 

function del(num){
  cara = $('#'+num);
  cara.remove();
}


sessionStorage.setItem('cara', 0 );

function add(){

  let caraLength =  sessionStorage.getItem('cara');
  let actualCara =  (Number(caraLength)+1);
  sessionStorage.setItem('cara', actualCara);
  let button = document.getElementById('cara');


  caraDiv = $('#cara');
  inputName = document.createElement("input");
  inputCara = document.createElement("input");
  div = document.createElement("div");
  div.setAttribute('id', actualCara);
  supp = document.createElement("div");
  supp.textContent = 'suprimer';
  supp.setAttribute('onClick', `del(${actualCara})` );



  inputName.setAttribute('name', 'name[]');
  inputName.setAttribute('placeholder', 'nom de la cara..');

  inputCara.setAttribute('name', 'cara[]');
  inputCara.setAttribute('placeholder', 'cara..');

  div.append(inputName);
  div.append(inputCara);
  div.append(supp);

  button.append(div);


}

// add caracteristic for category 
function addCategory(){

  categoryLength =  sessionStorage.getItem('category');
  actualCategory =  (Number(categoryLength)+1);
  sessionStorage.setItem('category', actualCategory);


  button = $('#otherCategory');
  inputFiltre = document.createElement("input");
  inputUnit = document.createElement("input");
  inputMin = document.createElement("input");
  inputMax = document.createElement("input");
  div = document.createElement("div");
  div.setAttribute('id', actualCategory);
  supp = document.createElement("div");
  supp.textContent = 'suprimer';
  supp.setAttribute('onClick', `delCategory(${actualCategory})` );



  inputFiltre.setAttribute('name', 'filtre[]');
  inputFiltre.setAttribute('placeholder', 'Intitulé du filtre');

  inputUnit.setAttribute('name', 'unit[]');
  inputUnit.setAttribute('placeholder', 'Unité (€, cm, m ..)');

  inputMin.setAttribute('name', 'min[]');
  inputMin.setAttribute('placeholder', 'Unité min');

  inputMax.setAttribute('name', 'max[]');
  inputMax.setAttribute('placeholder', 'Unité max');

  div.append(inputFiltre);
  div.append(inputUnit);
  div.append(inputMin);
  div.append(inputMax);
  div.append(supp);

  button.append(div);

}

function delCategory(num){
  cara = $('#'+num);
  cara.remove();
}

