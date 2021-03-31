
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}


let myCalendar = new VanillaCalendar({
    selector: "#myCalendar",
    months: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
    shortWeekday: ['Lun', 'Mar', 'Mer', 'Jeu', 'Vend', 'Sam', 'Dim'],
    onSelect: (data, elem) => {
        let date = formatDate(data.date);
        window.location.href = "https://boutique.staffdeco.fr/admin/" + date;
        //  Mettre la date 
    }
})


