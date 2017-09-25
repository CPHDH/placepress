<?php
add_action('init','add_curatescape_json');
function add_curatescape_json(){
	add_feed('curatescape_stories', 'render_curatescape_stories_json');
	add_feed('curatescape_tours', 'render_curatescape_tours_json');
}

// STORIES
function render_curatescape_stories_json(){
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
	    	'id' => $post->ID, 
	    	'title' => $post->post_title,
	    	'subtitle' => $post->story_subtitle,
	    	'thumb'=>get_the_post_thumbnail_url( $post->id ),
	    );
	}
	echo json_encode( $output );
}

//TOURS
function render_curatescape_tours_json(){
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
	    	'id' => $post->ID, 
	    	'title' => $post->post_title,
	    	'thumb'=>get_the_post_thumbnail_url( $post->id ),
	    );
	}
	echo json_encode( $output );
}