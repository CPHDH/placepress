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
		$lede = curatescape_setting('content_lede') ? curatescape_lede($post) : null;
		$media = curatescape_setting('content_media_gallery') ? curatescape_display_media_section($post) : null;
		$map = curatescape_setting('content_map') ? curatescape_story_map($post) : null;
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
