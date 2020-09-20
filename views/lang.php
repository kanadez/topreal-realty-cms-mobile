<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 06.03.2018
 * Time: 19:36
 */
if (!class_exists('Localization')) include (dirname(__FILE__).'/../classes/Localization.class.php');

$localization=new Localization;

function lang($phrase){
    global $localization;
    return $localization->getVariableCurLocale($phrase);
}

function lang_array($array){
    global $localization;
    $output=[];
    foreach($array as $phrase){
        array_push($output, $localization->getVariableCurLocale($phrase));
    }
    return $output;
}
//$ynFlag=[lang('yes'), lang('no'), "   "];