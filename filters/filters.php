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
** ARCHIVES: LOCATION POST TYPE FILTERS
** These filter the location type and post type descriptions in order to add an optional map
*/
add_filter('location_types_description', 'pp_add_location_types_map');
add_filter('get_the_post_type_description', 'pp_add_location_archive_map');
function pp_add_location_types_map($description)
{
    if (placepress_setting('enable_location_types_map') && !is_admin()) {
        $description = $description.'<figure><div id="placepress-map_archive" class="map-pp" data-lat="'.placepress_setting('default_latitude').'" data-lon="'.placepress_setting('default_longitude').'" data-zoom="'.placepress_setting('default_zoom').'" data-basemap="'.placepress_setting('default_map_type').'" data-type="archive"></div><figcaption class="map-caption-pp">'.__('All Results for Location Type', 'wp_placepress').'</figcaption></figure>';
    }
    return $description;
}
function pp_add_location_archive_map($description)
{
    $post_type = get_query_var('post_type');
    if ($post_type == 'locations' && (placepress_setting('enable_location_archive_map') && !is_admin())) {
        $description = $description.'<figure><div id="placepress-map_archive" class="map-pp" data-lat="'.placepress_setting('default_latitude').'" data-lon="'.placepress_setting('default_longitude').'" data-zoom="'.placepress_setting('default_zoom').'" data-basemap="'.placepress_setting('default_map_type').'" data-type="archive" data-location-type-selection="true"></div><figcaption class="map-caption-pp">'.__('All Locations', 'wp_placepress').'</figcaption></figure>';
    }
    return $description;
}
