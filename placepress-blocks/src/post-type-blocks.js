wp.domReady(function () {
	let deregistered = false;
	if (wp.data !== undefined) {
		wp.data.subscribe(() => {
			if(deregistered == false){ // run once
				if ( wp.data.select( 'core/edit-site' ) ) {
					console.warn('PlacePress: site-editor support is limited at this time. PlacePress Global Map blocks added using the site-editor interface may not render properly within the editor, but should work as expected on the front end.');
					deregistered = true;
					wp.blocks.unregisterBlockType("placepress/block-map-location");
					wp.blocks.unregisterBlockType("placepress/block-tour-stop");
				}else if( wp.data.select("core/editor" ) ){
					let postType = wp.data.select("core/editor" ).getCurrentPostType();
					if(postType){
						deregistered = true;
						if (postType !== "locations"){
							wp.blocks.unregisterBlockType("placepress/block-map-location");
						}
						if (postType !== "tours") {
							wp.blocks.unregisterBlockType("placepress/block-tour-stop");
						}
						if (postType !== "page" && postType !== "post") {
							wp.blocks.unregisterBlockType("placepress/block-map-global");
							wp.blocks.unregisterBlockType("placepress/block-map-global-type");
						}
					}
				}
			}
		});
	}
});
