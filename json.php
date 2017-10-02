<?php
add_action('init','add_curatescape_json');
function add_curatescape_json(){
	add_feed('curatescape_stories', 'render_curatescape_stories_json');
	add_feed('curatescape_tours', 'render_curatescape_tours_json');
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
	echo json_encode( $output );
}

//TOURS JSON
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
	    	'id' => intval( $post->ID ), 
	    	'title' => $post->post_title,
	    	'author'=>$post->post_author,
	    	'date'=>$post->post_date,
	    	'thumb'=>get_the_post_thumbnail_url( intval( $post->ID ) ),
	    	'meta'=>get_post_meta( intval( $post->ID ) ),	    	
	    );
	}
	echo json_encode( $output );
}