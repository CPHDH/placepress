<?php
/*
Plugin Name: PlacePress
Plugin URI: https://wpplacepress.org
Description: PlacePress adds Location and Tour post types, structured map blocks (for single and global locations), structured tour stop blocks, and custom taxonomies.
Version: 1.2.2
Text Domain: wp_placepress
Domain Path: /languages
Author: CSU Center for Public History + Digital Humanities
Author URI: http://csudigitalhumanities.org
Contributors: ebellempire,cphdh
Donate link: https://csudigitalhumanities.org/about/donate/
Tags: placepress, history, public, digital humanities, map, curatescape, blocks, gutenburg, location, tour, post types, walking, leaflet
Requires at least: 5.0
Tested up to: 5.6.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Copyright 2019  CSU Center for Public History + Digital Humanities  (email : digitalhumanities@csuohio.edu)
*/

if (! defined('ABSPATH')) {
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

/*
** PLUGIN SETTINGS
*/
require_once plugin_dir_path(__FILE__). 'admin/settings/settings.php';

/*
** POST TYPES AND TAXONOMIES
*/
require_once plugin_dir_path(__FILE__). 'admin/post_types.php';

/*
** METABOXES
*/
require_once plugin_dir_path(__FILE__). 'admin/metaboxes.php';

/*
** BLOCKS
*/
require_once plugin_dir_path(__FILE__). 'placepress-blocks/src/init.php';

/*
** WIDGETS
*/
require_once plugin_dir_path(__FILE__). 'widgets/widgets.php';

/*
** MENUS
*/
require_once plugin_dir_path(__FILE__). 'admin/menus.php';

/*
** DASHBOARD
*/
require_once plugin_dir_path(__FILE__). 'admin/dashboard.php';

/*
** LANGUAGES
*/
require_once plugin_dir_path(__FILE__). 'languages/languages.php';

/*
** HELPERS
*/
require_once plugin_dir_path(__FILE__). 'helpers/helpers.php';

/*
** API
*/
require_once plugin_dir_path(__FILE__). 'api/output.php';

/*
** FILTERS
*/
require_once plugin_dir_path(__FILE__). 'filters/filters.php';

/*
** ACTIVATE
*/
function placepress_activate()
{
    add_option('placepress_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'placepress_activate');

function placepress_plugin_activation_redirect()
{
    if (get_option('placepress_do_activation_redirect', false)) {
        delete_option('placepress_do_activation_redirect');
        flush_rewrite_rules();
        wp_redirect("options-general.php?page=placepress");
        exit;
    }
}
add_action('admin_init', 'placepress_plugin_activation_redirect');

/*
** DEACTIVATE
*/
function placepress_deactivate()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'placepress_deactivate');
