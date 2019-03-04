wp.domReady( function() {

    // @TODO: there must be a better way
    let postType=document.querySelector('form.metabox-base-form input#post_type').value;

    if(postType == 'locations'){
      wp.blocks.unregisterBlockType( 'placepress/block-map-tour' );
      wp.blocks.unregisterBlockType( 'placepress/block-map-global' );
    }

    if(postType == 'tours'){
      wp.blocks.unregisterBlockType( 'placepress/block-map-location' );
      wp.blocks.unregisterBlockType( 'placepress/block-map-global' );
    }

    if(postType == ( 'page' || 'post' )){
      wp.blocks.unregisterBlockType( 'placepress/block-map-location' );
      wp.blocks.unregisterBlockType( 'placepress/block-map-tour' );
    }

} );
