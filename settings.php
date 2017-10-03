<?php 
if( !defined('ABSPATH') ){
	exit;
}	
	
define( 'DEFAULT_COORDINATES', '[41.503240, -81.675249]' );
define( 'DEFAULT_ZOOM', 3 );
define( 'DISABLE_TOURS' , false );

add_action('admin_menu','curatescape_add_sublevel_menu');
function curatescape_add_sublevel_menu(){
	add_submenu_page(
		'options-general.php',
		'Curatescape Settings',
		'Curatescape',
		'manage_options',
		'curatescape',
		'curatescape_display_settings_page'		
	);
}

function curatescape_display_settings_page(){
	
	if( ! current_user_can('manage_options') ) return;
	
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() );?></h1>
		<form action="options.php" method="post">
			<?php settings_fields('curatescape_options');?>
			<?php do_settings_sections('curatescape');?>
			<?php submit_button();?>
		</form>
	</div>
	<?

}

add_action('admin_init','curatescape_register_settings');
function curatescape_register_settings(){

}

function curatescape_options(){
	
}

function curatescape_callback_validate_options(){
	
}



