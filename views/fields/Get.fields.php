<?php
/**
 * Created by PhpStorm.
 * User: Владимир Бугорков
 * Date: 07.02.2018
 * Time: 10:03
 */

//$ynFlag=[lang('yes'), lang('no'), "   "];

$EDIT_FLAG=0;

function comma_list($list, $vars=[]){
    $string="";
    foreach ($list as $key=>$value){
        if(isset($vars[$value]))$string.=$vars[$value];
        else $string.=$value;
        if($key+1<sizeof($list)) $string.=", ";
    }
    return $string;
}


function Field($type, $selector, $label, $params=[]){
    global $data;
   if($data->$selector==null) return;
   if($data->$selector=="")return;
   if(isset($params['null_value'])) if($data->$selector==$params['null_value']) return;
   if(is_object($data->$selector))if(isset($data->$selector->error))return;

   Row::begin();
    if($label!=null){
        if($params['bold_label']) BoldLabel($selector, $label);
            else Label($selector, $label);
    }

   if(!isset ($params['is_multiple'])) $params['is_multiple'] = false;

   switch ($type){
       case "select": Select($selector, $params['list'], $data->$selector, $params['is_multiple']); break;
       case "text": Text($selector, $data->$selector, $params['size']); break;
       case "bigtext": BigText($selector, $data->$selector, $params['size']); break;
       case "date": DateForm($selector, $data->$selector); break;
       case "phone": Phone($selector, $data->$selector, 30); break;
       case "email": Email($selector, $data->$selector, 30,   $params['message_tmpl'], $params['subject_tmpl']); break;
       case "geo": Geo($selector, $data->$selector); break;
       case "m-geo": MultiGeo($selector, $data->$selector); break;
   }

   Row::end();
}

function BoldLabel($var_name, $var_label){
    ?>
    <h3 style="font-weight: bold; text-align: center"><?=$var_label?></h3>
<?php
}

Function InlineField($type, $selector, $label, $params=[]){
    global $data;
   if($data->$selector==null) return;
   if($data->$selector=="")return;


   if(isset($params['null_value'])) if($data->$selector==$params['null_value']) return;
   if(is_object($data->$selector))if(isset($data->$selector->error))return;

    if($label!=null)Label($selector, $label);

   if(!isset ($params['is_multiple'])) $params['is_multiple'] = false;

   switch ($type){
       case "select": Select($selector, $params['list'], $data->$selector, $params['is_multiple']); break;
       case "text": Text($selector, $data->$selector, $params['size']); break;
       case "bigtext": BigText($selector, $data->$selector, $params['size']); break;
       case "date": DateForm($selector, $data->$selector); break;
       case "phone": Phone($selector, $data->$selector, 30); break;
       case "email": Email($selector, $data->$selector, 30,  $params['message_tmpl'], $params['subject_tmpl']); break;
       case "geo": Geo($selector, $data->$selector); break;
       case "m-geo": MultiGeo($selector, $data->$selector); break;
   }
}

function TSField($t_selector, $s_selector, $label, $params=[]){
      global $data;
   if($data->$t_selector==null) return;
   if($data->$t_selector=="")return;

   Row::begin();
    if($label!=null)Label($t_selector, $label);
   Text($t_selector, $data->$t_selector, $params['size'], $params['th_split']);
   Select($s_selector, $params['list'], $data->$s_selector);
   Row::end();
}

function TSFTField($tf_selector, $tt_selector, $s_selector, $label, $params=[]){
      global $data;
   if($data->$tf_selector==null) return;
   if($data->$tf_selector=="")return;

   Row::begin();
    if($label!=null)Label($tf_selector, $label);
    if($params['select_is_label'])Label($s_selector, $params['list'][$data->$s_selector].": ");
   Text($tf_selector, $data->$tf_selector, $params['size'], $params['th_split']);
   Label($tt_selector, " - ");
   Text($tt_selector, $data->$tt_selector, $params['size'], $params['th_split']);
   if($s_selector!=null&&!$params['select_is_label'])Select($s_selector, $params['list'], $data->$s_selector);
   Row::end();
}

function FieldsInRow(){

}

function Select($var_name, $vars, $var_default, $is_multiple=false){
    ?><span class="field_content" id="vi_<?=$var_name?>"><?php
    if ($is_multiple) {
        if(is_string($var_default)) $var_default=json_decode($var_default);
        echo comma_list($var_default, $vars);
    }else{
        echo $vars[$var_default];
    }
    ?></span><?php
}

function SelectLabel($var_name, $vars, $var_default){
    Label($var_name, $vars[$var_default].": ");
}

function Text($var_name, $var_default, $size, $th_split=false){
    if($th_split) $var_default=number_format($var_default, 0, '', ',');
    ?>
    <span class="field_content" id="<?=$var_name ?>"><?=$var_default ?></span>
    <?php
}

function BigText($var_name, $var_default, $size){
    ?>
    <p class="field_content" style="line-height: 1.2em; white-space: pre-wrap; font-size: 100%"><?=$var_default ?></p>
    <?php
}

function DateForm($var_name, $var_default){
    ?>
    <span class="field_content"><?=date("d/m/Y", $var_default) ?></span>
    <?php
}


function Label($var_name, $var_label){
    global $data;
    if($var_label=="auto") {
        $var_label=lang($var_name."_short_label");
        if($var_label=='')($var_name."_label");
        if($var_label=='') $var_label=lang($var_name);
    }
    //if($data->$var_name!=null){
    ?>
    <label class="field_content" for="<?=$var_name ?>"><?=$var_label?> </label>
    <?php //}
}

function Phone($var_name, $var_default, $size){
    global $subject_type;
    if($var_default){ ?>
    <span class="field_content" style="margin-right: 15px"><?=$var_default ?></span>
    <button id="owl_button_phone_2" type="button" onclick="<?=$subject_type?>.owlNewCall('<?=$var_default?>')" class="btn btn-default" ><i class="fa fa-phone"></i>❯</button>
    <button id="owl_button_sms_2" type="button" onclick="<?=$subject_type?>.owlNewSms('<?=$var_default?>')" class="btn btn-default" ><i class="fa fa-envelope-o"></i>❯</button>
    <?php }
}

function Email($var_name, $var_default, $size, $message_tmpl, $subject_tmpl){
    if($var_default){?>
    <span class="field_content" style="margin-right: 15px"><?=$var_default ?></span>
    <button id="owl_button_phone_2" type="button" onclick="document.location='mailto:<?=$var_default?>?subject=<?=$subject_tmpl?>&body=<?=$message_tmpl?>'" class="btn btn-default" ><i class="fa fa-at"></i>❯</button>
    <?php }
}

function Geo($var_name, $var_default_id){
    global $googleac;
    //var_dump($googleac->getShortName($var_default_id));
    ?>
    <span class="field_content" id="<?=$var_name ?>"><?=$googleac->getShortName($var_default_id)->short_name?><span class="field_content">
    <?php
}

function Hidden($var_name, $var_default=null){}

function MultiGeo($var_name, $var_default_ids){
    global $googleac;
    $var_default_ids=json_decode($var_default_ids);
    ?><span class="field_content" id="vi_<?=$var_name ?>"><?php
    foreach($var_default_ids as $key=>$var_default_id){
        echo $googleac->getShortName($var_default_id)->short_name;
        if(($key+1)<count($var_default_ids)) echo ", ";
    }
    ?></span><?php
}

function Attachments(){
    global $data;
    //var_dump($data->photos);
    if(isset($data->photos['error'])&&isset($data->docs['error'])) return;
    ?>

    <div class="form-group">
        <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
            <h3 style="font-weight: bold; margin: 5px; text-align: center;"><?=lang("photos_label")?></h3>
        </div>
        <div id="images_row"></div>

        <h3 style="font-weight: bold; margin: 5px; margin-top: 20px; text-align: center;"><?=lang("documents_label")?></h3>
        <div id="docs_row"></div>
    </div>
    <?php
}

function Documents(){
    global $data;
    if(isset($data->docs['error'])) return;
    ?>
    <div class="form-group">
    <h3 style="font-weight: bold; margin: 5px; margin-top: 20px;  text-align: center;"><?=lang("documents_label")?></h3>
        <div id="docs_row"></div>
    </div>
<?php
}

class Row{
    static function begin(){
        ?><div class="form-group form-inline" style="margin-bottom: 0"><?php
    }
    static function end(){
        ?></div><?php
    }
}