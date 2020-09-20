var modal=function (modal_id, offset) {

    this.id=modal_id;
    this.offset=offset;

    this.correctPosition=function () {
        var scrolled = window.pageYOffset || document.documentElement.scrollTop;
        $('#'+this.id).css({top: scrolled+this.offset});
    };

    offset = typeof offset !== 'undefined' ?  offset : 100;
    this.correctPosition();
    window.onscroll=function () {
        this.correctPosition();
    }.bind(this);

    $('#'+modal_id).modal('show');


};