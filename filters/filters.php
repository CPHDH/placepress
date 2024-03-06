<?php
if (!defined('ABSPATH')) {
    exit;
}

/*
** ARCHIVES: TOUR BLOCK FILTER
** This wraps the block on archive pages, giving it a post id data-attribute, used in placepress.js
*/
add_filter('render_block', 'tour_block_archive_wrapper', 10, 2);
function tour_block_archive_wrapper($block_content, $block)
{
    if (!is_single() && in_the_loop() && is_main_query()) {
        if ($block['blockName'] === 'placepress/block-tour-stop') {
            $content = '<div data-redirect-to-post-id="'.get_the_ID().'">';
            $content .= $block_content;
            $content .= '</div>';
            return $content;
        }
        return $block_content;
    } else {
        return $block_content;
    }
}

/*
** ARCHIVES: LOCATION POST TYPE AND TAX FILTER
** Filter the location type and location post type archive titles in order to add an optional map
*/
add_filter('get_the_archive_title', 'pp_add_archive_map');
function pp_add_archive_map($title){
    $post_type = get_query_var('post_type');
    $post_tax = get_query_var('taxonomy');
    $post_term = get_query_var('term');
    $append = null;
    $ltype = null;
    if(!is_admin()){
        if($post_type == 'locations' && (placepress_setting('enable_location_archive_map'))){
            $append = '<figure hidden><div id="placepress-map_archive" class="map-pp" data-lat="'.placepress_setting('default_latitude').'" data-lon="'.placepress_setting('default_longitude').'" data-zoom="'.placepress_setting('default_zoom').'" data-basemap="'.placepress_setting('default_map_type').'" data-type="archive" data-location-type-selection="true"></div><figcaption class="map-caption-pp">'.__('All Locations', 'wp_placepress').'</figcaption></figure>';
        }
        if($post_tax == 'location_types' && (placepress_setting('enable_location_types_map'))){
            $term = get_term_by('slug',$post_term,'location_types');
            if( isset($term) && isset($term->name) ){
                $ltype = ': '.$term->name;
            }
            $append = '<figure hidden><div id="placepress-map_archive" class="map-pp" data-lat="'.placepress_setting('default_latitude').'" data-lon="'.placepress_setting('default_longitude').'" data-zoom="'.placepress_setting('default_zoom').'" data-basemap="'.placepress_setting('default_map_type').'" data-type="archive"></div><figcaption class="map-caption-pp">'.sprintf(__('All Results for Location Type%s', 'wp_placepress'), $ltype).'</figcaption></figure>';
        }
    }
    return $title.$append;
}