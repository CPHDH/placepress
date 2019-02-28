<?php
if( !defined('ABSPATH') ){
	exit;
}
add_action('plugin_loaded','placepress_load_textdomain');
function placepress_load_textdomain(){
		load_plugin_textdomain('wp_placepress',false,plugin_dir_path( __FILE__ ).'/languages');
}
