<?php
/*
Plugin Name: PlacePress
Plugin URI: https://wpplacepress.org
Description: Adds Tour and Location post types, structured map blocks, and custom taxonomies. Designed for public historians, urbanists, and other humanities researchers.
Version: 0.9.7
Text Domain: wp_placepress
Domain Path: /languages
Author: CSU Center for Public History + Digital Humanities
Author URI: http://csudigitalhumanities.org
Contributors: ebellempire,cphdh
Donate link: https://csudigitalhumanities.org/about/donate/
Tags: placepress, history, public history, digital humanities, maps, curatescape, blocks, gutenburg, location, tour, post types
Requires at least: 5.0
Tested up to: 5.1
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

Copyright 2017  CSU Center for Public History + Digital Humanities  (email : digitalhumanities@csuohio.edu)
*/

if( ! defined('ABSPATH') ){
	exit;
}

/*
** PLUGIN SETTINGS
*/
require_once plugin_dir_path( __FILE__ ). 'admin/settings.php';

/*
** POST TYPES AND TAXONOMIES
*/
require_once plugin_dir_path( __FILE__ ). 'admin/post_types.php';

/*
** METABOXES
*/
require_once plugin_dir_path( __FILE__ ). 'admin/metaboxes.php';

/*
** BLOCKS
*/
require_once plugin_dir_path( __FILE__ ). 'placepress-blocks/src/init.php';

/*
** WIDGETS
*/
require_once plugin_dir_path( __FILE__ ). 'widgets/widgets.php';

/*
** MENUS
*/
require_once plugin_dir_path( __FILE__ ). 'admin/menus.php';

/*
** DASHBOARD
*/
require_once plugin_dir_path( __FILE__ ). 'admin/dashboard.php';

/*
** LANGUAGES
*/
require_once plugin_dir_path( __FILE__ ). 'languages/languages.php';

/*
** HELPERS
*/
require_once plugin_dir_path( __FILE__ ). 'helpers/helpers.php';

/*
** API
*/
require_once plugin_dir_path( __FILE__ ). 'api/output.php';
