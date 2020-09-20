function DataInput(){
    this.setAscription = function(){
        var ascription = $('#edit_field_ascription_modal select.ascription').val().trim();
        var ascription_text = $('#edit_field_ascription_modal select.ascription > option:selected').text();

        $('#ascription select').val(ascription);
        $('#ascription .field_content').text(ascription_text);

        $('#edit_field_ascription_modal').modal("hide");
    };

    this.setCountry = function(){
        var country_text = $('#edit_field_country_modal #country').val().trim();

        if (country_text.length > 0){
            $('#country_field input').val(search.geoloc.country);
            $('#country_field .field_content').text(country_text);
            $('form#special_search input.country').val(search.geoloc.country);
        }
        else{
            $('#country_field input').val("");
            $('#country_field .field_content').text("");
            $('form#special_search input.country').val("");
        }

        $('#edit_field_country_modal').modal("hide");
    };

    this.setLocality = function(){
        var city_text = $('#edit_field_locality_modal #locality').val().trim();

        if (city_text.length > 0){
            $('#city input').val(search.geoloc.city);
            $('#city .field_content').text(city_text);
            $('form#special_search input.city').val(search.geoloc.city);
        }
        else{
            $('#city input').val("");
            $('#city .field_content').text("");
            $('form#special_search input.city').val("");
        }

        $('#edit_field_locality_modal').modal("hide");
    };

    this.setStreet = function(){
        var street_text = $('#edit_field_route_modal #route_input').val().trim();

        if (street_text.length > 0){
            $('#street input').val(search.geoloc.street);
            $('#street .field_content').text(street_text);
        }
        else{
            search.geoloc.street = null;
            $('#street input').val("");
            $('#street .field_content').text("");
        }

        $('#edit_field_route_modal').modal("hide");
    };
    
    this.setContour = function(){
        var contour = $('#edit_field_contour_modal select.contour').val().trim();
        var contour_text = $('#edit_field_contour_modal select.contour > option:selected').text();

        $('#contour select').val(contour);
        
        if (contour.length > 0){
            $('#contour .field_content').text(contour_text);
        }
        else{
            $('#contour .field_content').text("");
        }
        
        $('#edit_field_contour_modal').modal("hide");
    };
    
    this.setProperty = function(){
        var property = $('#edit_field_property_modal select.property').val().trim();
        var property_text = $('#edit_field_property_modal select.property > option:selected').text();

        $('#property select').val(property);

        if (property.length > 0){
            $('#property .field_content').text(property_text);
        }
        else{
            $('#property .field_content').text("");
        }

        $('#edit_field_property_modal').modal("hide");
    };
    
    this.setStatus = function(){
        var status = $('#edit_field_status_modal select.status').val().trim();
        var status_text = $('#edit_field_status_modal select.status > option:selected').text();

        $('#status select').val(status);

        if (status.length > 0){
            $('#status .field_content').text(status_text);
        }
        else{
            $('#status .field_content').text("");
        }

        $('#edit_field_status_modal').modal("hide");
    };
    
    this.setPrice = function(){
        var from = $('#edit_field_price_modal input.from').val().trim();
        var to = $('#edit_field_price_modal input.to').val().trim();
        var currency = $('#edit_field_price_modal select.currency').val();
        var currency_text = $('#edit_field_price_modal select.currency > option:selected').text();

        $('#price input.from').val(utils.numberRemoveCommas(from));
        $('#price input.to').val(utils.numberRemoveCommas(to));
        $('#price select.currency').val(currency);

        if (from.length > 0 && to.length > 0 && currency.length > 0){
            $('#price .field_content').text(from+" - "+to+" "+currency_text);
        }
        else if (from.length > 0 && to.length === 0 && currency.length > 0){
            $('#price .field_content').text(from+" - ∞ "+currency_text);
        }
        else if (from.length === 0 && to.length > 0 && currency.length > 0){
            $('#price .field_content').text("0 - "+to+" "+currency_text);
        }
        else{
            $('#price .field_content').text("");
        }

        $('#edit_field_price_modal').modal("hide");
    };
    
    this.initPrice = function(){
        var from = $('#edit_field_price_modal input.from').val().trim();
        var to = $('#edit_field_price_modal input.to').val().trim();
        var currency = $('#edit_field_price_modal select.currency').val();
        var currency_text = $('#edit_field_price_modal select.currency > option:selected').text();

        if (from.length > 0 && to.length > 0 && currency.length > 0){
            $('#price .field_content').text(from+" - "+to+" "+currency_text);
        }
        else if (from.length > 0 && to.length === 0 && currency.length > 0){
            $('#price .field_content').text(from+" - ∞ "+currency_text);
        }
        else if (from.length === 0 && to.length > 0 && currency.length > 0){
            $('#price .field_content').text("0 - "+to+" "+currency_text);
        }
        else{
            $('#price .field_content').text("");
        }
    };

    this.setUpdated = function(){
        var from = $('#edit_field_updated_modal input.from').val().trim();
        var to = $('#edit_field_updated_modal input.to').val().trim();
        var from_timestamp = $('#edit_field_updated_modal input.from').datepicker("getDate")/1000;
        var to_timestamp = $('#edit_field_updated_modal input.to').datepicker("getDate")/1000;

        $('#updated input.from').val(from_timestamp > 0 ? from_timestamp : "");
        $('#updated input.to').val(to_timestamp > 0 ? to_timestamp : "");

        if (from.length > 0 && to.length > 0){
            $('#updated .field_content').text(from+" - "+to);
        }
        else if (from.length > 0 && to.length === 0){
            $('#updated .field_content').text(from+" - ∞");
        }
        else if (from.length === 0 && to.length > 0){
            $('#updated .field_content').text("∞ - "+to);
        }
        else{
            $('#updated .field_content').text("");
        }

        $('#edit_field_updated_modal').modal("hide");
    };
    
    this.initUpdated = function(){
        var from = utils.convertTimestampForDatepicker($('#updated input.from').val());
        var to = utils.convertTimestampForDatepicker($('#updated input.to').val());

        $('#edit_field_updated_modal input.from').val(from);
        $('#edit_field_updated_modal input.to').val(to);

        if (from.length > 0 && to.length > 0){
            $('#updated .field_content').text(from+" - "+to);
        }
        else if (from.length > 0 && to.length === 0){
            $('#updated .field_content').text(from+" - ∞");
        }
        else if (from.length === 0 && to.length > 0){
            $('#updated .field_content').text("∞ - "+to);
        }
        else{
            $('#updated .field_content').text("");
        }
    };

    this.initRooms = function(){
        var from = $('#edit_field_rooms_modal input.from').val().trim();
        var to = $('#edit_field_rooms_modal input.to').val().trim();

        if (from.length > 0 && to.length > 0){
            $('#rooms .field_content').text(from+" - "+to);
        }
        else if (from.length > 0 && to.length === 0){
            $('#rooms .field_content').text(from+" - ∞");
        }
        else if (from.length === 0 && to.length > 0){
            $('#rooms .field_content').text("0 - "+to);
        }
        else{
            $('#rooms .field_content').text("");
        }
    };
    
    this.setRooms = function(){
        var from = $('#edit_field_rooms_modal input.from').val().trim();
        var to = $('#edit_field_rooms_modal input.to').val().trim();

        $('#rooms input.from').val(from);
        $('#rooms input.to').val(to);

        if (from.length > 0 && to.length > 0){
            $('#rooms .field_content').text(from+" - "+to);
        }
        else if (from.length > 0 && to.length === 0){
            $('#rooms .field_content').text(from+" - ∞");
        }
        else if (from.length === 0 && to.length > 0){
            $('#rooms .field_content').text("0 - "+to);
        }
        else{
            $('#rooms .field_content').text("");
        }

        $('#edit_field_rooms_modal').modal("hide");
    };

    this.setSize = function(){
        var type = $('#edit_field_size_modal select.type').val();
        var type_text = $('#edit_field_size_modal select.type > option:selected').text();
        var from = $('#edit_field_size_modal input.from').val().trim();
        var to = $('#edit_field_size_modal input.to').val().trim();
        var dims = $('#edit_field_size_modal select.dimensions').val();
        var dims_text = $('#edit_field_size_modal select.dimensions > option:selected').text();

        $('#size input.from').val(from);
        $('#size input.to').val(to);
        $('#size select.dimensions').val(dims);
        $('#size input.type').val(type);

        if (from.length > 0 && to.length > 0 && dims.length > 0 && type.length > 0){
            $('#size .field_content').text(type_text+", "+from+" - "+to+" "+dims_text);
        }
        else if (from.length > 0 && to.length === 0 && dims.length > 0 && type.length > 0){
            $('#size .field_content').text(type_text+", "+from+" - ∞ "+dims_text);
        }
        else if (from.length === 0 && to.length > 0 && dims.length > 0 && type.length > 0){
            $('#size .field_content').text(type_text+", 0 - "+to+" "+dims_text);
        }
        else{
            $('#size .field_content').text("");
        }

        $('#edit_field_size_modal').modal("hide");
    };
    
    this.initSize = function(){
        var type = $('#edit_field_size_modal select.type').val();
        var type_text = $('#edit_field_size_modal select.type > option:selected').text();
        var from = $('#edit_field_size_modal input.from').val().trim();
        var to = $('#edit_field_size_modal input.to').val().trim();
        var dims = $('#edit_field_size_modal select.dimensions').val();
        var dims_text = $('#edit_field_size_modal select.dimensions > option:selected').text();

        if (from.length > 0 && to.length > 0 && dims.length > 0 && type.length > 0){
            $('#size .field_content').text(type_text+", "+from+" - "+to+" "+dims_text);
        }
        else if (from.length > 0 && to.length === 0 && dims.length > 0 && type.length > 0){
            $('#size .field_content').text(type_text+", "+from+" - ∞ "+dims_text);
        }
        else if (from.length === 0 && to.length > 0 && dims.length > 0 && type.length > 0){
            $('#size .field_content').text(type_text+", 0 - "+to+" "+dims_text);
        }
        else{
            $('#size .field_content').text("");
        }
    };

    this.setSpecial = function(){
        var special_by = $('#edit_field_special_modal select.special_by').val();
        var special_by_text = $('#edit_field_special_modal select.special_by > option:selected').text();
        var special_argument = $('#edit_field_special_modal input.special_argument').val().trim();

        $('#special_search select.special_by').val(special_by);
        $('#special_search input.special_argument').val(special_argument);

        if (special_by.length > 0 && special_argument.length > 0){
            $('#special_search .field_content').text(special_by_text+": "+special_argument);
            //$('#special_search_button').show();
            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        }
        else{
            $('#special_search .field_content').text("");
            //$('#special_search_button').hide();
        }
    };
    
    this.initSpecial = function(){
        var special_by = $('#edit_field_special_modal select.special_by').val();
        var special_by_text = $('#edit_field_special_modal select.special_by > option:selected').text();
        var special_argument = $('#edit_field_special_modal input.special_argument').val().trim();

        if (special_by.length > 0 && special_argument.length > 0){
            $('#special_search .field_content').text(special_by_text+": "+special_argument);
            //$('#special_search_button').show();
        }
        else{
            $('#special_search .field_content').text("");
            //$('#special_search_button').hide();
        }
    };

    this.onPriceFromKeyUp = function(){
        var price_value = utils.numberRemoveCommas($('#edit_field_price_modal input.from').val());
        $('#edit_field_price_modal input.from').val(utils.numberWithCommas(price_value));
    };
    
    this.onPriceToKeyUp = function(){
        var price_value = utils.numberRemoveCommas($('#edit_field_price_modal input.to').val());
        $('#edit_field_price_modal input.to').val(utils.numberWithCommas(price_value));
    };
    
    this.filterDate = function(input){
        var value = $(input).val().trim();
        $(input).val(value.toString().replace(/(?!\/)\D/g, ""));
    };
    
    this.filterRooms = function(input){
        var value = $(input).val().trim();
        $(input).val(value.toString().replace(/(?!\.)\D/g, ""));
    };
    
    this.eraseAll = function(){
        // street
        search.geoloc.street = null;
        $('#street input').val("");
        $('#street .field_content').text("");
        $('#edit_field_route_modal #route_input').val("")
        
        //contour
        $('#contour select').val("");
        $('#contour .field_content').text("");
        $('#edit_field_contour_modal select.contour').val("");
        
        //property
        $('#property select').val("");
        $('#property .field_content').text("");
        $('#edit_field_property_modal select.property').val("");
        
        //status
        $('#status select').val("");
        $('#status .field_content').text("");
        $('#edit_field_status_modal select.status').val("");
        
        //price
        $('#price input.from').val("");
        $('#price input.to').val("");
        $('#price select.currency').val("");
        $('#price .field_content').text("");
        $('#edit_field_price_modal input').val("");
        
        //updated
        $('#updated input.from').val("");
        $('#updated input.to').val("");
        $('#updated .field_content').text("");
        $('#edit_field_updated_modal input').val("");
        
        //rooms
        $('#rooms input.from').val("");
        $('#rooms input.to').val("");
        $('#rooms .field_content').text("");
        $('#edit_field_rooms_modal input').val("");
        
        //size
        $('#size input.from').val("");
        $('#size input.to').val("");
        $('#size select.dimensions').val("");
        $('#size input.type').val("");
        $('#size .field_content').text("");
        $('#edit_field_size_modal input').val("");
        
        //special
        $('#special_search select.special_by').val("");
        $('#special_search input.special_argument').val("");
        $('#special_search .field_content').text("");
        $('#edit_field_special_modal input, #edit_field_special_modal select').val("");
    };
}