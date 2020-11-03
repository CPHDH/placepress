<?php
if (!defined('ABSPATH')) {
    exit;
}
add_action('dashboard_glance_items', 'placepress_at_a_glance');
function placepress_at_a_glance()
{
    $args = array(
        'public' => true ,
        '_builtin' => false
    );
    $post_types = get_post_types($args, 'object', 'and');
    foreach ($post_types as $post_type) {
        $count = wp_count_posts($post_type->name);
        $num = number_format_i18n($count->publish);
        $text = _n($post_type->labels->singular_name, $post_type->labels->name, intval($count->publish));
        $type_name = $post_type->name;

        if (current_user_can('edit_posts')) {
            $output = '<a href="edit.php?post_type=' . $type_name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="'.$type_name.'-count ' . $type_name . '-count">' . $output . '</li>';
        }
    }
}
