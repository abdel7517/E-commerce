
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

        var url = window.location.href;
        var newURL;
        let length = (url.match(/\//g)).length;
        if(length > 5){

            while( length > 5){
                var url = remove_character(url, (url).lastIndexOf("/") );
                var newURL = url;
                length = (url.match(/\//g)).length;
            }
            console.log( newURL + "/" + "_"+date);

            window.location.href = newURL + "/" +date;

        }
        else{
            window.location.href =  window.location.href + "/" +  date;

        }

        //  Mettre la date 
    }
})

function remove_character(str, char_pos) 
 {
  part1 = str.substring(0, char_pos);
  part2 = str.substring(char_pos + 1, str.length);
  return part1;
 }



let form = $('#form_code');
form.submit( function(e) {
    e.preventDefault();
    let code = $("#code").val();

    var url = window.location.href;
    var newURL;
    let length = (url.match(/\//g)).length;
    if(length > 4){

        while( length > 4){
            var url = remove_character(url, (url).lastIndexOf("/") );
            var newURL = url;
            length = (url.match(/\//g)).length;
        }
        console.log( newURL + "/" + "_"+code);

        window.location.href = newURL + "/" +code;

    }
    else{
        window.location.href =  window.location.href + "/" +  code;

    }

})