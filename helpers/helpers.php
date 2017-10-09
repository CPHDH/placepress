<?php
if( ! defined('ABSPATH') ){
	exit;
}

function curatescape_setting($option){
	$options=get_option('curatescape_options',curatescape_options_default());
	if( isset($options[$option]) ){
		return $options[$option];
	}else{
		return null;
	}
}

function curatescape($post, $field, $markdown){
	
}

function curatescape_image_gallery($post){
	
}

function curatescape_media_playlist($post, $type){
	
}

function curatescape_media_shortcode($args){
	
}

function curatescape_story_map($post){
	
}

function curatescape_tour_map($post){
	
}

function curatescape_global_map($args){
	
}

function curatescape_stories_for_tour($post){
	
}

function curatescape_tour_navigation($post){
	
}