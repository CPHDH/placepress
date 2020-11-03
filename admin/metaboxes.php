<?php
if (!defined('ABSPATH')) {
    exit;
}
add_action('init', 'register_block_attributes');
function register_block_attributes()
{
    register_meta('post', 'api_coordinates_pp', array(
        'object_subtype' => 'locations',
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ));
}
