<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.02.2018
 * Time: 13:48
 */

function ClientViewBar($data){
    $search_view=$_REQUEST['search_view'] ? $_REQUEST['search_view'] : "response";
    $search_params= ($search_view=="map") ? "&mode=list" : "";
    ?>
    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <a id="back_button" href="/<?=$search_view?>?search_id=<?=$data->search_id.$search_params?>" class="btn btn-default">↩</a>
        <button type="button" id="edit_button" onclick="document.location='clientEdit?iClientId=<?=$data->id ?>&search_id=<?=$data->search_id?>'" class="btn btn-default"><i class="fa fa-pencil"></i></button>
        <a id="next_button" onclick="property.showLoader()" href="" class="btn btn-default">↓</a>
        <a id="previous_button" onclick="property.showLoader()" href="" class="btn btn-default">↑</a>
        <button type="button" onclick="client.showCalendarEventModal()" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        <a onclick="property.showLoader()" href="comparison?id=<?=$data->id ?>&subject=client" class="btn btn-default"><i class="fa fa-exchange"></i></a>
    </div>
    <?php
}

function ClientSaveBar($data){
    $back_link="/client?id=".$data->id."search_id=".$data->search_id;
    if(isset($_REQUEST['lastSearch'])) $back_link="/response?search_id=".$_REQUEST['lastSearch'];
    ?>
    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <a id="back_button" href="<?=$back_link?>" class="btn btn-default">↩</a>
        <button type="button" id="save_button" onclick="client.submitForm()" class="btn btn-default"><i class="fa fa-save"></i></button>
    </div>
    <?php
}

function PropertyViewBar($data){
    $search_view=$_REQUEST['search_view'] ? $_REQUEST['search_view'] : "response";
    $search_params= ($search_view=="map") ? "&mode=list" : "";
    ?>
    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <?php if ($_GET["mode"] == "view_stock"){ ?>
        <a id="back_button" href="#" onclick="history.back()" class="btn btn-default">↩</a>
        <?php }else{ ?>
        <a id="back_button" href="/<?=$search_view?>?search_id=<?=$data->search_id.$search_params?>" class="btn btn-default">↩</a>
        <?php } ?>
        
        <button type="button" id="edit_button" onclick="document.location='propertyEdit?iPropertyId=<?=$data->id ?>&search_id=<?=$data->search_id?>'" class="btn btn-default"><i class="fa fa-pencil"></i></button>
        <?php if(isset($data->next_id)){ ?><a id="next_button" onclick="property.showLoader()" href="/property?iPropertyId=<?=$data->next_id?>&search_id=<?=$data->search_id?>" class="btn btn-default">↓</a><?php } ?>
        <?php if(isset($data->prev_id)){ ?><a id="previous_button" onclick="property.showLoader()" href="/property?iPropertyId=<?=$data->prev_id?>&search_id=<?=$data->search_id?>" class="btn btn-default">↑</a><?php } ?>
        
        <div class="dropdown" style="display: inline;">
            <button type="button" data-toggle="dropdown" aria-expanded="false" class="btn btn-default dropdown-toggle"><i class="fa fa-file-text-o"></i><span class="caret"> </span></button>
            <ul class="dropdown-menu" role="menu" style="left: auto">
                <li>
                    <a href="javascript:void(0)" onclick="property.addToEvents('event_notification')"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?=lang("to_calendar_button")?></a>
                </li>
                <li>
                    <a onclick="property.showLoader()" href="comparison?id=<?=$data->id ?>&subject=property"><i class="fa fa-exchange"></i>&nbsp;&nbsp;<?=lang("comparison_button")?></a>
                </li>
                <li>
                    <a href="javascript:void(0)" onclick="property.check()"><i class="fa fa-phone"></i>&nbsp;&nbsp;<?=lang("check_button")?></a>
                </li>
                <?php if ($_GET["mode"] == "view_stock"){ ?>
                <li>
                    <a id="collected_open_stock_a" style="color:red" onclick="history.back()">
                        <i class="fa fa-link"></i>
                        <?=lang("stock_original")?>
                    </a>
                </li>
                <?php }else{ ?>
                <li>
                    <a id="collected_open_stock_a" href="/property?iPropertyId=<?=$data->id?>&mode=view_stock&search_id=<?=$data->search_id?>">
                        <i class="fa fa-link"></i>&nbsp;&nbsp;<?=lang("stock_original")?>
                    </a>
                </li>
                <?php } ?>
                <li>
                    <a href="/map?iPropertyId=<?=$data->id?>&mode=route&search_id=<?=$data->search_id?>">
                        <i class="fa fa-globe"></i>&nbsp;&nbsp;<?=lang("map_button")?>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="dropdown" style="display: inline;">
            <button type="button" data-toggle="dropdown" aria-expanded="false" class="btn btn-default dropdown-toggle"><i class="fa fa-bell"></i><span class="caret"> </span></button>
            <ul class="dropdown-menu" role="menu" style="left:-160px;">
                <?php
                $events = [];
                
                for ($e = 0; $e < count($data->events); $e++){
                    $event_type = $data->events[$e]->event;
                    $events[$event_type] = $data->events[$e]->start;
                } 
                ?>
                <li>
                    <a href="javascript:void(0)" class="event_sign" onclick="property.addToEvents('event_sign')">
                        <i class="fa fa-edit"></i>
                        <?= isset($events["event_sign"]) ? "&nbsp;<b>".date("d/m/Y", $events["event_sign"])."</b>" : "&nbsp;".lang("event_sign") ?>
                    </a>
                </li>
                <li class="own_icon">
                    <a id="event_visit_a" href="javascript:void(0)" class="event_a event_visit" onclick="property.addToEvents('event_visit')"></a>
                    <span onclick="property.addToEvents('event_visit')"><?= isset($events["event_visit"]) ? "<b>".date("d/m/Y", $events["event_visit"])."</b>" : lang("event_visit") ?></span>
                </li>
                <li class="own_icon">
                    <a id="event_appointment_a" href="javascript:void(0)" class="event_a event_appointment" onclick="property.addToEvents('event_appointment')"></a>
                    <span onclick="property.addToEvents('event_appointment')"><?= isset($events["event_appointment"]) ? "<b>".date("d/m/Y", $events["event_appointment"])."</b>" : lang("event_appointment") ?></span>
                </li>
                <!--<li>
                    <a id="event_notification_a" href="javascript:void(0)" class="event_notification" onclick="property.addToEvents('event_notification')"><i class="fa fa-bell"></i>&nbsp;&nbsp;<?=lang("event_notification")?></a>
                </li>-->
            </ul>
        </div>
        
        <!--<a href="javascript:void(0)" onclick="property.showLegend()" class="btn btn-default"><i class="fa fa-question"></i></a>-->
        
    </div>
    <?php
}

function PropertySaveBar($data){
    $back_link="/property?iPropertyId=".$data->id."search_id=".$data->search_id;
    if(isset($_REQUEST['lastSearch'])) $back_link="/response?search_id=".$_REQUEST['lastSearch'];
    ?>
    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <a id="back_button" href="<?=$back_link?>" class="btn btn-default">↩</a>
        <button type="button" id="save_button" onclick="property.submitForm()" class="btn btn-default"><i class="fa fa-save"></i></button>
    </div>
    <?php
}

function ErrorBar($error){
    ?>

    <div id="new_ad_warning_wrapper" class="form-group">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div style="margin-bottom:0;padding: 5px;" class="alert alert-danger alert-dismissable new_ad_warning">&nbsp;
                <span>Ошибка! <?php echo $error ?></span>&nbsp;&nbsp;
            </div>
        </div>
    </div>

    <?php
}

function EventsBar($property_data){
    ?>
    <?php
}

function CopyToStockBar($property_data){
    if(intval($property_data->stock)) {
    ?>
    <div class="form-group">  
    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <!--<div style="margin-bottom:0;padding: 5px;"
             class="stock_badge alert alert-warning alert-dismissable new_ad_warning">
            <span id="stock_badge_span" style="direction: ltr;"><?php
                if($property_data->foreign_stock==1) echo lang('just_copied').lang('from_stock');
                else echo lang('just_copied').' '.lang('to_stock');
                ?></span>,&nbsp;
            <span id="stock_badge_date_span"
                  style="direction: ltr;"><?php if($property_data->timestamp) echo date("d/m/Y", $property_data->timestamp) ?></span>&nbsp;&nbsp;
        </div>-->
    </div>
    </div><?php
    }
}


function StockWarningBar(){

    ?>
    
    <div class="form-group" id="stock_warning" style="display: none">
    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <div style="margin-bottom:0;padding: 5px;"
             class="alert alert-warning alert-dismissable new_ad_warning">
           <span><?=lang('stock_need_full_version_alert')?></span>
        </div>
    </div>
    </div><?php

}

function CalendarEventSuccessBar(){

    ?>
    <div class="form-group" id="calendar_success" style="display: none">
        <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
            <div style="margin-bottom:0;padding: 5px;"
                 class="alert alert-success alert-dismissable new_ad_warning">
                <span><?=lang('event_added_to_your_calendar')?></span>
            </div>
        </div>
    </div><?php

}

function OptionsPropertyBar($property_data){
    ?><div class="form-group header">
    <div class="col-sm-8 col-xs-8 col-lg-8 col-md-8">
        <?php if(intval($property_data->stock)){?>
            
            <?php $subcat_id = $property_data->yad2_subcat_id;
            $url="http://www.yad2.co.il/Nadlan/ViewImage.php?CatID=2&SubCatID=";
            if ($property_data->ascription == 0){
                $subcat_id = $subcat_id != null ? $subcat_id : 1;
                $url.=$subcat_id."&RecordID=".$property_data->external_id;
            }
            else if ($property_data->ascription == 1){
                $subcat_id = $subcat_id != null ? $subcat_id : 2;
                $url.=$subcat_id."&RecordID=".$property_data->external_id;
            } ?>
        <?php } ?>
        <!--<a id="collected_picture_a" href="<?=$url?>" class="btn btn-sm" ><i class="fa fa-picture-o"></i></a>-->
    </div>
        <!--<div class="col-sm-4 col-xs-4 col-lg-4 col-md-4" style="padding-top: 15px">
            <input autocomplete="off" id="stock_check" class="icheck" <?php if($property_data->stock) echo "checked" ?> type="checkbox" value="0" name="0" disabled />
        <label class="white_label" for="stock_check"><?=lang('stock')?></label></div>-->
    </div>
    </div><?php
}


function LastPropertyBar($property_data){
    if($property_data->external_new){?>
                        <div id="new_ad_warning_wrapper" class="form-group">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div style="padding: 5px;" class="alert alert-warning alert-dismissable new_ad_warning">
                                        <span style="direction: ltr;"><?=lang('last_ad_short')?></span>&nbsp;
                                        <span id="new_ad_warning_date_span" style="direction: ltr;"><?php if($property_data->external_new[0])echo date("d/m/Y", $property_data->external_new[0] ) ?></span>&nbsp;&nbsp;
                                        <a type="button" href="<?=$property_data->external_new[1] ?>" target="_blank" style="direction: ltr;"><i class="fa fa-eye"></i></a>
                                    </div>
                                </div>
                        </div><?php }

}

function DeleteConfirmBar(){
    ?>
    <div id="delete_confirm_modal" style="display: none; position: fixed; z-index:100">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"><?=lang('deletion_confirm_message')?></div>
                <div class="modal-footer">
                    <button id="delete_confirm_yes_button" class="btn btn-sm"><?=lang('yes')?></button>
                    <button class="btn btn-sm" onclick="$('#delete_confirm_modal').modal('hide')"><?=lang('no')?></button>
                </div>

            </div>

        </div>
    </div>
    <?php
}

function UploadFileNameBar(){
    global $subject_type;
    ?>
    <div id="file_upload_modal" style="display: none; position: fixed; z-index:100">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><?=lang('set_file_name')?></div>
                <div class="modal-body"><input type="text" id="new_file_name_input"> </div>
                <div class="modal-footer">
                    <button id="delete_confirm_yes_button" onclick="<?=$subject_type?>.setUploadedFileTitle()" class="btn btn-sm">OK</button>
                </div>

            </div>

        </div>
    </div>
    <?php
}

function SmsBar(){
    global $subject_type;
    ?>
    <div id="sms_bar" style="display: none; position: fixed; z-index:100">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><?=lang('sms_title_short')?><i id="sms_phone"></i></div>
                <div class="modal-body"><textarea rows="4" id="sms_text"></textarea> </div>
                <div class="modal-footer">
                    <button id="delete_confirm_yes_button" onclick="<?=$subject_type?>.owlSendSms($('#sms_phone').html(), $('#sms_text').val().trim())" class="btn btn-sm">OK</button>
                </div>

            </div>

        </div>
    </div>
    <?php
}

function SmsOkBar(){
    ?>
    <div id="sms_ok_bar" style="display: none; position: fixed; z-index:100">
        <div class="modal-dialog">
            <div class="modal-content">
                <?=lang('push_sms_send_button')?>
                <p><?=lang('if_nothing_happens_sms')?></p>
                <div class="modal-footer">
                    <button id="delete_confirm_yes_button" onclick="$('#sms_ok_bar').modal('hide')" class="btn btn-sm">OK</button>
                </div>

            </div>

        </div>
    </div>
    <?php
}

function ModalBar($message, $id){
    ?>
    <div id="<?=$id?>" style="display: none; position: fixed; z-index:100">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"><?=$message?></div>
                <div class="modal-footer">
                    <button class="btn btn-sm" onclick="$('#<?=$id?>').modal('hide')">OK</button>
                </div>

            </div>

        </div>
    </div><?php
}

function AddEventBar(){
    ?>
    <div class="modal fade" id="to_events_modal" style="z-index:2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><?=lang("create_calendar_event_h4")?></h4>
                </div>
                <div class="modal-body">
                    <div id="auth_success_alert" class="alert alert-success alert-dismissable" style="display:none">
                        <strong><span><?=lang("success_label")?></span>&#160;</strong><span id="after_auth_msg_label">Now create event below.</span>
                    </div>
                    <div id="auth_error_alert" class="alert alert-danger alert-dismissable" style="display:none">
                        <strong><span><?=lang("error_serious_span")?></span>&#160;</strong><span id="auth_error_msg_label">Something is wrong. Try again.</span>
                    </div>
                    <form class="form-horizontal" role="form" onsubmit="return false;">
                        <div class="form-group">
                            <label for="add_event_title_input" class="col-sm-3 control-label"><?=lang("event_title_label")?></label>
                            <div class="col-sm-9">
                                <input autocomplete="off" class="form-control" id="add_event_title_input"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=lang("event_start_label")?></label>
                            <div class="col-sm-6">
                                <input autocomplete="off" class="form-control" id="add_event_start_input"/>
                                <input id="add_event_start_time_select" class="form-control" type="time" onchange="hourForwardForEvent()"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=lang("event_end_label")?></label>
                            <div class="col-sm-6">
                                <input autocomplete="off" class="form-control" maxlength="20" id="add_event_end_input"/>
                                <input id="add_event_end_time_select" class="form-control" type="time" />
                            </div>
                        </div>
                        <div class="notification form-group">
                            <label class="col-sm-3 control-label"><i class="fa fa-bell"></i>&#160;<span><?=lang("notification")?></span></label>
                            <div class="col-sm-2">
                                <select id="add_event_notification_period_input" class="form-control" data-toggle="tooltip" title="">
                                    <option value="10">10 <?=lang("minutes")?></option>
                                    <option value="30">30 <?=lang("minutes")?></option>
                                    <option value="60">1 <?=lang("hours")?></option>
                                    <option value="120">2 <?=lang("hours")?></option>
                                    <option value="1440">1 <?=lang("days")?></option>
                                    <option value="2880">2 <?=lang("days")?></option>
                                </select>
                                <label for="notify_by_email_check">+ e-Mail</label>
                                <input id="notify_by_email_check" class="icheck" type="checkbox" />
                                <a id="event_notify_remove_a" href="javascript:void(0)" onclick="property_event.removeNotification()"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("close_button")?></button>
                    <button type="button" onclick="property_event.create()" class="btn btn-primary"><?=lang("create_button")?></button>
                </div>

            </div>

        </div>
    </div>
    <div class="modal fade" id="to_events_modal_success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><?=lang("create_event_h4")?></h4>
                </div>
                <div class="modal-body alert-success">
                    <?=lang("event_successfully_created")?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div><?php
}

function UpoadLimitError(){
    ModalBar(lang('file_too_big'), "upload_limit_modal");
}


function FilesLimitError(){
    ?>
    <div id="files_limit_error" style="display: none; position: fixed; z-index:100">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"><?=lang('more_five_elements') ?></div>
                <div class="modal-footer">
                    <button class="btn btn-sm" onclick="$('#files_limit_error').modal('hide')">OK</button>
                </div>

            </div>

        </div>
    </div>
    <?php
}

function PhoneDublicatedModal(){
    ?>
    <div id="phone_dublicated_modal" style="display: none; position: fixed; z-index:100">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"><?=lang('phone_dublicate_msg')?></div>
                <div class="modal-footer">
                    <a class="btn btn-primary" id="phone_dublicated_link"><?=lang('goto')?></a>
                    <button class="btn btn-default" onclick="$('#phone_dublicated_modal').modal('hide')"><?=lang('continue')?></button>
                </div>

            </div>

        </div>
    </div>
    <?php
}

function ToCalModalBar(){
    global $subject_type;
    ?>
    <div class="modal fade" id="to_cal_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="direction: ltr; display: none;"><div class="modal-dialog"><div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="direction: ltr; float: right;">×</button><h4 locale="create_calendar_event_h4" class="modal-title" id="myModalLabel" title=""><?=lang('create_calendar_event_h4') ?></h4>
                </div>
                <div class="modal-body">
                    <div id="auth_success_alert" class="alert alert-success alert-dismissable" style="display: none">
                        <strong><span locale="success_label" title="" style="direction: ltr;"><?=lang('success_label') ?></span>&nbsp;</strong><span id="after_auth_msg_label" style="direction: ltr;">Now create event below.</span>
                    </div>
                    <div id="auth_error_alert" class="alert alert-danger alert-dismissable" style="display:none">
                        <strong><span locale="error_serious_span" title="" style="direction: ltr;"><?=lang('error_serious_span') ?></span>&nbsp;</strong><span id="auth_error_msg_label" style="direction: ltr;">Something is wrong. Try again.</span>
                    </div>
                    <form id="to_cal_form" class="form-horizontal" role="form" onsubmit="return false;" style="text-align: left;">
                        <div class="form-group calendar_event_field" style="display: block;">
                            <label locale="event_title_label" for="event_title_input" class="col-sm-3 control-label" title="" style="direction: ltr;"><?=lang('event_title_label') ?></label><div class="col-sm-9"><input autocomplete="off" class="form-control" id="event_title_input" data-onenter-func="<?=$subject_type?>.createCalendarEvent()" onkeypress="utils.onEnter(event, this)" style="direction: ltr;"></div>
                        </div>
                        <div class="form-group calendar_event_field row" style="display: block;">
                            <label locale="event_start_label" class="col-sm-3 col-xs-12 control-label" title="" style="direction: ltr;"><?=lang('event_start_label') ?></label><div class="col-sm-6 col-xs-7"><input class="form-control" id="event_start_input" style="direction: ltr;"></div>
                            <div class="col-sm-3 col-xs-5"><select id="event_start_time_select" class="form-control" onclick="hourForward()" data-toggle="tooltip" title="" style="direction: ltr;"><option key="0" value="00:00:00">00:00</option>
                                    <option key="1" value="01:00:00">01:00</option>
                                    <option key="2" value="02:00:00">02:00</option>
                                    <option key="3" value="03:00:00">03:00</option>
                                    <option key="4" value="04:00:00">04:00</option>
                                    <option key="5" value="05:00:00">05:00</option>
                                    <option key="6" value="06:00:00">06:00</option>
                                    <option key="7" value="07:00:00">07:00</option>
                                    <option key="8" value="08:00:00">08:00</option>
                                    <option key="9" value="09:00:00">09:00</option>
                                    <option key="10" value="10:00:00">10:00</option>
                                    <option key="11" value="11:00:00">11:00</option>
                                    <option key="12" value="12:00:00">12:00</option>
                                    <option key="13" value="13:00:00">13:00</option>
                                    <option key="14" value="14:00:00">14:00</option>
                                    <option key="15" value="15:00:00">15:00</option>
                                    <option key="16" value="16:00:00">16:00</option>
                                    <option key="17" value="17:00:00">17:00</option>
                                    <option key="18" value="18:00:00">18:00</option>
                                    <option key="19" value="19:00:00">19:00</option>
                                    <option key="20" value="20:00:00">20:00</option>
                                    <option key="21" value="21:00:00">21:00</option>
                                    <option key="22" value="22:00:00">22:00</option>
                                    <option key="23" value="23:00:00">23:00</option></select></div>
                        </div>
                        <div class="form-group calendar_event_field" style="display: block;">
                            <label locale="event_end_label" class="col-sm-3 col-xs-12 control-label" title="" style="direction: ltr;"><?=lang('event_end_label') ?></label><div class="col-sm-6 col-xs-7"><input autocomplete="off" class="form-control" maxlength="20" id="event_end_input" style="direction: ltr;"></div>
                            <div class="col-sm-3 col-xs-5"><select id="event_end_time_select" class="form-control" data-toggle="tooltip" title="" style="direction: ltr;"><option key="0" value="00:00:00">00:00</option>
                                    <option key="1" value="01:00:00">01:00</option>
                                    <option key="2" value="02:00:00">02:00</option>
                                    <option key="3" value="03:00:00">03:00</option>
                                    <option key="4" value="04:00:00">04:00</option>
                                    <option key="5" value="05:00:00">05:00</option>
                                    <option key="6" value="06:00:00">06:00</option>
                                    <option key="7" value="07:00:00">07:00</option>
                                    <option key="8" value="08:00:00">08:00</option>
                                    <option key="9" value="09:00:00">09:00</option>
                                    <option key="10" value="10:00:00">10:00</option>
                                    <option key="11" value="11:00:00">11:00</option>
                                    <option key="12" value="12:00:00">12:00</option>
                                    <option key="13" value="13:00:00">13:00</option>
                                    <option key="14" value="14:00:00">14:00</option>
                                    <option key="15" value="15:00:00">15:00</option>
                                    <option key="16" value="16:00:00">16:00</option>
                                    <option key="17" value="17:00:00">17:00</option>
                                    <option key="18" value="18:00:00">18:00</option>
                                    <option key="19" value="19:00:00">19:00</option>
                                    <option key="20" value="20:00:00">20:00</option>
                                    <option key="21" value="21:00:00">21:00</option>
                                    <option key="22" value="22:00:00">22:00</option>
                                    <option key="23" value="23:00:00">23:00</option></select></div>
                        </div>
                        <div id="authorize-div" style="display: none; text-align: center;" class="form-group">
                            <div locale="auth_request_div" style="width:100%" title=""><?=lang('auth_request_div') ?></div>
                            <p></p>
                            <button id="authorize-button" type="button" class="btn btn-primary" onclick="handleAuthClick(event)" style="direction: ltr;">Authorize</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button locale="close_button" type="button" class="btn btn-default" data-dismiss="modal" title="" style="direction: ltr;"><?=lang('close_button') ?></button><button locale="create_button" type="button" onclick="<?=$subject_type?>.createCalendarEvent()" class="btn btn-primary calendar_event_field" title="" style="display: inline-block; direction: ltr;"><?=lang('create_button')?></button>
                </div>
            </div></div></div>
    <?php
}

function RouteBar(){

}

function PropertyRouteHeaderBar($property){
    global $googleac;
    ?>
    <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
        <a id="back_button" href="property?iPropertyId=<?=$property->id?>" class="btn btn-default">↩</a>
    </div>
    <div class="col-sm-10 col-xs-10 col-lg-10 col-md-10">
        <p><?=lang('property_label').' '.$property->id?> </p>
        <p><?=lang('address').': '.$googleac->getShortName($property->country)->short_name?>, <?=$googleac->getShortName($property->city)->short_name?>, <?=$googleac->getShortName($property->street)->short_name?>, <?=lang('house') ?> <?=$property->house_number?>
            <?php if($property->flat_number){ ?>, <?=lang('flat_number_label') ?>. <?php echo $property->flat_number; } ?></p>
    </div>
    <?php
}

function PropertyListHeaderBar($search){
    global $googleac;
    ?>

    <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">
        <p><span><label class="control-label" style="margin-left: 10px; margin-right: 5px"><?=lang('founded_span') ?> </label><span id="search_entries_founded_span">0</span></span>
            <span><label class="control-label" style="margin-left: 10px; margin-right: 5px"><?=lang('marked_span') ?> </label><span id="search_entries_marked_span">0</span></span>
            <span id="hidden_wrapper" style="display: none" ><label class="control-label" style="margin-left: 10px; margin-right: 5px"><?=lang('hidden') ?> </label><span id="search_entries_hidden_span">0</span></span></p>
    </div>
    <div class="col-sm-3 col-xs-3 col-lg-3 col-md-3">
        <input class="icheck" id="_all_markers" type="checkbox"><label><?=lang('all_label') ?></label>
    </div>
     <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
        <a id="new_button" href="/propertyEdit?lastSearch=<?= $search->id ?>" class="option-btn btn btn-default "><i class="fa fa-file-o"></i></a>
     </div>
      <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
            <a id="draw_new_button" href="javascript:0" class="option-btn btn btn-default"><i class="fa fa-pencil"></i></a>
            </div>
      <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
            <a id="map_reduce_button" href="javascript:0" class="option-btn btn btn-default "><i class="fa fa-scissors"></i></a>
      </div>
      <div class="col-sm-2 col-xs-2 col-lg-2 col-md-3">
            <a id="back_button" onclick="switchFromMapToList()" href="javascript:void(0)" data-list-url="/response?search_id=<?= $search->id ?>&from=map" class="option-btn btn btn-default "><i class="fa fa-list"></i></a>
      </div>
    <?php
}

function PropertyListFooterBar(){
    ?>
    <script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/32/2/intl/ru_ALL/drawing_impl.js"></script>
    <script src="/assets/js/vendor/markerwithlabel.js"></script>
    <script src="/assets/js/vendor/markerclusterer.js"></script>
    <script src="/assets/js/src/dc_map_list.js?v=7"></script>
    <script src="/assets/js/src/map_drawing_dc.js?v=7"></script>
    <script>
        $(window).load(function(){
            response_map=new ResponseMap();
            response_map.init();
        })

    </script>

    <?php
}

function GoogleMapBar(){
    ?>
    <div class="alert alert-danger alert-dismissable getposition_error" style="display:none">
        <?=lang("cant_get_geoposition")?> <?=lang("geo_data_disabled")?>
    </div>
    <span style="display:none"><?=lang("cant_get_geoposition")?> <?=lang("geo_data_disabled")?></span>
    <div class="col-sm-12 col-xs-12 col-lg-4 col-md-12">
        <div id="map"></div>
    </div>
    <script src="/assets/js/src/google_drections.js"></script>
    <script>
        var mapManager;
        
        function initMap(lat, lng) {
            switch(PHPData.mode){
                case "route":
                    var property=PHPData.data;
                    var property_location=new google.maps.LatLng(property.lat, property.lng);
                    
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var geolocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            
                            mapManager=new DcDirection("map", geolocation);
                            mapManager.routeFromMe(property_location);
                        }, function(){
                            $('.getposition_error').css("display", "inline-block");
                            alert($('.getposition_error + span').text());
                        });
                    }
                    
                    break;

                case "list":
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfK77teqImteAigaPtfkNZ6CG8kh9RX2g&callback=initMap&libraries=drawing,geometry"></script>
    <script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/32/2/intl/ru_ALL/drawing_impl.js"></script>

   <?php
}

class FormBar{
    public static function begin($action=""){
            ?>
        <form action="<?=$action?>" role="form" class="form-horizontal" method="post">
            <div class="col-sm-12 col-xs-12 col-lg-12 col-md-12">

                <div class="property_form"><?php
    }
    public static function end(){
        ?>      </div>
            </div>
        </form><?php
    }
}