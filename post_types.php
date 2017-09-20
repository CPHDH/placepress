<?php
add_action( 'init', 'curatescape_init' );
function curatescape_init() {
	
	if(DISABLE_TOURS==false){
		
		// Custom Post Type: Tour
		register_post_type('tours', 
			array(	
			'label' => __('Tours'),
			'labels' => array (
				'name' => __('Tours'),
				'singular_name' => __('Tour'),
				'add_new' => __('Add New'),
				'add_new_item' => __('Add New Tour'),
				'edit_item' => __('Edit Tour'),
				'new_item' => __('New Tour'),
				'view_item' => __('View Tour'),
				'search_items' => __('Search Tours'),
				'not_found' => __('No Tours Found'),
				'not_found_in_trash' => __('No Tours Found in Trash'),
				'parent_item_colon' => __('Parent Tour'),
				'all_items' => __('All Tours'),
				'archives' => __('Tour Archives'),
				'insert_into_item' => __('Insert into Tour'),
				'uploaded_to_this_item' => __('Uploaded to this Tour'),
				'featured_image' => __('Tour Image'),
				'set_featured_image' => __('Set Tour Image'),
				'remove_featured_image' => __('Remove Tour Image'),
				'use_featured_image' => __('Use as Tour Image'),
				'menu_name' => __('Tours'),
				'name_admin_bar' => __('Tour'),
				),	
			'description' => __('Tours are collections of location-based stories.'),
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
			) 
		);
	
		// Custom Taxonomy: Tour Types
		register_taxonomy('tour_types',
			'tours',
			array( 
				'hierarchical' => true, 
				'label' => __('Tour Types'),
				'labels' => array(
					'menu_name' => __('Types'),
					'add_new_item' => __('Add New Type'),
					'separate_items_with_commas' => __('Separate types with commas'),
					'choose_from_most_used' => __('Choose from most used types'),
					'add_or_remove_items' => __('Add or Remove Types'),
					'not_found' => __('No Tour Types Found'),
					'search_items' => __('Search Types'),
					'all_items' => __('All Types'),
					'update_item' => __('Update Type'),
					),
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'tour-type' ), 
				'singular_label' => __('Tour Type')
				) 
		);
	}	
	
	// Custom Post Type: Story
	register_post_type('stories', 
		array(	
		'label' => __('Stories'),
		'labels' => array (
			'name' => __('Stories'),
			'singular_name' => __('Story'),
			'add_new' => __('Add New'),
			'add_new_item' => __('Add New Story'),
			'edit_item' => __('Edit Story'),
			'new_item' => __('New Story'),
			'view_item' => __('View Story'),
			'search_items' => __('Search Stories'),
			'not_found' => __('No Stories Found'),
			'not_found_in_trash' => __('No Stories Found in Trash'),
			'parent_item_colon' => __('Parent Story'),
			'all_items' => __('All Stories'),
			'archives' => __('Story Archives'),
			'insert_into_item' => __('Insert into Story'),
			'uploaded_to_this_item' => __('Uploaded to this Story'),
			'featured_image' => __('Story Image'),
			'set_featured_image' => __('Set Story Image'),
			'remove_featured_image' => __('Remove Story Image'),
			'use_featured_image' => __('Use as Story Image'),
			'menu_name' => __('Stories'),
			'name_admin_bar' => __('Story'),
			),	
		'description' => __('Stories may stand alone or be used in Tours.'),
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
		) 
	);	
	
	
	// Custom Taxonomy: Story Types
	register_taxonomy('story_subjects',
		'stories',
		array( 
			'hierarchical' => true, 
			'label' => __('Subjects'),
			'labels' => array(
				'menu_name' => __('Subjects'),
				'add_new_item' => __('Add New Subject'),
				'separate_items_with_commas' => __('Separate subjects with commas'),
				'choose_from_most_used' => __('Choose from most used subjects'),
				'add_or_remove_items' => __('Add or Remove Subjects'),
				'not_found' => __('No Story Subjects Found'),
				'search_items' => __('Search Subjects'),
				'all_items' => __('All Subject'),
				'update_item' => __('Update Subject'),
				),			
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'subject' ),
			'singular_label' => __('Subject')
			) 
	);	
}
function curatescape_register_and_rewrite_flush() {
    curatescape_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'curatescape_register_and_rewrite_flush' );