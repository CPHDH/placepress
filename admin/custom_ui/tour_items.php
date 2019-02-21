<div id="admin-location-search-container">
	<input id="admin-location-search" type="text" placeholder="" onkeydown="if (event.keyCode == 13) return false">
</div>

<ul class="sortable-locations-for-item" id="sortable">
<?php
	if($locations=$post->tour_locations){
		foreach( explode(',',$locations) as $loc ){
			$title=get_the_title( intval($loc) );
			if( ($subtitle=get_post_meta( intval($loc), 'location_subtitle', true )) && get_post_meta( intval($loc), 'location_subtitle', true ) !==''){
				$title.= ': '.$subtitle;
			}
			echo '<li data-value="'.$loc.'" class="ui-state-default">'.
			'<div class="sortable-thumb" style="background-image:url('.get_the_post_thumbnail_url( intval( $loc ) ).');">'.
			'</div><div><h3>'.$title.'</h3>'.
			'<a class="button remove_link" href="" onclick="return removeLocation('.intval( $loc ).');">'.esc_html__( 'Remove', 'wp_placepress').'</a></div></li>';
		}
}
?>
</ul>
<script>
var endpoint = '<?php echo get_site_url().'?feed=placepress_locations_admin';?>';
var locations=new Array();

// translatable labels and messages
var textdomain_error_generic = '<?php echo esc_html__( 'Unable to get locations ...', 'wp_placepress');?>';
var textdomain_placeholder_default = '<?php echo esc_html__( 'Type a title or subtitle to add location to tour...', 'wp_placepress');?>';
var textdomain_placeholder_getting = '<?php echo esc_html__( 'Getting locations...', 'wp_placepress');?>';
var textdomain_remove = '<?php echo esc_html__( 'Remove', 'wp_placepress');?>';

jQuery('#tour_locations table').hide();
jQuery('#admin-location-search').attr('placeholder',textdomain_placeholder_getting);

jQuery(document).ready(function( $ ) {
	$.ajax({
		url: endpoint,
	}).done(function( response ) {
		$('#admin-location-search').attr('placeholder',textdomain_placeholder_default);
		if(response.length){
			$(response).each(function(i,r){
				var subtitle = ( typeof r.meta.location_subtitle !== 'undefined' ) ? r.meta.location_subtitle[0] : false;
				locations.push({
					value:r.id,
					label:r.title+(subtitle ? ': '+subtitle : ''),
					thumb:r.thumb,
				});
			});

			$( "#admin-location-search" ).autocomplete({
			  minLength: 2,
			  source: locations,
			  select: function( e, ui ) {
			  	addLocation(ui.item);
			  	e.preventDefault();
			    return false;
			  },
			});

		}else{
			$('#admin-location-search').attr('placeholder',textdomain_error_generic);
			$('#admin-location-search').attr('disabled',true);
		}
	}).fail(function(e){
		$('#admin-location-search').attr('placeholder',textdomain_error_generic);
		$('#admin-location-search').attr('disabled',true);
	});

	$( function() {
		$( "#sortable" ).sortable({
			placeholder: "ui-state-highlight",
			items: 'li',
			cursor: "move",
			start: function(e, ui){
			    ui.placeholder.height(ui.item.height());
			},
			stop: function(event,ui){
		    	// update list and input on drag-end
				var uv=new Array();
				$('#sortable.sortable-locations-for-item li').each(function(){
					if($(this).attr('data-value') > 0){
						uv.push($(this).attr('data-value'));
					}
				});
				uv=uv.unique();
				$("input#tour_locations").val(uv.join());

	    	}
		});
		$( "#sortable" ).disableSelection();
	} );

	function addLocation(new_addition){
		var current_values = $( "input#tour_locations").val();
		var updated_values = current_values.length > 0 ? current_values.split(',').map(Number) : new Array();


		if(updated_values.indexOf( parseInt( new_addition.value) ) === -1){
			$('#sortable').prepend('<li data-value="'+new_addition.value+'" class="ui-state-default"><div class="sortable-thumb" style="background-image:url('+new_addition.thumb+');"></div><div><h3>'+new_addition.label+'</h3><a class="button  remove_link" href="" onclick="return removeLocation('+new_addition.value+');">'+textdomain_remove+'</a></div></li>');
		}else{
			// TODO: add some notice
		}

		updated_values.push(new_addition.value);
		updated_values=updated_values.unique();

		$( "input#tour_locations").val( updated_values );

	  	$( "#admin-location-search" ).val('').focus();
	}

});

function removeLocation(id){
	var vals=jQuery('input#tour_locations').val().split(',').map(Number);
	var remove = vals.indexOf(id);
	if(remove != -1) {
		vals.splice(remove, 1);
		jQuery('input#tour_locations').val(vals.unique());
		jQuery('.sortable-locations-for-item li[data-value='+id+']').fadeOut();
	}
	return false;
}



</script>
