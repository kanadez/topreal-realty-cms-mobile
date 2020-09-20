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
include(dirname(__FILE__).'/classes/Stock.class.php');
include(dirname(__FILE__).'/classes/Contour.class.php');
include(dirname(__FILE__).'/classes/Utils.class.php');
include(dirname(__FILE__).'/classes/Search.data.php');
include(dirname(__FILE__).'/classes/Search.class.php');
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
$stock = new Stock;
$contour = new Contour;
$googleac = new GoogleAC;
$search = new Search;
$response_list = new ResponseList;

$login_failed = false;
$search_id = null;
$list_id = null;
$search_fields = ["city", "street", "contour", "property", "status", "price_from", "price_to", "currency", "history_from", "history_to", "rooms_from", "rooms_to", "object_size_from", "object_size_to", "object_dimensions", "object_type", "parking", "elevator", "air_cond", "furniture"];

$flags = [
    "parking" => NULL,
    "elevator" => NULL,
    "air_cond" => NULL,
    "furniture" => NULL
];

$conditions=[];
$selected=[];

User::setSeenMobile();

if(isset($_REQUEST['search_id'])) {
    $conditions=(array) $response_list->get($_REQUEST['search_id']);
    $search_id=$_REQUEST['search_id'];
}
else $conditions=$_POST;

if(isset($_REQUEST['selected']))$selected=json_decode($search->getSelectedOnMap($_REQUEST['selected'])->data);

//var_dump($selected->data);
//exit;

//var_dump($conditions);



if (strlen($conditions["ascription"]) > 0 && !empty($conditions["country"])){
    $data = [
        "type" => 1,
        "ascription" => intval($conditions["ascription"]),
        "country" => strval($conditions["country"])
    ];
    
    for ($i = 0; $i < count($search_fields); $i++){
        $field = $search_fields[$i];
        
        if (strlen($conditions[$field]) > 0){
            switch ($field) {
                case "city":
                    $data[$field] = strval($conditions[$field]);
                break;
                case "street":
                    $data[$field] = json_encode([strval($conditions[$field])]);
                break;
                case "contour":
                    $data[$field] = intval($conditions[$field]);
                break;
                case "price_from":
                    $data[$field] = intval($conditions[$field]);
                    $data["currency"] = intval($conditions["currency"]);
                break;
                case "price_to":
                    $data[$field] = intval($conditions[$field]);
                    $data["currency"] = intval($conditions["currency"]);
                break;
                case "property":
                    $data[$field] = json_encode([strval($conditions[$field])]);
                break;
                case "status":
                    $data[$field] = json_encode([strval($conditions[$field])]);
                break;
                case "history_from":
                    $data["history_type"] = 0;
                    $data["history_from"] = intval($conditions[$field]);
                break;
                case "history_to":
                    $data["history_type"] = 0;
                    $data["history_to"] = intval($conditions[$field]);
                break;
                case "rooms_from":
                    $data["rooms_type"] = 1;
                    $data[$field] = strval($conditions[$field]);
                break;
                case "rooms_to":
                    $data["rooms_type"] = 1;
                    $data[$field] = strval($conditions[$field]);
                break;
                case "object_size_from":
                    $data["object_type"] = intval($conditions["object_type"]);
                    $data["object_dimensions"] = intval($conditions["object_dimensions"]);
                    $data[$field] = strval($conditions[$field]);
                break;
                case "object_size_to":
                    $data["object_type"] = intval($conditions["object_type"]);
                    $data["object_dimensions"] = intval($conditions["object_dimensions"]);
                    $data[$field] = strval($conditions[$field]);
                break;
                case "parking":
                    $flags["parking"] = 1;
                break;
                case "elevator":
                    $flags["elevator"] = 1;
                break;
                case "air_cond":
                    $flags["air_cond"] = 1;
                break;
                case "furniture":
                    $flags["furniture"] = 1;
                break;
            }
        }
        else{
            $data[$field] = NULL;
        }
    }
    if($stock->checkPayed()) $data['stock']=1;

        foreach ($flags as $key => $value){
        $data[$key] = $flags[$key];
    }
    
    //var_dump($data);
    
    if (strlen($conditions["id"]) > 0){
        $search_id = intval($conditions["id"]);
        $search->set($search_id, json_encode($data));
    }
    else{
        $tmp_search = $search->createTemporary();
        $search_id = $search->createNew($tmp_search, json_encode($data));
    }
}
else if (strlen($conditions["special_argument"]) > 0){
    $data["type"] = 2;
    $data["stock"] = 1;
    $data["country"] = strlen($conditions["country"]) > 0 ? $conditions["country"] : null;
    $data["city"] = strlen($conditions["city"]) > 0 ? $conditions["city"] : null;
    $data["special_by"] = intval($conditions["special_by"]);
    $data["special_argument"] = strval($conditions["special_argument"]);
    
    if (strlen($conditions["id"]) > 0){
        $search_id = intval($conditions["id"]);
        $search->set($search_id, json_encode($data));
    }
    else{
        $tmp_search = $search->createTemporary();
        $search_id = $search->createNew($tmp_search, json_encode($data));
    }
}

if (!isset($_GET["id"]) && !isset($_GET["list"]) && $search_id == null){
    header("Location: query");
}
elseif (isset($_GET["id"])){
    $search_id = $_GET["id"];
}
elseif (isset($_GET["list"])){
    $list_id = $_GET["list"];
}

if ($list_id == null){
    if (isset($_GET["sort"])){
        $data = [];
        $data["sort_by"] = $_GET["sort"];
        $data["sort_desc"] = $_GET["order"] == "asc" ? 1 : 0;
        $search->set($search_id, json_encode($data));
    }
    
    $search_response = $search->getIDsOnly($search_id);
    //$search_response = $search->query($search_id);
    //var_dump($search_response["conditions"]);
    //var_dump($search_response);
}
else{
    $list_data = $response_list->get($list_id);
    $search_response["properties"] = $list_data["data"];
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
    <title>TopReal - <?=lang('search_results') ?></title>
    <meta name="theme-color" content="#507299" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script src="https://use.fontawesome.com/a60baa35a1.js"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Fonts  -->
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
    <button onclick="topFunction()" class="btn btn-success" id="totop" title="Top"><i class="fa fa-arrow-up"></i></button>
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
                <select id="locale_select" style="max-width:75px;float:right;" class="form-control" title="Select language" data-toggle="tooltip">
                    <option value=""></option>
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
                <a href="#" onclick="response.showLegend()" style="margin-right:16px;float:right;color:white;">
                    <i style="margin-top:9px;" class="fa fa-question-circle"></i>
                </a> 
            </div>
        </header>
        <!--sidebar left end-->
        <!--main content start-->
        <section class="main-content-wrapper">
            <div class="pageheader">
                <?php if ($list_id == null) { ?>
                    <?php if ($search_response["conditions"]["title"] == null) { ?>
                    <h3><?=lang('my_last_search') ?>: <?php if ($search_id != null) echo parseConditions($search_response["conditions"]); ?></h3>
                    <?php }else{ ?>
                    <h3><?=lang('header_title_search') ?> "<?php echo $search_response["conditions"]["title"]; ?>"</h3>
                    <?php } ?>
                <?php }else{ ?>
                <h3><?=lang('header_title_list_search')?> "<?php echo $list_data["title"]; ?>"</h3>
                <?php } ?>
            </div>
            <?php if(isset($_REQUEST['errorMessage'])) ErrorBar($_REQUEST['errorMessage']); ?>
            <section id="main-content" style="padding-top:5px;" class="animated fadeInUp">
                <div class="row">
                    <form role="form" class="form-horizontal">
                        <div class="col-sm-12">
                            <div class="response_form">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
                                        <div>
                                            <span>
                                                <label class="control-label" style="margin-left: 10px; margin-right: 0px"><?=lang('founded_span') ?></label>
                                                <span><?php echo count($search_response["properties"])+count($search_response["clients"]); ?></span>
                                            </span>
                                            <span>
                                                <label class="control-label" style="margin-left: 10px; margin-right: 0px"><?=lang('marked_span') ?></label>
                                                <span id="search_entries_marked_span"><?=count($selected)?></span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group"> <div class="row" style="margin:5px 0;">
                                    <div class="col-sm-3 col-xs-3 col-lg-3 col-md-3" style="padding-right:0; padding-top: 6px">
                                        <input id="mark_all_checkbox" class="icheck advopt regular" type="checkbox" name="0" />
                                        <label for="mark_all_checkbox"><?=lang('all_label')?></label>
                                    </div>
                                    <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                        <a href="query?id=<?php echo $search_id;?>" style="color:white;" class="btn btn-success">↩</a>
                                    </div>
                                    <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                        <div class="btn-group">
                                            <button type="button" class="option-btn btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-wrench"></i><span class="caret" style="direction: ltr;"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a data-toggle="modal" data-target="#pre_contours_modal" href="#"><i class="fa fa-cog"></i>&nbsp;&nbsp;<?=lang('pre_contours')?></a>
                                                </li>
                                                <li>
                                                    <a onclick="showLoader()" href="/map?mode=list&search_id=<?= $search_id ?>&action=new_contour"><i class="fa fa-pencil"></i>&nbsp;&nbsp;<?=lang('draw_new_contour_button')?></a>
                                                </li>
                                                <li>
                                                    <a id="reduce_button" href="javascript:void(0)"><i class="fa fa-scissors"></i>&nbsp;&nbsp;<?=lang('reduce_button')?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                        <div id="output_group" style="" class="btn-group">
                                            <button type="button" class="option-btn btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-sort"></i><span class="caret" style="direction: ltr;"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=date&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('date_label')?></span><i style="margin: 0 10px;" class="<?=parseSorting("date")?>"></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=price&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('price_label')?></span><i style="margin: 0 10px;" class="<?=parseSorting("price")?>"></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=street&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('street')?></span><i style="margin: 0 10px;" class="<?=parseSorting("street")?>"></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=house&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('home_a')?></span><i style="margin: 0 10px;" class="<?=parseSorting("house")?>"></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=property&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('property_label')?></span><i style="margin: 0 10px;" class="<?=parseSorting("property")?>"></i></a>
                                                </li>
                                                <li>
                                                    <a href="response?id=<?php echo $search_id; ?>&sort=agent&order=<?php echo $_GET["order"] == "desc" ? "asc" : "desc"; ?>"><span><?=lang('agent_label')?></span><i style="margin: 0 10px;" class="<?=parseSorting("agent")?>"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                                        <a href='/map?mode=list&search_id=<?=$search_id?>' onclick="showLoader()" class="option-btn btn btn-default"><i class="fa fa-globe"></i></a>
                                    </div> </div>
                                    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
                                        <table id="response_table" class="list">
                                            <?php for ($i = 0; $i < count($search_response["properties"]); $i++){
                                                $checked="";
                                                if(in_array($search_response["properties"][$i]->id, $selected)) $checked="checked";?>
                                            <tr style="<?php $i%2 == 0 ? "background-color:#f5f5f5" : ""; ?>" id="property_<?=$search_response["properties"][$i]->id?>_row" class="row_<?=$checked?>">
                                                <td style="width:1%;padding: 5px 0;">
                                                    <input id="property_<?=$search_response["properties"][$i]->id?>_check" class="icheck advopt regular" type="checkbox" name="0" <?=$checked?> />
                                                    <a <?=getCallRow($search_response["properties"][$i])?> class="call_button"><i class="fa fa-phone"></i></a>
                                                </td>
                                                <td class="data">
                                                    <a onclick="showLoader()" href="property?iPropertyId=<?php echo $search_response["properties"][$i]->id; ?>&search_id=<?=$search_id?>">
                                                        <?php echo getResponseRow($search_response["properties"][$i], $search_response["street_googleac"]); ?>
                                                    </a>
                                                </td>                                                
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
                                        <?php if (count($search_response["clients"]) > 0){ ?>
                                        <h3 style="padding: 5px 0 10px 0;"><?=lang("found_clients_h3")?></h3>
                                        <?php } ?>
                                        <table id="response_table" class="list">
                                            <?php for ($i = 0; $i < count($search_response["clients"]); $i++){
                                                $checked="";
                                                if(in_array($search_response["clients"][$i]->id, $selected)) $checked="checked";?>
                                            <tr style="<?php $i%2 == 0 ? "background-color:#f5f5f5" : ""; ?>" id="property_<?=$search_response["clients"][$i]->id?>_row" class="row_<?=$checked?>">
                                                <td>
                                                    <input id="property_<?=$search_response["clients"][$i]->id?>_check" class="icheck advopt regular" type="checkbox" name="0" <?=$checked?> />
                                                </td>
                                                <td class="data">
                                                    <a onclick="showLoader()" href="client?id=<?php echo $search_response["clients"][$i]->id; ?>&search_id=<?=$search_id?>">
                                                        <?php echo getResponseClientRow($search_response["clients"][$i], $search_response["street_googleac"]); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </section>

        <?php ModalBar(lang('no_selected_caption'), "reduce_null_modal");?>
        <!--main content end-->
    </section>
    <div id="legend_modal" class="modal" onclick="response.closeLegend()">
        <a href="#" style="float:right;margin:10px 10px -46px 10px;"><i class="fa fa-times"></i></a>
        <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
            <a href="#" class="btn btn-sm">
                <i class="fa fa-search"></i>
            </a>
            <span><?=lang('change_search_label')?></span>
            <br />
            <a href="#" class="btn btn-sm"><i class="fa fa-scissors"></i></a>
            <span><?=lang('reduce_button')?></span>
            <br />
            <a href="#" class="btn btn-sm"><i class="fa fa-sort"></i></a>
            <span><?=lang('sorting')?></span>
            <br />
            <a href="#" class="btn btn-sm"><i class="fa fa-globe"></i></a>
            <span><?=lang('to_map')?></span>
            <br />
        </div>
    </div>
    <div id="call_ok_bar" style="display: none; position: fixed; z-index:100" class="in" aria-hidden="false"><div class="modal-backdrop  in" style="height: 231px;"></div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"><?=lang("phone_is_calling_now")?></div>
                <div class="modal-footer">
                    <button class="btn btn-sm" onclick="$('#call_ok_bar').modal('hide')">OK</button>
                </div>

            </div>

        </div>
    </div>
    <div class="modal fade" id="pre_contours_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('pre_contours')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <table id="searches_list_table" class="table table-bordered">
                        <tbody>
                            <?php $city = isset($conditions["city"]) ? $conditions["city"] : $search_response["conditions"]["city"]; ?>
                            <?php $contours = $contour->getPreContoursList($city); ?>
                            <?php for ($s = 0; $s < count($contours); $s++){ ?>
                                <?php if ($contours[$s]->title != NULL){ ?>
                                <tr>
                                    <td><a style="color: #337ab7;" href="#" onclick="response.openPreContourOnList(<?= $contours[$s]->id ?>, <?= $search_id ?>)"><?php echo $contours[$s]->title; ?></a></td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="loader_modal" class="modal"></div>
    <script>
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("totop").style.display = "block";
            } else {
                document.getElementById("totop").style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
        
        function showLoader(){
            $('#loader_modal').show()
        }
    </script>
    <script src="assets/js/vendor/jquery-1.11.1.min.js"></script>
    <script src="/assets/js/src/utils.js?v=<?=$app_ver?>"></script>

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
        $response_row .= "<i class='fa fa-map-marker'></i> ".$street_name." ".$data->house_number.($data->flat_number == null ? "" : "/".$data->flat_number);
    }
    
    $response_row .= '<table class="row_table"><tr>';
    
    // дача, 700,000 ILS, Sderot Oved Ben Ami 2, Застроено 34 м², 1 комнат, агент: Лидия, 054-9919898,  Обновлено: 23/01/2018 , карточка 63264
    $types = json_decode($data->types);
    
    for ($t = 0; $t < count($types); $t++){
        $key = $types[$t];
        $response_row .= "<td>".($t > 0 ? ", " : "").lang($query_form_data["property_type"][$key])."</td>";
    }
    
    $code = $data->currency_id;
    $response_row .= "<td>".$currency->getSymbolCode($code)." ".number_format($data->price, 0, ".", ",")." </td>";
    $street_name = null;
    
    $response_row .= "<td><i class='fa fa-thumb-tack'></i> ".date("d-m-Y", $data->last_updated != null ? $data->last_updated : $data->timestamp)."</td>";
    $response_row .= "</tr><tr>";
    
    if ($data->home_size != null){
        $dims_code = $data->home_dims;
        $response_row .= "<td><i class='fa fa-home'></i> ".$data->home_size." ".lang($dimensions->getSymbolCode($dims_code))."</td>";
    }
    
    if ($data->floor_from != null){
        $response_row .= "<td><i class='fa fa-building'></i> ".$data->floor_from."</td>";//." ".lang("rooms_noregister_span");
    }
    
    if ($data->rooms_count != null){
        $response_row .= "<td><i class='fa fa-bed'></i> ".$data->rooms_count."</td>";//." ".lang("rooms_noregister_span");
    }
    
    $response_row .= "</tr><tr>";
    $response_row .= isset($data->name) ? "<td><i class='fa fa-user'></i> ".$data->name."</td>" : "";
    $response_row .= "<td><i class='fa fa-phone'></i> ".$data->contact1."</td>";
    
    /*if ($data->contact2 != null){
        $response_row .= ", ".$data->contact2;
    }
    if ($data->contact3 != null){
        $response_row .= ", ".$data->contact3;
    }
    if ($data->contact4 != null){
        $response_row .= ", ".$data->contact4;
    }*/
    
    $response_row .= "<td>№ ".$data->id."</td>";
    $response_row .= "</tr></table>";
    
    return $response_row;
}

function getCallRow($property_data){
    $response = 'style="display:none"';
    
    if (isIOS()){
        $response = "href=\"tel:$property_data->contact1\"";
    }
    else{
        $response = "onclick=\"response.owlNewCall('property', ".$property_data->id.", '".$property_data->contact1."', '".$property_data->name."');return false;\"";
    }
    
    return $response;
}

function isIOS(){
    //Detect special conditions devices
    $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
    //$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
    //$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

    //do something with this information
    if( $iPod || $iPhone || $iPad){
        return true;
    }
}

function getResponseClientRow($data, $streets){
    global $query_form_data, $currency, $dimensions, $user;
    $response_row = "";
    
    // дача, 700,000 ILS, Sderot Oved Ben Ami 2, Застроено 34 м², 1 комнат, агент: Лидия, 054-9919898,  Обновлено: 23/01/2018 , карточка 63264
    $types = json_decode($data->property_types);
    
    for ($t = 0; $t < count($types); $t++){
        $key = $types[$t];
        $response_row .= ($t > 0 ? ", " : "").lang($query_form_data["property_type"][$key]);
    }
    
    $response_row .= ", ".number_format($data->price_from, 0, ".", ",");
    $response_row .= " - ".number_format($data->price_to, 0, ".", ",");
    $code = $data->currency_id;
    $response_row .= " ".$currency->getSymbolCode($code);
    
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
                    
                    $response .= number_format($from, 0, ".", ",")." - ".number_format($to, 0, ".", ",")." ".$curr;
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

function parseSorting($argument){
    $sort = $_GET["sort"];
    $order = $_GET["order"];
    $response = "";
    $arrow_down = "fa fa-arrow-down";
    $arrow_up = "fa fa-arrow-up";
    
    if ($sort == $argument){
        $response = $order == "desc" ? $arrow_down : $arrow_up;
    }
    
    return $response;
}

?>