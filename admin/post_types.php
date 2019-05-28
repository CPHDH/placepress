<?php
if( !defined('ABSPATH') ){
	exit;
}

add_action( 'init', 'placepress_init' );
function placepress_init() {

	// // Custom Post Type: Tour
	// register_post_type('tours',
	// 	array(
	// 	'label' => __('Tours','wp_placepress'),
	// 	'labels' => array (
	// 		'name' => __('Tours','wp_placepress'),
	// 		'singular_name' => __('Tour','wp_placepress'),
	// 		'add_new' => __('Add New','wp_placepress'),
	// 		'add_new_item' => __('Add New Tour','wp_placepress'),
	// 		'edit_item' => __('Edit Tour','wp_placepress'),
	// 		'new_item' => __('New Tour','wp_placepress'),
	// 		'view_item' => __('View Tour','wp_placepress'),
	// 		'search_items' => __('Search Tours','wp_placepress'),
	// 		'not_found' => __('No Tours Found','wp_placepress'),
	// 		'not_found_in_trash' => __('No Tours Found in Trash','wp_placepress'),
	// 		'parent_item_colon' => __('Parent Tour','wp_placepress'),
	// 		'all_items' => __('All Tours','wp_placepress'),
	// 		'archives' => __('Tours','wp_placepress'),
	// 		'insert_into_item' => __('Insert into Tour','wp_placepress'),
	// 		'uploaded_to_this_item' => __('Uploaded to this Tour','wp_placepress'),
	// 		'featured_image' => __('Tour Image','wp_placepress'),
	// 		'set_featured_image' => __('Set Tour Image','wp_placepress'),
	// 		'remove_featured_image' => __('Remove Tour Image','wp_placepress'),
	// 		'use_featured_image' => __('Use as Tour Image','wp_placepress'),
	// 		'menu_name' => __('Tours','wp_placepress'),
	// 		'name_admin_bar' => __('Tour','wp_placepress'),
	// 		),
	// 	'description' => __('Collections of locations.','wp_placepress'),
	// 	'public' => true,
	// 	'show_ui' => true,
	// 	'show_in_menu' => true,
	// 	'show_in_nav_menus' => true,
	// 	'menu_position' => 20,
	// 	'menu_icon' => 'dashicons-location-alt',
	// 	'capability_type' => 'post',
	// 	'hierarchical' => false,
	// 	'publicly_queryable'=>true,
	// 	'show_in_rest'=>true,
	// 	'delete_with_user'=>false,
	// 	'has_archive'=>true,
	// 	'query_var' => true,
	// 	'has_archive' => true,
	// 	'supports' => array('title','editor','thumbnail','author','excerpt','comments','revisions','custom-fields'),
	// 	'taxonomies' => array('post_tag','tour_types'),
	// 	'rewrite' => array( 'slug' => 'tours' ),
	// 	)
	// );
	//
	// // Custom Taxonomy: Tour Types
	// register_taxonomy('tour_types',
	// 	'tours',
	// 	array(
	// 		'hierarchical' => true,
	// 		'label' => __('Tour Types','wp_placepress'),
	// 		'labels' => array(
	// 			'menu_name' => __('Tour Types','wp_placepress'),
	// 			'add_new_item' => __('Add New Type','wp_placepress'),
	// 			'new_item_name'=>__('New Tour Type Name','wp_placepress'),
	// 			'separate_items_with_commas' => __('Separate types with commas','wp_placepress'),
	// 			'choose_from_most_used' => __('Choose from most used types','wp_placepress'),
	// 			'add_or_remove_items' => __('Add or Remove Types','wp_placepress'),
	// 			'not_found' => __('No Tour Types Found','wp_placepress'),
	// 			'search_items' => __('Search Types','wp_placepress'),
	// 			'all_items' => __('All Types','wp_placepress'),
	// 			'update_item' => __('Update Type','wp_placepress'),
	// 			'parent_item'=>__('Parent Type','wp_placepress'),
	// 			'singular_name'=>__('Tour Type','wp_placepress'),
	// 			),
	// 		'show_ui' => true,
	// 		'show_in_rest'=>true,
	// 		'query_var' => true,
	// 		'rewrite' => array( 'slug' => 'tour-type' ),
	// 		'singular_label' => __('Tour Type','wp_placepress')
	// 		)
	// );

	// Custom Post Type: Location
	register_post_type('locations',
		array(
		'label' => __('Locations','wp_placepress'),
		'labels' => array (
			'name' => __('Locations','wp_placepress'),
			'singular_name' => __('Location','wp_placepress'),
			'add_new' => __('Add New','wp_placepress'),
			'add_new_item' => __('Add New Location','wp_placepress'),
			'edit_item' => __('Edit Location','wp_placepress'),
			'new_item' => __('New Location','wp_placepress'),
			'view_item' => __('View Location','wp_placepress'),
			'search_items' => __('Search Locations','wp_placepress'),
			'not_found' => __('No Locations Found','wp_placepress'),
			'not_found_in_trash' => __('No Locations Found in Trash','wp_placepress'),
			'parent_item_colon' => __('Parent Location','wp_placepress'),
			'all_items' => __('All Locations','wp_placepress'),
			'archives' => __('Locations','wp_placepress'),
			'insert_into_item' => __('Insert into Location','wp_placepress'),
			'uploaded_to_this_item' => __('Uploaded to this Location','wp_placepress'),
			'featured_image' => __('Location Image','wp_placepress'),
			'set_featured_image' => __('Set Location Image','wp_placepress'),
			'remove_featured_image' => __('Remove Location Image','wp_placepress'),
			'use_featured_image' => __('Use as Location Image','wp_placepress'),
			'menu_name' => __('Locations','wp_placepress'),
			'name_admin_bar' => __('Location','wp_placepress'),
			),
		'description' => __('Geolocated, media-rich, narratives.','wp_placepress'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'menu_position' => 21,
		'menu_icon' => 'dashicons-location',
		'capability_type' => 'post',
		'hierarchical' => false,
		'publicly_queryable'=>true,
		'show_in_rest'=>true,
		'delete_with_user'=>false,
		'has_archive'=>true,
		'query_var' => true,
		'has_archive' => true,
		'supports' => array('title','editor','thumbnail','author','excerpt','comments','revisions','custom-fields'),
		'taxonomies' => array('post_tag','location_types'),
		'rewrite' => array( 'slug' => 'locations' ),
		)
	);

	// Custom Taxonomy: Tour Types
	register_taxonomy('location_types',
		'locations',
		array(
			'hierarchical' => true,
			'label' => __('Location Types','wp_placepress'),
			'labels' => array(
				'menu_name' => __('Location Types','wp_placepress'),
				'add_new_item' => __('Add New Type','wp_placepress'),
				'new_item_name'=>__('New Location Type Name','wp_placepress'),
				'separate_items_with_commas' => __('Separate types with commas','wp_placepress'),
				'choose_from_most_used' => __('Choose from most used types','wp_placepress'),
				'add_or_remove_items' => __('Add or Remove Types','wp_placepress'),
				'not_found' => __('No Location Types Found','wp_placepress'),
				'search_items' => __('Search Types','wp_placepress'),
				'all_items' => __('All Types','wp_placepress'),
				'update_item' => __('Update Type','wp_placepress'),
				'parent_item'=>__('Parent Type','wp_placepress'),
				'singular_name'=>__('Location Type','wp_placepress'),
				),
			'show_ui' => true,
			'show_in_rest'=>true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'location-type' ),
			'singular_label' => __('Location Type','wp_placepress')
			)
	);

} /* end init */

function placepress_register_and_rewrite_flush() {
    placepress_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'placepress_register_and_rewrite_flush' );
