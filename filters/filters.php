<?php
if( ! defined('ABSPATH') ){
	exit;
}

/*
** Content Filters
*/
add_filter( 'the_title', 'curatescape_filter_title', 10, 2);
add_filter( 'the_content', 'curatescape_filter_content' );

function curatescape_filter_title($title,$id){
	if(is_singular('stories') && in_the_loop() && $id == get_the_ID()){
		$post = $GLOBALS['post'];
		$subtitle = curatescape_setting('content_subtitle') ? curatescape_subtitle($post) : null;		
		$title = $title.$subtitle;
	}
	return $title;
}
function curatescape_filter_content($content){
	if(is_singular('stories') && in_the_loop() && is_main_query()){
		$post = $GLOBALS['post'];
		
		// omit content already placed via shortcodes
		$includeAudio = !has_shortcode( $content, 'curatescape_audio' );
		$includeVideo = !has_shortcode( $content, 'curatescape_video' );
		$includeImages = !has_shortcode( $content, 'curatescape_images' );
		$includeMap = !has_shortcode( $content, 'curatescape_map' );
		
		$lede = curatescape_setting('content_lede') ? curatescape_lede($post) : null;
		$media = curatescape_setting('content_media_gallery') ? curatescape_display_media_section($post,$includeImages, $includeAudio, $includeVideo) : null;
		$map = curatescape_setting('content_map') && $includeMap ? curatescape_story_map($post) : null;
		$related = curatescape_setting('content_related_sources') ? curatescape_related_sources($post) : null;
		return $lede.$content.$media.$map.$related;
	}elseif(is_singular('tours') && in_the_loop() && is_main_query()){
		$post = $GLOBALS['post'];
		$map = curatescape_tour_map($post);
		$locations = curatescape_stories_for_tour($post);
		return $content.$map.$locations;
	}else{
		return $content;	
	}
	
}

/*
** Admin Filters
*/
add_filter('hidden_meta_boxes', 'curatescape_hidden_meta_boxes', 10, 2);

function curatescape_hidden_meta_boxes($hidden, $screen) {
    $post_type= $screen->id;
    switch ($post_type) {
	    case 'stories':
	    	$hidden[]= 'postcustom';
	    	break;
	    case 'tours':
	    	$hidden[]= 'postcustom';
	    	break;	    	
    }
    return $hidden;
}

/*
** Editor Filters
*/
add_filter('gutenberg_can_edit_post_type', 'curatescape_disable_gutenberg', 10, 2);

function curatescape_disable_gutenberg($can_edit, $post_type) {
	
	if ($post_type === 'stories') return false; // disable for stories
	
	if ($post_type === 'tours') return false; // disable for tours
	
	return $can_edit;
	
}
