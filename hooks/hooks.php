<?php
if (!defined('ABSPATH')) {
    exit;
}

/*
** ARCHIVES: AUTHOR AND TAG
** This modifies author and tag queries in order to add support for locations and tours
** Tries not to overwrite existing types -- though I'm not sure that $query->get('post_type') ever returns a result
*/
add_action( 'pre_get_posts', 'pp_pre_get_posts' );
function pp_pre_get_posts( $query )
{
    if ( is_admin() || ! $query->is_main_query()) {
        return;
    }
    if (is_author() || is_tag()){
        $currentTypes = $query->get('post_type');
        $desiredTypes = array('post', 'locations', 'tours');
        if(is_array($currentTypes)){
            $query->set('post_type', array_merge($currentTypes,$desiredTypes));
        }else{
            $query->set('post_type', $desiredTypes);
        }
        return;
    }
}