<?php

/*ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);*/

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

include(dirname(__FILE__).'/classes/GoogleAC.class.php');
include(dirname(__FILE__).'/classes/Stock.class.php');
include(dirname(__FILE__).'/classes/Utils.class.php');
//include(dirname(__FILE__).'/classes/Doc.class.php');
include(dirname(__FILE__).'/classes/Authorized.only.php');

include(dirname(__FILE__).'/classes/Search.class.php');
include(dirname(__FILE__).'/classes/PropertyEvent.class.php');
include(dirname(__FILE__).'/classes/Property.class.php');
include(dirname(__FILE__).'/classes/Property.data.php');
include(dirname(__FILE__) . '/views/lang.php');
//include(dirname(__FILE__) . '/views/fields/header.php');
include(dirname(__FILE__) . '/views/PageView.class.php');
include(dirname(__FILE__) . '/views/fields/Get.fields.php');
include(dirname(__FILE__) . '/views/fields/Bars.fields.php');
include(dirname(__FILE__) . '/views/forms/Property.form.php');


OnlyForAuthorized();

$subject_type="property";

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
$stock = new Stock;
$googleac = new GoogleAC;
$search=new Search;

User::setSeenMobile();

$defaults=new Defaults;
$my_defaults=$defaults->getSearch();

$property_event = new PropertyEvent();
$property=new Property();

$agentName=$user->getMyAgentName(($user->getMyId()));

$ynFlag=[lang('yes'), lang('no'), "   "];

$data=(object)[];
$error=null;
$fatal_error=false;

if (isset($_REQUEST['iPropertyId'])) $data = (object) $property->get($_REQUEST['iPropertyId'], $_REQUEST['mode']);
else {
    $error = lang('property_id_undefined');
    $fatal_error=true;
}

if(isset($data->error)) {
    $error = $data->error['description'];
    $fatal_error=true;
}
if(isset($_REQUEST['errorMessage'])) $error = $_REQUEST['errorMessage'];
if(isset($_REQUEST['search_id'])){
    $data->search_id=$_REQUEST['search_id'];
    $query=$search->getIDsOnly($_REQUEST['search_id'])["properties"];
    $current_index=0;
    foreach( $query as $k=>$prop) if($data->id == $prop->id) $current_index=$k;
    //$current_index=array_search($data->id, $query);
    $data->search=$query;
    $data->search_index=$current_index;
    if(isset($query[$current_index+1]))$data->next_id=$query[$current_index+1]->id;
    if(isset($query[$current_index-1]))$data->prev_id=$query[$current_index-1]->id;
}

//HeaderView();
$pageView=new PageView();
$pageView->name=lang('property_label');
if($_REQUEST['mode']=="view_stock") {
    $pageView->name=lang('stock');
    $pageView->blueHeader=true;
}
$pageView->id=$data->id;
$pageView->short="property";
$pageView->title=$pageView->name." ".$data->id;
$pageView->plugins=["photoswipe"];
if($data->last_updated) $pageView->title.=", ".date("d/m/Y", $data->last_updated );

global $googleac;
$pageView->JSData=(object)[
    "id"=>$data->id,
    "name"=>$data->name,
    "data"=>$data,
    "country"=>$data->country,
    "city"=>$data->city,
    "geo"=>(object)["lat"=>$my_defaults->lat, "lng"=>$my_defaults->lng],
    "street"=>json_decode($data->street)[0],
    "street_name"=>$googleac->getShortName($data->street),
    "photos"=>$data->photos,
    "docs"=>$data->docs,
    "mode"=>"look"
];

$pageView->begin();
    
     if($error!=null) ErrorBar($error);
     if($fatal_error) exit();
     PropertyViewBar($data);
        FormBar::begin();
            OptionsPropertyBar($data);
            CalendarEventSuccessBar();
            EventsBar($data);
            CopyToStockBar($data);
            LastPropertyBar($data);
            PropertyForm();
        FormBar::end();
    $pageView->end();
    SmsBar();
    ToCalModalBar();
    SmsOkBar();
    ModalBar(lang('phone_is_calling_now'), "call_ok_bar");
    AddEventBar();
?>

