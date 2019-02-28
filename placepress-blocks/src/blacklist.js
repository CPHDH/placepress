wp.domReady( function() {

    if(document.body.classList.contains('post-type-locations')){ // @TODO: there must be a better way
      wp.blocks.unregisterBlockType( 'placepress/block-map-tour' );
      wp.blocks.unregisterBlockType( 'placepress/block-map-global' );
    }

    if(document.body.classList.contains('post-type-tours')){ // @TODO: there must be a better way
      wp.blocks.unregisterBlockType( 'placepress/block-map-location' );
      wp.blocks.unregisterBlockType( 'placepress/block-map-global' );
    }

} );
