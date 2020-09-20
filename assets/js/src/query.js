var datainput = new DataInput();
var utils = new Utils();
var localization = new Localization();
var synonim = new Synonim("search");
var ac_synonim = new AutocompleteSynonim("search");
var ac = new Autocomplete("search");
var search = new Search();

$(document).click(function(e){
    var target = $(e.target);
    
    if (
            !target.is('.autocomplete-container') && 
            !target.is('.autocomplete-item') &&
            !target.is('.autocomplete-icon') &&
            !target.is('.autocomplete-item-query') && 
            !target.is('.autocomplete-matched') &&
            !target.is('.synonim-container') && 
            !target.is('.synonim-item') &&
            !target.is('.synonim-icon') &&
            !target.is('.synonim-item-query') && 
            !target.is('.synonim-matched')
    ){
        ac.hide();
    }
});

function Search(){
    this.geoloc = {};
    this.defaults = {};
    
    this.init = function(){
        initAutocomplete();
        localization.init();
        
        $.post("/api/search/getempty.json", null, function (response){
            search.defaults.search = response;

            for (var key in response){
                if (response[key] != null){
                    switch (key) {
                        case "country":
                            search.geoloc.country = response[key];
                        break;
                        case "city":
                            search.geoloc.city = response[key];
                            ac.getCityLocales(response[key]);
                            search.current_city = response[key];
                        break;
                        case "lat":
                            search.geoloc.lat = response[key];
                        break;
                        case "lng":
                            search.geoloc.lng = response[key];
                        break;
                        case "street":
                            search.geoloc.street = response[key];
                        break;
                    }
                }
            }
        });
        
        datainput.initPrice();
        datainput.initUpdated();
        datainput.initRooms();
        datainput.initSize();
        datainput.initSpecial();
        placeDetailsByPlaceId($('#country_field'), service_country, $('#country'));
        placeDetailsByPlaceId($('#city'), service_city, $('#locality'));
        streetDetailsByPlaceId();
        
        $('#edit_field_updated_modal input.from').datepicker({dateFormat: "dd/mm/yy"});
        $('#edit_field_updated_modal input.to').datepicker({dateFormat: "dd/mm/yy"});
    };
    
    this.checkCityExisting = function(){
        if ($('#locality').val().trim().length === 0){
            $('#route_input').attr("disabled");
        }
    };
    
}