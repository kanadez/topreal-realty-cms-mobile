/**
 * Created by Darkcooder on 02.03.2018.
 */
function Tagit($tags) {
    this.view=$($tags).tagit();
    this.labels=[];
    this.ids=[];

    this.init=function(labels, ids){
        if(typeof ids!='undefined')$.each(ids, function (i, id) {
            if(typeof labels[i]!='undefined') this.addTag(labels[i], id);
        }.bind(this));
        return this;
    };

    this.addTag=function (label, id) {
        this.labels.push(label);
        this.ids.push(id);
        this.view.tagit("createTag", label);
        return this;
    };

    this.onTagRemove=function (label) {
        var index=this.labels.indexOf(label);
        this.labels.splice(index, 1);
        this.ids.splice(index, 1);
        return this;
    };

    this.view.tagit({afterTagRemoved: function(event, ui){
        this.onTagRemove(ui.tagLabel);
    }.bind(this)});

    this.getInput=function () {
        return this.view.children('.tagit-new').children('input');
    };

    this.jsonIds=function(){
        return JSON.stringify(this.ids);
    };

    return this;
}
