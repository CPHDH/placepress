// repeatable buttons
var buttons = jQuery('.repeatable_button');
jQuery(buttons).each(function(){
	jQuery(this).click(function(e){
		var el = jQuery(this).siblings('.hidden').first();
		jQuery(el).removeClass('hidden');
	})
});

// adjust initial height for textareas with longer content
/*
function setHeight(jq_in){
    jq_in.each(function(index, elem){
        elem.style.minHeight = elem.scrollHeight+'px'; 
    });
}
*/
// setHeight(jQuery('.postbox-container textarea[id^="story_"], .postbox-container textarea[id^="tour_"]'));

// helpers
Array.prototype.unique = function() {
    var a = this.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};