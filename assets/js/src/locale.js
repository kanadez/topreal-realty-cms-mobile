/**
 * Created by Darkcooder on 06.03.2018.
 */
var locale;
$.post("/api/defaults/getlocale.json", {}, function (response){
    locale=response;
    $('#locale_select').val(locale);
    
    setArabic();
});

function setArabic(){
    if (locale == "he" || locale == "ar" || locale == "fa"){
        $('.search_form i.fa-pencil').css("float", "left");
        $('li.own_icon').addClass("own_icon_arabic");
        $('li.own_icon > a').css("margin-left", "0px");
        $('#add_event_notification_period_input + label').css("margin", "0 14px -2px 2px");
    }
}

$('#locale_select').change(function () {
    locale=$('#locale_select').val();
    //$.cookie("locale", locale);
    if (locale.length == 2){
        utils.setCookie("locale", locale, {expires: 315360000});
        $.post("/api/defaults/set.json",{
            parameter: "locale",
            value: locale
        },function (response){

            location.reload();
        });
    }
});

