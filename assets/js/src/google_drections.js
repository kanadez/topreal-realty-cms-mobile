/**
 * Created by Владимир on 21.02.2018.
 */

function DcDirection(map_id, geolocation){
    _dc_direction=this;

    this.me=new google.maps.LatLng(geolocation.lat, geolocation.lng);
    this.map={};
    this.service=new google.maps.DirectionsService();
    this.display=new google.maps.DirectionsRenderer();



    this.init=function (map_id) {
        this.loadMyLoc();
        this.map = new google.maps.Map(document.getElementById(map_id), {
            zoom: 4,
            center: this.me
        });
        this.display.setMap(this.map);
    };

    this.loadMyLoc=function () {
        //navigator.geolocation.getCurrentPosition(function (position) {
        //    _dc_direction.me=new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        //})
    };

    this.routeFromMe=function (destination) {
        var request={
            origin: this.me,
            destination: destination,
            travelMode: 'DRIVING'
        };
        this.service.route(request, function(result, status) {
            if (status == 'OK') {
                _dc_direction.display.setDirections(result);
            }
        });
    };

    this.init(map_id);
}
