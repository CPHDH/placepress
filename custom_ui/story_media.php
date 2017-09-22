<script>
	var hasValue;
	jQuery('#story_media_row td input[id^="story_media"]').each(function(){
		hasValue=jQuery(this).val().length > 0;
		jQuery(this).after(''+
		'<button title="Select Media" class="story_media_picker button '+(hasValue ? 'hidden' : '')+'"><span class="dashicons dashicons-admin-media"></span> Select</button>'+
		'<button title="Remove" class="button remove '+(!hasValue ? 'hidden' : '')+'" onclick="return remove_field(this)"><span class="dashicons dashicons-no"></span> Remove</button>');
	});

	var remove_field=function(e){
		jQuery(e).siblings('input').val('').parent('div').hide();
		return false;		
	}

	
	var curatescape_media_picker_init = function(selector, button_selector)  {
		// VIA: https://www.gavick.com/blog/use-wordpress-media-manager-plugintheme
	    var clicked_button = false;
	    jQuery(selector).each(function (i, input) {
	        var button = jQuery(input).next(button_selector);
	        button.click(function (event) {
	            event.preventDefault();
	            var selected_img;
	            clicked_button = jQuery(this);
	 
	            // check for media manager instance
	            if(wp.media.frames.curatescape_frame) {
	                wp.media.frames.curatescape_frame.open();
	                return;
	            }
	            // configuration of the media manager new instance
	            wp.media.frames.curatescape_frame = wp.media({
	                title: 'Select file',
	                multiple: false,
	                library: {
	                    type: ['audio','image','video']
	                },
	                button: {
	                    text: 'Use selected file'
	                }
	            });
	 
	            // Function used for the image selection and media manager closing
	            var curatescape_media_set_image = function() {
	                var selection = wp.media.frames.curatescape_frame.state().get('selection');
	 
	                // no selection
	                if (!selection) {
	                    return;
	                }
	 
	                // iterate through selected elements
	                selection.each(function(attachment) {
	                    var url = attachment.attributes.url;
	                    clicked_button.prev(selector).val(url);
	                });
	                
	                jQuery(clicked_button).hide().siblings('button').show();
	                
	            };
	 
	            // closing event for media manger
	            wp.media.frames.curatescape_frame.on('close', curatescape_media_set_image);
	            // image selection event
	            wp.media.frames.curatescape_frame.on('select', curatescape_media_set_image);
	            // showing media manager
	            wp.media.frames.curatescape_frame.open();
	        });
	   });
	};
	
	curatescape_media_picker_init('#story_media_row td input[id^="story_media"]', '.story_media_picker');
	
</script>
	