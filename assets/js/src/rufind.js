/**
 * Created by User on 07.03.2018.
 */
function fru(phrase){
    $.post("/api/localization/ru.json",{
        phrase: phrase
    }, function(request){
        console.log (request);
    })}