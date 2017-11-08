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
	//var_dump($images);
	// todo...
	$html = '<section class="curatescape-section curatescape-media-section curatescape-images-section">';
	$html .= '<h2 class="curatescape-section-heading curatescape-section-heading-images">'.__('Images').'</h2>';
	$html .= '<div class="curatescape-flex curatescape-image-grid">';
	foreach($images as $file){
		$caption_array=array_filter(array($file['title'],$file['description']));
		$caption=htmlentities( ( implode( ': ', $caption_array ) ) );	
		$html.='<div><div title="'.$file['title'].'" style="background-image:url('.$file['url'].')" data-caption="'.$caption.'"></div></div>';
	}
	$html .= '</div>';
	$html .= '</section>';
	return $html;
}

/*
** Audio playlist
** returns audio playlist
*/	
function curatescape_audio_playlist($audio){
	// todo...
	$html = '<section class="curatescape-section curatescape-media-section curatescape-audio-section">';
	$html .= '<h2 class="curatescape-section-heading curatescape-section-heading-audio">'.__('Audio').'</h2>';
	foreach($audio as $file){
		$caption_array=array_filter(array($file['title'],$file['description']));
		$caption=htmlentities( ( implode( ': ', $caption_array ) ) );		
		$html .= '<audio class="curatescape-audio" controls data-caption="'.$caption.'"><source src="'.$file['url'].'" type="audio/mpeg"></audio>';
	}
	$html .= '</section>';
	return $html;
}

/*
** Video playlist
** returns video playlist
*/	
function curatescape_video_playlist($video){
	// todo...
	$html = '<section class="curatescape-section curatescape-media-section curatescape-video-section">';
	$html .= '<h2 class="curatescape-section-heading curatescape-section-heading-video">'.__('Video').'</h2>';
	foreach($video as $file){
		$caption_array=array_filter(array($file['title'],$file['description']));
		$caption=htmlentities( ( implode( ': ', $caption_array ) ) );		
		$html .= '<video class="curatescape-video" controls data-caption="'.$caption.'"><source src="'.$file['url'].'" type="video/mp4"></video>';
	}	$html .= '</section>';
	return $html;
}

/*
** Media section
** returns interactive image gallery, audio playlist, and video playlist for Story post
*/	
function curatescape_display_media_section($post){
	$media=curatescape_get_media($post);
	$html = count($media['images']) ? curatescape_image_gallery($media['images']) : null;
	$html .= count($media['audio']) ? curatescape_audio_playlist($media['audio']) : null;
	$html .= count($media['video']) ? curatescape_video_playlist($media['video']) : null;
	return $html;
}

/*
** Story Map section
** returns interactive map for Story post
*/	
function curatescape_story_map($post){
	if($coords=$post->location_coordinates){
		$caption_array = array(
			curatescape_street_address($post),
			curatescape_access_information($post),
			curatescape_official_website($post)
		);
		$caption = implode(' ~ ', array_filter($caption_array));
		$html = '<h2 class="curatescape-section-heading curatescape-section-heading-map">'.__('Map').'</h2>';
		$html .= '<figure  class="curatescape-figure">';		
		$html .= '<div id="curatescape-item-map" class="curatescape-map curatescape-item-map">';
		$html .= '</div>';
		$html .= '</figure>';	
		$html .= '<figcaption class="curatescape-figcaption">'.$caption.'</figcaption>';
		return '<section class="curatescape-section curatescape-map-section">'.$html.'</section>';
	}else{
		return null;
	}
	
}

/*
** Tour Map section
** returns interactive map for Story posts in current Tour post
*/
function curatescape_tour_map($post){
	if($locations = $post->tour_locations){
		$html = '<h2 class="curatescape-section-heading curatescape-section-heading-map">'.__('Map').'</h2>';
		$html .= '<figure  class="curatescape-figure">';		
		$html .= '<div id="curatescape-item-map" class="curatescape-map curatescape-item-map">';
		$html .= '</div>';
		$html .= '</figure>';	
		return '<section class="curatescape-section curatescape-map-section">'.$html.'</section>';		
	}
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
	if($locations = $post->tour_locations){
		$locations=explode(',',$locations);
		$html = '<h2 class="curatescape-section-heading curatescape-section-heading-locations">'.__('Locations').'</h2>';
		$html .= '<div class="curatescape-tour-locations">';
		foreach($locations as $id){
			$post=get_post( $id );
			$html .= '<div class="curatescape-tour-location curatescape-flex">';
				$html .= get_the_post_thumbnail( $post, 'thumbnail');
				$html .= '<div>';
				$html .= '<h3><a href="'.get_the_permalink( $post ).'">'.get_the_title( $post ).curatescape_subtitle( $post ).'</a></h3>';
				$html .= '<p> </p>';
				$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
		return '<section class="curatescape-section curatescape-locations-section">'.$html.'</section>';
	}else{
		return null;		
	}
}

/*
** Tour Navigation
*/	
function curatescape_tour_navigation($post){
	// todo...
	return '<p>tour navigation goes here...</p>';		
}