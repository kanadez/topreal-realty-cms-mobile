<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 11.02.2018
 * Time: 16:06
 */


//include "Property.ru.php";


function ClientForm(){
    global $data, $property_form_data, $client_form_data, $currency_codes, $dimensions_data, $ynFlag, $googleac, $agentName, $contour;

    $room_types=['1'=>lang('rooms_label'), '2'=>lang('bedrooms_label')];

    $streets=[];

    foreach (json_decode($data->street) as $street) array_push($streets, $googleac->getShortName($street)->short_name);

    $email_tmpl=comma_list([
        $data->contact1, $data->contact2, $data->contact3, $data->contact4,
        "Клиент №".$data->id, $property_form_data["ascription"][$data->ascription],
        $data->price_from."-".$data->price_to." ".$currency_codes[$data->currency_id],  $data->rooms_from."-".$data->rooms_to." ".$room_types[$data->rooms_type],
        $data->home_size_from."-".$data->home_size_to." ".$dimensions_data['size'][$data->home_dims], $data->lot_size_from."-".$data->lot_size_to." ".$dimensions_data['size'][$data->lot_dims],
        $googleac->getShortName($data->city)->short_name, comma_list($streets), "Агент ".$agentName
    ]);

    $subject_tmpl="Карточка №".$data->id;

    $contours=[];
    $contours["0"]="Новый контур без названия";
    foreach($contour->getContoursList(null) as $ctr) $contours[$ctr->id]=$ctr->title;



    Field("select", "ascription", lang("ascription_label"), ["list"=>lang_array($property_form_data["ascription"])]);
    Field("select", "property_types", lang("property_short_label"), ["list"=>lang_array($property_form_data["property_type"]), "is_multiple" => true, "is_required"=>true]);
    Field("select", "status", lang('status_label'), ["list"=>lang_array($client_form_data['status'])]);
    TSFTField("price_from", "price_to", "currency_id", lang("price_label"), ["size"=>2, "list"=>$currency_codes, "th_split"=>true]);

    Field("geo", "country", "auto");
    Field("geo", "city", "auto");
    Row::begin();
    if($data->contour!=null) $gm=1; else $gm=0;
    SelectLabel("geo_mode", [lang('street'), lang('contour_option')], $gm);
    InlineField("m-geo", "street", "");
    Hidden("blank_input");
    Select("contour", $contours, $data->contour);
    Row::end();

    TSFTField("floor_from", "floor_to", null, lang('floor_label'), ["size"=>4]);

    Field("text", "floors_count", lang('floors_label'), ["size"=>4]);

    global $EDIT_FLAG;

    TSFTField("rooms_from", "rooms_to", "rooms_type", null, ["list"=>$room_types, "null_value"=>0, "select_is_label"=>1, "size"=>4]);

    //Row::begin();
    //InlineField("select", null, );
    //InlineField("text",  ": ", ["size"=>5]);
    //InlineField("text", " - ", ["size"=>5]);
    //Row::end();

    TSFTField("home_size_from", "home_size_to", "home_size_dims", lang("home_noregister_span"), ["size"=>2, "list"=>lang_array($property_form_data["dimension"])]);
    TSFTField("lot_size_from", "lot_size_to", "lot_size_dims", lang("lot_option"), ["size"=>2, "list"=>lang_array($property_form_data["dimension"])]);


    //Row::begin();
    Field("select", "no_ground_floor_flag", lang('no_ground_floor_option_label'), ["list"=>$ynFlag, "null_value"=>2]);
    Field("select", "no_last_floor_flag", lang('no_last_floor_option_lable'), ["list"=>$ynFlag, "null_value"=>2]);
    //Row::end();

    Row::begin();
    InlineField("select", "air_cond_flag", lang("air_cond_option_label"), ["list"=>$ynFlag, "null_value"=>2]);
    InlineField("select", "parking_flag", lang("parking_option_label"), ["list"=>$ynFlag, "null_value"=>2]);
    Row::end();

    Row::begin();
    InlineField("select", "elevator_flag", lang("elevator_option_label"), ["list"=>$ynFlag, "null_value"=>2]);
    InlineField("select", "furniture_flag", lang("furniture_label"), ["list"=>$ynFlag, "null_value"=>2]);
    Row::end();

    Field("select", "front_flag", lang('facade_option_label'), ["list"=>$ynFlag, "null_value"=>2]);

    Field("text", "age", lang("age_label"), ["size"=>4]);
    Field("date", "free_from", lang("free_from_label"));
    Field("text", "name", lang("name_label"));
    Field("phone", "contact1", lang("phone_short_label")." 1 ", ["is_required"=>true]);
    Field("phone", "contact2", lang("phone_short_label")." 2 ");
    Field("phone", "contact3", lang("phone_short_label")." 3 ");
    Field("phone", "contact4", lang("phone_short_label")." 4 ");
    Field("email", "email", "eMail: ", ['subject_tmpl'=>$subject_tmpl, 'message_tmpl'=>$email_tmpl]);

    Documents();

    Field("bigtext", "remarks_text", lang("quotes_title"), ["size"=>4, "bold_label"=>true]);
}