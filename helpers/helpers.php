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
function placepress_setting($option){
	$options=get_option('placepress_options',placepress_options_default());
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
function placepress_parse_markdown($string,$singleline=true){
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
function placepress_get_story_media($post){
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

					$title=$attachment_meta['title'] ? $attachment_meta['title'] : null;
					$caption=$attachment_meta['caption'] ? $attachment_meta['caption'] : null;
					$description=$attachment_meta['description'] ? $attachment_meta['description'] : null;
					$description_combined = implode(' ~ ', array_filter(array($description,$caption )));
					$caption_array=array_filter(array($title,$description_combined));
					$caption_combined=implode( ': ', $caption_array );

					$images[]=array(
						'id'=>$attachment_meta['id'],
						'url'=>$attachment_meta['sizes']['medium']['url'],
						'url_original'=>$attachment_meta['url'],
						'title_attribute'=>$title, // for HTML
						'title'=>$caption_combined, // for PSWP captioning
						'description'=>$description,
						'h'=>$attachment_meta['sizes']['full']['height'],
						'w'=>$attachment_meta['sizes']['full']['width'],
						'src'=>$attachment_meta['sizes']['full']['url'],
						'msrc'=>$attachment_meta['sizes']['medium']['url'],
					);
					break;
				case 'audio':
					$audio[]=array(
						'id'=>$attachment_meta['id'],
					);
					break;
				case 'video':
					$video[]=array(
						'id'=>$attachment_meta['id'],
					);
					break;
				default:
					$other[]=array(
						'id'=>$attachment_meta['id'],
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
function placepress_image_gallery($images,$containerTag='section',$includeHeading=true){
	$headerVisibility=$includeHeading ? null : 'hidden';
	if( placepress_setting('disable_pswp') !== 1 ){
		$hiddenImageJSON='<div id="pswp-images" hidden class="hidden placepress-hidden" aria-role="hidden">'.htmlspecialchars(json_encode( $images )).'</div>';
		$photoswipe_ui_markup = $hiddenImageJSON.'<div id="pswp" class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div> </div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>';
		$html = '<'.$containerTag.' class="placepress-section placepress-media-section placepress-images-section">';
		$html .= '<h2 '.$headerVisibility.' class="placepress-section-heading placepress-section-heading-images">'.__('Images').'</h2>';
		$html .= '<div class="placepress-flex placepress-image-grid">';
		$i=0;
		foreach($images as $file){
			$html.='<div class="pswp_item_container"><div class="pswp_item" data-pswp-index="'.$i.'" title="'.$file['title_attribute'].'" style="background-image:url('.$file['url'].')"></div></div>';
			$i++;
		}
		$html .= '</div>';
		$html .= $photoswipe_ui_markup.'</'.$containerTag.'>';
		return $html;
	}else{
		$html = '<'.$containerTag.' class="placepress-section placepress-media-section placepress-images-section">';
		$html .= '<h2 '.$headerVisibility.' class="placepress-section-heading placepress-section-heading-images">'.__('Images').'</h2>';
		$html .= '<div class="placepress-inline-images">';
		$i=0;
		foreach($images as $file){
			$html .='<div class="placepress-inline-image-outer">';
			$html .='<a target="_blank" href="'.$file['url_original'].'" class="placepress-inline-image" title="'.$file['title_attribute'].'" style="background-image:url('.$file['url'].')"></a>';
			$html .= '<div class="placepress-inline-title"><strong>'.$file['title_attribute'].'</strong></div>';
			$html .= '<p class="placepress-inline-image-description">'.$file['title'].'</p>'; // combined caption normally used for PSWP
			$html .='</div>';
			$i++;
		}
		$html .= '</div>';
		$html .= '</'.$containerTag.'>';
		return $html;
	}

}

/*
** Audio playlist
** returns audio playlist
*/
function placepress_audio_playlist($audio,$containerTag='section',$includeHeading=true){
	$headerVisibility=$includeHeading ? null : 'hidden';
	$html = '<'.$containerTag.' class="placepress-section placepress-media-section placepress-audio-section">';
	$html .= '<h2 '.$headerVisibility.' class="placepress-section-heading placepress-section-heading-audio">'.__('Audio').'</h2>';
	$ids=array();
	foreach($audio as $file){
		$ids[] = $file['id'];
	}
	$html .= do_shortcode('[playlist type="audio" ids="'.implode(',',$ids).'" style="light"]');
	$html .= '</'.$containerTag.'>';
	return $html;
}

/*
** Video playlist
** returns video playlist
*/
function placepress_video_playlist($video,$containerTag='section',$includeHeading=true){
	$headerVisibility=$includeHeading ? null : 'hidden';
	$html = '<'.$containerTag.' class="placepress-section placepress-media-section placepress-video-section">';
	$html .= '<h2 '.$headerVisibility.' class="placepress-section-heading placepress-section-heading-video">'.__('Video').'</h2>';
	foreach($video as $file){
		$ids[] = $file['id'];
	}
	$html .= do_shortcode('[playlist type="video" ids="'.implode(',',$ids).'" style="light"]');
	$html .= '</'.$containerTag.'>';
	return $html;
}

/*
** Media section
** returns interactive image gallery, audio playlist, and/or video playlist for Story post
** media content already placed via shortcodes will be omitted from placepress_filter_content()
*/
function placepress_display_media_section($post, $includeImages=true, $includeAudio=true, $includeVideo=true){
	$html = null;
	$media=placepress_get_story_media($post);
	if(count($media)){
		$html .= count($media['images']) && $includeImages ? placepress_image_gallery($media['images']) : null;
		$html .= count($media['audio']) && $includeAudio ? placepress_audio_playlist($media['audio']) : null;
		$html .= count($media['video']) && $includeVideo ? placepress_video_playlist($media['video']) : null;
	}
	return $html;
}

/*
** Story Map section
** returns interactive map for Story post
*/
function placepress_story_map($post,$includeHeading=true){
	if($coords=$post->location_coordinates){
		$headerVisibility=$includeHeading ? null : 'hidden';
		$zoom=$post->location_zoom ? $post->location_zoom : placepress_setting('default_zoom');
		$thumbnail = has_post_thumbnail( $post->ID ) ? wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'medium' ) : 0;
		$caption_array = array(
			placepress_street_address($post),
			placepress_access_information($post),
			placepress_official_website($post)
		);
		$caption = implode(' ~ ', array_filter($caption_array));
		$html = '<h2 '.$headerVisibility.' class="placepress-section-heading placepress-section-heading-map">'.__('Map').'</h2>';
		$html .= '<figure  class="placepress-figure z-index-adjust">';
		$html .= '<div id="placepress-story-map" class="placepress-map placepress-item-map" data-coords="'.$coords.'" data-zoom="'.$zoom.'" data-default-layer="'.placepress_setting('default_map_type').'" data-zoom="'.placepress_setting('default_zoom').'" data-center="'.placepress_setting('default_coordinates').'" data-mapbox-token="'.placepress_setting('mapbox_key').'" data-mapbox-satellite="'.placepress_setting('mapbox_satellite').'" data-maki="'.placepress_setting('maki_markers').'" data-maki-color="'.placepress_setting('maki_markers_color').'" data-thumb="'.$thumbnail.'" data-address="'.placepress_street_address($post).'" data-marker-clustering="0">';
		$html .= '</div>';
		$html .= '</figure>';
		$html .= '<figcaption class="placepress-figcaption"><p>'.$caption.'</p></figcaption>';
		return '<section class="placepress-section placepress-map-section">'.$html.'</section>';
	}else{
		return null;
	}

}

/*
** Tour Map section
** returns interactive map for Story posts in current Tour post
*/
function placepress_tour_map($post){
	if($locations = $post->tour_locations){
		$location_json = array();
		$i=0;
		foreach(explode(',',$locations) as $id){
			$post=get_post(intval($id));
			$thumbnail = has_post_thumbnail( $post->ID ) ? wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'medium' ) : 0;
			$location_json[$i]=array();
			$location_json[$i]['id']=$id;
			$location_json[$i]['coords']=$post->location_coordinates;
			$location_json[$i]['title']=$post->post_title;
			$location_json[$i]['subtitle']=placepress_subtitle($post);
			$location_json[$i]['thumb']=$thumbnail;
			$location_json[$i]['permalink']=$post->guid;
			$i++;
		}
		$html = '<h2 class="placepress-section-heading placepress-section-heading-map">'.__('Map').'</h2>';
		$html .= '<figure  class="placepress-figure z-index-adjust">';
		$html .= '<div id="placepress-tour-map" class="placepress-map placepress-item-map" data-locations="'.htmlentities(json_encode($location_json)).'" data-default-layer="'.placepress_setting('default_map_type').'" data-zoom="'.placepress_setting('default_zoom').'" data-center="'.placepress_setting('default_coordinates').'" data-mapbox-token="'.placepress_setting('mapbox_key').'" data-mapbox-satellite="'.placepress_setting('mapbox_satellite').'" data-maki="'.placepress_setting('maki_markers').'" data-maki-color="'.placepress_setting('maki_markers_color').'" data-marker-clustering="0">';
		$html .= '</div>';
		$html .= '</figure>';
		return '<section class="placepress-section placepress-map-section">'.$html.'</section>';
	}
}

/*
** Global Map section
** returns interactive map for all Story posts
*/
function placepress_global_map(){
	$html = '<figure  class="placepress-figure z-index-adjust">';
	$html .= '<div id="placepress-global-map" class="placepress-map placepress-item-map" data-default-layer="'.placepress_setting('default_map_type').'" data-zoom="'.placepress_setting('default_zoom').'" data-center="'.placepress_setting('default_coordinates').'" data-mapbox-token="'.placepress_setting('mapbox_key').'" data-mapbox-satellite="'.placepress_setting('mapbox_satellite').'" data-maki="'.placepress_setting('maki_markers').'" data-maki-color="'.placepress_setting('maki_markers_color').'" data-marker-clustering="'.placepress_setting('marker_clustering').'">';
	$html .= '</div>';
	$html .= '</figure>';
	return $html;
}

/*
** Street address
*/
function placepress_street_address($post){
	return $post->story_street_address ? placepress_parse_markdown($post->story_street_address) : null;
}

/*
** Access information
*/
function placepress_access_information($post){
	return $post->story_access_information ? placepress_parse_markdown($post->story_access_information) : null;
}

/*
** Official website
*/
function placepress_official_website($post){
	return $post->story_official_website ? placepress_parse_markdown($post->story_official_website) : null;
}

/*
** Subtitle
*/
function placepress_subtitle($post){
	return $post->story_subtitle ? '<br><span class="placepress-subtitle">'.placepress_parse_markdown($post->story_subtitle).'</span>' : null;
}

/*
** Lede
*/
function placepress_lede($post){
	return $post->story_lede ? '<p class="placepress-lede">'.placepress_parse_markdown($post->story_lede).'</p>' : null;
}

/*
** Related resources section
** returns related resources section for Story post
*/
function placepress_related_sources($post){
	if($post->story_related_resources){
		$html='<h2 class="placepress-section-heading placepress-section-heading-related-resources">'.__('Related Sources').'</h2>';
		$html .= '<ul class="placepress-related-sources">';
		foreach($post->story_related_resources as $rr){
			$html .= '<li>'.placepress_parse_markdown($rr).'</li>';
		}
		$html .= '</ul>';
		return '<section class="placepress-section placepress-related-resources-section">'.$html.'</section>';;
	}else{
		return null;
	}
}

/*
** Tour Locations section
** returns locations section for Tour post
*/
function placepress_stories_for_tour($post){
	if($locations = $post->tour_locations){
		$locations=explode(',',$locations);
		$html = '<h2 class="placepress-section-heading placepress-section-heading-locations">'.__('Locations').'</h2>';
		$html .= '<div class="placepress-tour-locations">';
		foreach($locations as $id){
			$post=get_post( $id );
			if($post->location_coordinates){
				$excerpt = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
				$html .= '<div class="placepress-tour-location placepress-flex">';
					$html .= get_the_post_thumbnail( $post, 'thumbnail');
					$html .= '<div>';
					$html .= '<h3><a href="'.get_the_permalink( $post ).'">'.get_the_title( $post ).placepress_subtitle( $post ).'</a></h3>';
					$html .= '<p>'.substr($excerpt, 0, 240).'...</p>';
					$html .= '</div>';
				$html .= '</div>';
			}
		}
		$html .= '</div>';
		return '<section class="placepress-section placepress-locations-section">'.$html.'</section>';
	}else{
		return null;
	}
}

/*
** Tour Navigation
*/
function placepress_tour_navigation($post){
	// todo...
	return '<p>tour navigation goes here...</p>';
}
