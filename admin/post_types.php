<?php
if( !defined('ABSPATH') ){
	exit;
}		
	
add_action( 'init', 'curatescape_init' );
function curatescape_init() {
	
	if(curatescape_setting('disable_tours')==false){
		
		// Custom Post Type: Tour
		register_post_type('tours', 
			array(	
			'label' => __('Tours','wp_curatescape'),
			'labels' => array (
				'name' => __('Tours','wp_curatescape'),
				'singular_name' => __('Tour','wp_curatescape'),
				'add_new' => __('Add New','wp_curatescape'),
				'add_new_item' => __('Add New Tour','wp_curatescape'),
				'edit_item' => __('Edit Tour','wp_curatescape'),
				'new_item' => __('New Tour','wp_curatescape'),
				'view_item' => __('View Tour','wp_curatescape'),
				'search_items' => __('Search Tours','wp_curatescape'),
				'not_found' => __('No Tours Found','wp_curatescape'),
				'not_found_in_trash' => __('No Tours Found in Trash','wp_curatescape'),
				'parent_item_colon' => __('Parent Tour','wp_curatescape'),
				'all_items' => __('All Tours','wp_curatescape'),
				'archives' => __('Tours','wp_curatescape'),
				'insert_into_item' => __('Insert into Tour','wp_curatescape'),
				'uploaded_to_this_item' => __('Uploaded to this Tour','wp_curatescape'),
				'featured_image' => __('Tour Image','wp_curatescape'),
				'set_featured_image' => __('Set Tour Image','wp_curatescape'),
				'remove_featured_image' => __('Remove Tour Image','wp_curatescape'),
				'use_featured_image' => __('Use as Tour Image','wp_curatescape'),
				'menu_name' => __('Tours','wp_curatescape'),
				'name_admin_bar' => __('Tour','wp_curatescape'),
				),	
			'description' => __('Tours are collections of location-based stories.','wp_curatescape'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-location-alt',
			'capability_type' => 'post',
			'hierarchical' => false,
			'publicly_queryable'=>true,
			'show_in_rest'=>true,
			'delete_with_user'=>false,
			'has_archive'=>true,		
			'query_var' => true,
			'has_archive' => true,
			'supports' => array('title','editor','thumbnail','author','excerpt','comments','revisions'),
			'taxonomies' => array('tour_types'),
			'rewrite' => array( 'slug' => 'tours' ),
			) 
		);
	
		// Custom Taxonomy: Tour Types
		register_taxonomy('tour_types',
			'tours',
			array( 
				'hierarchical' => true, 
				'label' => __('Tour Types','wp_curatescape'),
				'labels' => array(
					'menu_name' => __('Types','wp_curatescape'),
					'add_new_item' => __('Add New Type','wp_curatescape'),
					'separate_items_with_commas' => __('Separate types with commas','wp_curatescape'),
					'choose_from_most_used' => __('Choose from most used types','wp_curatescape'),
					'add_or_remove_items' => __('Add or Remove Types','wp_curatescape'),
					'not_found' => __('No Tour Types Found','wp_curatescape'),
					'search_items' => __('Search Types','wp_curatescape'),
					'all_items' => __('All Types','wp_curatescape'),
					'update_item' => __('Update Type','wp_curatescape'),
					),
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'tour-type' ), 
				'singular_label' => __('Tour Type','wp_curatescape')
				) 
		);
	}	
	
	// Custom Post Type: Story
	register_post_type('stories', 
		array(	
		'label' => __('Stories','wp_curatescape'),
		'labels' => array (
			'name' => __('Stories','wp_curatescape'),
			'singular_name' => __('Story','wp_curatescape'),
			'add_new' => __('Add New','wp_curatescape'),
			'add_new_item' => __('Add New Story','wp_curatescape'),
			'edit_item' => __('Edit Story','wp_curatescape'),
			'new_item' => __('New Story','wp_curatescape'),
			'view_item' => __('View Story','wp_curatescape'),
			'search_items' => __('Search Stories','wp_curatescape'),
			'not_found' => __('No Stories Found','wp_curatescape'),
			'not_found_in_trash' => __('No Stories Found in Trash','wp_curatescape'),
			'parent_item_colon' => __('Parent Story','wp_curatescape'),
			'all_items' => __('All Stories','wp_curatescape'),
			'archives' => __('Stories','wp_curatescape'),
			'insert_into_item' => __('Insert into Story','wp_curatescape'),
			'uploaded_to_this_item' => __('Uploaded to this Story','wp_curatescape'),
			'featured_image' => __('Story Image','wp_curatescape'),
			'set_featured_image' => __('Set Story Image','wp_curatescape'),
			'remove_featured_image' => __('Remove Story Image','wp_curatescape'),
			'use_featured_image' => __('Use as Story Image','wp_curatescape'),
			'menu_name' => __('Stories','wp_curatescape'),
			'name_admin_bar' => __('Story','wp_curatescape'),
			),	
		'description' => __('Stories may stand alone or be used in Tours.','wp_curatescape'),
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
		'supports' => array('title','editor','thumbnail','author','excerpt','comments','revisions'),
		'taxonomies' => array('post_tag','story_subjects'),
		'rewrite' => array( 'slug' => 'stories' ),
		) 
	);	
	
	
	// Custom Taxonomy: Story Types
	register_taxonomy('story_subjects',
		'stories',
		array( 
			'hierarchical' => true, 
			'label' => __('Subjects','wp_curatescape'),
			'labels' => array(
				'menu_name' => __('Subjects','wp_curatescape'),
				'add_new_item' => __('Add New Subject','wp_curatescape'),
				'separate_items_with_commas' => __('Separate subjects with commas','wp_curatescape'),
				'choose_from_most_used' => __('Choose from most used subjects','wp_curatescape'),
				'add_or_remove_items' => __('Add or Remove Subjects','wp_curatescape'),
				'not_found' => __('No Story Subjects Found','wp_curatescape'),
				'search_items' => __('Search Subjects','wp_curatescape'),
				'all_items' => __('All Subject','wp_curatescape'),
				'update_item' => __('Update Subject','wp_curatescape'),
				),			
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'subject' ),
			'singular_label' => __('Subject','wp_curatescape')
			) 
	);	
}
function curatescape_register_and_rewrite_flush() {
    curatescape_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'curatescape_register_and_rewrite_flush' );