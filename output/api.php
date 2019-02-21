<?php
if( !defined('ABSPATH') ){
	exit;
}

// add feeds for admin custom UI
add_action('init','add_placepress_json');
function add_placepress_json(){
	add_feed('placepress_locations_admin', 'placepress_render_admin_locations_json');
	add_feed('placepress_locations_public', 'placepress_render_public_locations_json');
}

// manage cached transients
add_action( 'edit_terms', 'placepress_delete_transients' );
add_action( 'save_post', 'placepress_delete_transients' );
add_action( 'deleted_post', 'placepress_delete_transients' );
add_action( 'transition_post_status', 'placepress_delete_transients' );
function placepress_delete_transients() {
     delete_transient( 'placepress_locations_admin' );
     delete_transient( 'placepress_locations_public' );
}


// Helper: Repeatable fields
function unserialize_post_meta($postID){
	$postMeta=get_post_meta( $postID );
	$out=array();
	foreach($postMeta as $key=>$val){
		if($key == 'location_related_resources' || $key == 'location_factoid'){
			$serialized = get_post_meta( $postID, $key, true );
			$out[$key]=maybe_unserialize($serialized);
		}else{
			$out[$key]=$val;
		}
	}
	return $out;
}

// STORIES ADMIN JSON
function placepress_render_admin_locations_json(){
	if ( false === ( $output = get_transient( 'placepress_locations' ) ) ) {
		header( 'Content-Type: application/json' );
		$permissible=( wp_get_current_user() ) ? 'any' : 'publish';
		$args = array(
		    'post_type' => 'locations',
		    'post_status' => $permissible,
		    'nopaging' => true
		);
		$query = new WP_Query( $args );
		$posts = $query->get_posts();
		$output = array();
		foreach( $posts as $post ) {
		    $output[] = array(
		    	'id' => intval( $post->ID ),
		    	'title' => $post->post_title,
		    	'thumb'=>get_the_post_thumbnail_url( intval( $post->ID ) ),
		    	'meta'=>unserialize_post_meta( intval( $post->ID ) ),
		    );
		}
	    set_transient( 'placepress_locations_admin', $output, 1 * MINUTE_IN_SECONDS ); // cache results
	}
	echo json_encode( $output );
}

// STORIES PUBLIC JSON
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
			$postMeta=get_post_meta( $post->ID );
		    $output[] = array(
		    	'id' => intval( $post->ID ),
		    	'title' => $post->post_title,
		    	'thumb'=>get_the_post_thumbnail_url( intval( $post->ID ) ),
				'subtitle' => $postMeta['location_subtitle'][0],
		    	'location_coordinates' => $postMeta['location_coordinates'][0],
		    	'permalink' => get_permalink( $post->ID ),
		    );
		}
	    set_transient( 'placepress_locations_public', $output, 3 * MINUTE_IN_SECONDS ); // cache results
	}
	echo json_encode( $output );
}

// WP REST API EXTENSIONS
$object='post'; // specific post types are not supported in WP REST API; https://core.trac.wordpress.org/ticket/38323
$args = array(
    'type'      => 'string',
    'description'    => 'A meta key associated with a string meta value.',
    'single'        => true,
    'show_in_rest'    => true,
);
register_meta( $object, 'location_subtitle', $args );
register_meta( $object, 'location_lede', $args);
register_meta( $object, 'location_media', $args );
register_meta( $object, 'location_street_address', $args );
register_meta( $object, 'location_access_information', $args );
register_meta( $object, 'location_official_website', $args );
register_meta( $object, 'location_coordinates', $args );
register_meta( $object, 'location_zoom', $args );
register_meta( $object, 'tour_locations', $args );
register_meta( $object, 'tour_postscript', $args );
// register_meta( $object, 'location_related_resources', $args ); // @TODO: cannot use serialized/array data, so maybe rewrite this field
