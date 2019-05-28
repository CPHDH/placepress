<?php
if( !defined('ABSPATH') ){
	exit;
}

// add feeds for admin custom UI
add_action('init','add_placepress_json');
function add_placepress_json(){
	add_feed('placepress_locations_public', 'placepress_render_public_locations_json');
}

// manage cached transients
add_action( 'edit_terms', 'placepress_delete_transients' );
add_action( 'save_post', 'placepress_delete_transients' );
add_action( 'deleted_post', 'placepress_delete_transients' );
add_action( 'transition_post_status', 'placepress_delete_transients' );
function placepress_delete_transients() {
     delete_transient( 'placepress_locations_public' );
}

// LOCATIONS PUBLIC JSON
function placepress_render_public_locations_json(){
	if ( false === ( $output = get_transient( 'placepress_locations' ) ) ) {
		header( 'Content-Type: application/json' );
		$args = array(
		    'post_type' => 'locations',
		    'post_status' => 'publish',
		    'nopaging' => true
		);
		$query = new WP_Query( $args );
		$posts = $query->get_posts();
		$output = array();
		foreach( $posts as $post ) {
      if(has_block( 'placepress/block-map-location', $post )){
  			$postMeta=get_post_meta( $post->ID );
  		    $output[] = array(
  		    	'id' => intval( $post->ID ),
  		    	'title' => $post->post_title,
            'permalink' => get_permalink( $post->ID ),
  		    	'api_coordinates_pp' => $postMeta['api_coordinates_pp'][0],
  		    	'thumbnail'=>get_the_post_thumbnail_url($post,'medium'),
  		    );
		  }
	    set_transient( 'placepress_locations_public', $output, 3 * MINUTE_IN_SECONDS ); // cache results
    }
	}
	echo json_encode( $output );
}
