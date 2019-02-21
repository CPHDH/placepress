<?php
if( ! defined('ABSPATH') ){
	exit;
}

/*
** Content Filters
*/
add_filter( 'the_title', 'placepress_filter_title', 10, 2);
add_filter( 'the_content', 'placepress_filter_content' );

function placepress_filter_title($title,$id){
	if(is_singular('stories') && in_the_loop() && $id == get_the_ID()){
		$post = $GLOBALS['post'];
		$subtitle = placepress_setting('content_subtitle') ? placepress_subtitle($post) : null;
		$title = $title.$subtitle;
	}
	return $title;
}
function placepress_filter_content($content){
	if(is_singular('stories') && in_the_loop() && is_main_query()){
		$post = $GLOBALS['post'];

		// omit content already placed via shortcodes
		$includeAudio = !has_shortcode( $content, 'placepress_audio' );
		$includeVideo = !has_shortcode( $content, 'placepress_video' );
		$includeImages = !has_shortcode( $content, 'placepress_images' );
		$includeMap = !has_shortcode( $content, 'placepress_map' );

		$lede = placepress_setting('content_lede') ? placepress_lede($post) : null;
		$media = placepress_setting('content_media_gallery') ? placepress_display_media_section($post,$includeImages, $includeAudio, $includeVideo) : null;
		$map = placepress_setting('content_map') && $includeMap ? placepress_story_map($post) : null;
		$related = placepress_setting('content_related_sources') ? placepress_related_sources($post) : null;
		return $lede.$content.$media.$map.$related;
	}elseif(is_singular('tours') && in_the_loop() && is_main_query()){
		$post = $GLOBALS['post'];
		$map = placepress_tour_map($post);
		$locations = placepress_stories_for_tour($post);
		return $content.$map.$locations;
	}else{
		return $content;
	}

}

/*
** Admin Filters
*/
add_filter('hidden_meta_boxes', 'placepress_hidden_meta_boxes', 10, 2);

function placepress_hidden_meta_boxes($hidden, $screen) {
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
add_filter('gutenberg_can_edit_post_type', 'placepress_disable_gutenberg', 10, 2);

function placepress_disable_gutenberg($can_edit, $post_type) {

	if ($post_type === 'stories') return false; // disable for stories

	if ($post_type === 'tours') return false; // disable for tours

	return $can_edit;

}
