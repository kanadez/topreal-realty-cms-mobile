<?php
/**
 * Created by PhpStorm.
 * User: Владимир Бугорков
 * Date: 05.02.2018
 * Time: 23:15
 */

define('PROJECT_HOME', dirname( __FILE__ ) );

global $dimensions_data; // данные о валютах

$dimensions_data = array();

$dimensions_data ['size'] = ["акр", "а", "га", "фт²", "км²", "м²", "миль²", "ярдов²", "дунам"];