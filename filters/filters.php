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
		$post = $GLOBALS['post'];
		$subtitle = curatescape_setting('content_subtitle') ? curatescape_subtitle($post) : null;		
		$title = $title.$subtitle;
	}
	return $title;
}
function curatescape_story_content($content){
	if(is_single() && in_the_loop() && is_main_query()){
		$post = $GLOBALS['post'];
		$lede = curatescape_setting('content_lede') ? curatescape_lede($post) : null;
		$media = curatescape_setting('content_media_gallery') ? curatescape_display_media_section($post) : null;
		$map = curatescape_setting('content_map') ? curatescape_story_map($post) : null;
		$related = curatescape_setting('content_related_sources') ? curatescape_related_sources($post) : null;
		$content = $lede.$content.$media.$map.$related;
	}
	return $content;
}
