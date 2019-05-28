wp.domReady( function() {
	// Unregister blocks depending on post type
	const postType = document.querySelector(
		'form.metabox-base-form input#post_type'
	).value;

	if ( postType == 'locations' ) {
		wp.blocks.unregisterBlockType( 'placepress/block-map-tour' );
		wp.blocks.unregisterBlockType( 'placepress/block-map-global' );
	}

	if ( postType == 'tours' ) {
		wp.blocks.unregisterBlockType( 'placepress/block-map-location' );
		wp.blocks.unregisterBlockType( 'placepress/block-map-global' );
	}

	if ( postType == 'page' || postType == 'post' ) {
		wp.blocks.unregisterBlockType( 'placepress/block-map-location' );
		wp.blocks.unregisterBlockType( 'placepress/block-map-tour' );
	}
} );
