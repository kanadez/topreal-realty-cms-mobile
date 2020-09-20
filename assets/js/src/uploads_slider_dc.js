function UploadsSlider(object_for){
    this.step = 200;
    this.w = 154;
    this.finish_margin = null;
    this.start_margin = 0;
    this.move_block = 0;

    this.$main_img=$('#images_row');
    this.$main_doc=$('#docs_row');

    this.reinit = function(){
        this.$main_img.html("");
        this.$main_doc.html("");

        if (object_for=="property"){
            property.reinitImages();
            property.reinitDocs();
        }
        else if (object_for=="client"){
            client.reinitDocs();
        }
    };

    this.initImages = function(){
        if (imageviewer.thumbs.length > 0 && property.just_created === 0){
            $('.gallery_element_box_empty').hide();

            var hidden_if_not_edit="";
            if(PHPData.mode!="edit") hidden_if_not_edit="hidden";

            this.$main_img.html("");

            for (var i = 0; i < imageviewer.thumbs.length; i++){

                this.$main_img.append(
                    '<div id="first_gallery_box_div" class="gallery_element_box">\n\
                        <div class="gallery_element_box_img_wrapper">\n\
                            <img id="uploaded_image_'+imageviewer.thumbs[i].id+'" src="http://topreal.top/storage/'+imageviewer.thumbs[i].image+'" onload="stretchCatalogPhoto(this)" style="width: 160px; height: 120px;" />\n\
                            <button id="upload_image_'+imageviewer.thumbs[i].id+'_button" type="button" onclick="property.openRemovePhotoDialog('+imageviewer.thumbs[i].id+')" class="btn '+hidden_if_not_edit+' btn-sm delete_upload_button"><i class="fa fa-times"></i></button>\n\
                        </div>\n\
                        <div id="image_zoom_div" class="zoom" onclick="openPhotoSwipe('+i+')"></div>\n\
                        <div class="caption">'+imageviewer.thumbs[i].name+'</div>\n\
                    </div>');
            }

            if (property.mode === 1){
                $('.delete_upload_button').attr("disabled", true);
            }

/*            if (imageviewer.thumbs.length === 1){
                this.$main_img.append(
                    '<div class="gallery_element_box_empty">\n\
                        <div class="gallery_element_box_img_wrapper">\n\
                            <span locale="no_image">No image</span>\n\
                        </div>\n\
                    </div>\n\
                    <div class="gallery_element_box_empty">\n\
                        <div class="gallery_element_box_img_wrapper">\n\
                            <span locale="no_image">No image</span>\n\
                        </div>\n\
                    </div>');
            }
            else if (imageviewer.thumbs.length === 2){
                this.$main_img.append(
                    '<div class="gallery_element_box_empty">\n\
                        <div class="gallery_element_box_img_wrapper">\n\
                            <span locale="no_image">No image</span>\n\
                        </div>\n\
                    </div>');
            }*/

            this.finish_margin = $('.gallery_element_box').length*this.w-$('#vip_content_wrapper_div').width();
        }
    };

    this.initDocs = function(){
        var just_created_flag = null;

        var hidden_if_not_edit="";
        if(PHPData.mode!="edit") hidden_if_not_edit="hidden";

        if (object_for=="property"){
            just_created_flag = property.just_created;
        }
        else if (object_for=="client"){
            just_created_flag = client.just_created;
        }

        if (docviewer.docs.length > 0 && just_created_flag === 0){
            $('.gallery_element_box_empty').hide();

            if (object_for=="property"){
                var subject = "property";
            }
            else{
                var subject = "client";
            }

            this.$main_doc.append(
                '<ul id="doc_list"></ul>'
            );

            for (var i = 0; i < docviewer.docs.length; i++){
                $('#doc_list').append(
                    '<li class="fa fa-file-text">\n\
                            <a href="https://docs.google.com/viewer?url='+docviewer.host+'/storage/'+docviewer.docs[i].location+'" target="_blank"> <span id="uploaded_doc_title_'+docviewer.docs[i].id+'">'+docviewer.docs[i].name+'</span></a>\n\
                            <button id="upload_doc_'+docviewer.docs[i].id+'_button" type="button" onclick="'+subject+'.openRemoveDocDialog('+docviewer.docs[i].id+')" class="btn '+hidden_if_not_edit+' btn-sm"><i class="fa fa-times"></i></button>\n\
                    </li>');
            }

            if ((object_for=="property" && property.mode === 1) || (object_for=="client" && client.mode === 1)){
                $('.delete_upload_button').attr("disabled", true);
            }

 /*           if (docviewer.docs.length === 1){
                this.$main_doc.append(
                    '<div class="gallery_element_box_empty">\n\
                        <div class="gallery_element_box_img_wrapper">\n\
                            <span locale="no_image">No image</span>\n\
                        </div>\n\
                    </div>\n\
                    <div class="gallery_element_box_empty">\n\
                        <div class="gallery_element_box_img_wrapper">\n\
                            <span locale="no_image">No image</span>\n\
                        </div>\n\
                    </div>');
            }else if (docviewer.docs.length === 2){
                this.$main_doc.append(
                    '<div class="gallery_element_box_empty">\n\
                        <div class="gallery_element_box_img_wrapper">\n\
                            <span locale="no_image">No image</span>\n\
                        </div>\n\
                    </div>');
            }*/

            this.finish_margin = $('.gallery_element_box').length*this.w-$('#vip_content_wrapper_div').width();
        }
    };

    this.right = function(){
        if (this.move_block === 0 && $('#first_gallery_box_div').css("margin-left") != undefined){
            this.move_block = 1;
            var margin = Number($('#first_gallery_box_div').css("margin-left").replace("px", ""));

            if (-margin <= this.finish_margin){
                $('#first_gallery_box_div').animate({"marginLeft":"-="+this.step},100, function(){uslider.move_block = 0;});
            }
            else{
                this.move_block = 0;
            }
        }
    };

    this.left = function(){
        if (this.move_block === 0){
            var margin = Number($('#first_gallery_box_div').css("margin-left").replace("px", ""));

            if (margin <= this.start_margin){
                $('#first_gallery_box_div').animate({"marginLeft":"+="+this.step},100, function(){uslider.move_block = 0;});
            }
            else{
                this.move_block = 0;
            }
        }
    };

}

function stretchCatalogPhoto(photo){
    var image = $(photo);

    if (image.width() < image.height()){
        var ratio = image.height()/image.width();
        image.css({width: "150px", height: 150*ratio+"px"});
    }
    else if (image.width() >= image.height()){
        var ratio = image.width()/image.height();
        image.css({width: 120*ratio, height: 120});
    }
}