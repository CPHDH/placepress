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
 * Add PlacePress blocks category
 */
function placepress_block_categories( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'placepress',
                'title' => __( 'PlacePress', 'wp_placepress' ),
                'icon'  => 'location',
            ),
        )
    );
}
add_filter( 'block_categories', 'placepress_block_categories', 10, 2 );

function placepress_editor_js() {
    wp_enqueue_script(
        'placepress-settings',
        plugins_url( 'post-type-blocks.js', __FILE__ ),
        array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' )
    );
		wp_localize_script( 'placepress-settings', 'placepress_plugin_settings',
        array(
            'placepress_defaults' => get_option('placepress_options', placepress_options_default()),
        )
		);
}
add_action( 'enqueue_block_editor_assets', 'placepress_editor_js' );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function placepress_blocks_cgb_block_assets($hook) { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'placepress_blocks-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	wp_enqueue_style(
		'placepress-leaflet-css',
		'https://unpkg.com/leaflet@1.4.0/dist/leaflet.css',
		array()
	);

	wp_enqueue_script(
			'placepress-leaflet-js',
			'https://unpkg.com/leaflet@1.4.0/dist/leaflet.js',
			array()
	);

	if(placepress_setting('marker_clustering')==true){
		wp_enqueue_style(
				'placepress-cluster-css',
				'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css',
				array()
		);

		wp_enqueue_style(
				'placepress-cluster-css-default',
				'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css',
				array()
		);

		wp_enqueue_script(
				'placepress-cluster-js',
				'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js',
				array('placepress-leaflet-js')
		);
	}

	wp_enqueue_script(
			'placepress-tiles',
			plugins_url( 'tile-provider.js', __FILE__ ),
			array()
	);

	wp_enqueue_script(
			'placepress-location',
			plugins_url( 'placepress-location.js', __FILE__ ),
			array('placepress-leaflet-js','placepress-tiles')
	);

	if($hook !== 'settings_page_placepress' && $hook !== 'post.php'){
		// see also admin_enqueue_scripts in placepress.php
		$plugin_settings  = 'const placepress_plugin_options = '. json_encode(get_option('placepress_options', placepress_options_default())) .'; ';
		wp_add_inline_script('placepress-location', $plugin_settings, 'before');
	}

	if(placepress_setting('tours_caption_display') <= 0){
		wp_add_inline_style('placepress_blocks-cgb-style-css', ".tour-stop-caption-pp{display:none;}");
	}
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'placepress_blocks_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function placepress_blocks_cgb_editor_assets() { // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'placepress_blocks-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'placepress_blocks-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'placepress_blocks_cgb_editor_assets' );
