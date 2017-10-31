<?php
if( !defined('ABSPATH') ){
	exit;
}	

// add custom feeds
add_action('init','add_curatescape_json');
function add_curatescape_json(){
	add_feed('curatescape_stories', 'render_curatescape_stories_json');
	add_feed('curatescape_tours', 'render_curatescape_tours_json');
}

// manage cached transients
add_action( 'edit_terms', 'curatescape_delete_transients' );
add_action( 'save_post', 'curatescape_delete_transients' );
add_action( 'deleted_post', 'curatescape_delete_transients' );
add_action( 'transition_post_status', 'curatescape_delete_transients' );
function curatescape_delete_transients() {
     delete_transient( 'curatescape_stories' );
     delete_transient( 'curatescape_tours' );
}


// Helper: Repeatable fields
function unserialize_post_meta($postID){
	$postMeta=get_post_meta( $postID );
	$out=array();
	foreach($postMeta as $key=>$val){
		if($key == 'story_related_resources' || $key == 'story_factoid'){
			$serialized = get_post_meta( $postID, $key, true );
			$out[$key]=maybe_unserialize($serialized);
		}else{
			$out[$key]=$val;
		}
	}
	return $out;
}

// STORIES JSON
function render_curatescape_stories_json(){
	if ( false === ( $output = get_transient( 'curatescape_stories' ) ) ) {
		header( 'Content-Type: application/json' );
		$permissible=( wp_get_current_user() ) ? 'any' : 'publish';
		$args = array( 
		    'post_type' => 'stories', 
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
		    	'author'=>array(
		    		'id'=>$post->post_author,
		    		'display_name'=>get_the_author_meta('display_name', intval( $post->post_author )),
		    		'user_login'=>get_the_author_meta('user_login', intval( $post->post_author ))
		    		),
		    	'date'=>$post->post_date,
		    	'thumb'=>get_the_post_thumbnail_url( intval( $post->ID ) ),
		    	'meta'=>unserialize_post_meta( intval( $post->ID ) ),
		    );
		}			
	    set_transient( 'curatescape_stories', $output, 5 * MINUTE_IN_SECONDS ); // cache results
	}
	echo json_encode( $output );	
}

//TOURS JSON
function render_curatescape_tours_json(){
	if ( false === ( $output = get_transient( 'curatescape_tours' ) ) ) {
		header( 'Content-Type: application/json' );
		$permissible=( wp_get_current_user() ) ? 'any' : 'publish';
		$args = array( 
		    'post_type' => 'tours', 
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
		    	'author'=>$post->post_author,
		    	'date'=>$post->post_date,
		    	'thumb'=>get_the_post_thumbnail_url( intval( $post->ID ) ),
		    	'meta'=>get_post_meta( intval( $post->ID ) ),	    	
		    );
		}
		set_transient( 'curatescape_tours', $output, 5 * MINUTE_IN_SECONDS ); // cache results
	}
	echo json_encode( $output );
}