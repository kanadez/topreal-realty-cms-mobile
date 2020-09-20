/**
 * Created by Владимир on 21.02.2018.
 */
var utils=new Utils();
var urlparser = new URLparser();
var map={default_lat: 32.0880577, default_lng: 34.7272052};

map.init=function () {
    var header_height=200;
    $("#map").css({height: $(window).height()-header_height+"px"});
};
