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
include(dirname(__FILE__).'/classes/Login.class.php');
include(dirname(__FILE__).'/classes/Agency.class.php');
include(dirname(__FILE__).'/classes/AgencyORM.class.php');
include(dirname(__FILE__).'/classes/Defaults.class.php');
include(dirname(__FILE__).'/classes/GoogleAC.class.php');
include(dirname(__FILE__).'/classes/ClientComparison.class.php');
include(dirname(__FILE__).'/classes/ClientComparisonList.class.php');
include(dirname(__FILE__).'/classes/PropertyComparison.class.php');
include(dirname(__FILE__).'/classes/PropertyComparisonList.class.php');
include(dirname(__FILE__).'/classes/Stock.class.php');
include(dirname(__FILE__).'/classes/Utils.class.php');
include(dirname(__FILE__).'/classes/Search.data.php');
include(dirname(__FILE__).'/classes/Search.class.php');
include(dirname(__FILE__).'/classes/Property.class.php');
include(dirname(__FILE__).'/classes/Client.class.php');
include(dirname(__FILE__) . '/views/fields/Bars.fields.php');
include(dirname(__FILE__).'/classes/ResponseList.class.php');
include(dirname(__FILE__).'/views/lang.php');

session_start();

if (!isset($_SESSION["user"])){ // если разлогинен
    header("Location: /");
}

$app_ver = "1.1.2";
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
$clientcomp = new ClientComparison;
$clientcomplist = new ClientComparisonList;
$propertycomp = new PropertyComparison;
$propertycomplist = new PropertyComparisonList;
$stock = new Stock;
$googleac = new GoogleAC;
$search = new Search;
$property = new Property;
$client = new Client;
$comparison_response = null;
$search_response = null;

User::setSeenMobile();

if (isset($_GET["id"]) && isset($_GET["subject"])){
    $subj = $_GET["subject"];
    
    if ($subj == "client"){
        $comparison_response = $property->getListByClient($_GET["id"], $_GET["mode"], isset($_GET["from"]) ? $_GET["from"] : "2_months");
        $search_response["clients"] = $comparison_response["data"];
    }
    else{
        $comparison_response = $client->getListByProperty($_GET["id"], $_GET["mode"], isset($_GET["from"]) ? $_GET["from"] : "2_months");
        $search_response["properties"] = $comparison_response["data"];
    }
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TopReal - <?=lang("comparison")?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Fonts  -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/simple-line-icons.css">
    <!-- Switchery -->
    <link rel="stylesheet" href="assets/plugins/switchery/switchery.min.css">
    <!-- CSS Animate -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!--Page Level CSS-->
    <link rel="stylesheet" href="assets/plugins/icheck/css/all.css?v=<?=$app_ver?>">
    <!-- Custom styles for this theme -->
    <link rel="stylesheet" href="assets/css/main.css?v=<?=$app_ver?>">
    <link rel="stylesheet" href="assets/css/response.css?v=<?=$app_ver?>">
    <!-- Feature detection -->
    <script src="assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/vendor/html5shiv.js"></script>
    <script src="assets/js/vendor/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <section id="main-wrapper" class="theme-default">
        <header id="header">
            <!--logo start-->
            <div class="brand col-xs-5 col-sm-5 col-md-5 col-lg-5 row">
                <a href="/query" class="logo">
                    <i class="icon-layers"></i>
                    <span>TOP</span>REAL</a>
            </div>
            <!--logo end-->
            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 language_select">
                <a href="/?logout" style="margin-left:16px;float:right;color:white;">
                    <i style="margin-top:9px;" class="fa fa-sign-out"></i>
                </a> 
                <select id="locale_select" style="max-width:110px;float:right;" class="form-control" title="Select language" data-toggle="tooltip">
                    <option value="en">English</option>
                    <option value="he">‫עברית‬</option>
                    <option value="fr">Français</option>
                    <option value="ru">Русский</option>
                    <option value="el">ελληνικά</option>
                    <option value="es">Español</option>
                    <option value="it">Italiano</option>
                    <option value="de">Deutsch</option>
                    <option value="pt">Português</option>
                    <option value="ar">العَرَبِيَّة</option>
                    <option value="da">Dansk</option>
                    <option value="nl">Nederlands</option>
                    <option value="hu">Magyar</option>
                    <option value="tr">Türkçe</option>
                    <option value="lt">Lietuvių kalba</option>
                    <option value="sr">Српски</option>
                    <option value="pl">Język polski</option>
                    <option value="fa">فارسی</option>
                    <option value="cs">Ceský jazyk</option>
                    <option value="ro">Limba română</option>
                    <option value="sv">Svenska</option>
                </select>
                
            </div>
        </header>
        <!--sidebar left end-->
        <!--main content start-->
        <section class="main-content-wrapper">
            <div class="pageheader">
                <?php if ($subj == "property"){ ?>
                <h3><?=lang('comparison_clients_list')?> <?=lang('for_property_span')?> <?=$_GET["id"]?></h3>
                <?php }elseif ($subj == "client"){ ?>
                <h3><?=lang('comparison_properties_list')?> <?=lang('for_client_span')?> <?=$_GET["id"]?></h3>
                <?php } ?>
            </div>
            <?php if(isset($_REQUEST['errorMessage'])) ErrorBar($_REQUEST['errorMessage']); ?>
            <section id="main-content" class="animated fadeInUp">
                <div class="row">
                    <form role="form" class="form-horizontal" action="comparison" method="GET">
                        <div class="col-sm-12">
                            <div class="response_form">
                                <div class="row">
                                    <div class="col-sm-5 col-xs-5 col-lg-5 col-md-5" style="white-space:nowrap;">
                                        <a href="#" class="btn btn-default" onclick="history.back()">↩</a>
                                        <span>
                                            <label class="control-label" style="margin-left: 10px; margin-right: 5px"><?=lang('founded_span') ?></label>
                                            <?php if ($subj == "property"){ ?>
                                            <span><?php echo count($search_response["properties"]); ?></span>
                                            <?php }elseif ($subj == "client"){ ?>
                                            <span><?php echo count($search_response["clients"]); ?></span>
                                            <?php } ?>
                                        </span>
                                    </div>
                                    <div class="col-sm-7 col-xs-7 col-lg-7 col-md-7" style="white-space:nowrap;">
                                        <button class="btn btn-success" style="float:right;margin-left:5px;padding:8px 8px;" type="submit"><i class="fa fa-search"></i></button>
                                        <select name="from" id="comparison_timestamp_offset" style="max-width:100px;float:right;" class="form-control">
                                            <option <?= $_GET["from"] == "today" ? "selected" : "" ?> value="today" title="">За сегодня</option>
                                            <option <?= $_GET["from"] == "week" ? "selected" : "" ?> value="week" title="">До недели</option>
                                            <option <?= $_GET["from"] == "2_weeks" ? "selected" : "" ?> value="2_weeks" title="">До 2 недель</option>
                                            <option <?= $_GET["from"] == "month" ? "selected" : "" ?> value="month" title="">До месяца</option>
                                            <option <?= $_GET["from"] == "2_months" ? "selected" : "" ?> <?= !isset($_GET["from"]) ? "selected" : "" ?> value="2_months" title="">До 2 мес.</option>
                                            <option <?= $_GET["from"] == "3_months" ? "selected" : "" ?> value="3_months" title="">До 3 мес.</option>
                                            <option <?= $_GET["from"] == "4_months" ? "selected" : "" ?> value="4_months" title="">До 4 мес.</option>
                                            <option <?= $_GET["from"] == "5_months" ? "selected" : "" ?> value="5_months" title="">До 5 мес.</option>
                                            <option <?= $_GET["from"] == "6_months" ? "selected" : "" ?> value="6_months" title="">До 6 мес.</option>
                                            <option <?= $_GET["from"] == "7_months" ? "selected" : "" ?> value="7_months" title="">До 7 мес.</option>
                                            <option <?= $_GET["from"] == "8_months" ? "selected" : "" ?> value="8_months" title="">До 8 мес.</option>
                                            <option <?= $_GET["from"] == "9_months" ? "selected" : "" ?> value="9_months" title="">До 9 мес.</option>
                                            <option <?= $_GET["from"] == "10_months" ? "selected" : "" ?> value="10_months" title="">До 10 мес.</option>
                                            <option <?= $_GET["from"] == "11_months" ? "selected" : "" ?> value="11_months" title="">До 11 мес.</option>
                                            <option <?= $_GET["from"] == "12_months" ? "selected" : "" ?> value="12_months" title="">До 12 мес.</option>
                                            <option <?= $_GET["from"] == "all_time" ? "selected" : "" ?> value="all_time" title="">Всё</option>
                                        </select>
                                        <input type="hidden" name="mode" value="new" />
                                        <input type="hidden" name="id" value="<?=$_GET["id"]?>" />
                                        <input type="hidden" name="subject" value="<?=$subj?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!--<div class="col-sm-3 col-xs-3 col-lg-3 col-md-3" style="padding-right:0; padding-top: 6px">
                                        <input id="mark_all_checkbox" class="icheck advopt regular" type="checkbox" name="0" />
                                        <label for="mark_all_checkbox"><?=lang('all_label')?></label>
                                    </div>-->
                                    
                                    
                                    <!--<div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                        <button id="" type="button" data-toggle="dropdown" aria-expanded="false" class="option-btn btn btn-default dropdown-toggle"><i class="fa fa-file-o"></i><span class="caret" style="direction: ltr;"> </button>

                                        <ul class="dropdown-menu" role="menu" style="left: auto">
                                            <li>
                                                <a href="propertyEdit?lastSearch=<?=$search_id?>'"><span><?=lang('new_property_label')?></span></a>
                                            </li>
                                            <li>
                                                <a href="clientEdit?lastSearch=<?=$search_id?>'"><span><?=lang('new_client_label')?></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                        <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                            <a id="reduce_button" href="javascript:0" class="option-btn btn btn-default "><i class="fa fa-scissors"></i></a>
                                        </div>-->
                                    <!--<div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                        <div id="output_group" style="" class="btn-group">
                                            <button type="button" class="option-btn btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-sort"></i>&nbsp;<span class="caret" style="direction: ltr;"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=date&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('date_label')?></span><i style="margin: 0 10px;" class="fa fa-arrow-down"></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=price&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('price_label')?></span><i style="margin: 0 10px;" class=""></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=street&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('street_label')?></span><i style="margin: 0 10px;" class=""></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=house&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('home_a')?></span><i style="margin: 0 10px;" class=""></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=property&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('property_label')?></span><i style="margin: 0 10px;" class=""></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=agent&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('agent_label')?></span><i style="margin: 0 10px;" class=""></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>-->
                                    <!--<div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                        <a href='/map?mode=list&search_id=<?=$search_id?>' class="option-btn btn btn-default"><i class="fa fa-globe"></i></a>
                                    </div>-->
                                    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
                                        <table id="response_table" class="list">
                                            <?php if ($subj == "property"){ ?>
                                                <?php for ($i = 0; $i < count($search_response["properties"]); $i++){
                                                    $checked="";
                                                    if(in_array($search_response["properties"][$i]->id, $selected)) $checked="checked";?>
                                                <tr style="<?php $i%2 == 0 ? "background-color:#f5f5f5" : ""; ?>" id="property_<?=$search_response["properties"][$i]->id?>_row" class="row_<?=$checked?>">
                                                    <!--<td>
                                                        <input id="property_<?=$search_response["properties"][$i]->id?>_check" class="icheck advopt regular" type="checkbox" name="0" <?=$checked?> />
                                                    </td>-->
                                                    <td class="data">
                                                        <a href="client?id=<?php echo $search_response["properties"][$i]->id; ?>&comparison=<?=$search_id?>">
                                                            <?php echo getResponseRow($search_response["properties"][$i], $search_response["street_googleac"]); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            <?php }elseif ($subj == "client"){ ?>
                                                <?php for ($i = 0; $i < count($search_response["clients"]); $i++){
                                                    $checked="";
                                                    if(in_array($search_response["clients"][$i]->id, $selected)) $checked="checked";?>
                                                <tr style="<?php $i%2 == 0 ? "background-color:#f5f5f5" : ""; ?>" id="property_<?=$search_response["clients"][$i]->id?>_row" class="row_<?=$checked?>">
                                                    <!--<td>
                                                        <input id="property_<?=$search_response["clients"][$i]->id?>_check" class="icheck advopt regular" type="checkbox" name="0" <?=$checked?> />
                                                    </td>-->
                                                    <td class="data">
                                                        <a href="property?iPropertyId=<?php echo $search_response["clients"][$i]->id; ?>&comparison=<?=$search_id?>">
                                                            <?php echo getResponseClientRow($search_response["clients"][$i], $search_response["street_googleac"]); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                                
                                <!--<div class="form-group">
                                    <div class="col-sm-12">
                                        <a href="query?id=<?php echo $search_id;?>" class="btn btn-success"><?=lang('change_search_label')?></a>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </section>

        <?php ModalBar(lang('no_selected_caption'), "reduce_null_modal");?>
        <!--main content end-->
    </section>
    
    <script src="assets/js/vendor/jquery-1.11.1.min.js"></script>
    <script src="/assets/js/src/utils.js"></script>

    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/navgoco/jquery.navgoco.min.js"></script>
    <script src="assets/plugins/icheck/js/icheck.min.js?v=<?=$app_ver?>"></script>
    <script src="assets/plugins/switchery/switchery.min.js"></script>
    <script src="assets/plugins/pace/pace.min.js"></script>
      <script src="assets/plugins/fullscreen/jquery.fullscreen-min.js"></script>
    <script src="assets/js/src/app.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/modal.js?v=<?=$app_ver?>"></script>
    <script>
        var PHPData=<?=json_encode($search_response)?>;
        PHPData.properties_checked=<?=json_encode($selected)?>
    </script>
    <script src="/assets/js/src/response.js?v=<?=$app_ver?>"></script>
    <script src="/assets/js/src/locale.js?v=<?=$app_ver?>"></script>
    <!--Page Level JS-->

    <script>
    $(document).ready(function() {

    });
    </script>
</body>

</html>

<?php 

function getResponseRow($data, $streets){
    global $query_form_data, $currency, $dimensions, $user;
    $response_row = "";
    
    // дача, 700,000 ILS, Sderot Oved Ben Ami 2, Застроено 34 м², 1 комнат, агент: Лидия, 054-9919898,  Обновлено: 23/01/2018 , карточка 63264
    $types = json_decode($data->property_types);
    
    for ($t = 0; $t < count($types); $t++){
        $key = $types[$t];
        $response_row .= ($t > 0 ? ", " : "").lang($query_form_data["property_type"][$key]);
    }
    
    $response_row .= ", ".number_format($data->price_from, 0, ".", " ");
    $response_row .= " - ".number_format($data->price_to, 0, ".", " ");
    $code = $data->currency_id;
    $response_row .= " ".$currency->getSymbolCode($code);
    $street_name = null;
    
    /*for ($s = 0; $s < count($streets["places"]); $s++){
        if ($streets["places"][$s]->placeid == $data->street){
            $street_name = $streets["places"][$s]->short_name;
            break;
        }
    }
    
    if ($street_name == null){
        for ($s = 0; $s < count($streets["places_synonims"]); $s++){
            if ($streets["places_synonims"][$s]->placeid == $data->street){
                $street_name = $streets["places_synonims"][$s]->short_name;
                break;
            }
        }
    }
    
    if ($street_name == null){
        for ($s = 0; $s < count($streets["synonims"]); $s++){
            if ($streets["synonims"][$s]->placeid == $data->street){
                $street_name = $streets["synonims"][$s]->short_name;
                break;
            }
        }
    }
    
    if ($street_name == null){
        $response_row .= ", ";
    }
    else{
        $response_row .= ", ".$street_name." ".$data->house_number.($data->flat_number == null ? "" : "/".$data->flat_number);
    }*/
    
    if ($data->home_size != null){
        $dims_code = $data->home_dims;
        $response_row .= ", ".lang("home_noregister_span")." ".$data->home_size." ".lang($dimensions->getSymbolCode($dims_code));
    }
    
    if ($data->rooms_count != null){
        $response_row .= ", ".$data->rooms_count." ".lang("rooms_noregister_span");
    }
    
    $response_row .= ", ".lang('agent_noregister_span')." ".$user->getMyAgentName($data->agent_id);
    $response_row .= ", ".$data->contact1;
    
    if ($data->contact2 != null){
        $response_row .= ", ".$data->contact2;
    }
    if ($data->contact3 != null){
        $response_row .= ", ".$data->contact3;
    }
    if ($data->contact4 != null){
        $response_row .= ", ".$data->contact4;
    }
    
    $response_row .= ", ".lang('lastupd_noregister_span').": ".date("d-m-Y", $data->last_updated != null ? $data->last_updated : $data->timestamp);
    $response_row .= ", ".lang('card_noregister_span')." ".$data->id;
    
    return $response_row;
}

function getResponseClientRow($data, $streets){
    global $query_form_data, $currency, $dimensions, $user;
    $response_row = "";
    
    // дача, 700,000 ILS, Sderot Oved Ben Ami 2, Застроено 34 м², 1 комнат, агент: Лидия, 054-9919898,  Обновлено: 23/01/2018 , карточка 63264
    $types = json_decode($data->types);
    
    for ($t = 0; $t < count($types); $t++){
        $key = $types[$t];
        $response_row .= ($t > 0 ? ", " : "").lang($query_form_data["property_type"][$key]);
    }
    
    $response_row .= ", ".number_format($data->price, 0, ".", " ");
    $code = $data->currency_id;
    $response_row .= " ".$currency->getSymbolCode($code);
    $street_name = null;
    
    for ($s = 0; $s < count($streets["places"]); $s++){
        if ($streets["places"][$s]->placeid == $data->street){
            $street_name = $streets["places"][$s]->short_name;
            break;
        }
    }
    
    if ($street_name == null){
        for ($s = 0; $s < count($streets["places_synonims"]); $s++){
            if ($streets["places_synonims"][$s]->placeid == $data->street){
                $street_name = $streets["places_synonims"][$s]->short_name;
                break;
            }
        }
    }
    
    if ($street_name == null){
        for ($s = 0; $s < count($streets["synonims"]); $s++){
            if ($streets["synonims"][$s]->placeid == $data->street){
                $street_name = $streets["synonims"][$s]->short_name;
                break;
            }
        }
    }
    
    if ($street_name == null){
        $response_row .= ", ";
    }
    else{
        $response_row .= ", ".$street_name." ".$data->house_number.($data->flat_number == null ? "" : "/".$data->flat_number);
    }
    
    if ($data->home_size != null){
        $dims_code = $data->home_dims;
        $response_row .= ", ".lang("home_noregister_span")." ".$data->home_size." ".lang($dimensions->getSymbolCode($dims_code));
    }
    
    if ($data->rooms_count != null){
        $response_row .= ", ".$data->rooms_count." ".lang("rooms_noregister_span");
    }
    
    $response_row .= ", ".lang('agent_noregister_span')." ".$user->getMyAgentName($data->agent_id);
    $response_row .= ", ".$data->contact1;
    
    if ($data->contact2 != null){
        $response_row .= ", ".$data->contact2;
    }
    if ($data->contact3 != null){
        $response_row .= ", ".$data->contact3;
    }
    if ($data->contact4 != null){
        $response_row .= ", ".$data->contact4;
    }
    
    $response_row .= ", ".lang('lastupd_noregister_span').": ".date("d-m-Y", $data->last_updated != null ? $data->last_updated : $data->timestamp);
    $response_row .= ", ".lang('card_noregister_span')." ".$data->id;
    
    return $response_row;
}

function parseConditions($conditions){
    global $googleac, $query_form_data, $currency;
    
    $response = "";
    //<!--Израиль, Нетания, центр города-юг, актуально, квартира/пентхаус, 1,000,000-1,200,000 ш.-->
    foreach ($conditions as $key => $value) {
        if ($value != NULL){
            switch ($key) {
                case "country":
                    $response .= $googleac->getShortName($value)->short_name.", ";
                break;
                case "city":
                    $response .= $googleac->getShortName($value)->short_name.", ";
                break;
                case "street":
                    $decoded = json_decode($value);
                    $response .= $googleac->getShortName($decoded[0])->short_name.", ";
                break;
                case "status":
                    $response .= $query_form_data["status"][$value].", ";
                break;
                case "property":
                    $decoded = json_decode($value);
                    
                    for ($d = 0; $d < count($decoded); $d++){
                        $key = $decoded[$d];
                        $response .= $query_form_data["property_type"][$key].", ";
                    }  
                break;
                case "price_from":
                    $from = $value;
                    $to = $conditions["price_to"] == NULL ? "∞" : $conditions["price_to"];
                    $curr = $currency->getSymbolCode($conditions["currency"]);
                    
                    $response .= $from." - ".$to." ".$curr;
                break;
                case "price_to":
                    if ($conditions["price_from"] != NULL){
                        continue;
                    }
                    
                    $to = $value;
                    $from = 0;
                    $curr = $currency->getSymbolCode($conditions["currency"]);
                    
                    $response .= $from." - ".$to." ".$curr;
                break;
            }
        }
    }
    
    return $response;
}

?>