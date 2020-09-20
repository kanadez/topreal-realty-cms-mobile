<?php

include(dirname(__FILE__).'/settings.php');
include(dirname(__FILE__).'/classes/Database/TinyMVCDatabase.class.php');
include(dirname(__FILE__).'/classes/Database/TinyMVCDatabaseObject.class.php');
include(dirname(__FILE__).'/classes/Subscription.class.php');
include(dirname(__FILE__).'/classes/SubscriptionImprove.class.php');
include(dirname(__FILE__).'/classes/SubscriptionExpired.class.php');
include(dirname(__FILE__).'/classes/User.class.php');
include(dirname(__FILE__).'/classes/Currency.class.php');
include(dirname(__FILE__).'/classes/Dimensions.class.php');
include(dirname(__FILE__).'/classes/Dimensions.data.php');
//include(dirname(__FILE__).'/classes/Defaults.class.php');
include(dirname(__FILE__).'/classes/Login.class.php');
include(dirname(__FILE__).'/classes/Agency.class.php');
include(dirname(__FILE__).'/classes/AgencyORM.class.php');
include(dirname(__FILE__).'/classes/Stock.class.php');
include(dirname(__FILE__).'/classes/Utils.class.php');
include(dirname(__FILE__).'/classes/Doc.class.php');
include(dirname(__FILE__).'/classes/Authorized.only.php');
include(dirname(__FILE__).'/views/fields/Edit.fields.php');
include(dirname(__FILE__).'/views/fields/Bars.fields.php');
include(dirname(__FILE__).'/classes/Client.class.php');
include(dirname(__FILE__).'/classes/Property.data.php');
include(dirname(__FILE__).'/classes/Client.data.php');
include(dirname(__FILE__).'/classes/Contour.class.php');

include(dirname(__FILE__).'/views/PageView.class.php');
include(dirname(__FILE__).'/views/forms/Client.form.php');
include(dirname(__FILE__) . '/views/lang.php');

OnlyForAuthorized();

$subject_type="client";

$subscription = new Subscription;
$subscription_improve = new SubscriptionImprove;
$subscription_expired = new SubscriptionExpired;
$user = new User;
$login = new Login;
$agency = new Agency;
$currency = new Currency;
$dimensions = new Dimensions;
$utils = new Utils;
$defaults=new Defaults;
$contour=new Contour;

$stock = new Stock;


$client=new Client();

$ynFlag=[lang('yes'), lang('no'), "   "];

$data=(object)[];
$action=" ";

$my_defaults=$defaults->getSearch();

User::setSeenMobile();

if(!isset($_REQUEST['act'])){                                           //Данных формы нет - будем отображать форму

    if (isset($_REQUEST['iClientId'])) {                              //Форма редактирования карточки iPropertyId
        $data = $client->get($_REQUEST['iClientId']);
        if(isset($_REQUEST['search_id']))$data->search_id=$_REQUEST['search_id'];
        $tryEditStatus=$client->tryEdit($data->id);
        //var_dump($tryEditStatus);
        if($tryEditStatus!=1){                                              //Если нет прав переадресуем на последнюю просматриваемую карточку и выдаем ошибку
            header('Location: property?iPropertyId='.$data->id.'&errorMessage='.$tryEditStatus['error']['description'].'&search_id='.$data->search_id);
            exit();
        } else $action="edit";
    }
    else{
        $tryCreateStatus = $client->createTemporary();                //Форма создания новой карточки
        //var_dump($tryCreateStatus);
        //header('Location: response?search_id='.$_REQUEST['lastSearch'].'&errorMessage=This is probe error message');
        if(intval($tryCreateStatus)<10){                                //Если нет прав переадресуем на последнюю просматриваемую карточку и выдаем ошибку
            header('Location: response?search_id='.$_REQUEST['lastSearch'].'&errorMessage='.$tryCreateStatus['error']['description']);
        }else{
            $action = "create";
            $yn_fields=["no_ground_floor_flag", "no_last_floor_flag", "front_flag", 'air_cond_flag', 'parking_flag', 'elevator_flag', 'furniture_flag'];
            //Загружаем дефолты в данные формы
            $data=$my_defaults;
            $data->free_from=null;
            $data->currency_id=$data->currency;
            $data->home_dims=$data->object_dimensions;
            $data->lot_dims=$data->object_dimensions;
            $data->id=intval($tryCreateStatus);
            $data->stock="0";

            foreach ($yn_fields as $field) $data->$field=2;             //Устанавливаем пустое значение для полей Да/Нет
            //$client->set($data->id, json_encode($data), 0);
        }
    }

}

//Поля карточки, которые необходимо передавать методу set



$client_fields=["id", "title", "property_types", "ascription", "status", "agent_id", "agency", "street", "street_text", "neighborhood", "neighborhood_text", "city", "city_text", "region", "region_text", "country", "country_text", "lat", "lng", "contour", "flat_number", "floors_count", "floor_from", "floor_to", "rooms_type", "rooms_to", "rooms_from", "home_size_from", "home_size_to", "home_size_dims", "lot_size_from", "lot_size_to", "lot_size_dims", "age_from", "price_from", "price_to", "currency_id", "last_updated", "furniture_flag", "parking_flag", "no_ground_floor_flag", "no_last_floor_flag", "front_flag", "elevator_flag", "air_cond_flag", "project_id", "free_from", "name", "email", "contact1", "contact1_remark", "contact1_type", "contact2", "contact2_remark", "contact2_type", "contact3", "contact3_remark", "contact3_type", "contact4", "contact4_remark", "contact4_type", "remarks_text", "free_number", "details", "agent_editing", "permissions", "temporary", "timestamp", "deleted", "external_id_winwin", "tmp"];

if (isset($_REQUEST['act'])){

    $id=$_REQUEST['id'];

    if($_REQUEST['act']=="save"){  //Сохранение данных при редактировании или создании новой карточки

        $data=$client->get($id);
        $history_data=(object)[];
        $set_data=(object)[];
        foreach ($client_fields as $key){
            if(isset($_POST[$key])){
                $value=$_POST[$key];
                if(is_array($value))$value=json_encode($value);
                if($data->$key!=$value)$history_data->$key=$value;
                $set_data->$key=$value;
                //echo "key=".$key."<br>";
                //var_dump($set_data->$key);
                //echo "value=".json_encode($value)."<br>";
            }else{
                $set_data->$key=$data->$key;
            }
            if($set_data->$key=="")$set_data->$key=null;

        }
        //echo json_encode($set_data);
        $set_data->free_from=strtotime($set_data->free_from);
        if(! ($set_data->rooms_from || $set_data->rooms_to))$set_data->rooms_type=0;
        if($set_data->free_from==0)unset($set_data->free_from);
        if(!$_REQUEST['iClientId'])$client->createNew($id, json_encode($set_data));
        else $client->set($id, json_encode($set_data));
        $client->setHistory($id, json_encode($history_data));
        $client->unlock($id);
        header('Location: client?id='.$id);
        exit();
    }
}
//var_dump($data->types);
//HeaderView();
$pageView=new PageView();
$pageView->name=lang('header_title_client');
$pageView->id=$data->id;
$pageView->short="client";
$pageView->title=$pageView->name." ".$data->id;
$pageView->plugins=["fileupload", "tagsinput", "tagit"];
if($data->last_updated) $pageView->title.=", ".date("d/m/Y", $data->last_updated );

if($action=="create")$pageView->title=lang('new_client_label');

$pageView->JSData=(object)[
    "id"=>$data->id,
    "stock"=>$data->stock,
    "data"=>$data,
    "country"=>$data->country,
    "city"=>$data->city,
    "geo"=>(object)["lat"=>$my_defaults->lat, "lng"=>$my_defaults->lng],
    "street"=>json_decode($data->street)[0],
    "street_name"=>$googleac->getShortName($data->street),
    "photos"=>$data->photos,
    "docs"=>$data->docs,
    "mode"=>"edit"
];

$pageView->begin();
DeleteConfirmBar();
FilesLimitError();
UploadFileNameBar();
UpoadLimitError();
ToCalModalBar();
PhoneDublicatedModal();
ClientSaveBar($data);

FormBar::begin("/clientEdit");
if($action!="create"){
    //OptionsPropertyBar($data);
    //StockWarningBar();
    CalendarEventSuccessBar();
    CopyToStockBar($data);
    LastPropertyBar($data);
}

?>

    <input type="hidden" name="act" value="save">
    <input type="hidden" name="id" value="<?=$data->id ?>">
<?php
ClientForm();
FormBar::end();

$pageView->end();