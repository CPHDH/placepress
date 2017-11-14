<?php 
if( ! defined('ABSPATH') ){
	exit;
}

/*
** Shortcodes
*/

add_shortcode('curatescape_images', 'curatescape_image_gallery_shortcode');
add_shortcode('curatescape_audio', 'curatescape_audio_playlist_shortcode');
add_shortcode('curatescape_video', 'curatescape_video_playlist_shortcode');
add_shortcode('curatescape_map', 'curatescape_map_shortcode');
add_shortcode('curatescape_global_map', 'curatescape_global_map_shortcode');

function curatescape_image_gallery_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$media=curatescape_get_story_media($post);
		$includeHeading = curatescape_no_heading_atts($atts);
		return count($media['images']) ? curatescape_image_gallery($media['images'],'aside',$includeHeading) : null;
	}
}

function curatescape_audio_playlist_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$media=curatescape_get_story_media($post);
		$includeHeading = curatescape_no_heading_atts($atts);
		return count($media['audio']) ? curatescape_audio_playlist($media['audio'],'aside',$includeHeading) : null;		
	}
}

function curatescape_video_playlist_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$media=curatescape_get_story_media($post);
		$includeHeading = curatescape_no_heading_atts($atts);
		return count($media['video']) ? curatescape_video_playlist($media['video'],'aside',$includeHeading) : null;
	}
}

function curatescape_map_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$includeHeading = curatescape_no_heading_atts($atts);
		return curatescape_story_map($post,$includeHeading);
	}
}

function curatescape_global_map_shortcode($atts){
	wp_enqueue_style( 'curatescape_public_css' );
    wp_enqueue_style( 'leafletcss' );	    
    wp_enqueue_script( 'leafletjs' );  	
    wp_enqueue_script( 'curatescape_global_map_js' );	
	return curatescape_global_map();
}

function curatescape_no_heading_atts($atts){
	if( 
		(is_array($atts) && array_key_exists('no-heading',$atts)) || 
		(is_array($atts) && $atts[0] == 'no-heading') 
	){
		return false;
	}else{
		return true;
	}
}