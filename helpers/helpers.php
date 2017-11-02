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
** Parse string as Markdown
** set $singleline to false to enable automatic paragraphs 
*/
function curatescape_parse_markdown($string,$singleline=true){
	$parsedown = new Parsedown();
	if($singleline){
		return $parsedown->setBreaksEnabled(true)->setMarkupEscaped(true)->line($string);
	}else{
		return $parsedown->setBreaksEnabled(true)->setMarkupEscaped(true)->text($string);
	}
}

/*
** Get Media Files
** returns array of media file URLs sorted by type
*/	
function curatescape_get_media($post){
	if($post->story_media){
		$media = explode(',',$post->story_media);
		
		$images=array();
		$audio=array();
		$video=array();
		$other=array();
		
		foreach($media as $m){
			$id = intval( $m );
			$attachment_meta = wp_prepare_attachment_for_js($id);
			$type = $attachment_meta['type'];
			switch($type){
				case 'image':
					$images[]=array(
						'url'=>$attachment_meta['url'],
						'title'=>$attachment_meta['title'] ? $attachment_meta['title'] : null,
						'description'=>$attachment_meta['description'] ? $attachment_meta['description'] : null
					);
					break;
				case 'audio':
					$audio[]=array(
						'url'=>$attachment_meta['url'],
						'title'=>$attachment_meta['title'] ? $attachment_meta['title'] : null,
						'description'=>$attachment_meta['description'] ? $attachment_meta['description'] : null
					);
					break;
				case 'video':
					$video[]=array(
						'url'=>$attachment_meta['url'],
						'title'=>$attachment_meta['title'] ? $attachment_meta['title'] : null,
						'description'=>$attachment_meta['description'] ? $attachment_meta['description'] : null
					);
					break;
				default:
					$other[]=array(
						'url'=>$attachment_meta['url'],
						'title'=>$attachment_meta['title'] ? $attachment_meta['title'] : null,
						'description'=>$attachment_meta['description'] ? $attachment_meta['description'] : null						
					);
			}
		}
		return array('images'=>$images,'audio'=>$audio,'video'=>$video,'other'=>$other);
	}else{
		return array();
	}	
}

/*
** Image gallery
** returns interactive image gallery
*/	
function curatescape_image_gallery($images){
	// todo...
	$html = '<h3 class="curatescape-section-heading curatescape-section-heading-images">'.__('Images').'</h3>';
	$html .= '<p>image gallery goes here... '.count($images).'</p>';
	return $html;
}

/*
** Audio playlist
** returns audio playlist
*/	
function curatescape_audio_playlist($audio){
	// todo...
	$html = '<h3 class="curatescape-section-heading curatescape-section-heading-audio">'.__('Audio').'</h3>';
	$html .= '<p>audio playlist goes here... '.count($audio).'</p>';
	return $html;
}

/*
** Video playlist
** returns video playlist
*/	
function curatescape_video_playlist($video){
	// todo...
	$html = '<h3 class="curatescape-section-heading curatescape-section-heading-video">'.__('Video').'</h3>';
	$html .= '<p>video playlist goes here... '.count($video).'</p>';
	return $html;
}

/*
** Media section
** returns interactive image gallery, audio playlist, and video playlist for Story post
*/	
function curatescape_display_media_section($post){
	$media=curatescape_get_media($post);
	$html = '<h2 class="curatescape-section-heading curatescape-section-heading-media">'.__('Media').'</h2>';
	$html .= count($media['images']) ? curatescape_image_gallery($media['images']) : null;
	$html .= count($media['audio']) ? curatescape_audio_playlist($media['audio']) : null;
	$html .= count($media['video']) ? curatescape_video_playlist($media['video']) : null;
	return '<section class="curatescape-section curatescape-media-section">'.$html.'</section>';
}

/*
** Story Map section
** returns interactive map for Story post
*/	
function curatescape_story_map($post){
	if($coords=$post->location_coordinates){
		// todo...
		$html = '<h2 class="curatescape-section-heading curatescape-section-heading-map">'.__('Map').'</h2>';
		$html.= '<p>map goes here... '.$coords.' </p>';		
		$caption=array(curatescape_street_address($post),curatescape_access_information($post),curatescape_official_website($post));
		$html.= implode(' ~ ', array_filter($caption));
	}
	return '<section class="curatescape-section curatescape-map-section">'.$html.'</section>';;
}

/*
** Tour Map section
** returns interactive map for Story posts in current Tour post
*/
function curatescape_tour_map($post){
	// todo...
	return '<p>map goes here...</p>';		
}

/*
** Global Map section
** returns interactive map for all Story posts
*/
function curatescape_global_map($args){
	// todo...
	return '<p>map goes here...</p>';		
}

/*
** Street address
*/	
function curatescape_street_address($post){
	return $post->story_street_address ? curatescape_parse_markdown($post->story_street_address) : null;
}

/*
** Access information
*/	
function curatescape_access_information($post){
	return $post->story_access_information ? curatescape_parse_markdown($post->story_access_information) : null;
}

/*
** Official website
*/	
function curatescape_official_website($post){
	return $post->story_official_website ? curatescape_parse_markdown($post->story_official_website) : null;
}

/*
** Subtitle
*/	
function curatescape_subtitle($post){
	return $post->story_subtitle ? '<br><span class="curatescape-subtitle"><em>'.curatescape_parse_markdown($post->story_subtitle).'</em></span>' : null;
}

/*
** Lede
*/	
function curatescape_lede($post){
	return $post->story_lede ? '<p class="curatescape-lede"><em>'.curatescape_parse_markdown($post->story_lede).'</em></p>' : null;
}

/*
** Related resources section
** returns related resources section for Story post
*/	
function curatescape_related_sources($post){
	if($post->story_related_resources){
		$html='<h2 class="curatescape-section-heading curatescape-section-heading-related-resources">'.__('Related Sources').'</h2>';
		$html .= '<ul class="curatescape-related-sources">';
		foreach($post->story_related_resources as $rr){
			$html .= '<li>'.curatescape_parse_markdown($rr).'</li>';
		}
		$html .= '</ul>';
		return '<section class="curatescape-section curatescape-related-resources-section">'.$html.'</section>';;
	}else{
		return null;
	}
}

/*
** Tour Locations section
** returns locations section for Tour post
*/	
function curatescape_stories_for_tour($post){
	// todo...
	return '<p>stories list goes here...</p>';		
}

/*
** Tour Navigation
*/	
function curatescape_tour_navigation($post){
	// todo...
	return '<p>tour navigation goes here...</p>';		
}