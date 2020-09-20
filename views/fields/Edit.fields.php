<?php
include(dirname(__FILE__) . '/../../classes/Defaults.class.php');
include(dirname(__FILE__) . '/../../classes/Localization.class.php');
include(dirname(__FILE__) . '/../../classes/GoogleAC.class.php');


$defaults = new Defaults;
$localization = new Localization;
$googleac = new GoogleAC;

$EDIT_FLAG=1;

$site="m";

//$ynFlag=[lang('yes'), lang('no'), "   "];

function comma_list($list){
    $string="";
    foreach ($list as $key=>$value){
        $string.=$value;
        if($key+1<sizeof($list)) $string.=", ";
    }
    return $string;
}

function Field($type, $selector, $label, $params=[]){
    global $data;

    Row::begin();
    if($label!=null)Label($selector, $label);

    if(!isset ($params['is_multiple'])) $params['is_multiple'] = false;

    switch ($type){
        case "select": Select($selector, $params['list'], $data->$selector, $params['is_multiple'], $params['is_required']); break;
        case "text": Text($selector, $data->$selector, $params['size'], $params['is_required']); break;
        case "bigtext": BigText($selector, $data->$selector, $params['size']); break;
        case "date": DateForm($selector, $data->$selector); break;
        case "phone": Phone($selector, $data->$selector, 30, $params['is_required']); break;
        case "email": Email($selector, $data->$selector, 30,  $params['subject_tmpl'], $params['message_tmpl']); break;
        case "geo": Geo($selector, $data->$selector); break;
        case "m-geo": MultiGeo($selector, $data->$selector); break;
    }

    Row::end();
}
function InlineField($type, $selector, $label, $params=[]){
    global $data;

    if($label!=null)Label($selector, $label);

    if(!isset ($params['is_multiple'])) $params['is_multiple'] = false;

    switch ($type){
        case "select": Select($selector, $params['list'], $data->$selector, $params['is_multiple'], $params['is_required']); break;
        case "text": Text($selector, $data->$selector, $params['size'], $params['is_required']); break;
        case "bigtext": BigText($selector, $data->$selector, $params['size']); break;
        case "date": DateForm($selector, $data->$selector); break;
        case "phone": Phone($selector, $data->$selector, 30, $params['is_required']); break;
        case "email": Email($selector, $data->$selector, 30,  $params['subject_tmpl'], $params['message_tmpl']); break;
        case "geo": Geo($selector, $data->$selector); break;
        case "m-geo": MultiGeo($selector, $data->$selector); break;
    }

}

function TSField($t_selector, $s_selector, $label, $params=[]){     //Text-Select Field
    global $data;

    Row::begin();
    if($label!=null)Label($t_selector, $label);
    Text($t_selector, $data->$t_selector, $params['size'], $params['is_required']);
    Select($s_selector, $params['list'], $data->$s_selector);
    Row::end();
}

function TSFTField($tf_selector, $tt_selector, $s_selector, $label, $params=[]){    //Text-Select From-To Field
    global $data;

    Row::begin();
    if($params['select_is_label'])Select($s_selector, $params['list'], $data->$s_selector);
    if($label!=null)Label($tf_selector, $label);
    Text($tf_selector, $data->$tf_selector, $params['size'], $params['is_required']);
    Label($tt_selector, " &mdash; &nbsp;");
    Text($tt_selector, $data->$tt_selector, $params['size'], $params['is_required']);
    if($s_selector!=null&&!$params['select_is_label']) Select($s_selector, $params['list'], $data->$s_selector);
    Row::end();
}

function FieldsInRow(){

}

function strToArray(&$str){
    preg_match_all("/\d+/", $str, $str);
    if(isset($str[0]))$str=$str[0];
}

function Select($var_name, $vars, $var_default, $is_multiple=false, $is_required=false){
    $name_add="";
    if($is_multiple) $name_add.="[]";
    ?>
    <select id="<?=$var_name ?>" name="<?=$var_name.$name_add?>" class="field_content multiselect-container" <?php if($is_multiple) echo "multiple='multiple' "; if($is_required) echo "required"?>>
        <?php foreach ($vars as $key=>$var) { ?>
            <option value = "<?=$key?>" <?php if($is_multiple){
                if(is_string($var_default)) strToArray($var_default);
                if(in_array($key, $var_default))echo "selected='selected'";
            } else if($var_default==$key) echo "selected='selected'" ?>><?=$var?></option>
        <?php }?>
    </select>
    <?php
}

function SelectLabel($var_name, $vars, $var_default){
    Select($var_name, $vars, $var_default);
}

function Text($var_name, $var_default, $size, $is_required=false){
    ?>
    <input id="<?=$var_name?>" name="<?=$var_name?>" type="text" style="width:auto" class="form-control field_content" size="<?=$size ?>" value="<?=$var_default ?>" <?php if($is_required) echo "required"?>>
    <?php
}

function BigText($var_name, $var_default, $size){
    ?>
    <textarea id="<?=$var_name?>" name="<?=$var_name?>" type="text" style="width: 100%; display: block" class="field_content form-control" rows="<?=$size ?>"><?=$var_default ?></textarea>
    <?php
}

function DateForm($var_name, $var_default){
    ?>
    <input id="<?=$var_name?>" name="<?=$var_name?>" style="width:auto" class="form-control" value="<?php if($var_default) echo date("d.m.Y", $var_default) ?>">
    <?php
}

function Label($var_name, $var_label){
    if($var_label=="auto") {
        $var_label=lang($var_name."_short_label");
        if($var_label=='')($var_name."_label");
        if($var_label=='') $var_label=lang($var_name);
    }
    ?>
    <label class="field_content" for="<?=$var_name ?>"><?=$var_label?> </label>
    <?php
}

function Phone($var_name, $var_default, $size, $is_required=false){
    ?>
    <input id="<?=$var_name?>" name="<?=$var_name?>"  type="phone" style="width:auto" class="field_content form-control" size="<?=$size ?>" value="<?=$var_default ?>" <?php if($is_required) echo "required"?>>
    <?php
}

function Email($var_name, $var_default, $size, $message_tmpl, $subject_tmpl){
    ?>
    <input id="<?=$var_name?>" name="<?=$var_name?>" type="email" style="width:auto" class="field_content form-control" size="<?=$size ?>" value="<?=$var_default ?>">
    <?php
}

function Geo($var_name, $var_default_id){
    global $googleac;
    ?>
    <input id="<?=$var_name?>" value="<?=$googleac->getShortName($var_default_id)->short_name?>" type="text" size="30" style="width:auto" class="field_content form-control" autocomplete="off">
    <input id="<?=$var_name?>_id" name="<?=$var_name?>" class="field_content hidden" autocomplete="off" value="<?=$var_default_id?>">
    <?php
}

function Hidden($var_name, $var_default=null){
    ?><input type="hidden" id="<?=$var_name?>" name="<?=$var_name?>" value="<?=$var_default?>"><?php
}

function MultiGeo($var_name, $var_default_ids_json){
    global $googleac;
    $var_default_ids=json_decode($var_default_ids_json);
    $var_default_values=[];
    ?><ul id="<?=$var_name?>Tags"><?php
    foreach ($var_default_ids as $var_default_id){
        $var_short=$googleac->getShortName($var_default_id)->short_name;
        array_push($var_default_values, $var_short);
    }
    ?></ul>
    <input id="<?=$var_name?>_id" name="<?=$var_name?>" class="field_content hidden" autocomplete="off" value='<?=$var_default_ids_json?>'>
    <script>
        var <?=$var_name?>Tags;
        $(document).ready(function () {
            <?=$var_name?>Tags=new Tagit($('#<?=$var_name?>Tags'));
            <?=$var_name?>Tags.init(<?=json_encode($var_default_values)?>, <?=$var_default_ids_json?>);
            <?=$var_name?>Tags.view.css({width: '68%', 'marfin-left': 'auto', 'margin-right': '10px', float: 'right'});
            $ti=<?=$var_name?>Tags.getInput();
            $ti.attr({id: '<?=$var_name?>', size: 20}).unbind('blur');
            $ti.focus(function () {
                geolocate();
                ac.search(this);
                //synonim.search(this);
            });
            $ti.keyup(function () {
                this.size=20;
                ac.search(this);
                //synonim.search(this);
            });
        })
        </script>
    <?php
}

function Attachments(){
    global $site;
    //DeleteConfirmBar();
    ?>


        <div class="form-group">
            <div class="col-sm-8 col-xs-8 col-lg-8 col-md-8">
                <h3 style="font-weight: bold; margin: 5px;"><?=lang("photos_label")?></h3>
            </div>
            <div class="col-sm-4 col-xs-4 col-lg-4 col-md-4 ">
                <label for="photo" class="btn btn-default"><i class=" fa fa-camera" id="photo_loader"></i></label> <input id="photo" type="file" name="file" class="hidden" accept=".gif, .jpg, .png" data-url="https://topreal.top/storage/upload.php?site=<?=$site?>">
            </div>
            <div id="images_row"></div>
            <div class="row" style="font-weight: bold; margin: 5px; margin-top: 20px;">
            <div class="col-sm-8 col-xs-8 col-lg-8 col-md-8">
                <h3 style="font-weight: bold; margin-top: 10px"><?=lang("documents_label")?></h3>
            </div>
            <div class="col-sm-4 col-xs-4 col-lg-4 col-md-4 ">
                <label for="doc" class="btn btn-default"><i class=" fa fa-file-text" id="doc_loader"></i></label> <input id="doc" type="file" name="file" class="hidden" accept=".doc, .docx, .xls, .xlsx, ppt, .pptx, .docm .dot, .dotx, .dotm, .html, .txt, .rtf, .odt, .pdf" data-url="https://topreal.top/storage/upload.php?site=<?=$site?>">
            </div>
            </div>
            <div id="docs_row"></div>
        </div>

    <?php
}

function Documents(){
    global $data, $site;
    //if(isset($data->docs['error'])) return;
    ?>
    <div class="form-group">
        <div class="col-sm-8 col-xs-8 col-lg-8 col-md-8">
            <h3 style="font-weight: bold; margin-top: 10px"><?=lang("documents_label")?></h3>
        </div>
        <div class="col-sm-4 col-xs-4 col-lg-4 col-md-4 ">
            <label for="doc" class="btn btn-default"><i class=" fa fa-file-text" id="doc_loader"></i></label> <input id="doc" type="file" name="file" class="hidden" accept=".doc, .docx, .xls, .xlsx, ppt, .pptx, .docm .dot, .dotx, .dotm, .html, .txt, .rtf, .odt, .pdf" data-url="https://topreal.top/storage/upload.php?site=<?=$site?>">
        </div>
    </div>
    <div id="docs_row"></div>
    </div>
    <?php
}


class Row{
    static function begin(){
        ?><div class="form-group form-inline"><?php
    }
    static function end(){
        ?></div><?php
    }
}