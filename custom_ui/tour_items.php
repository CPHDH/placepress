<script>
jQuery(document).ready(function() {
	jQuery.ajax({
		url: '<?php echo get_site_url().'?feed=curatescape_stories';?>',
	}).done(function( response ) {
		console.log(response);
	}).fail(function(e){
		console.log(e);
	});	
});		
</script>