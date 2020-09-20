<?php
include "ViewPlugin.data.php";

/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.03.2018
 * Time: 14:53
 */
global $view_plugins;

class ViewPlugin
{
    public $scripts=[];
    public $stylesheets=[];
    public $version=1;



    public static $all=[];

    public function htmlScripts(){
        foreach ($this->scripts as $script){
            ?><script src="<?=$script?>"></script><?php
        }
    }

    public function htmlStylesheets(){
        foreach ($this->stylesheets as $script){
            ?><link rel="stylesheet" href="<?=$script?>"><?php
        }
    }

    public function __construct($source)
    {
        $this->scripts=$source['scripts'];
        $this->stylesheets=$source['stylesheets'];
        //var_dump($source);
    }

    public static function init($plugins){
        global $view_plugins;
        //var_dump($plugins);
        foreach ($plugins as $plugin){
            if(isset($view_plugins[$plugin])) array_push(ViewPlugin::$all, new ViewPlugin($view_plugins[$plugin]));
        }
    }

    public static function allScripts(){
        foreach (ViewPlugin::$all as $plugin) $plugin->htmlScripts();
    }

    public static function allStylesheets(){
        foreach (ViewPlugin::$all as $plugin) $plugin->htmlStylesheets();
    }

}