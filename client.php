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
include(dirname(__FILE__).'/classes/Doc.class.php');
include(dirname(__FILE__).'/classes/Authorized.only.php');

include(dirname(__FILE__).'/classes/Contour.class.php');
include(dirname(__FILE__).'/classes/Client.class.php');
include(dirname(__FILE__).'/classes/Property.data.php');
include(dirname(__FILE__).'/classes/Client.data.php');

//include(dirname(__FILE__) . '/views/fields/header.php');
include(dirname(__FILE__) . '/views/PageView.class.php');
include(dirname(__FILE__) . '/views/fields/Get.fields.php');
include(dirname(__FILE__) . '/views/fields/Bars.fields.php');
include(dirname(__FILE__) . '/views/forms/Client.form.php');
include(dirname(__FILE__) . '/views/lang.php');

OnlyForAuthorized();

$subject_type="client";

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
$contour=new Contour;
//var_dump($contour);

$defaults=new Defaults;
$my_defaults=$defaults->getSearch();

$client=new Client();

$agentName=$user->getMyAgentName(($user->getMyId()));

$ynFlag=[lang('yes'), lang('no'), "   "];

$data=(object)[];
$error=null;
$fatal_error=false;

User::setSeenMobile();

if (isset($_REQUEST['id'])) $data = (object) $client->get($_REQUEST['id']);
else {
    $error = lang('client_id_undefined');
    $fatal_error=true;
}

if(isset($data->error)) {
    $error = $data->error['description'];
    $fatal_error=true;
}
if(isset($_REQUEST['errorMessage'])) $error = $_REQUEST['errorMessage'];
if(isset($_REQUEST['search_id']))$data->search_id=$_REQUEST['search_id'];

//HeaderView();
$pageView=new PageView();
$pageView->name=lang('header_title_client');

$pageView->id=$data->id;
$pageView->short="client";
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
SmsBar();
ToCalModalBar();
SmsOkBar();
ModalBar(lang('phone_is_calling_now'), "call_ok_bar");
if($error!=null) ErrorBar($error);
if($fatal_error) exit();
ClientViewBar($data);
FormBar::begin();
CalendarEventSuccessBar();
CopyToStockBar($data);
LastPropertyBar($data);
ClientForm();
FormBar::end();
$pageView->end();
?>

