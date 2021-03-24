// filter mobile animation

let filterButton = document.getElementById('picto-filtre');
let filterContainer = document.getElementById('filter');

filterButton.addEventListener('click', function(){
    if(!filterContainer.classList.contains('none')){
        filterContainer.classList.add('none');
        filterContainer.style.display = 'none';
    }else{
        filterContainer.classList.remove('none');
        filterContainer.style.display = 'block';
    }
})