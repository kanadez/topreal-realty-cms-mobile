<?php

include(dirname(__FILE__).'/settings.php');
include(dirname(__FILE__).'/classes/Database/TinyMVCDatabase.class.php');
include(dirname(__FILE__).'/classes/Database/TinyMVCDatabaseObject.class.php');
include(dirname(__FILE__).'/classes/Subscription.class.php');
include(dirname(__FILE__).'/classes/SubscriptionImprove.class.php');
include(dirname(__FILE__).'/classes/SubscriptionExpired.class.php');
include(dirname(__FILE__).'/classes/User.class.php');
include(dirname(__FILE__).'/classes/Currency.class.php');
include(dirname(__FILE__).'/classes/Dimensions.class.php');
include(dirname(__FILE__).'/classes/Dimensions.data.php');
include(dirname(__FILE__).'/classes/Login.class.php');
include(dirname(__FILE__).'/classes/Agency.class.php');
include(dirname(__FILE__).'/classes/AgencyORM.class.php');
include(dirname(__FILE__).'/classes/Defaults.class.php');
include(dirname(__FILE__).'/classes/Localization.class.php');
include(dirname(__FILE__).'/classes/GoogleAC.class.php');
include(dirname(__FILE__).'/classes/Stock.class.php');
include(dirname(__FILE__).'/classes/Utils.class.php');
//include(dirname(__FILE__).'/classes/Doc.class.php');
include(dirname(__FILE__).'/classes/Authorized.only.php');

include(dirname(__FILE__).'/classes/Property.class.php');
include(dirname(__FILE__).'/classes/Search.class.php');
include(dirname(__FILE__).'/lib/search.lib.php');
include(dirname(__FILE__).'/classes/Property.data.ru.php');
include(dirname(__FILE__) . '/views/lang.php');

//include(dirname(__FILE__) . '/views/fields/header.php');
include(dirname(__FILE__) . '/views/PageView.class.php');
//include(dirname(__FILE__) . '/views/fields/Get.fields.php');
include(dirname(__FILE__) . '/views/fields/Bars.fields.php');
//include(dirname(__FILE__) . '/views/forms/Property.form.php');

OnlyForAuthorized();

$subject_type="map";

$subscription = new Subscription;
$subscription_improve = new SubscriptionImprove;
$subscription_expired = new SubscriptionExpired;
$user = new User;
$login = new Login;
$agency = new Agency;
$currency = new Currency;
$dimensions = new Dimensions;
$utils = new Utils;
$defaults = new Defaults;
$localization = new Localization;
$stock = new Stock;
$googleac = new GoogleAC;
$search = new Search;

$defaults=new Defaults;
$my_defaults=$defaults->getSearch();

$property=new Property();
$response_list= new Search();

$agentName=$user->getMyAgentName(($user->getMyId()));

$data=(object)[];
$error=null;
$fatal_error=false;

$mode = filter_input(INPUT_GET, "mode");
$search_id = filter_input(INPUT_GET, "search_id");
$property_id = filter_input(INPUT_GET, "iPropertyId");

$is_route=false;
$is_list=false;

User::setSeenMobile();

$pageView=new PageView();
$pageView->initGoogleMap=true;

switch ($mode){
    case "route":
        $pageView->name=lang('route_title');
        $data=$property->get($property_id);
        $pageView->title=lang('route_title').": ".$googleac->getShortName($data->street)->short_name.", ".$data->house_number;
        $pageView->short="map";
        $pageView->plugins=["google_directions"];
        $pageView->JSData=(object)["data"=>$data, "mode"=>"route"];
        $is_route=true;
        break;

    case "list":
        $pageView->name=lang('property_search_title');
        $data=$response_list->get($search_id);
        $pageView->title= isset($search_id) ? lang('property_search_title').": ".parseConditions((array)$data) : "";
        $pageView->short="map";
        $pageView->plugins=["google_directions"];
        $pageView->JSData=(object)[
            "data" => $data, 
            "mode" => "list", 
            "id" => $search_id,
            "response" => isset($search_id) ? $search->query($search_id) : null
        ];
        $is_list=true;
        break;
}


//HeaderView();


$pageView->begin();
//SmsBar();
//ToCalModalBar();
if($error!=null) ErrorBar($error);
if($fatal_error) exit();
if($is_route)PropertyRouteHeaderBar($data);
if($is_list) PropertyListHeaderBar($data);
//PropertyViewBar($data);
//FormBar::begin();
GoogleMapBar();
if($is_list) PropertyListFooterBar($data);
ModalBar(lang('no_selected_caption'), "reduce_null_modal");
$pageView->end();
?>

