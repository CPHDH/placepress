<div id="selected-media-preview">
	<?php 
	$table_rows = '';
	if($post->story_media){
		$media = explode(',',$post->story_media);
		foreach($media as $attachment_id){
			
			$id = intval( $attachment_id );
			$attachment_meta = wp_prepare_attachment_for_js($id);
			
			$type = $attachment_meta['type'];
			$subtype = isset($attachment_meta['subtype']) ? strtoupper($attachment_meta['subtype']) : __('Unknown Format');
			$title = $attachment_meta['title'] ? $attachment_meta['title'] : __('Untitled');
			$url= ($attachment_meta['type']=='image') ? $attachment_meta['url'] : $attachment_meta['icon'];
			$filesizeHumanReadable = $attachment_meta['filesizeHumanReadable'];
			$duration = isset($attachment_meta['fileLength']) ? $attachment_meta['fileLength'] : null;
			$dimensions = isset($attachment_meta['width']) ? $attachment_meta['width'].' x '.$attachment_meta['height'].' pixels' : null;
			$editLink = '<a class="edit_link button" href="'.$attachment_meta['editLink'].'" target="_blank">'.__('Edit File Metadata').'</a>';
			$caption=$attachment_meta['caption'] ? $attachment_meta['caption'] : 'This file does not have a caption.';
			$description=$attachment_meta['description'] ? $attachment_meta['description'] : 'This file does not have a description.';	
			$editActions=$editLink.' <a class="button  remove_link" href="" onclick="return removeMedia('.$id.');">'.__('Remove').'</a>';
			
			$inlineMeta=array_filter(array( $duration, $dimensions, $filesizeHumanReadable, $subtype ));		
			$inlineMeta='<div class="inline-meta">'.implode(' ~ ',$inlineMeta).'</div>';
			
	    	$table_rows .= '<tr data-id="'.$id.'"><td><div class="preview-image '.$type.'" style="background-image:url('.$url.');"></div></td>';
	    	$table_rows .= '<td><h3>'.$title.'</h3>'.$inlineMeta.'<div>'.$description.'</div><div class="actions">'.$editActions.'</div></td></tr>';		
		}
		
		echo '<table><thead><tr><th>Media Preview</th><th>Media Details</th></tr></thead><tbody>'.$table_rows.'</tbody></table>';
	}
	?>
</div>

<script>
	var input=jQuery('#story_media_row td input#story_media');
	if(jQuery('#selected-media-preview table tbody').length){
		var tbody=jQuery('#selected-media-preview table tbody');
	}else{
		jQuery('#selected-media-preview').append('<table><thead><tr><th>Media Preview</th><th>Media Details</th></tr></thead><tbody></tbody></table>');
		var tbody=jQuery('#selected-media-preview table tbody');
	}
	
	// hide the text input field and add the media select button
	jQuery(input).hide().after('<button title="Select Media" class="story_media_picker button "><span class="dashicons dashicons-admin-media"></span> Select</button>');
	
	function removeMedia(id){
		var vals=jQuery(input).val().split(',').map(Number);
		var remove = vals.indexOf(id);
		if(remove != -1) {
			vals.splice(remove, 1);
			jQuery(input).val(vals);
			jQuery('#selected-media-preview table tr[data-id='+id+']').fadeOut();
		}	
			
		return false;
	}
	
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
	                multiple: true,
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
		            var table_row='';
	                var selection = wp.media.frames.curatescape_frame.state().get('selection');
					var existing_selection=jQuery(input).val();
			        existing_selection=existing_selection.split(',').map(Number);	
			        	 
	                // no selection
	                if (!selection) {
	                    return;
	                }
					
	                // iterate through selected elements
	                new_selection = new Array();
	                selection.each(function(attachment) {

	                    var url = attachment.attributes.url;
	                    var id = attachment.id;
	                    var title = attachment.attributes.title ? attachment.attributes.title : 'Untitled';
	                    var description = attachment.attributes.description ? attachment.attributes.description : 'This file does not have a description.';
	                    var type = attachment.attributes.type;
	                    var subtype = attachment.attributes.subtype ? attachment.attributes.subtype : 'Unknown Format';
	                    var filesizeHumanReadable = attachment.attributes.filesizeHumanReadable;
	                    var duration = attachment.attributes.fileLength ? attachment.attributes.fileLength : null;
	                    var dimensions = attachment.attributes.width ? attachment.attributes.width+' x '+attachment.attributes.height+' pixels' : null;
	                    var editLink = '<a class="edit_link button" href="'+attachment.attributes.editLink+'" target="_blank">Edit File Metadata</a>';
	                    var editActions = editLink+' <a class="button  remove_link" href="" onclick="return removeMedia('+id+');">Remove</a>';
						var inlineMeta = [duration, dimensions, filesizeHumanReadable, subtype];	
						inlineMeta = inlineMeta.filter(function(e){
							return e !== null;
						}).join(' ~ ');	
						inlineMeta = '<div class="inline-meta">'+inlineMeta+'</div>';
	                    
	                    if(attachment.id > 0 && (existing_selection.indexOf( parseInt(id) )=='-1')){
		                    new_selection.push(attachment.id);
					    	table_row += '<tr data-id="'+id+'"><td><div class="preview-image '+type+'" style="background-image:url('+url+');"></div></td>';
					    	table_row += '<td><h3>'+title+'</h3>'+inlineMeta+'<div>'+description+'</div><div class="actions">'+editActions+'</div></td></tr>';
		                }
	                    
	                });
	                // merge and de-duplicate new and existing selections
	                var updated = jQuery.merge(new_selection,existing_selection).unique();
	                
	                // set value
	                clicked_button.prev(selector).val(updated);
	                
	                // update table UI, which might be hidden if empty	                
		            jQuery(tbody).prepend(table_row);
	            };
	 
	            // closing event for media manger
	            //wp.media.frames.curatescape_frame.on('close', curatescape_media_set_image);
	            
	            // image selection event
	            wp.media.frames.curatescape_frame.on('select', curatescape_media_set_image);
	            
	            // showing media manager
	            wp.media.frames.curatescape_frame.open();
	            
	        });
	   });
	};
	
	curatescape_media_picker_init('#story_media_row td input#story_media', '.story_media_picker');
	
</script>
	