var buttons = jQuery('.repeatable_button');
jQuery(buttons).each(function(){
	jQuery(this).click(function(e){
		var el = jQuery(this).siblings('input.hidden,textarea.hidden').first();
		jQuery(el).removeClass('hidden');
	})
});