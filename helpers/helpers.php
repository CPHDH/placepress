<?php
if( ! defined('ABSPATH') ){
	exit;
}

// Constants
define('PRECONNECT_CDN', 'https://unpkg.com');
define('LEAFLET_JS', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js');
define('LEAFLET_CSS', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css');
define('CLUSTER_JS', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js');
define('CLUSTER_CSS', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css');
define('CLUSTER_CSS_DEFAULT', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css');
define('PLACEPRESS_TILE_PROVIDER', plugins_url() . '/placepress/placepress-blocks/src/tile-provider.js');
define('PLACEPRESS_MAIN_JS', plugins_url() . '/placepress/placepress-blocks/src/placepress.js');
define('PLACEPRESS_SETTINGS_JS', plugins_url() .'/placepress/admin/settings/settings.js');
define('PLACEPRESS_SETTINGS_CSS', plugins_url() .'/placepress/admin/settings/settings.css');

// Only load Leaflet assets as needed
function placepress_helper_needs_leaflet_assets(){
	if(
		has_block('placepress/block-tour-stop')
		|| has_block('placepress/block-map-location')
		|| has_block('placepress/block-map-global')
	){
			return true;
		}else{
			return false;
		}
}

// Only load cluster assets as needed
function placepress_helper_needs_cluster_assets(){
	if(
		placepress_setting('marker_clustering')==true
		&& !has_block('placepress/block-tour-stop')
		&& !is_singular('tours')
		&& !is_singular('locations')
	){
			return true;
		}else{
			return false;
		}
}

// Only load global scripts and styles as needed:
// i.e. for Tours, Locations, Global Map Blocks, & PP Settings
function placepress_helper_has_scriptable_content($isadmin = false){
	if(
		has_block('placepress/block-tour-stop')
		|| has_block('placepress/block-map-location')
		|| has_block('placepress/block-map-global')
		|| get_query_var( 'post_type' ) === 'locations'
		|| get_query_var( 'post_type' ) === 'tours'
		|| $isadmin
	){
		return true;
	}else{
		return false;
	}
}

// Header preconnect
function placepress_helper_preconnect(){
?>
<link rel="preconnect" href="<?php echo PRECONNECT_CDN;?>">
<?php
}

// Generic callback to return Leaflet related assets
function placepress_helper_leaflet_assets($infooter=true){

	// Global
	wp_enqueue_style('placepress-leaflet-css',
		LEAFLET_CSS,
		array()
	);

	wp_enqueue_script('placepress-leaflet-js',
		LEAFLET_JS,
		array(), false, $infooter
	);

	wp_enqueue_script('placepress-tiles',
		PLACEPRESS_TILE_PROVIDER,
		array(), false, $infooter
	);

	// Conditional: Marker Clustering (Setting)
	if(placepress_helper_needs_cluster_assets()){

		wp_enqueue_style('placepress-cluster-css',
			CLUSTER_CSS,
			array()
		);

		wp_enqueue_style('placepress-cluster-css-default',
			CLUSTER_CSS_DEFAULT,
			array()
		);

		wp_enqueue_script('placepress-cluster-js',
			CLUSTER_JS,
			array('placepress-leaflet-js'), false, $infooter
		);

	}
}
