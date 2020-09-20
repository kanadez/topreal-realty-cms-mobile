var datainput = new DataInput();
var utils = new Utils();
//var localization = new Localization();
var synonim = new Synonim("client");
var ac_synonim = synonim;
//var ac_synonim = new AutocompleteSynonim("client");
var ac = new Autocomplete("client");
var client = new Client();
var search = client;
var slider;

var requiredFields=["ascription", "property_types", "price_from", "price_to", "country", "contact1"];

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

function Client(){
    this.geoloc = {};
    this.defaults = {};
    this.just_created=0;
    //this.data=PHPData.data;

    this.init = function(){
        if(PHPData.mode!="look")initAutocomplete();

        //localization.init();
        client.data=PHPData.data;


        client.geoloc.country = PHPData.country;

        client.geoloc.city = PHPData.city;
        ac.getCityLocales(PHPData.city);
        client.current_city = PHPData.city;

        client.geoloc.lat = PHPData.geo.lat;

        client.geoloc.lng = PHPData.geo.lat;

        client.geoloc.street = PHPData.street;

        $("#free_from").datepicker({dateFormat: "dd.mm.yy"})


        if(PHPData.mode!="look")placeDetailsByPlaceId(client.geoloc.country, service_country, $('#country'));
        if(PHPData.mode!="look")placeDetailsByPlaceId(client.geoloc.city, service_city, $('#city'));
        //streetDetailsByPlaceId();
        $("#street").focus(function () {
            geolocate();
            ac.search(this);
            synonim.search(this);
        });
        $("#street").keyup(function () {
            ac.search(this);
            synonim.search(this);
        });

        app.customCheckbox();

        $('#stock_check').next().on('click', function(event){
            client.onStockChange();
        });


        this.selectedMax($("#types"), 4);

        slider=new UploadsSlider("client");

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
            formData: {
                client: PHPData.id,
                action: "photo"
            },
            add: function(a,b){
                $('#new_file_name_input').val(client.image_new_filename_prefix+b.originalFiles[0].name);
                $('#photo_loader').removeClass('fa-camera').addClass('fa-spinner');
                //alert ("Начинаем загружать фото");
                b.submit();
            },
            // start: function(){
            // $('#image_loader_div').show();
            // },
            done: function (e, data) {
                //alert("Ответ от сервера получен!");
                client.uploaded_doc_id = null;
                client.uploaded_image_id = data.result;
                slider.reinit();

                //$('#image_loader_div').hide();
                //client.onPhotoOrDocChange("photo_upload", $('#new_file_name_input').val());
                client.modalInit('file_upload_modal');
                $('#file_upload_modal').modal("show");
                $('#photo_loader').removeClass('fa-spinner').addClass('fa-camera');
            }
        })
    };

    this.modalInit=function(modal_id, offset){
        offset = typeof offset !== 'undefined' ?  offset : 100;
        client.correctModalPosition(modal_id, offset);
        window.onscroll=function () {
            client.correctModalPosition(modal_id, offset);
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
                client: PHPData.id,
                action: "client_doc"
            },
            add: function(a,b){
                $('#new_file_name_input').val(client.doc_new_filename_prefix+b.originalFiles[0].name);

                $('#doc_loader').removeClass('fa-file-text').addClass('fa-spinner');

                for (var i = 0; i < b.originalFiles.length; i++){
                    if (b.originalFiles[i].size > 1000000){
                        client.modalInit('upload_limit_modal');
                        $('#upload_limit_modal').modal("show");
                        $('#doc_loader').removeClass('fa-spinner').addClass('fa-file-text');
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
                client.uploaded_image_id = null;
                client.uploaded_doc_id = data.result;
                slider.reinit();
                //$('#image_loader_div').hide();
                //client.onPhotoOrDocChange("doc_upload", $('#new_file_name_input').val());
                client.modalInit('file_upload_modal');
                $('#file_upload_modal').modal("show");
                $('#doc_loader').removeClass('fa-spinner').addClass('fa-file-text');
            }
        });

    };

    this.setUploadedFileTitle = function(){
        $('#file_upload_modal').modal('hide');
        if ($('#new_file_name_input').val().trim().length !== 0){
            if (this.uploaded_doc_id != null){ // если документ
                //$('#uploaded_doc_title_'+this.uploaded_doc_id).text($('#new_file_name_input').val().trim());

                $.post("/api/clientdoc/settitle.json",{
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
                    client: this.data != null ? this.data.id : this.temporary_id,
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
                client.modalInit('files_limit_error');
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
                client.modalInit('files_limit_error');
                if($('#doc').prop('disabled'))$('#files_limit_error').modal('show');
            })
        }else{
            $('#doc').prop('disabled', false);
            $("label[for='doc']").click(function (){});
        }
    };

    this.reinitImages = function(){
        $.post("/api/"+(PHPData.stock == 1 ? "stock" : "client")+"/getphotos.json",{
            iclientId: PHPData.id
        },function (response){
            client.initImageViewer(response)
        });
    };

    this.reinitDocs = function(){
        $.post("/api/"+(PHPData.stock == 1 ? "stock" : "client")+"/getdocs.json",{
            client_id: PHPData.id
        },function (response){
            client.initDocViewer(response)
        });
    };

    this.openRemovePhotoDialog = function(photo_id){
        $('#delete_confirm_yes_button').attr("onclick", "client.removePhoto("+photo_id+")");
        client.modalInit('delete_confirm_modal');
        $('#delete_confirm_modal').modal("show");
    };

    this.openRemoveDocDialog = function(doc_id){
        $('#delete_confirm_yes_button').attr("onclick", "client.removeDoc("+doc_id+")");
        client.modalInit('delete_confirm_modal');
        $('#delete_confirm_modal').modal("show");
    };

    this.removePhoto = function(photo_id){
        //this.fixChangeTime();
        //$('#uploaded_image_'+photo_id).css("opacity", "0.2");
        //$('#upload_image_'+photo_id+'_button').children(".fa").removeClass("fa-times").addClass("fa-refresh");
        //$('#upload_image_'+photo_id+'_button').attr("onclick", "client.restorePhoto("+photo_id+")");
        $('#delete_confirm_modal').modal("hide");

        $.post("/api/client/removephoto.json",{
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
             //client.initImageViewer();
             client.onPhotoOrDocChange("photo_delete", imageviewer.getNameById(response));
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
        //$('#upload_doc_'+doc_id+'_button').attr("onclick", "client.restoreDoc("+doc_id+")");
        $('#delete_confirm_modal').modal("hide");

        $.post("/api/client/removedoc.json",{
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
             //client.initDocViewer();
             client.onPhotoOrDocChange("doc_delete", docviewer.getNameById(response));
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
            $('#street_id').val(streetTags.jsonIds());
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
        params.subject_type="client";
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

            $('#event_title_input').val(event_title);
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
    

    
    $('#geo_mode').change(function () {
        if($('#geo_mode').val()==0) this.streetGeoMode();
        else this.contourGeoMode();
    }.bind(this));

    //Проверка номера телефона

    $("[id^=contact]").blur(function () {
        if($(this).val()=='') return;
        $.post("/api/client/searchbyphone.json", {
            //object_type: "client",
            id: PHPData.id,
            phone: $(this).val()
        }, function (response) {
            if(response){
                $('#phone_dublicated_link').attr({href: '/client?id='+response});
                client.modalInit('phone_dublicated_modal', 100).modal('show');
                //$('#phone_dublicated_modal').modal('show');
            }
        });
    });

    this.streetGeoMode = function(){
        $('#geo_mode').val(0);
        $('#contour').attr({name: null}).next().hide();
        $('#vi_contour').hide();
        $('#blank_input').attr({name: "contour"});

        $('#street_id').attr({name: "street"});
        $('#streetTags').show();
    };
    
    this.contourGeoMode = function(){
        $('#geo_mode').val(1);
        $('#streetTags').hide();
        $('#vi_street').hide();
        $('#street_id').attr({name: null});
        $('#blank_input').attr({name: "street"});

        $('#contour').attr({name: "contour"}).next().show();
    };

    $(document).ready(function () {
        if(PHPData.data.contour==null) this.streetGeoMode();
        else this.contourGeoMode();
    }.bind(this));


}

function showCalendarEventSuccess(){
    $('#calendar_success').show();
    setTimeout(function () {
        $('#calendar_success').hide();
    }, 3000)
}

