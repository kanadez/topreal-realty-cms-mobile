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
include(dirname(__FILE__).'/classes/Utils.class.php');
include(dirname(__FILE__).'/classes/Search.data.php');
include(dirname(__FILE__).'/classes/SearchResponse.class.php');
include(dirname(__FILE__).'/classes/ResponseList.class.php');
include(dirname(__FILE__).'/classes/Search.class.php');
include(dirname(__FILE__).'/classes/Contour.class.php');
include(dirname(__FILE__).'/views/lang.php');

session_start();

if (!isset($_SESSION["user"])){ // если разлогинен
    header("Location: /");
}

$app_ver = "1.1.1";

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
$search_response = new SearchResponse;
$response_list = new ResponseList;
$search = new Search;
$contour = new Contour;

User::setSeenMobile();

if (!isset($_GET["id"])){
    $search_data = $search->getEmpty();
}
else{
    $search_id = $_GET["id"];
    $search_data = $search->get($search_id);
}

//var_dump($search_data);

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
    <title>TopReal - <?=lang('header_title_search')?></title>
    <meta name="theme-color" content="#507299" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css?v=<?=$app_ver?>">
    <!-- Fonts  -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css?v=<?=$app_ver?>">
    <link rel="stylesheet" href="assets/css/simple-line-icons.css?v=<?=$app_ver?>">
    <!-- Switchery -->
    <link rel="stylesheet" href="assets/plugins/switchery/switchery.min.css?v=<?=$app_ver?>">
    <!-- CSS Animate -->
    <link rel="stylesheet" href="assets/css/animate.css?v=<?=$app_ver?>">
    <!--Page Level CSS-->
    <link rel="stylesheet" href="assets/plugins/icheck/css/all.css?v=<?=$app_ver?>">
    <link type="text/css" rel="stylesheet" href="assets/plugins/jqueryui/jquery-ui.css?v=<?=$app_ver?>"/>
    <!-- Custom styles for this theme -->
    <link rel="stylesheet" href="assets/css/ac_synonim.css?v=<?=$app_ver?>">
    <link rel="stylesheet" href="assets/css/autocomplete.css?v=<?=$app_ver?>">
    <link rel="stylesheet" href="assets/css/synonim.css?v=<?=$app_ver?>">
    <link rel="stylesheet" href="assets/css/main.css?v=<?=$app_ver?>">
    <link rel="stylesheet" href="assets/css/query.css?v=<?=$app_ver?>">
    <!-- Feature detection -->
    <script src="assets/js/vendor/modernizr-2.6.2.min.js?v=<?=$app_ver?>"></script>
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
                <select style="max-width:110px;float:right;" id="locale_select" class="form-control" title="Select language" data-toggle="tooltip">
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
            </div>
        </header>
        <!--sidebar left end-->
        <!--main content start-->
        <section class="main-content-wrapper">
            <div class="pageheader">
                <h3><?=lang('regular_search')?></h3>
            </div>
            <section id="main-content" class="animated fadeInUp" style="padding-top: 10px;">
                <div class="row">
                    <form role="form" class="form-horizontal" method="POST" action="response">
                        <input name="id" value="<?php echo $search_id; ?>" class="hidden">
                        <div class="col-sm-12">
                            <div class="search_form">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="dropdown" style="display: inline;">
                                            <button id="" type="button" data-toggle="dropdown" aria-expanded="false" class=" btn btn-transparent dropdown-toggle"><i class="fa fa-file-o"></i><span class="caret" style="direction: ltr;"> </button>

                                            <ul class="dropdown-menu" role="menu" style="left: auto">
                                                <li>
                                                    <a href="propertyEdit?lastSearch=<?=$search_id?>'"><span><?=lang('new_property_label')?></span></a>
                                                </li>
                                                <li>
                                                    <a href="clientEdit?lastSearch=<?=$search_id?>'"><span><?=lang('new_client_label')?></span></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <button data-toggle="modal" data-target="#edit_field_lists_modal" type="button" class="btn btn-transparent"><i class="fa fa-list"></i></button>
                                        <button data-toggle="modal" data-target="#edit_field_searches_modal" type="button" class="btn btn-transparent"><i class="fa fa-save"></i></button>
                                        <button type="button" onclick="datainput.eraseAll()" class="btn btn-transparent"><i class="fa fa-eraser"></i></button>
                                        <button data-toggle="modal" data-target="#edit_field_special_modal" type="button" class="btn btn-transparent"><i class="fa fa-search-plus"></i></button>
                                        <button onclick="showLoader()" id="list_search_button" type="submit" class="btn btn-success">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <a id="ascription" data-toggle="modal" href="#" data-target="#edit_field_ascription_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="ascription"><?=lang("ascription_label")?></label>
                                    <select name="ascription" class="contour form-control hidden">
                                        <option value="0" <?php echo $search_data->ascription == 0 ? "selected" : ""; ?> ></option>
                                        <option value="1" <?php echo $search_data->ascription == 1 ? "selected" : ""; ?> ></option>
                                        <option value="2" <?php echo $search_data->ascription == 2 ? "selected" : ""; ?> ></option>
                                        <option value="3" <?php echo $search_data->ascription == 3 ? "selected" : ""; ?> ></option>
                                    </select>
                                    <span class="field_content"><?php echo lang($query_form_data["ascription"][$search_data->ascription]); ?></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="country_field" href="#" data-toggle="modal" data-target="#edit_field_country_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="country"><?=lang('country_label')?></label>
                                    <input name="country" value="<?php echo $search_data->country; ?>" class="form-control regular hidden" autocomplete="off">
                                    <span class="field_content"><?//$googleac->getShortName($search_data->country)->short_name?></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="city" href="#" data-toggle="modal" data-target="#edit_field_locality_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="locality"><?=lang('city_label')?></label>
                                    <span id="city_not_selected_error" class="error_span" style="display: none;">Пожалуйста, выберите город из списка ниже!</span>
                                    <input name="city" value="<?php echo $search_data->city; ?>" class="form-control regular hidden" />
                                    <span class="field_content"><?//$googleac->getShortName($search_data->city)->short_name ?></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="street" href="#" data-toggle="modal" data-target="#edit_field_route_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="route"><?=lang('street')?></label>
                                    <input name="street" value="<?php $d = json_decode($search_data->street); echo $d[0]; ?>" type="text" class="form-control hidden" maxlength="200">
                                    <span class="field_content"><?=$googleac->getShortName($d[0])->short_name?></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="contour" href="#" data-toggle="modal" data-target="#edit_field_contour_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="contour_select"><?=lang('contour_option')?></label>
                                    <?php $contours_list = $contour->getContoursList($search_id); ?>
                                    <select name="contour" class="contour form-control hidden">
                                        <option value="" title="">Выберите контур</option>
                                        <?php for ($c = 0; $c < count($contours_list); $c++){ ?>
                                            <option <?php echo $search_data->contour == $contours_list[$c]->id ? "selected" : ""; ?> value="<?php echo $contours_list[$c]->id; ?>" title=""><?php echo $contours_list[$c]->title; ?></option>
                                            <?php if ($search_data->contour == $contours_list[$c]->id) $contour_name = $contours_list[$c]->title; ?>
                                        <?php } ?>
                                    </select>
                                    <span class="field_content"><?php echo $contour_name; ?></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="property" href="#" data-toggle="modal" data-target="#edit_field_property_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="property"><?=lang("property_short_label")?></label>
                                    <?php $types_list = lang($query_form_data["property_type"]); ?>
                                    <select name="property" class="hidden form-control">
                                        <option value="" title="">Выберите недвижимость</option>
                                        <?php for ($c = 0; $c < count($types_list); $c++){ ?>
                                            <option <?php $d = json_decode($search_data->property); echo $d[0] == $c ? "selected" : ""; ?> value="<?php echo $c; ?>" title=""><?php echo lang($types_list[$c]); ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="field_content"><?php echo $query_form_data["property_type"][$d[0]]; ?></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="price" href="#" data-toggle="modal" data-target="#edit_field_price_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="price"><?=lang("price_label")?></label>
                                    <input name="price_from" value="<?php echo $search_data->price_from; ?>" type="text" maxlength="11" class="from form-control regular hidden">
                                    <input name="price_to" value="<?php echo $search_data->price_to; ?>" type="text" maxlength="11" class="to form-control regular hidden">
                                    <?php $curr_list = Currency::getList(); ?>
                                    <select name="currency" class="currency hidden form-control">
                                        <option value="" title="" selected></option>
                                        <?php for ($c = 0; $c < count($curr_list); $c++){ ?>
                                            <option <?php echo $search_data->currency == $curr_list[$c]->code ? "selected" : ""; ?> value="<?php echo $curr_list[$c]->code; ?>" title=""><?php echo $curr_list[$c]->symbol; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="field_content"></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="status" href="#" data-toggle="modal" data-target="#edit_field_status_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="status"><?=lang('status_label')?></label>
                                    <?php $status_list = $query_form_data["status"]; ?>
                                    <select name="status" class="hidden form-control">
                                        <option value="" title=""></option>
                                        <?php for ($c = 0; $c < count($status_list); $c++){ ?>
                                            <option <?php $d = json_decode($search_data->status); echo $d[0] == $c && !is_null($search_data->status) ? "selected" : ""; ?> value="<?php echo $c; ?>" title=""><?php echo lang($status_list[$c]); ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="field_content"><?php echo lang($query_form_data["status"][$d[0]]); ?></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="updated" href="#" data-toggle="modal" data-target="#edit_field_updated_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="last_updated"><?=lang('update_short_label')?></label>
                                    <input name="history_from" type="text" maxlength="20" value="<?php echo $search_data->history_type == 0 ? $search_data->history_from : ""; ?>" class="from form-control regular hidden">
                                    <input name="history_to" type="text" maxlength="20" value="<?php echo $search_data->history_type == 0 ? $search_data->history_to : ""; ?>" class="to form-control regular hidden">
                                    <span class="field_content"></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="rooms" href="#" data-toggle="modal" data-target="#edit_field_rooms_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="rooms"><?=lang('rooms_label')?></label>
                                    <input name="rooms_from" type="text" maxlength="4" value="<?php echo $search_data->rooms_type == 0 ? $search_data->rooms_from : ""; ?>" class="from form-control regular hidden">
                                    <input name="rooms_to" type="text" maxlength="4" value="<?php echo $search_data->rooms_type == 0 ? $search_data->rooms_to : ""; ?>" class="to form-control regular hidden">
                                    <span class="field_content"></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a id="size" href="#" data-toggle="modal" data-target="#edit_field_size_modal" class="form-group field_wrapper">
                                    <label class="control-label" for="lot_size"><?=lang('square_label')?></label>
                                    <input name="object_type" type="text" value="<?php echo $search_data->object_type; ?>" class="type form-control regular hidden">
                                    <input name="object_size_from" type="text" value="<?php echo $search_data->object_size_from; ?>" maxlength="11" class="from form-control regular hidden">
                                    <input name="object_size_to" type="text" value="<?php echo $search_data->object_size_to; ?>" maxlength="11" class="to form-control regular hidden">
                                    <?php $dims_list = Dimensions::getList(); ?>
                                    <select name="object_dimensions" class="dimensions hidden">
                                        <option value="" title="" selected>Выберите меру</option>
                                        <?php for ($d = 0; $d < count($dims_list); $d++){ ?>
                                            <option <?php echo $search_data->object_dimensions == $dims_list[$d]->code ? "selected" : ""; ?> value="<?php echo $dims_list[$d]->code; ?>" title=""><?php echo $dims_list[$d]->short_title; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="field_content"></span>
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <div class="form-group">     
                                    <div class="col-sm-12">
                                        <table class="checkboxes">
                                            <tr>
                                                <td>
                                                    <input name="parking" <?php echo $search_data->parking == 1 ? "checked" : "" ?> id="parking_advopt_check" class="icheck advopt regular" type="checkbox" />
                                                    <label for="parking_advopt_check"><?=lang('parking_option_label')?></label>
                                                </td>
                                                <td>
                                                    <input name="elevator" <?php echo $search_data->elevator == 1 ? "checked" : "" ?> id="elevator_advopt_check" class="icheck advopt regular" type="checkbox" />
                                                    <label for="elevator_advopt_check"><?=lang('elevator_option_label')?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input name="air_cond" <?php echo $search_data->air_cond == 1 ? "checked" : "" ?> id="aircond_advopt_check" class="icheck advopt regular" type="checkbox" />
                                                    <label for="aircond_advopt_check"><?=lang('air_cond_option_label')?></label>
                                                </td>
                                                <td>
                                                    <input name="furniture" <?php echo $search_data->furniture == 1 ? "checked" : "" ?> id="furniture_advopt_check" class="icheck advopt" type="checkbox" />
                                                    <label for="furniture_advopt_check"><?=lang('furniture_label')?></label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </section>
        <!--<section class="main-content-wrapper" style="padding:10px 0 40px 0;">
            <div class="pageheader" style="padding: 10px 15px;">
                <h3>
                    <?=lang('advanced_search')?>
                    <a id="special" href="#" data-toggle="modal" data-target="#edit_field_special_modal" style="float:right;" class="form-group field_wrapper">
                        <i class="fa fa-pencil"></i>
                    </a>
                </h3>
                
            </div>
        </section>-->
        <!--main content end-->
    </section>
    
    <div class="modal fade" id="edit_field_size_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('square_label')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select class="type form-control">
                                    <option value="" title=""><?=lang('select_type')?></option>
                                    <option value="0" <?php echo $search_data->object_type == 0 ? "selected" : ""; ?> title=""><?=lang('lot_option')?></option>
                                    <option value="1" <?php echo $search_data->object_type == 1 ? "selected" : ""; ?> title=""><?=lang('construction')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input class="form-control from" value="<?php echo $search_data->object_size_from; ?>" placeholder="<?=lang('from')?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input class="form-control to" value="<?php echo $search_data->object_size_to; ?>" placeholder="<?=lang('to_var')?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select class="dimensions form-control">
                                    <option value="" title="" selected><?=lang('select_measure')?></option>
                                    <?php for ($d = 0; $d < count($dims_list); $d++){ ?>
                                        <option <?php echo $search_data->object_dimensions == $dims_list[$d]->code ? "selected" : ""; ?> value="<?php echo $dims_list[$d]->code; ?>" title=""><?php echo lang($dims_list[$d]->locale); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setSize()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_rooms_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('rooms_label')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input onkeyup="datainput.filterRooms(this)" value="<?php echo $search_data->rooms_type == 1 ? $search_data->rooms_from : ""; ?>" class="form-control from" placeholder="<?=lang('from')?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input onkeyup="datainput.filterRooms(this)" value="<?php echo $search_data->rooms_type == 1 ? $search_data->rooms_to : ""; ?>" class="form-control to" placeholder="<?=lang('to_var')?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setRooms()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_updated_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('lastupd_noregister_span')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input onkeyup="datainput.filterDate(this)" class="form-control from" placeholder="<?=lang('from')?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input onkeyup="datainput.filterDate(this)" class="form-control to" placeholder="<?=lang('to_var')?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setUpdated()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_price_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('price_label')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <select class="currency form-control">
                                <option value="" title=""><?=lang('select_currency_label')?></option>
                                <?php for ($c = 0; $c < count($curr_list); $c++){ ?>
                                    <option <?php echo $search_data->currency == $curr_list[$c]->code ? "selected" : ""; ?> value="<?php echo $curr_list[$c]->code; ?>" title=""><?php echo $curr_list[$c]->symbol; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input value="<?php echo isset($search_data->price_from) ? number_format($search_data->price_from, 0, ".", ",") : ""; ?>" onkeyup="datainput.onPriceFromKeyUp()" class="form-control from" placeholder="<?=lang('from')?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input value="<?php echo isset($search_data->price_to) ? number_format($search_data->price_to, 0, ".", ",") : ""; ?>" onkeyup="datainput.onPriceToKeyUp()" class="form-control to" placeholder="<?=lang('to_var')?>">
                            </div>
                        </div>                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setPrice()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_property_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('property_label')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php $types_list = $query_form_data["property_type"]; ?>
                                <?php $property_decoded = json_decode($search_data->property); ?>
                                <select class="property form-control">
                                    <option value="" title=""><?=lang('select_property_option')?></option>
                                    <?php for ($c = 0; $c < count($types_list); $c++){ ?>
                                        <option <?php echo $property_decoded[0] == $c ? "selected" : ""; ?> value="<?php echo $c; ?>" title=""><?php echo lang($types_list[$c]); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setProperty()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_contour_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('contour_option')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php $contours_list = $contour->getContoursList($search_id); ?>
                                <select class="contour form-control">
                                    <option value="" title=""><?=lang('select_contour_option')?></option>
                                    <?php for ($c = 0; $c < count($contours_list); $c++){ ?>
                                        <option <?php echo $search_data->contour == $contours_list[$c]->id ? "selected" : ""; ?> value="<?php echo $contours_list[$c]->id; ?>" title=""><?php echo $contours_list[$c]->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setContour()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_status_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('status_label')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <?php $status_list = $query_form_data["status"]; ?>
                                <?php $status_decoded = json_decode($search_data->status); ?>
                                <select class="status form-control">
                                    <option value="" title=""><?=lang('select_status_option')?></option>
                                    <?php for ($c = 0; $c < count($status_list); $c++){ ?>
                                        <option <?php echo $status_decoded[0] == $c ? "selected" : ""; ?> value="<?php echo $c; ?>" title=""><?php echo lang($status_list[$c]); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setStatus()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_route_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('street')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input id="route_input" class="form-control" type="text" autocomplete="off" ac_types="address" geotype="route" onfocus="geolocate();ac.search(this);synonim.search(this);" onkeyup="ac.search(this);synonim.search(this);">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setStreet()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_locality_modal" style="z-index:999" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('city_label')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input id="locality" onFocus="geolocate()" onchange="search.checkCityExisting()" type="text" placeholder="" maxlength="200" class="form-control regular" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setLocality()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_country_modal" style="z-index:999" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('country_unnecessary_td')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input id="country" onFocus="geolocate()" onchange="" type="text" placeholder="" maxlength="200" class="form-control regular" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setCountry()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_ascription_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('ascription_span')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select class="form-control ascription">
                                    <option value="0" <?php echo $search_data->ascription == 0 ? "selected" : ""; ?> ><?=lang('sale')?></option>
                                    <option value="1" <?php echo $search_data->ascription == 1 ? "selected" : ""; ?> ><?=lang('rent')?></option>
                                    <option value="2" <?php echo $search_data->ascription == 2 ? "selected" : ""; ?> ><?=lang('sale_client')?></option>
                                    <option value="3" <?php echo $search_data->ascription == 3 ? "selected" : ""; ?> ><?=lang('rent_client')?></option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                    <button type="button" onclick="datainput.setAscription()" class="btn btn-primary save"><?=lang('save_button')?></button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_special_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('special_search_h2')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select onchange="datainput.setSpecial()" class="form-control special_by">
                                    <option value="" title=""><?=lang('choose_what_search')?></option>
                                    <option <?php echo $search_data->special_by == 0 ? "selected" : ""; ?> value="0" title=""><?=lang('text_option_label')?></option>
                                    <option <?php echo $search_data->special_by == 2 ? "selected" : ""; ?> value="2" title=""><?=lang('card_noregister_span')?></option>
                                    <option <?php echo $search_data->special_by == 3 ? "selected" : ""; ?> value="3" title=""><?=lang('phone_option_label')?></option>
                                    <option <?php echo $search_data->special_by == 4 ? "selected" : ""; ?> value="4" title="">e-Mail</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input onchange="datainput.setSpecial()" type="text" value="<?php echo $search_data->special_argument; ?>" placeholder="<?=lang('enter_a_value')?>" maxlength="255" class="special_argument form-control regular">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <form role="form" id="special_search" class="form-horizontal" method="POST" action="response">
                        <input name="id" value="<?php echo $search_id; ?>" class="hidden">
                        <input name="country" value="<?php echo $search_data->country; ?>" class="hidden country" autocomplete="off" />
                        <input name="city" value="<?php echo $search_data->city; ?>" class="hidden  city" autocomplete="off"/>
                        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12" style="padding-top:10px;">
                            <input name="special_argument" type="text" value="<?php echo $search_data->special_argument; ?>" maxlength="255" class="special_argument form-control regular hidden">
                            <select name="special_by" class="special_by hidden">
                                <option value="" title="" selected>Выберите тип</option>
                                <option <?php echo $search_data->special_by == 0 ? "selected" : ""; ?> value="0" title="">Текст</option>
                                <option <?php echo $search_data->special_by == 2 ? "selected" : ""; ?> value="2" title="">Карточка</option>
                                <option <?php echo $search_data->special_by == 3 ? "selected" : ""; ?> value="3" title="">Телефон</option>
                                <option <?php echo $search_data->special_by == 4 ? "selected" : ""; ?> value="4" title="">e-Mail</option>
                            </select>
                            <div class="form-group">
                                <!--<input id="furniture_advopt_check" class="icheck advopt" type="checkbox" name="0" />-->
                                <button type="button" data-dismiss="modal" onclick="" class="btn btn-default"><?=lang('cancel')?></button>
                                <button onclick="showLoader()" id="special_search_button" type="submit" class="btn btn-success">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_searches_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('list_search_button')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <table id="searches_list_table" class="table table-bordered">
                        <tbody>
                            <?php $searches = $search->getSearchesList(); ?>
                            <?php for ($s = 0; $s < count($searches); $s++){ ?>
                                <?php if ($searches[$s]->title != NULL){ ?>
                                <tr>
                                    <td><a style="color: #337ab7;" href="query?id=<?php echo $searches[$s]->id; ?>"><?php echo $searches[$s]->title; ?></a></td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_field_lists_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?=lang('lists_short_button')?></h4>
                </div>
                <div class="modal-body form-group form-horizontal">
                    <table class="table table-bordered">
                        <tbody>
                            <?php $lists = $response_list->getAll(); ?>
                            <?php for ($l = 0; $l < count($lists); $l++){ ?>
                                <?php if ($lists[$l]->title != NULL){ ?>
                                <tr>
                                    <td><a style="color: #337ab7;" href="response?list=<?php echo $lists[$l]->id; ?>"><?php echo $lists[$l]->title; ?></a></td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="synonim-container synonim-logo" style="position: absolute; display: none;"></div>
    <div class="autocomplete-container autocomplete-logo" style="position: absolute; display: none;"></div>
    <div id="loader_modal" class="modal"></div>
    
    <script>
        function showLoader(){
            $('#loader_modal').show()
        }
    </script>
    
    <script id="ss" type="text/javascript" src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDfK77teqImteAigaPtfkNZ6CG8kh9RX2g&amp;libraries=places,drawing,geometry&amp;language=<?=$localization->getDefaultLocale()['locale_value'] ?>'></script>
    <script src="assets/js/vendor/jquery-1.11.1.min.js"></script>
    <script src="assets/plugins/jqueryui/jquery-ui.js"></script>
    <script src="assets/plugins/jqueryui/datepicker_locale/datepicker-fr.js"></script>
    <script src="assets/plugins/jqueryui/datepicker_locale/datepicker-he.js"></script>
    <script src="assets/plugins/jqueryui/datepicker_locale/datepicker-ru.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/navgoco/jquery.navgoco.min.js"></script>
    <script src="assets/plugins/icheck/js/icheck.min.js?v=<?=$app_ver?>"></script>
    <script src="assets/plugins/switchery/switchery.min.js"></script>
    <script src="assets/plugins/pace/pace.min.js"></script>
    <script src="assets/plugins/fullscreen/jquery.fullscreen-min.js"></script>
    <script src="assets/js/src/app.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/datainput.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/googlemaps_search.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/utils.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/localization.js?v=<?=$app_ver?>"></script>
    <script src="/assets/js/src/locale.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/autocomplete_synonim.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/autocomplete.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/synonim.js?v=<?=$app_ver?>"></script>
    <script src="assets/js/src/query.js?v=<?=$app_ver?>"></script>
    <!--Page Level JS-->

    <script>
    $(document).ready(function() {
        app.customCheckbox();
        search.init();
    });
    </script>
</body>

</html>
