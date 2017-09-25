<script>
	var hasValue;
	var inputs=jQuery('#story_media_row td input[id^="story_media"]');
	jQuery(inputs).each(function(){
		// add UI - select and remove buttons
		hasValue=jQuery(this).val().length > 0;
		jQuery(this).before('<div class="media-thumb" style="background-image:url('+(hasValue ? jQuery(this).val() : '')+')"></div>');
		jQuery(this).after(''+
		'<button title="Select Media" class="story_media_picker button '+(hasValue ? 'hidden' : '')+'"><span class="dashicons dashicons-admin-media"></span> Select</button>'+
		'<button title="Remove" class="button remove '+(!hasValue ? 'hidden' : '')+'" onclick="return remove_field(this)"><span class="dashicons dashicons-no"></span> Remove</button>');
	});

	var remove_field=function(e){
		// clear value and remove input UI
		jQuery(e).siblings('input').val('').parent('div').hide();
		return false;		
	}

	jQuery(inputs).on("change",function(){
		console.log('...');	
	});
	
	var curatescape_media_picker_init = function(selector, button_selector)  {
		// See: https://codex.wordpress.org/Javascript_Reference/wp.media
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
	                },
	                searchable: true,
	                filterable: 'all',						                
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
	                    jQuery(clicked_button).siblings('.media-thumb').css('background-image','url('+url+')');
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
	