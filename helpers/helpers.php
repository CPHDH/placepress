<?php
if( ! defined('ABSPATH') ){
	exit;
}

// Generic callback to return Leaflet related assets
function placepress_helper_leaflet_assets(){

	// Global
	wp_enqueue_style('placepress-leaflet-css',
		'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',
		array()
	);

	wp_enqueue_script('placepress-leaflet-js',
		'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js',
		array()
	);

	wp_enqueue_script('placepress-tiles',
		plugins_url() . '/placepress/placepress-blocks/src/tile-provider.js',
		array()
	);

	// Conditional: Marker Clustering (Setting)
	if(placepress_setting('marker_clustering')==true
		&& !has_block('placepress/block-tour-stop')
		&& !is_singular('tours')
		&& !is_singular('locations')){

		wp_enqueue_style('placepress-cluster-css',
			'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css',
			array()
		);

		wp_enqueue_style('placepress-cluster-css-default',
			'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css',
			array()
		);

		wp_enqueue_script('placepress-cluster-js',
			'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js',
			array('placepress-leaflet-js')
		);

	}
}
