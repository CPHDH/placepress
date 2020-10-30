<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
*** Add PlacePress blocks category
**/
function placepress_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'placepress',
				'title' => __( 'PlacePress', 'wp_placepress' ),
				'icon'  => 'location'
			),
		)
	);
}
add_filter( 'block_categories', 'placepress_block_categories', 10, 2 );

/**
 * Enqueue PlacePress Global Assets
 */
*** Ask browser to preconnect to CDN
**/
function placepress_preconnect_header(){
	echo placepress_helper_preconnect();
}
add_action('wp_head','placepress_preconnect_header', 2);

function placepress_enqueue_global_assets($hook){

	$isadmin = boolval(
		$hook === 'settings_page_placepress'
		|| $hook === 'post.php'
		|| $hook === 'post-new.php'
	);

	// Only for Tours, Locations, & Global Map Blocks
	// ==============================================
	if(has_block('placepress/block-tour-stop')
		|| has_block('placepress/block-map-location')
		|| has_block('placepress/block-map-global')
		|| get_query_var( 'post_type' ) === 'locations'
		|| get_query_var( 'post_type' ) === 'tours'
		|| $isadmin
	){

		// Global Styles
		wp_enqueue_style('placepress_blocks-style-css',
			plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
			array( 'wp-editor' )
		);

		// Leaflet Assets
		placepress_helper_leaflet_assets(!$isadmin); // in footer unless admin

		// Front End Only
		if(!$isadmin){

			wp_enqueue_script('placepress-main',
				PLACEPRESS_MAIN_JS,
				array('placepress-leaflet-js','placepress-tiles'), false, true
			);

			$plugin_settings  = 'const placepress_plugin_options = '. json_encode(get_option('placepress_options', placepress_options_default())) .'; ';

			wp_add_inline_script('placepress-main',
				$plugin_settings, 'before');
		}

		// Conditional: Tour Caption (Setting)
		if(placepress_setting('tours_caption_display') <= 0){

			wp_add_inline_style('placepress_blocks-style-css',
				".tour-stop-caption-pp{display:none;}");

		}
	}

}

add_action( 'enqueue_block_assets', 'placepress_enqueue_global_assets' );

/**
*** Enqueue PlacePress Editor Assets
**/
function placepress_enqueue_editor_assets(){

	// Scripts for editor
	wp_enqueue_script('placepress_blocks-cgb-block-js',
 		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ),
 		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),false, true
	);

	// Plugin settings for editor
	wp_enqueue_script('placepress-settings',
		plugins_url( 'post-type-blocks.js', __FILE__ ),
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),false, true
	);

	wp_localize_script( 'placepress-settings',
		'placepress_plugin_settings',
		array('placepress_defaults' => get_option('placepress_options', placepress_options_default()))
	);

 	// Styles for editor
 	wp_enqueue_style('placepress_blocks-editor-css',
 		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ),
 		array( 'wp-edit-blocks' )
 	);

}

add_action( 'enqueue_block_editor_assets', 'placepress_enqueue_editor_assets' );

/**
*** Enqueue PlacePress Settings Page Assets
**/
function placepress_settings_js($hook) {

	if($hook !== 'settings_page_placepress'){
		// only on plugin settings page
		return;
	}

	// Leaflet Assets
	placepress_helper_leaflet_assets();

	wp_enqueue_script( 'placepress_settings_js',
		plugins_url() .'/placepress/admin/settings/settings.js', false, true );

	$plugin_settings  = 'const placepress_plugin_options = '. json_encode(get_option('placepress_options', placepress_options_default())) .'; ';

	wp_add_inline_script('placepress_settings_js',
			$plugin_settings, 'before');
}
function placepress_settings_css() {
	wp_enqueue_style('placepress_settings_css',
		plugins_url() .'/placepress/admin/settings/settings.css', false );

}

add_action( 'admin_enqueue_scripts','placepress_enqueue_global_assets' );
add_action( 'admin_enqueue_scripts', 'placepress_settings_js' );
add_action( 'admin_enqueue_scripts', 'placepress_settings_css' );
