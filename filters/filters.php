<?php
if( ! defined('ABSPATH') ){
	exit;
}

/*
** Content Filters
*/
add_filter( 'the_title', 'curatescape_story_title', 10, 2);
add_filter( 'the_content', 'curatescape_story_content' );

function curatescape_story_title($title,$id){
	if(is_single() && in_the_loop() && $id == get_the_ID()){
		$post=$GLOBALS['post'];
		$subtitle = $post->story_subtitle ? '<br><span class="curatescape-subtitle"><em>'.$post->story_subtitle.'</em></span>' : null;		
		$title=$title.$subtitle;
	}
	return $title;
}
function curatescape_story_content($content){
	if(is_single() && in_the_loop() && is_main_query()){
		$post=$GLOBALS['post'];
		$lede = $post->story_lede ? '<p class="curatescape-lede"><em>'.$post->story_lede.'</em></p>' : null;
		$photos = curatescape_image_gallery($post);
		$audio = curatescape_media_playlist($post,'audio');
		$video = curatescape_media_playlist($post,'video');
		$map = curatescape_story_map($post);
		$content=$lede.$content.$photos.$audio.$video.$map;
	}
	return $content;
}