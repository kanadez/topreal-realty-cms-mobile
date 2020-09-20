<?php

function localization_getdefaultlocale(){
    global $localization;
    
    return $localization->getDefaultLocale();
}

function localization_getlocale(){
    global $localization;
    
    return $localization->getLocale($_POST["locale"]);
}

function localization_ru(){
    global $localization;

    return $localization->findRuPhrase($_POST['phrase']);
}

?>
