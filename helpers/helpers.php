<?php
if( ! defined('ABSPATH') ){
	exit;
}

/*
** Include Parsedown lib
*/	
require_once plugin_dir_path( __DIR__ ).'libraries/parsedown/Parsedown.php';


/*
** Plugin options
*/	
function curatescape_setting($option){
	$options=get_option('curatescape_options',curatescape_options_default());
	if( isset($options[$option]) ){
		return $options[$option];
	}else{
		return null;
	}
}

/*
** Plugin fields
** returns value of custom field, optionally passing through markup parser
** curatescape_text($post,'story_lede',true);
** curatescape_text(get_post(678),'story_byline',true);
*/	
function curatescape_text($post, $field, $markdown=true){
	$parsedown = new Parsedown();
	$text=$post->$field; 
	return $parsedown->setBreaksEnabled(true)->setMarkupEscaped(true)->text($text);
}

/*
** Content Filters
*/
add_filter( 'wp_title', 'curatescape_story_title' );
add_filter( 'the_content', 'curatescape_story_content' );
add_filter( 'the_author', 'curatescape_story_byline' );
add_filter( 'author_link ', 'curatescape_story_byline_link' );

function curatescape_story_title($title){
	// do something
	return $title;
}
function curatescape_story_content($content){
	// do something
	return $content;
}
function curatescape_story_byline($author){
	// do something
	return $author;
}
function curatescape_story_byline_link($link){
	// do something
	return $link;
}

/*
** Image gallery
** returns interactive image gallery for Story post
** curatescape_image_gallery($post);
** curatescape_image_gallery(get_post(678));
*/	
function curatescape_image_gallery($post){
	if($post->story_media){
		$media = explode(',',$post->story_media);
		foreach($media as $attachment_id){
			$id = intval( $attachment_id );
			$attachment_meta = wp_prepare_attachment_for_js($id);	
			$type = $attachment_meta['type'];
			if($attachment_meta['type']=='image'){
				$url=$attachment_meta['url'];
				$description=$attachment_meta['description'] ? $attachment_meta['description'] : null;	
				// build array of image files...
			}
		}
		// do something...
		echo '<p>image gallery goes here...</p>';
	}
}

/*
** Media playlist
** returns interactive media playlist for Story post
** curatescape_media_playlist($post,'audio');
** curatescape_media_playlist(get_post(678),'video');
*/	
function curatescape_media_playlist($post, $type){
	if($post->story_media){
		$media = explode(',',$post->story_media);
		foreach($media as $attachment_id){
			$id = intval( $attachment_id );
			$attachment_meta = wp_prepare_attachment_for_js($id);	
			$type = $attachment_meta['type'];
			if($attachment_meta['type']==$type){
				$url=$attachment_meta['url'];
				$description=$attachment_meta['description'] ? $attachment_meta['description'] : null;	
				// build array of media files...
			}
		}
		// do something...
		echo '<p>'.$type.' playlist goes here...</p>';
	}	
}

function curatescape_story_map($post){
	if($coords=$post->location_coordinates){
		// do something...
		echo '<p>map goes here...</p>';		
		
	}
	if($post->story_street_address || $post->story_access_information || $post->story_official_website){
		// do something...
		echo '<p>caption for map goes here...</p>';	
	}
}

function curatescape_tour_map($post){
	// todo...
	echo '<p>map goes here...</p>';		
}

function curatescape_global_map($args){
	// todo...
	echo '<p>map goes here...</p>';		
}

function curatescape_stories_for_tour($post){
	// todo...
	echo '<p>stories list goes here...</p>';		
}

function curatescape_tour_navigation($post){
	// todo...
	echo '<p>tour navigation goes here...</p>';		
}