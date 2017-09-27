<script>
var endpoint = '<?php echo get_site_url().'?feed=curatescape_stories';?>';	
jQuery(document).ready(function() {
	jQuery.ajax({
		url: endpoint,
	}).done(function( response ) {
		console.log(response);
	}).fail(function(e){
		console.log(e);
	});	
});		
</script>