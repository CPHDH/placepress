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

function curatescape_image_gallery_shortcode($atts){
	global $post;
	$media=curatescape_get_media($post);
	$includeHeading = curatescape_no_heading_atts($atts);
	return count($media['images']) ? curatescape_image_gallery($media['images'],'aside',$includeHeading) : null;
}

function curatescape_audio_playlist_shortcode($atts){
	global $post;
	$media=curatescape_get_media($post);
	$includeHeading = curatescape_no_heading_atts($atts);
	return count($media['audio']) ? curatescape_audio_playlist($media['audio'],'aside',$includeHeading) : null;
}

function curatescape_video_playlist_shortcode($atts){
	global $post;
	$media=curatescape_get_media($post);
	$includeHeading = curatescape_no_heading_atts($atts);
	return count($media['video']) ? curatescape_video_playlist($media['video'],'aside',$includeHeading) : null;
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