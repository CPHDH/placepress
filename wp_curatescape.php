<?php
/*
Plugin Name: Curatescape for WordPress
Plugin URI: https://curatescape.org
Description: Publish location-based, media-rich, structured narratives compatible with Curatescape mobile apps. Designed for public historians, urbanists, and other humanities researchers. Adds Tour and Story post types, as well as custom taxonomies and metadata fields.
Version: 0.9.4
Text Domain: wp_curatescape
Domain Path: /languages
Author: CSU Center for Public History + Digital Humanities 
Author URI: http://csudigitalhumanities.org
Contributors: ebellempire,cphdh
Donate link: https://csudigitalhumanities.org/about/donate/
Tags: curatescape,history,public history, digital humanities
Requires at least: 4.9
Tested up to: 4.9
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*  
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Copyright 2017  CSU Center for Public History + Digital Humanities  (email : digitalhumanities@csuohio.edu)
*/

if( ! defined('ABSPATH') ){
	exit;
}

/*
** PLUGIN SETTINGS
*/
require_once plugin_dir_path( __FILE__ ). 'admin/settings.php';

/*
** POST TYPES AND TAXONOMIES
*/
require_once plugin_dir_path( __FILE__ ). 'admin/post_types.php';

/* 
** FIELDS AND METABOXES
*/
require_once plugin_dir_path( __FILE__ ). 'admin/metaboxes.php';

/* 
** MENUS
*/
require_once plugin_dir_path( __FILE__ ). 'admin/menus.php';

/*
** JSON OUTPUT
*/	
require_once plugin_dir_path( __FILE__ ). 'output/api.php';

/*
** WIDGETS
*/	
require_once plugin_dir_path( __FILE__ ). 'widgets/widgets.php';

/*
** HELPERS
*/	
require_once plugin_dir_path( __FILE__ ). 'helpers/helpers.php';

/*
** FILTERS
*/	
require_once plugin_dir_path( __FILE__ ). 'filters/filters.php';

/*
** SHORTCODES
*/	
require_once plugin_dir_path( __FILE__ ). 'shortcodes/shortcodes.php';

/*
** DASHBOARD
*/
add_action( 'dashboard_glance_items' , 'curatescape_at_a_glance' );
function curatescape_at_a_glance(){
    $args = array(
        'public' => true ,
        '_builtin' => false
    );
    $post_types = get_post_types( $args , 'object' , 'and' );
    foreach( $post_types as $post_type ) {
        $count = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $count->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name , intval( $count->publish ) );
        $type_name = $post_type->name;

        if ( current_user_can( 'edit_posts' ) ) {
            $output = '<a href="edit.php?post_type=' . $type_name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="'.$type_name.'-count ' . $type_name . '-count">' . $output . '</li>';
        }        
    }
}


/*
** STYLES AND SCRIPTS
*/
add_action( 'admin_enqueue_scripts', 'curatescape_admin_scripts' ); // Admin  
function curatescape_admin_scripts(){
	// Curatescape
	wp_register_style( 'curatescape_admin_css', plugin_dir_url( __FILE__ ) . 'styles/admin.css');
	wp_register_script( 'curatescape_admin_js', plugin_dir_url( __FILE__ ) . 'scripts/admin.js', '', '', true);
	// Leaflet
	wp_register_style( 'leafletcss', '//unpkg.com/leaflet@1.2.0/dist/leaflet.css');
	wp_register_script( 'leafletjs', '//unpkg.com/leaflet@1.2.0/dist/leaflet.js', '', '', false );

    global $pagenow;
    if ($pagenow != 'post.php' && $pagenow != 'post-new.php') {
        return;
    }   
    
	// Enqueue
	wp_enqueue_style( 'curatescape_admin_css' );
    wp_enqueue_style( 'leafletcss' );	    
    wp_enqueue_script( 'leafletjs' );   
    wp_enqueue_script( 'curatescape_admin_js' ); 
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-autocomplete' );
}  
add_action( 'wp_enqueue_scripts', 'curatescape_public_scripts' ); // Public
function curatescape_public_scripts(){
	// Curatescape
	wp_register_style( 'curatescape_public_css', plugin_dir_url( __FILE__ ) . 'styles/public.css');
	wp_register_script( 'curatescape_story_js', plugin_dir_url( __FILE__ ) . 'scripts/story.js', '', '', true);
	wp_register_script( 'curatescape_tour_js', plugin_dir_url( __FILE__ ) . 'scripts/tour.js', '', '', true);
	wp_register_script( 'curatescape_global_map_js', plugin_dir_url( __FILE__ ) . 'scripts/global_map.js', '', '', true);	
	// Leaflet
	wp_register_style( 'leafletcss', '//unpkg.com/leaflet@1.2.0/dist/leaflet.css');
	wp_register_script( 'leafletjs', '//unpkg.com/leaflet@1.2.0/dist/leaflet.js', '', '', false ); 
	// Maki Markers
	wp_register_script( 'makijs', plugin_dir_url( __FILE__ ) . 'libraries/leaflet.makimarkers/Leaflet.MakiMarkers.js', array('leafletjs'), '', false);
	// Clustering
	wp_register_style( 'clustercss', plugin_dir_url( __FILE__ ) . 'libraries/leaflet.markercluster/MarkerCluster.Default.css');
	wp_register_script( 'clusterjs', plugin_dir_url( __FILE__ ) . 'libraries/leaflet.markercluster/leaflet.markercluster.js', array('leafletjs'), '', false);
	// Photoswipe
	wp_register_style( 'photoswipecss', plugin_dir_url( __FILE__ ) . 'libraries/photoswipe/photoswipe.css');
	wp_register_style( 'photoswipecss_ui', plugin_dir_url( __FILE__ ) . 'libraries/photoswipe/default-skin/default-skin.css');    
	wp_register_script( 'photoswipejs', plugin_dir_url( __FILE__ ) . 'libraries/photoswipe/photoswipe.min.js', '', '', true);
	wp_register_script( 'photoswipejs_ui', plugin_dir_url( __FILE__ ) . 'libraries/photoswipe/photoswipe-ui-default.min.js', '', '', true);	
	
	// Enqueue	
	if( is_singular('stories') ){
		wp_enqueue_style( 'curatescape_public_css' );
	    wp_enqueue_style( 'leafletcss' );
	    wp_enqueue_script( 'leafletjs' );
	    if( curatescape_setting('disable_pswp') !== 1 ){
		    wp_enqueue_style( 'photoswipecss' );
		    wp_enqueue_style( 'photoswipecss_ui' );	
		    wp_enqueue_script( 'photoswipejs' );
		    wp_enqueue_script( 'photoswipejs_ui' );	
	    }     	
	    if( curatescape_setting( 'maki_markers' )){
		    wp_enqueue_script( 'makijs' );	
		}
	    wp_enqueue_script( 'curatescape_story_js' );
	}   
	if( is_singular( 'tours' ) ){
		wp_enqueue_style( 'curatescape_public_css' );
	    wp_enqueue_style( 'leafletcss' );	    
	    wp_enqueue_script( 'leafletjs' );  	
	    if(curatescape_setting( 'maki_markers' )){
		    wp_enqueue_script( 'makijs' );
		}
	    wp_enqueue_script( 'curatescape_tour_js' );		    
	}  
	// See shortcodes.php for additional script and style inclusions. 	
}

/*
** LANGUAGES
*/	
add_action('plugin_loaded','curatescape_load_textdomain');
function curatescape_load_textdomain(){
		load_plugin_textdomain('wp_curatescape',false,plugin_dir_path( __FILE__ ).'/languages');
}
