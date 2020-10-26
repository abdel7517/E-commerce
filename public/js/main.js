let mainImage = document.getElementById('imageMain');

function changeMainImage(value) {
    mainImage.src = value;
    console.log(value);

}


// facture
var select = document.getElementsByTagName("ul")[3].children;
var texte = document.getElementsByClassName("current")[0].textContent;
rowLivraison = document.getElementById("livraison");



for (i = 0; i < select.length; i++) {
    content = select.item(i).textContent;
    select.item(i).addEventListener('click', (e) => {
        total = document.getElementById('total');
        if (e.target.textContent == "Récupération en boutique Rue Marcel Dassault 93140 Bondy") {
            rowLivraison.classList.add("recup");
            previousTotal = total.textContent;
            let stringToFloat = parseFloat(previousTotal);
            let atualPrice = stringToFloat - 90;
            actualTotal = atualPrice.toFixed(2);
            total.innerHTML = actualTotal;
        } else {
            rowLivraison.classList.remove("recup");
            previousTotal = total.textContent;
            let stringToFloat = parseFloat(previousTotal);
            let atualPrice = stringToFloat + 90;
            actualTotal = atualPrice.toFixed(2);
            total.innerHTML = actualTotal;

        }
    })
};

// select.item(0).addEventListener('click', (e) => {
//     console.log(e.target.textContent);
// })