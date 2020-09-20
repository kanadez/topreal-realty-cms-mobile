<?php
        include "ViewPlugin.class.php";
        //include "lang.php";
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 09.02.2018
 * Time: 22:38
 */

class PageView{

    public $title;
    public $name;
    public $short;
    public $id;
    public $JSData;
    public $plugins=[];
    public $version="1.1.3";
    public $blueHeader=false;
    public $initGoogleMap=false;


    public function begin(){
        global $localization;
        ViewPlugin::init($this->plugins);
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$this->title?></title>
    <meta name="theme-color" content="#507299" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css?v=<?=$this->version?>">

    <link rel="stylesheet" href="/assets/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css?v=<?=$this->version?>">
    <!-- Fonts  -->
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css?v=<?=$this->version?>">
    <link rel="stylesheet" href="/assets/css/simple-line-icons.css?v=<?=$this->version?>">
    <!-- Switchery -->
    <link rel="stylesheet" href="/assets/plugins/switchery/switchery.min.css?v=<?=$this->version?>">
    <!-- CSS Animate -->
    <link rel="stylesheet" href="/assets/css/animate.css?v=<?=$this->version?>">
    <!--Page Level CSS-->
    <link rel="stylesheet" href="/assets/plugins/icheck/css/all.css?v=<?=$this->version?>">
    <link type="text/css" rel="stylesheet" href="/assets/plugins/jqueryui/jquery-ui.css?v=<?=$this->version?>?v=1"/>
    <!-- Custom styles for this theme -->
    <link rel="stylesheet" href="/assets/css/ac_synonim.css?v=<?=$this->version?>">
    <link rel="stylesheet" href="/assets/css/autocomplete.css?v=<?=$this->version?>">
    <link rel="stylesheet" href="/assets/css/synonim.css?v=<?=$this->version?>">
    <link rel="stylesheet" href="/assets/css/main.css?v=<?=$this->version?>">
    <link rel="stylesheet" href="/assets/css/uploads_slider.css?v=<?=$this->version?>">
    <link rel="stylesheet" href="/assets/css/<?=$this->short?>.css?v=<?=$this->version?>">
    <!-- Feature detection -->
    <script src="/assets/js/vendor/modernizr-2.6.2.min.js?v=<?=$this->version?>"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/assets/js/vendor/html5shiv.js?v=<?=$this->version?>"></script>
    <script src="/assets/js/vendor/respond.min.js?v=<?=$this->version?>"></script>
    <![endif]-->

    <?php ViewPlugin::allStylesheets()?>

    <script src="/assets/js/vendor/jquery-1.11.1.min.js?v=<?=$this->version?>"></script>
    <script src="/assets/plugins/jqueryui/jquery-ui.js?v=<?=$this->version?>"></script>
    <!--Page Level JS-->

    <script>
        var PHPData=<?=json_encode($this->JSData)?>;
        $(document).ready(function() {
            //app.customCheckbox();
            var views=$('.multiselect-container');
            views.multiselect();
            <?=$this->short?>.init();



        });
    </script>

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
            <select id="locale_select" style="max-width:75px;float:right;" class="form-control" title="Select language" data-toggle="tooltip" onchange="document.cookie='locale='+this.value">
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
            <a href="#" onclick="property.showLegend()" style="margin-right:16px;float:right;color:white;">
                <i style="margin-top:9px;" class="fa fa-question-circle"></i>
            </a>
        </div>
    </header>
    <!--sidebar left end-->
    <!--main content start-->
    <section class="main-content-wrapper">
        <div class="pageheader" <?php if($this->blueHeader){?>style="background: rgb(177, 255, 251);"<?php } ?>>
            <h3><?=$this->title?><?= $this->JSData->data->stock == 1 ? ", <b>".lang("stock")."</b>" : ""  ?></h3>
        </div>
        <section id="main-content" class="animated fadeInUp">
            <div class="row">
<?php
    }

    public function end(){
        global $localization;
        ?>
            </div>
        </section>
    </section>

    <!--main content end-->
</section>
<div class="synonim-container synonim-logo" style="position: absolute; display: none;"></div>
<div class="autocomplete-container autocomplete-logo" style="position: absolute; display: none;"></div>
<div id="legend_modal" class="modal" onclick="property.closeLegend()">
    <a href="#" style="float:right;margin:10px 10px -46px 10px;"><i class="fa fa-times"></i></a>
    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <a href="#" class="btn btn-sm">
            <i class="fa fa-link"></i>
        </a>
        <span><?=lang('stock_original_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm"><i class="fa fa-picture-o"></i></a>
        <span><?=lang('picture_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm"><i class="fa fa-globe"></i></a>
        <span><?=lang('globe_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm">↩</a>
        <span><?=lang('back_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm"><i class="fa fa-pencil"></i></a>
        <span><?=lang('edit_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm">↓, ↑</a>
        <span><?=lang('scroll_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm"><i class="fa fa-calendar"></i></a>
        <span><?=lang('calendar_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm"><i class="fa fa-exchange"></i></a>
        <span><?=lang('comparison_legend')?></span>
        <br />
        <a href="#" class="btn btn-sm"><i class="fa fa-eye"></i></a>
        <span><?=lang('eye_legend')?></span>
    </div>
</div>
<div id="loader_modal" class="modal"></div>
        <!-- Firebase -->
        <script src="https://www.gstatic.com/firebasejs/4.13.0/firebase.js"></script>
        <script>
          // Initialize Firebase
          var config = {
            apiKey: "AIzaSyAMzFtWpU_W9nhTqTtMmS0fsTiZLsx0k9Y",
            authDomain: "topreal-website.firebaseapp.com",
            databaseURL: "https://topreal-website.firebaseio.com",
            projectId: "topreal-website",
            storageBucket: "",
            messagingSenderId: "893387567483"
          };
          firebase.initializeApp(config);
        </script>
        <script type="text/javascript" src="firebase_subscribe.js"></script>
        <?php if(!$this->initGoogleMap){?><script id="ss" type="text/javascript" src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDfK77teqImteAigaPtfkNZ6CG8kh9RX2g&amp;libraries=places,drawing,geometry&amp;language=<?=$localization->getDefaultLocale()['locale_value'] ?>'></script><?php } ?>

        <?php if (in_array("fileupload", $this->plugins)){?>
            <!--JQuery File Upload Plugin -->
        <script src="/assets/js/plugins/fileupload/jquery.ui.widget.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/plugins/fileupload/jquery.iframe-transport.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/plugins/fileupload/jquery.fileupload.js?v=<?=$this->version?>"></script>
        <?php } ?>
        <script src="/assets/plugins/jqueryui/datepicker_locale/datepicker-fr.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/jqueryui/datepicker_locale/datepicker-he.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/jqueryui/datepicker_locale/datepicker-ru.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/bootstrap/js/bootstrap.min.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/navgoco/jquery.navgoco.min.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/icheck/js/icheck.min.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/switchery/switchery.min.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/pace/pace.min.js?v=<?=$this->version?>"></script>
        <script src="/assets/plugins/fullscreen/jquery.fullscreen-min.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/app.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/datainput.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/googlemaps_dc.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/urlparser.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/utils.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/locale.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/autocomplete_synonim_dc.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/autocomplete_dc.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/synonim_dc.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/imageviewer_dc.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/docviewer_dc.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/uploads_slider_dc.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/modal.js?v=<?=$this->version?>"></script>
        <script src="/assets/js/src/localization.js?v=<?=$this->version?>"></script>
        <script src="assets/js/src/<?=$this->short?>.js?v=<?=$this->version?>"></script>

<script src="/assets/js/src/googlecalendar.js?v=<?=$this->version?>"></script>
<script src="/assets/js/src/googleplus.js?v=<?=$this->version?>"></script>
<script src="/assets/js/vendor/jstz.min.js?v=<?=$this->version?>"></script>

<script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>
<script src="https://plus.google.com/js/client:platform.js" async="true" defer="true"></script>
<script>
function showLoader(){
    $('#loader_modal').show()
}
</script>

<?php ViewPlugin::allScripts();

if(in_array("photoswipe", $this->plugins)){?>
        <!--PhotoSwipe-->
<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe.
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides.
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>
<?php } ?>

        </body>

        </html>
        <?php

    }
}

