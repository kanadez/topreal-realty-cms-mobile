/**
 * Created by Владимир on 27.02.2018.
 */
var utils=new Utils();
$('input.icheck').iCheck({
    checkboxClass: 'icheckbox_flat-grey',
    radioClass: 'iradio_flat-grey'
});

var response = { 
    search_id: PHPData.conditions.id,
    all_marked: 0, 
    clients: PHPData.clients, 
    properties: PHPData.properties, 
    properties_checked: 0, 
    clients_checked: 0, 
    reduced: 0
};

$(document).ready(function () {
    $.each(response.properties, function (i, property) {
        $('#property_'+property.id+'_check').on('ifChecked', function () {
            response.properties_checked++;
            response.countUpdate();
            $('#property_'+property.id+"_row").removeClass("row_").addClass("row_checked");
        });
        $('#property_'+property.id+'_check').on('ifUnchecked', function () {
            response.properties_checked--;
            response.countUpdate();
            $('#property_'+property.id+"_row").removeClass("row_checked").addClass("row_");
        });
    })
});

response.properties_checked=PHPData.properties_checked.length;

response.reduce=function () {
    $(".row_").hide();
    $(".row_checked").show();
    this.reduced=1;
};

response.unreduce=function () {
    $(".row_").show();
    this.reduced=0;
};

$('#reduce_button').click(function () {
    if(this.properties_checked+this.clients_checked<=0){
        modal("reduce_null_modal", 200);
        return;
    }
    if(this.reduced){
        this.unreduce();
        $('#reduce_button').removeClass('btn-info').addClass('btn-default');
    }
    else{
        this.reduce();
        $('#reduce_button').removeClass('btn-default').addClass('btn-info');
    }
}.bind(response));

response.markAll = function(){
    this.client_phones = [];
    this.property_phones = [];

    if (this.reduced === 1){
        this.reduce();
    }

    if (this.all_marked === 0){
        //$('#unmark_all_button').show();
        //$('#mark_all_button').hide();
        $('.card_selected').addClass("card_not_selected").removeClass("card_selected");

        if (this.clients != null){
            $('#client_all_check').iCheck('check');
            $('#client_expanded_all_check').iCheck('check');

            for (var i = 0; i < this.clients.length; i++){
                this.client_phones.push({card: Number(this.clients[i].id)});
                $('#client_'+this.clients[i].id+'_check').iCheck('check');
                $('#client_'+this.clients[i].id+'_check_expanded').iCheck('check');
            }
        }

        if (this.properties != null){
            $('#property_all_check').iCheck('check');
            $('#property_expanded_all_check').iCheck('check');

            for (var i = 0; i < this.properties.length; i++){
                this.property_phones.push({card: Number(this.properties[i].id)});
                $('#property_'+this.properties[i].id+'_check').iCheck('check');
                $('#property_'+this.properties[i].id+"_row").removeClass("row_").addClass("row_checked");
                //$('#property_'+this.properties[i].id+'_check_expanded').iCheck('check');
            }
        }

        $('.card_not_selected').addClass("card_selected").addClass("hl_yellow").removeClass("card_not_selected");
        $('#property_results_table_expanded .card_selected').children("td").addClass("hl_yellow");
        $('#client_results_table_expanded .card_selected').children("td").addClass("hl_yellow");
        this.all_marked = 1;
        //$('#mark_all_button').text("Unmark all");
    }
    else if (this.all_marked === 1){
        //$('#unmark_all_button').hide();
        //$('#mark_all_button').show();
        $('.card_selected').removeClass("hl_yellow").removeClass("card_selected").addClass("card_not_selected");
        $('#property_results_table_expanded .card_not_selected').children("td").removeClass("hl_yellow");
        $('#client_results_table_expanded .card_not_selected').children("td").removeClass("hl_yellow");
        $('.list_icheck').iCheck("uncheck");
        $('#client_all_check').iCheck('uncheck');
        $('#property_all_check').iCheck('uncheck');
        $('#property_expanded_all_check').iCheck('uncheck');
        $('#client_expanded_all_check').iCheck('uncheck');
        if (this.properties != null){
            $('#property_all_check').iCheck('check');
            $('#property_expanded_all_check').iCheck('check');

            for (var i = 0; i < this.properties.length; i++){
                //this.property_phones.push({card: Number(this.properties[i].id)});
                $('#property_'+this.properties[i].id+'_check').iCheck('uncheck');
                $('#property_'+this.properties[i].id+"_row").removeClass("row_checked").addClass("row_");
                //$('#property_'+this.properties[i].id+'_check_expanded').iCheck('check');
            }
        }
        this.all_marked = 0;
        //$('#mark_all_button').text("Mark all");
    }
        this.properties_checked=this.property_phones.length;
        this.clients_checked=this.client_phones.length

};

response.countUpdate=function () {
    $('#search_entries_marked_span').html(this.properties_checked+this.clients_checked);
};



var mac=$('#mark_all_checkbox');
    mac.on('ifClicked', response.markAll.bind(response));
    
response.showLegend = function(){
    $('#legend_modal').show();
};

response.closeLegend = function(){
    $('#legend_modal').hide();
};
    
response.owlNewCall = function(subject, card, phone, name) {
    response.owlSession({
        subject_contact: phone, 
        event_type: "call-out", 
        subject_type: subject, 
        card: card, 
        subject_name: name
    });
    response.modalInit('call_ok_bar');
    $('#call_ok_bar').modal('show');
    
    return false;
};

response.owlSession=function(params){
    $.post("/api/owl/createsession.json", params, null);
};

response.modalInit=function(modal_id, offset){
    offset = typeof offset !== 'undefined' ?  offset : 100;
    response.correctModalPosition(modal_id, offset);
    window.onscroll=function () {
        response.correctModalPosition(modal_id, offset);
    };
    $modal=$('#'+modal_id);
    return $modal;
};

response.correctModalPosition = function (modal_id, offset) {
    //var scrolled = document.documentElement.scrollTop;
    $('#'+modal_id).css({top: 150});
};

response.openPreContourOnList = function(contour_id, search_id){
    showLoader();
    
    $.post("/api/contour/copy.json",{
        id: contour_id,
        search: search_id
    }, function(){
        location.href = "response?id="+response.search_id;
    });
};