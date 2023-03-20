<?php
if (! defined('ABSPATH')) {
    exit;
}

// brute force script loading on front/home page
// user option for themes that return false negative on has_block('placepress/block-map-global')
function placepress_helper_force_front_page()
{
    if (placepress_setting('force_front_page')==true
        && (is_front_page() || is_home())) {
        return true;
    } else {
        return false;
    }
}

// Only load Leaflet assets as needed
function placepress_helper_needs_leaflet_assets()
{
    if (
        has_block('placepress/block-tour-stop')
        || has_block('placepress/block-map-location')
        || has_block('placepress/block-map-global')
        || has_block('placepress/block-map-global-type')
    ) {
        return true;
    } else {
        return false;
    }
}

// Only load cluster assets as needed
function placepress_helper_needs_cluster_assets()
{
    if (
        placepress_setting('marker_clustering')==true
        && !has_block('placepress/block-tour-stop')
        && !is_singular('tours')
        && !is_singular('locations')
    ) {
        return true;
    } else {
        return false;
    }
}

// Only load global scripts and styles as needed:
// i.e. for Tours, Locations, Global Map Blocks, & PP Settings
function placepress_helper_has_scriptable_content($isadmin = false)
{
    if (
        has_block('placepress/block-tour-stop')
        || has_block('placepress/block-map-location')
        || has_block('placepress/block-map-global')
        || has_block('placepress/block-map-global-type')
        || get_query_var('post_type') === 'locations'
        || get_query_var('post_type') === 'tours'
        || placepress_helper_force_front_page()
        || $isadmin
    ) {
        return true;
    } else {
        return false;
    }
}

// Generic callback to return Leaflet related assets
function placepress_helper_leaflet_assets($infooter=true)
{

    // Global
    wp_enqueue_style(
        'placepress-leaflet-css',
        LEAFLET_CSS,
        array()
    );

    wp_enqueue_script(
        'placepress-leaflet-js',
        LEAFLET_JS,
        array(),
        false,
        $infooter
    );

    wp_enqueue_script(
        'placepress-tiles',
        PLACEPRESS_TILE_PROVIDER,
        array(),
        false,
        $infooter
    );

    // Conditional: Marker Clustering (Setting)
    if (placepress_helper_needs_cluster_assets()) {
        wp_enqueue_style(
            'placepress-cluster-css',
            CLUSTER_CSS,
            array()
        );

        wp_enqueue_style(
            'placepress-cluster-css-default',
            CLUSTER_CSS_DEFAULT,
            array()
        );

        wp_enqueue_script(
            'placepress-cluster-js',
            CLUSTER_JS,
            array('placepress-leaflet-js'),
            false,
            $infooter
        );
    }
}
