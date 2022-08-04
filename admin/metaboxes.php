<?php
if (!defined('ABSPATH')) {
    exit;
}
// register meta
add_action('init', 'register_block_attributes');
function register_block_attributes()
{
    register_meta('post', 'api_coordinates_pp', array(
        'object_subtype' => 'locations',
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() {
            return current_user_can( 'edit_posts' );
        }
    ));
}
// protect meta w/o adding underscore
add_filter( 'is_protected_meta', 'pp_protected_meta', 10, 3 );
function pp_protected_meta( $protected, $meta_key, $meta_type ) {
        if($meta_key == 'api_coordinates_pp'){
            $protected = true;
        }
        return $protected;
}