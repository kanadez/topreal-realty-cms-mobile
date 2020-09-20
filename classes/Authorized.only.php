<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 09.02.2018
 * Time: 20:05
 */

function OnlyForAuthorized(){
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: /");
        exit();
    }
}