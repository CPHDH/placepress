<?php 
if( !defined('ABSPATH') ){
	exit;
}	
	
/*
** MENU
*/
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

/*
** SETTINGS PAGE
*/
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

/*
** REGISTER SETTINGS
*/
add_action('admin_init','curatescape_register_settings');
function curatescape_register_settings(){
	
	register_setting(
		'curatescape_options',
		'curatescape_options',
		'curatescape_callback_validate_options'
	);
	
	/*
	** Sections
	*/
	add_settings_section(
		'curatescape_section_map',
		'Map Settings',
		'curatescape_callback_section_map',
		'curatescape'
	);


	add_settings_section(
		'curatescape_section_other',
		'Additional Settings',
		'curatescape_callback_section_other',
		'curatescape'
	);	
	
	/*
	** Fields
	*/
	add_settings_field(
		'default_coordinates',
		'Default Coordinates',
		'curatescape_callback_field_text',
		'curatescape',
		'curatescape_section_map',
		['id'=>'default_coordinates','label'=>esc_html__('Enter the default map coordinates, e.g. [41.503240, -81.675249]','wp_curatescape')]
	);	

	add_settings_field(
		'default_map_type',
		'Default Map Type',
		'curatescape_callback_field_select',
		'curatescape',
		'curatescape_section_map',
		['id'=>'default_map_type',
		'label'=>'Choose the default map type','options'=>array(
			'street'=>esc_html__('Street','wp_curatescape'),
			'terrain'=>esc_html__('Terrain','wp_curatescape'),
			'satellite'=>esc_html__('Satellite','wp_curatescape')
		)]
	);	

	add_settings_field(
		'default_zoom',
		'Default Zoom Level',
		'curatescape_callback_field_text_number',
		'curatescape',
		'curatescape_section_map',
		['id'=>'default_zoom','label'=>esc_html__('Choose a number between 0 (zoomed all the way out) and 20 (zoomed all the way in).','wp_curatescape'),'min'=>0,'max'=>20]
	);


	add_settings_field(
		'disable_tours',
		'Disable Tours',
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_other',
		['id'=>'disable_tours','label'=>esc_html__('Disable Tours','wp_curatescape')]
	);

}

/*
** DEFAULTS
*/	
function curatescape_options_default(){
	return array(
		'default_coordinates'=>'[41.503240, -81.675249]',
		'default_map_type'=>'street',
		'default_zoom'=>3,
		'disable_tours'=>false,
	);
}

/*
** CALLBACKS
*/

function curatescape_callback_section_map(){
	echo '<p>'.esc_html__('Customize default settings for Curatescape maps.','wp_curatescape').'</p>';
}

function curatescape_callback_section_other(){
	echo '<p>'.esc_html__('Customize additional Curatescape settings.','wp_curatescape').'</p>';
}

// Text
function curatescape_callback_field_text($args){
	$options = get_option('curatescape_options',curatescape_options_default());
	
	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
	
	echo '<input id="curatescape_options_'.$id.'" name="curatescape_options['.$id.']" type="text" size="40" value="'.$value.'"><br>';
	echo '<label for="curatescape_options_'.$id.'">'.$label.'</label>';
}

// Text -- Number
function curatescape_callback_field_text_number($args){
	$options = get_option('curatescape_options',curatescape_options_default());
	
	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	$min = isset( $args['min'] ) ? $args['min'] : 0;
	$max = isset( $args['max'] ) ? $args['max'] : 100;
	
	$value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
	
	echo '<input id="curatescape_options_'.$id.'" name="curatescape_options['.$id.']" type="number" min="'.$min.'" max="'.$max.'" value="'.$value.'"><br>';
	echo '<label for="curatescape_options_'.$id.'">'.$label.'</label>';
}

// Textarea
function curatescape_callback_field_textarea($args){
	$options = get_option('curatescape_options',curatescape_options_default());
	
	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$allowed_tags = wp_kses_allowed_html('post');
	
	$value = isset( $options[$id] ) ? wp_kses( stripslashes_deep( $options[$id] ), $allowed_tags ) : '';	
	
	echo '<textarea id="curatescape_options_'.$id.'" name="curatescape_options['.$id.']" rows="5" cols="50">'.$value.'</textarea><br>';
	echo '<label for="curatescape_options_'.$id.'">'.$label.'</label>';
}

// Radio
function curatescape_callback_field_radio($args){
	$options = get_option('curatescape_options',curatescape_options_default());
	
	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$radio_options = isset( $args['options'] ) ? $args['options'] : array();
	
	$selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';	
	
	foreach($radio_options as $value=>$label){
		$checked = checked( $selected_option === $value,true,false );
		echo '<label><input name="curatescape_options['.$id.']" type="radio" value="'.$value.'" '.$checked.'>';
		echo '<span>'.$label.'</span></label><br>';
	}
}

// Checkbox
function curatescape_callback_field_checkbox($args){
	$options = get_option('curatescape_options',curatescape_options_default());
	
	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$checked = isset( $options[$id] ) ? checked( $options[$id],1,false ) : '';	
	echo '<input id="curatescape_options_'.$id.'" name="curatescape_options['.$id.']" type="checkbox" value="1" '.$checked.'>';
	echo '<label for="curatescape_options['.$id.']">'.$label.'</label>';
}

// Select
function curatescape_callback_field_select($args){
	$options = get_option('curatescape_options',curatescape_options_default());
	
	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	
	$select_options = isset( $args['options'] ) ? $args['options'] : array();
	
	$selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';	
	
	echo '<select id="curatescape_options_'.$id.'" name="curatescape_options['.$id.']">';
	foreach($select_options as $value=>$option){
		$selected = selected( $selected_option === $value,true,false );
		echo '<option value="'.$value.'" '.$selected.'>'.$option.'</option>';
	}
	echo '</select> ';
	echo '<label for="curatescape_options_'.$id.'">'.$label.'</label>';
}

/*
** VALIDATE
*/	
function curatescape_callback_validate_options($input){
	
	$defaults=curatescape_options_default();
	

	if( isset( $input['default_coordinates'] )){
		$input['default_coordinates'] = sanitize_text_field( $input['default_coordinates'] );
	}
	
	if( isset( $input['default_zoom'] )){
		$input['default_zoom'] = sanitize_text_field( $input['default_zoom'] );
	}	
	
	if( ! isset( $input['default_map_type'] )){
		$input['default_map_type'] = null;
	}
	
	$map_types=array('street'=>'Street','terrain'=>'Terrain','satellite'=>'Satellite');
	if( ! array_key_exists( $input['default_map_type'], $map_types )){
		$input['default_map_type'] = null;
	}

	if( ! isset( $input['disable_tours'] )){
		$input['disable_tours'] = null;
	}
	
	$input['disable_tours'] = $input['disable_tours'] == 1 ? 1 : 0;

	return $input;
}
