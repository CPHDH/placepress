<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// deletes options but not content (i.e. location and tour data)!
delete_option('placepress_options');
