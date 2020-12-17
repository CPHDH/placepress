wp.domReady(function () {
	// Unregister blocks depending on post type
	const postType = document.querySelector(
		"form.metabox-base-form input#post_type"
	).value;

	if (postType == "locations") {
		wp.blocks.unregisterBlockType("placepress/block-map-global");
		wp.blocks.unregisterBlockType("placepress/block-map-global-type");
		wp.blocks.unregisterBlockType("placepress/block-tour-stop");
	}

	if (postType == "tours") {
		wp.blocks.unregisterBlockType("placepress/block-map-location");
		wp.blocks.unregisterBlockType("placepress/block-map-global");
		wp.blocks.unregisterBlockType("placepress/block-map-global-type");
	}

	if (postType == "page" || postType == "post") {
		wp.blocks.unregisterBlockType("placepress/block-map-location");
		wp.blocks.unregisterBlockType("placepress/block-tour-stop");
	}
});
