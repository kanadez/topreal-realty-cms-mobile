<?php

/*ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);*/

include(dirname(__FILE__).'/Mobile_Detect.php');
$detect = new Mobile_Detect;

if(!$detect->isMobile() || $detect->isTablet() ){
    header("Location: https://topreal.top");
}

include(dirname(__FILE__).'/settings.php');
include(dirname(__FILE__).'/classes/Database/TinyMVCDatabase.class.php');
include(dirname(__FILE__).'/classes/Database/TinyMVCDatabaseObject.class.php');
include(dirname(__FILE__).'/classes/Subscription.class.php');
include(dirname(__FILE__).'/classes/SubscriptionImprove.class.php');
include(dirname(__FILE__).'/classes/SubscriptionExpired.class.php');
include(dirname(__FILE__).'/classes/User.class.php');
include(dirname(__FILE__).'/classes/Defaults.class.php');
include(dirname(__FILE__).'/classes/Login.class.php');
include(dirname(__FILE__) . '/views/lang.php');

session_start();

$app_ver = "1.0.8";
$subscription = new Subscription;
$subscription_improve = new SubscriptionImprove;
$subscription_expired = new SubscriptionExpired;
$user = new User;
$login = new Login;
$defaults=new Defaults;

$login_failed = false;
$login_error = null;

$auth_cookie = filter_input(INPUT_COOKIE, 'auth');

if (isset($_POST["login"]) && isset($_POST["password"])){
    $response = $login->authorize($_POST["login"], $_POST["password"]);
    
    if (!isset($response["error"])){
        setcookie("auth", $response["auth_token"], time()+2592000, "/"); // не работает, надо попробовать праоверить напрямую из $_COOKIES
        header("Location: /query");
    }
    else{
        $login_failed = true;
        $login_error = $response["error"]["description"];
    }
}
elseif (isset($auth_cookie) && !isset($_GET["logout"])){
    $response = $login->authorizeWithToken($auth_cookie);
    
    if ($response){
        header("Location: /query");
    }
}
elseif (isset($_GET["logout"])){
    $user = new User;
    $user->unauthorize();
    session_destroy();
    session_unset();
    unset($_SESSION["user"]);
    unset($_SESSION["LAST_ACTIVITY"]);
    setcookie("auth", $response["auth_token"], time()-2592000, "/");
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
    <title>Topreal</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="assets/js/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Fonts -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/simple-line-icons.css">
    <!-- CSS Animate -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!-- Carousel -->
    <link rel="stylesheet" href="assets/js/plugins/carousel/owl.carousel.css">
    <!-- Custom styles for this theme -->
    <link rel="stylesheet" href="assets/css/main_index.css?v=<?=$app_ver?>">
    <!-- Custom styles -->
    <link rel="stylesheet" href="assets/css/index.css?v=<?=$app_ver?>">
    <!-- Feature detection -->
    <script src="assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/vendor/html5shiv.js"></script>
    <script src="assets/js/vendor/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    
    <!-- NAVBAR
    ================================================== -->
    <header class="navbar-wrapper">
        <div class="navbar navbar-default navbar-static-top home-navbar" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <!--logo start-->
                    <div class="brand col-xs-7 col-sm-7 col-md-7 col-lg-7 row">
                        <a href="index.html" class="logo">
                            <i class="icon-layers"></i>
                            <span>TOP</span>REAL</a>
                    </div>
                    <!--logo end-->
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 language_select">
                        <select id="locale_select" class="form-control" title="Select language" data-toggle="tooltip">
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
                </div>
            </div>
        </div>
    </header>


    <!-- Intro section
    ================================================== -->
    <section id="intro">
        <div class="overlay-bg">
            <div class="container">
                <div class="hero">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wow pulse text-center">
                            <header>
                                <h1><?=lang('index_mobile_h1')?></h1>
                                <h3><?=lang('index_mobile_h3')?></h3>
                            </header>
                            <div class="text-left remarks">
                                <p><?=lang('index_moblie_p1')?></p>
                                <p><?=lang('index_moblie_p2')?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section
    ================================================== -->
    <section id="features">
        <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wow pulse text-center">
                <header>
                    <h2><?=lang('log_in')?></h2>
                </header>
            </div>
            <form role="form" method="POST" action="">
                <div class="form-group">
                    <label for="exampleInputEmail1">e-Mail</label>
                    <input type="email" name="login" class="form-control" id="exampleInputEmail1" placeholder="<?=lang('type_email')?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1"><?=lang('password')?></label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="<?=lang('type_password')?>">
                </div>
                <?php if ($login_failed && $login_error == "forbidden_credentials_incorrect"){ ?>
                <div class="alert alert-danger alert-dismissable">
                    Неверные e-Mail или пароль.
                </div>
                <?php } ?>
                <?php if ($login_failed && $login_error == "forbidden_already_authorized"){ ?>
                <div class="alert alert-danger alert-dismissable">
                    Вы уже авторизованы.
                </div>
                <?php } ?>
                <button type="submit" class="btn btn-primary btn-block"><?=lang('sgin_in')?></button>
                <button type="submit" class="transparent btn btn-default btn-block"><?=lang('forgot_your_password')?></button>
            </form>
        </div>
    </section>

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <div itemscope="" itemtype="https://schema.org/Organization">
                        <div class="copy">© <span class="footer_span" id="footer_name_span" itemprop="name">Top Real Services Limited</span> 2018. 
                        <span class="footer_span"><?=lang('all_rigths_reserved')?></span>&#160;
                    </div>
                </div>
                <!--<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 text-right">
                    <span>Follow us on </span>  <a href="javascript:void(0)" class="social facebook"><i class="fa fa-facebook"></i></a> <a href="javascript:void(0)" class="social twitter"><i class="fa fa-twitter"></i></a>
                </div>-->
            </div>
        </div>
    </footer>


    <!--Global JS-->
    <script src="assets/js/vendor/jquery-1.11.1.min.js"></script>
    <script src="assets/js/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/plugins/nav/jquery.nav.js"></script>
    <script src="assets/js/plugins/sticky/jquery.sticky.js"></script>
    <script src="assets/js/plugins/scrollTo/jquery.scrollTo.js"></script>
    <script src="assets/js/plugins/wow/wow.min.js"></script>
    <script src="assets/js/plugins/parallax/jquery.parallax-1.1.3.js"></script>
    <script src="assets/js/plugins/carousel/owl.carousel.js"></script>
    <script src="assets/js/src/app_index.js?v=<?=$app_ver?>"></script>
    <script src="/assets/js/src/utils.js?v=<?=$app_ver?>"></script>
    <script>var utils=new Utils();</script>
    <script src="/assets/js/src/locale.js?v=<?=$app_ver?>"></script>
</body>

</html>
