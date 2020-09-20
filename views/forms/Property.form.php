<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 11.02.2018
 * Time: 16:06
 */

//include "Property.ru.php";


function PropertyForm(){
    global $data, $property_form_data, $currency_codes, $dimensions_data, $ynFlag, $googleac, $agentName;

    $email_tmpl=comma_list([
        $data->contact1, $data->contact2, $data->contact3, $data->contact4,
        "Карточка №".$data->id, $property_form_data["ascription"][$data->ascription],
        $data->price." ".$currency_codes[$data->currency_id],  $data->rooms_count." Комнаты",
        $data->home_size." ".$dimensions_data['size'][$data->home_dims], $data->lot_size." ".$dimensions_data['size'][$data->lot_dims],
        $googleac->getShortName($data->city)->short_name, $googleac->getShortName($data->street)->short_name, $data->house_number, $data->flat_number,
        "Агент ".$agentName
    ]);

    $subject_tmpl="Карточка №".$data->id;



    Field("select", "ascription", lang("ascription_label"), ["list"=>lang_array($property_form_data["ascription"])]);
    Field("select", "status", lang('status_label'), ["list"=>lang_array($property_form_data['status'])]);
    Field("select", "types", lang("property_short_label"), ["list"=>lang_array($property_form_data["property_type"]), "is_multiple" => true, "is_required"=>true]);
    TSField("price", "currency_id", lang("price_label"), ["size"=>8, "list"=>$currency_codes, "is_required"=>true, "th_split"=>true]);

    Field("geo", "country", "auto");
    Field("geo", "city", "auto");
    Field("geo", "street", "auto");

    Row::begin();
    InlineField("text", "house_number", lang("home_a"), ["size"=>4]);
    InlineField("text", "flat_number", lang("flat_number_label"), ["size"=>4]);
    Row::end();

    Row::begin();
    InlineField("text", "floor_from", lang("floors_short_label"), ["size"=>4]);
    InlineField("text", "floors_count", " / ", ["size"=>4]);
    Row::end();

    Row::begin();
    InlineField("text", "rooms_count", lang("rooms_label"), ["size"=>5]);
    InlineField("text", "bedrooms_count", lang("bedrooms_label"), ["size"=>5]);
    InlineField("text", "bathrooms_count", lang("bathrooms"), ["size"=>5]);
    Row::end();

    TSField("home_size", "home_dims", lang("home_noregister_span"), ["size"=>2, "list"=>lang_array($property_form_data["dimension"])]);
    TSField("lot_size", "lot_dims", lang("lot_option"), ["size"=>2, "list"=>lang_array($property_form_data["dimension"])]);


    Row::begin();
    InlineField("select", "air_cond_flag", lang("air_cond_option_label"), ["list"=>$ynFlag, "null_value"=>2]);
    InlineField("select", "parking_flag", lang("parking_option_label"), ["list"=>$ynFlag, "null_value"=>2]);
    Row::end();

    Row::begin();
    InlineField("select", "elevator_flag", lang("elevator_option_label"), ["list"=>$ynFlag, "null_value"=>2]);
    InlineField("select", "furniture_flag",lang("furniture_label"), ["list"=>$ynFlag, "null_value"=>2]);
    Row::end();

    Field("select", "views", lang("view_label"), ["list"=>lang_array($property_form_data["view"]), "is_multiple" => true]);
    Field("select", "directions", lang("directions_label"), ["list"=>lang_array($property_form_data["direction"]), "is_multiple" => true]);
    Field("text", "age",  lang("age_label"), ["size"=>4]);
    Field("date", "free_from", lang("free_from_label"));
    Field("text", "name", lang("name_label"));
    Field("phone", "contact1", lang("phone_short_label")." 1 ", ["is_required"=>true]);
    Field("phone", "contact2", lang("phone_short_label")." 2 ");
    Field("phone", "contact3", lang("phone_short_label")." 3 ");
    Field("phone", "contact4", lang("phone_short_label")." 4 ");
    Field("email", "email", "eMail: ", ['subject_tmpl'=>$subject_tmpl, 'message_tmpl'=>$email_tmpl]);

    Attachments();

    Field("bigtext", "remarks_text", lang("quotes_title"), ["size"=>4, "bold_label"=>true]);
}