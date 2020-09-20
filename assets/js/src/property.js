var datainput = new DataInput();
var urlparser = new URLparser();
var utils = new Utils();
var localization = new Localization();
var synonim = new Synonim("property");
var ac_synonim = synonim;
//var ac_synonim = new AutocompleteSynonim("property");
var ac = new Autocomplete("property");
var property_event = new PropertyEvent();
var property = new Property();
var search = property;
var slider;

var requiredFields=["types", "price", "contact1"];

var imageviewer, docviewer;

$(document).click(function(e){
    var target = $(e.target);

    if (
        !target.is('.autocomplete-container') &&
        !target.is('.autocomplete-item') &&
        !target.is('.autocomplete-icon') &&
        !target.is('.autocomplete-item-query') &&
        !target.is('.autocomplete-matched') &&
        !target.is('.synonim-container') &&
        !target.is('.synonim-item') &&
        !target.is('.synonim-icon') &&
        !target.is('.synonim-item-query') &&
        !target.is('.synonim-matched')
    ){
        ac.hide();
    }
});

function Property(){
    this.geoloc = {};
    this.defaults = {};
    this.just_created=0;
    //this.data=PHPData.data;

    this.init = function(){
        if(PHPData.mode!="look")initAutocomplete();

        localization.init();
        property.data=PHPData.data;


        property.geoloc.country = PHPData.country;

        property.geoloc.city = PHPData.city;
                            ac.getCityLocales(PHPData.city);
        property.current_city = PHPData.city;

        property.geoloc.lat = PHPData.geo.lat;

        property.geoloc.lng = PHPData.geo.lat;

        property.geoloc.street = PHPData.street;

        $("#free_from").datepicker({dateFormat: "dd.mm.yy"})


        if(PHPData.mode!="look")placeDetailsByPlaceId(property.geoloc.country, service_country, $('#country'));
        if(PHPData.mode!="look")placeDetailsByPlaceId(property.geoloc.city, service_city, $('#city'));
        //streetDetailsByPlaceId();
        $("#street").focus(function () {
            geolocate();
            ac.search(this);
            //synonim.search(this);
        });
        $("#street").keyup(function () {
            ac.search(this);
            //synonim.search(this);
        });

        app.customCheckbox();

        $('#stock_check').next().on('click', function(event){
            property.onStockChange();
        });


        this.selectedMax($("#types"), 4);

        slider=new UploadsSlider("property");

        PHPData.photos=PHPData.photos?PHPData.photos:[];
        PHPData.docs=PHPData.docs?PHPData.docs:[];

        this.initImageViewer(PHPData.photos);
        //slider.initImages();

        this.initDocViewer(PHPData.docs);
        //slider.initDocs();

        if(PHPData.mode!="look") {
            this.initPhotoUploader();
            this.initDocUploader();
        }
        this.initCalendarDialog();

        $('#add_event_start_input').datepicker({ dateFormat: "yy-mm-dd" });
        $('#add_event_end_input').datepicker({  dateFormat: "yy-mm-dd" });
        $('#add_event_start_input').datepicker('setDate', new Date());
        $('#add_event_end_input').datepicker('setDate', new Date());
        
        var now = new Date();
        var next_hour = now.getHours() == 23 ? "00" : utils.leadZero(now.getHours()+1, 2);
        $('#add_event_start_time_select').val(next_hour+":00");
        hourForwardForEvent();
    };
    
    this.initCalendarDialog = function () {
        $('#event_start_input').datepicker({ dateFormat: "yy-mm-dd" });
        $('#event_end_input').datepicker({  dateFormat: "yy-mm-dd" });
        $('#event_start_input').datepicker('setDate', new Date());
        $('#event_end_input').datepicker('setDate', new Date());
        $('#event_end_time_select').val("01:00:00");
    };

    this.initPhotoUploader=function () {
        $("#photo").fileupload({
             options:{
                maxFileSize: 10000000
            },
            formData: {
                property: PHPData.id,
                action: "photo"
            },
            add: function(a,b){
                $('#new_file_name_input').val(b.originalFiles[0].name);
                $('#photo_loader').removeClass('fa-camera').addClass('fa-spinner').addClass('fa-spin');
                //alert ("Начинаем загружать фото");
                for (var i = 0; i < b.originalFiles.length; i++){
                    if (b.originalFiles[i].size > 10000000){
                        property.modalInit('upload_limit_modal');
                        $('#upload_limit_modal').modal("show");
                        $('#photo_loader').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-camera');
                    }
                    else{
                        b.submit();
                    }
                }  
           },
           // start: function(){
               // $('#image_loader_div').show();
           // },
            done: function (e, data) {
                //alert("Ответ от сервера получен!");
                property.uploaded_doc_id = null;
                property.uploaded_image_id = data.result;
                slider.reinit();

                //$('#image_loader_div').hide();
                //property.onPhotoOrDocChange("photo_upload", $('#new_file_name_input').val());
                property.modalInit('file_upload_modal');
                $('#file_upload_modal').modal("show");
                $('#photo_loader').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-camera');
            }
        })
    };

    this.modalInit=function(modal_id, offset){
        offset = typeof offset !== 'undefined' ?  offset : 100;
        property.correctModalPosition(modal_id, offset);
        window.onscroll=function () {
            property.correctModalPosition(modal_id, offset);
        };
        $modal=$('#'+modal_id);
        return $modal;
    };

    this.correctModalPosition = function (modal_id, offset) {
        var scrolled = window.pageYOffset || document.documentElement.scrollTop;
        $('#'+modal_id).css({top: scrolled+offset});
    };

    this.initDocUploader=function () {
        $('#doc').fileupload({
            options:{
                maxFileSize: 1000000
            },
            formData: {
                property: PHPData.id,
                action: "property_doc"
            },
            add: function(a,b){
                $('#new_file_name_input').val(b.originalFiles[0].name);

                $('#doc_loader').removeClass('fa-file-text').addClass('fa-spinner').addClass('fa-spin');

                for (var i = 0; i < b.originalFiles.length; i++){
                    if (b.originalFiles[i].size > 1000000){
                        property.modalInit('upload_limit_modal');
                        $('#upload_limit_modal').modal("show");
                        $('#doc_loader').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-file-text');
                    }
                    else{
                        b.submit();
                    }
                }
           },
            start: function(){
               // $('#image_loader_div').show();
            },
            done: function (e, data) {
                property.uploaded_image_id = null;
                property.uploaded_doc_id = data.result;
                slider.reinit();
                //$('#image_loader_div').hide();
                //property.onPhotoOrDocChange("doc_upload", $('#new_file_name_input').val());
                property.modalInit('file_upload_modal');
                $('#file_upload_modal').modal("show");
                $('#doc_loader').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-file-text');
            }
        });

    };

    this.setUploadedFileTitle = function(){
        $('#file_upload_modal').modal('hide');
        if ($('#new_file_name_input').val().trim().length !== 0){
            if (this.uploaded_doc_id != null){ // если документ
                //$('#uploaded_doc_title_'+this.uploaded_doc_id).text($('#new_file_name_input').val().trim());

                $.post("/api/propertydoc/settitle.json",{
                    id: this.uploaded_doc_id,
                    title: $('#new_file_name_input').val().trim()
                },function (response){

                });
            }
            else{ // если изображение
                var image_id = null;

                for (var i = 0; i < imageviewer.thumbs.length; i++){
                    if (imageviewer.thumbs[i].image === this.uploaded_image_id){
                        image_id = imageviewer.thumbs[i].id;
                    }
                }

                //$('#uploaded_image_title_'+image_id).text($('#new_file_name_input').val().trim());

                $.post("/api/photo/settitle.json",{
                    property: this.data != null ? this.data.id : this.temporary_id,
                    file: this.uploaded_image_id,
                    title: $('#new_file_name_input').val().trim()
                },function (response){
                });
            }
        }
        else{
            $('#new_file_name_input').focus();
        }
    };

    this.initImageViewer=function(photos){
        imageviewer = new ImageViewer(photos, 0);
        imageviewer.showLast();
        slider.initImages();
        if(PHPData.mode!="look" && photos.length>=5){
            $('#photo').prop('disabled', true);
            $("label[for='photo']").click(function () {
                property.modalInit('files_limit_error');
                if($('#photo').prop('disabled'))$('#files_limit_error').modal('show');
            })
        }else{
        $('#photo').prop('disabled', false);
        $("label[for='photo']").click(function (){});
    }
    };

    this.initDocViewer=function (docs) {
        docviewer= new DocViewer(docs, 0);
        //docviewer.showLast();
        slider.initDocs();
        if(PHPData.mode=="edit" && docs.length>=5){
            $('#doc').prop('disabled', true);
            $("label[for='doc']").click(function () {
                property.modalInit('files_limit_error');
                if($('#doc').prop('disabled'))$('#files_limit_error').modal('show');
            })
        }else{
            $('#doc').prop('disabled', false);
            $("label[for='doc']").click(function (){});
        }
    };

    this.reinitImages = function(){
        $.post("/api/"+(PHPData.stock == 1 ? "stock" : "property")+"/getphotos.json",{
            iPropertyId: PHPData.id
        },function (response){
            property.initImageViewer(response)
        });
    };

    this.reinitDocs = function(){
        $.post("/api/"+(PHPData.stock == 1 ? "stock" : "property")+"/getdocs.json",{
            iPropertyId: PHPData.id
        },function (response){
            property.initDocViewer(response)
        });
    };

    this.openRemovePhotoDialog = function(photo_id){
        $('#delete_confirm_yes_button').attr("onclick", "property.removePhoto("+photo_id+")");
        property.modalInit('delete_confirm_modal');
        $('#delete_confirm_modal').modal("show");
    };

    this.openRemoveDocDialog = function(doc_id){
        $('#delete_confirm_yes_button').attr("onclick", "property.removeDoc("+doc_id+")");
        property.modalInit('delete_confirm_modal');
        $('#delete_confirm_modal').modal("show");
    };

    this.removePhoto = function(photo_id){
        //this.fixChangeTime();
        //$('#uploaded_image_'+photo_id).css("opacity", "0.2");
        //$('#upload_image_'+photo_id+'_button').children(".fa").removeClass("fa-times").addClass("fa-refresh");
        //$('#upload_image_'+photo_id+'_button').attr("onclick", "property.restorePhoto("+photo_id+")");
        $('#delete_confirm_modal').modal("hide");

        $.post("/api/property/removephoto.json",{
            id: photo_id
        },function (response){
        /*    if (response.error != undefined){
                if (response.error.code == 501){
                    utils.accessErrorModal(response.error.description);
                }
                else{
                    utils.errorModal(response.error.description);
                }
            }
            else{
                //property.initImageViewer();
                property.onPhotoOrDocChange("photo_delete", imageviewer.getNameById(response));
                imageviewer.images_counter--;
                imageviewer.unlockUploadButton();
            }*/
        slider.reinit();
        });
    };

    this.removeDoc = function(doc_id){
        //this.fixChangeTime();
        //$('#uploaded_doc_'+doc_id).css("opacity", "0.2");
        //$('#upload_doc_'+doc_id+'_button').children(".fa").removeClass("fa-times").addClass("fa-refresh");
        //$('#upload_doc_'+doc_id+'_button').attr("onclick", "property.restoreDoc("+doc_id+")");
        $('#delete_confirm_modal').modal("hide");

        $.post("/api/property/removedoc.json",{
            id: doc_id
        },function (response){
            /*if (response.error != undefined){
                if (response.error.code == 501){
                    utils.accessErrorModal(response.error.description);
                }
                else{
                    utils.errorModal(response.error.description);
                }
            }
            else{
                //property.initDocViewer();
                property.onPhotoOrDocChange("doc_delete", docviewer.getNameById(response));
                docviewer.docs_counter--;
                docviewer.unlockUploadButton();
            }*/

            slider.reinit();
        });
    };

    this.selectedMax=function($select, max){
        $select.change(function () {
            var curopts=$select.val();
            var newopts=[];
            if(curopts.length>max) newopts=curopts.slice(0, max);
            if(newopts.length) $select.val(newopts);
            $select.multiselect('rebuild');
        })
    };

    this.checkCityExisting = function(){
        if ($('#locality').val().trim().length === 0){
            $('#route_input').attr("disabled");
        }
    };

    this.checkRequiredFields=function() {
        var result=true;
        $.each(requiredFields, function (i, field) {
            $field=$('#'+field);
            if(!$field.val()){
                result=false;
                $field.css('background-color', 'rgb(242, 222, 222)');
                $field.focus(function () {
                    $(this).css('background-color', '');
                });
                if($field[0].tagName=="SELECT") {
                    $btn=$field.next().find("button");
                        $btn.css('background-color', 'rgb(242, 222, 222)');
                        $btn.click(function () {
                            $btn.css('background-color', '');
                        })
                }
            }
        });
        return result;
    };

    this.showError

    this.submitForm=function () {
        if(this.checkRequiredFields()){
            $('form').submit();
        }
    };

    this.onStockChange = function () {
        $('#stock_warning').show();
        setTimeout(function () {
            $('#stock_warning').hide();
        }, 3000)
    };

    this.owlSession=function(params){
        params.card=PHPData.id;
        params.subject_type="property";
        params.subject_name=PHPData.name;
        $.post("/api/owl/createsession.json", params, function(response){

        });
    };
    
    this.owlNewCall=function (contact) {
        this.owlSession({subject_contact: contact, event_type: 'call-out'});
        this.modalInit('call_ok_bar');
        $('#call_ok_bar').modal('show');
        //window.location="tel:"+contact;
    };
    
    this.owlNewSms=function (contact) {
        this.modalInit('sms_bar');
        $('#sms_bar').modal('show');
        $('#sms_phone').html(contact);
    };
    
    this.owlSendSms=function (contact, text) {
        this.owlSession({subject_contact: contact, event_type: 'sms-out', sms_text: text});
        $('#sms_bar').modal('hide');
        this.modalInit('sms_ok_bar');
        $('#sms_ok_bar').modal('show');
        //window.location="sms:"+contact+"?body="+text;

    };

    this.showCalendarEventModal = function(){
        //loadCalendarApi();

        var event_title = this.data.name != null ? this.data.name : "";

        for (var i = 1; i <= 4; i++){
            if (this.data["contact"+i] != null && this.data["contact"+i].length > 0){
                event_title += (i === 1 ? " " : "/")+this.data["contact"+i];
            }
        }

        var street=

        $('#event_title_input').val(event_title+" "+PHPData.street_name.short_name);
        this.modalInit('to_cal_modal', 10);
        $('#to_cal_modal').modal('show');
    };

    this.createCalendarEvent = function(){
        var title = $('#event_title_input').val().trim();
        var start_date = $('#event_start_input').val().trim();
        var start_time = $('#event_start_time_select').val().trim();
        var end_date = $('#event_end_input').val().trim();
        var end_time = $('#event_end_time_select').val().trim();

        if (title.length !== 0 && start_date.length !== 0 && end_date.length !== 0){
            //url="https://www.google.com/calendar/render?action=TEMPLATE" +
            //    "&text="+title+
            //    "&dates="+start_date+"T"+start_time+"/"+end_date+"T"+end_time;
            //window.open(url);

            createSimpleEvent(title, start_date+"T"+start_time, end_date+"T"+end_time);
        }
    };

    $("[id^=contact]").blur(function () {
        if($(this).val()=='') return;
        $.post("/api/property/searchbyphone.json", {
            //object_type: "client",
            id: PHPData.id,
            phone: $(this).val()
        }, function (response) {
            if(response){
                $('#phone_dublicated_link').attr({href: '/property?iPropertyId='+response});
                property.modalInit('phone_dublicated_modal', 100).modal('show');
                //$('#phone_dublicated_modal').modal('show');
            }
        });
    });

    $('#stock_check').iCheck();
    
    this.showLegend = function(){
        $('#legend_modal').show();
    };
    
    this.closeLegend = function(){
        $('#legend_modal').hide();
    };
    
    this.showLoader = function(){
        $('#loader_modal').show()
    };
    
    this.check = function(){
        $.post("/api/search/check.json",{
            object_type: "property",
            object_id: urlparser.getParameter("iPropertyId")
        },function (response){
            if (response.error != undefined)
                alert(response.error.description);
            else{
                if (response == 0){
                    alert("There are no the same phone cards.");
                }
                else{
                    location.href = "response?id="+response;
                }
            }
        });
    };
    
    this.addToEvents = function(event){
        $('#to_events_modal').modal('show');
        
        property_event.reinit();
        property_event.setEvent(event);
        var event_title = this.data["contact1"]+" "+(event != "event_notification" ? localization.getVariable(event) : PHPData.street_name.short_name);
        
        $('#add_event_title_input').val(event_title);
        $('#to_event_modal').modal('show');
        
    };
}

function PropertyEvent(){
    this.event = null;
    this.notification_removed = false;
    
    this.reinit = function(){
        this.notification_removed = false;
        $('#to_events_modal .notification').show();
    };
    
    this.create = function(){
        var title = $('#add_event_title_input').val().trim();
        var start_hour = $('#add_event_start_time_select').val().split(":")[0]*3600;
        var end_hour = $('#add_event_end_time_select').val().split(":")[0]*3600;
        var start_minute = $('#add_event_start_time_select').val().split(":")[1]*60;
        var end_minute = $('#add_event_end_time_select').val().split(":")[1]*60;
        var start = $('#add_event_start_input').datepicker("getDate")/1000+start_hour+start_minute;
        var end = $('#add_event_end_input').datepicker("getDate")/1000+end_hour+end_minute;
        var notification = $('#add_event_notification_period_input').val()*60;
        var email = $('#notify_by_email_check:checked').length;
        var property_id = property.data == null ? property.temporary_id : property.data.id;
        
        if (title.length > 0){
            subscribe();
            
            $.post("/api/propertyevent/create.json",{
                property: property_id,
                event: this.event,
                title: title,
                start: start,
                end: end,
                notification: this.notification_removed ? 0 : notification,
                email: email                
            },function (response){
                if (response.error != undefined){
                    utils.errorModal(response.error.description);
                }
                else{
                    $('#'+response.event).text(utils.getDateOnlyFromTimestamp(response.start));
                    //$('a.'+response.event).attr("onclick", "");
                    $('#to_events_modal').modal("hide");
                    //utils.successModal(localization.getVariable("event_successfully_created"));
                    $('#to_events_modal_success').modal("show");
                }
            });
            
            var start_date = $('#add_event_start_input').val();
            var start_time = $('#add_event_start_time_select').val()+":00";
            var end_date = $('#add_event_end_input').val();
            var end_time = $('#add_event_end_time_select').val()+":00";
            
            createSimpleEvent(title+", "+localization.getVariable("card_noregister_span")+" "+property_id, start_date+"T"+start_time, end_date+"T"+end_time);
        }
    };
    
    this.setEvent = function(event){
        this.event = event;
    };
    
    this.removeNotification = function(){
        this.notification_removed = true;
        $('#to_events_modal .notification').hide();
    };
}

function hourForwardForEvent(){
    var time = $('#add_event_start_time_select').val();
    var hour = Number(time.split(":")[0]);
    var minute = time.split(":")[1];
    var last_time = hour != 23 ? (hour <= 8 ? "0"+(hour+1) : hour+1)+":"+minute : "00:00";
    $('#add_event_end_time_select').val(last_time);
}

function showCalendarEventSuccess(){
    $('#calendar_success').show();
    setTimeout(function () {
        $('#calendar_success').hide();
    }, 3000)
}

function URLparser(){
   this.url_params = {};
   this.url_string = "";
   
    this.getParameter = function(parameter){
      var params_string = window.location.href.slice(window.location.href.indexOf('?') + 1);
      var params = params_string.split("&");
      var result = {};
      
      for (var i = 0; i < params.length; i++){
         var tmp = params[i].split("=");
         result[tmp[0]] = tmp[1];
      }
      
      return result[parameter];
   };
   
   this.setParameter = function(parameter, value){
      this.url_params[parameter] = value;
      this.url_string = "";
      
      for (var key in this.url_params)
         this.url_string += key+"="+this.url_params[key]+"&";
         
      window.history.pushState(null, null, "?"+(this.url_string = this.url_string.substring(0, this.url_string.length - 1)));
   };
   
   this.clearParams = function(){
      this.url_params = {};
   };
   
   this.getParams = function(){
      var params_string = window.location.href.slice(window.location.href.indexOf('?') + 1);
      var params = params_string.split("&");
      var result = {};
      for (var i = 0; i < params.length; i++){
         var tmp = params[i].split("=");
         result[tmp[0]] = tmp[1];
      }
      
      this.url_papams = result;
      return result;
   };
}