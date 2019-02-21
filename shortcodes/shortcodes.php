<?php
if( ! defined('ABSPATH') ){
	exit;
}

/*
** Shortcodes
*/

add_shortcode('placepress_images', 'placepress_image_gallery_shortcode');
add_shortcode('placepress_audio', 'placepress_audio_playlist_shortcode');
add_shortcode('placepress_video', 'placepress_video_playlist_shortcode');
add_shortcode('placepress_map', 'placepress_map_shortcode');
add_shortcode('placepress_global_map', 'placepress_global_map_shortcode');

function placepress_image_gallery_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$media=placepress_get_story_media($post);
		$includeHeading = placepress_no_heading_atts($atts);
		return count($media['images']) ? placepress_image_gallery($media['images'],'aside',$includeHeading) : null;
	}
}

function placepress_audio_playlist_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$media=placepress_get_story_media($post);
		$includeHeading = placepress_no_heading_atts($atts);
		return count($media['audio']) ? placepress_audio_playlist($media['audio'],'aside',$includeHeading) : null;
	}
}

function placepress_video_playlist_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$media=placepress_get_story_media($post);
		$includeHeading = placepress_no_heading_atts($atts);
		return count($media['video']) ? placepress_video_playlist($media['video'],'aside',$includeHeading) : null;
	}
}

function placepress_map_shortcode($atts){
	global $post;
	if(is_singular( 'stories' )){
		$includeHeading = placepress_no_heading_atts($atts);
		return placepress_story_map($post,$includeHeading);
	}
}

function placepress_global_map_shortcode($atts){
	wp_enqueue_style( 'placepress_public_css' );
    wp_enqueue_style( 'leafletcss' );
    wp_enqueue_script( 'leafletjs' );

    if(placepress_setting('marker_clustering')){
		wp_enqueue_style('clustercss');
		wp_enqueue_script('clusterjs');
    }
    if(placepress_setting('maki_markers')){
	    wp_enqueue_script('makijs');
    }

    wp_enqueue_script( 'placepress_global_map_js' );

	return placepress_global_map();
}

function placepress_no_heading_atts($atts){
	if(
		(is_array($atts) && array_key_exists('no-heading',$atts)) ||
		(is_array($atts) && $atts[0] == 'no-heading')
	){
		return false;
	}else{
		return true;
	}
}
