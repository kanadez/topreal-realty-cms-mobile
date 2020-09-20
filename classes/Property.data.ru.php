<?php

define('PROJECT_HOME', dirname( __FILE__ ) );

//global $property_data;
global $property_from_data;

$property_data = array();
$property_form_data = array();

$property_form_data["ascription"] = array( // дефолтные опции формы поиска
    "продажа",
    "аренда"
);
$property_form_data["property_type"] = array(
    "квартира",
    "bauhaus",
    "дача",
    "торговый",
    "коттедж",
    "дуплекс",
    "дуплекс +",
    "ферма",
    "квартира с садом",
    "гостиница",
    "дом",
    "участок",
    "пентхауз",
    "офис",
    "другой",
    "часть квартиры",
    "квартира на крыше",
    "магазин",
    "студия",
    "вилла",
    "склад"
);
$property_form_data["status"] = array(
    "actual",
    "not_actual",
    "rented",
    "sold",
    "frozen",
    "shared",
    "tested",
    "brokeraged",
    "canceled",
    "auctioned"
);
/*$property_form_data["history_type"] = array(
    "Last updated",
    "In call",
    "Out call"
);*/
$property_form_data["view"] = array(
    "фасад",
    "задний двор",
    "торец",
    "море",
    "горы",
    "парк"
);
$property_form_data["dimension"] = array(
    "acre",
    "are",
    "hectare",
    "sq_ft",
    "sq_km",
    "sq_m",
    "sq_mi",
    "sq_yd",
    "dunam"
);
$property_form_data["direction"] = array(
    "Ю",
    "З",
    "С",
    "В"
);
$property_form_data["comparison_action"] = array(
    "brokering",
    "appointment",
    "property_inspection",
    "sms",
    "email",
    "phone_call"
);
$property_form_data["comparison_condition"] = array(
    "available",
    "interesting",
    "not_interesting",
    "problem",
    "offered",
    "signed",
    "dilled"
);