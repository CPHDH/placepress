<?php
if( !defined('ABSPATH') ){
	exit;
}
/*
** GET PLUGIN OPTION
*/
function placepress_setting($option){
	$options=get_option('placepress_options',placepress_options_default());
	if( isset($options[$option]) ){
		return $options[$option];
	}else{
		return null;
	}
}

/*
** MENU
*/
add_action('admin_menu','placepress_add_sublevel_menu');
function placepress_add_sublevel_menu(){
	add_submenu_page(
		'options-general.php',
		'PlacePress Settings',
		'PlacePress',
		'manage_options',
		'placepress',
		'placepress_display_settings_page'
	);
}

/*
** SETTINGS PAGE
*/
function placepress_display_settings_page(){
	if( ! current_user_can('manage_options') ) return;
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() );?></h1>
		<form action="options.php" method="post">
			<?php settings_fields('placepress_options');?>
			<?php do_settings_sections('placepress');?>
			<?php submit_button();?>
		</form>
	</div>
	<?
}

/*
** REGISTER SETTINGS
*/
add_action('admin_init','placepress_register_settings');
function placepress_register_settings(){

	register_setting(
		'placepress_options',
		'placepress_options',
		'placepress_callback_validate_options'
	);

	/*
	** Sections
	*/
	add_settings_section(
		'placepress_section_map',
		esc_html__('Map Settings: General','wp_placepress'),
		'placepress_callback_section_map',
		'placepress'
	);

	// add_settings_section(
	// 	'placepress_section_mapbox',
	// 	esc_html__('Map Settings: Mapbox','wp_placepress'),
	// 	'placepress_callback_section_mapbox',
	// 	'placepress'
	// );

	add_settings_section(
		'placepress_section_content',
		esc_html__('Content Settings: Post Types','wp_placepress'),
		'placepress_callback_section_content',
		'placepress'
	);

	/*
	** Fields
	*/
	add_settings_field(
		'default_latitude',
		esc_html__('Default Latitude','wp_placepress'),
		'placepress_callback_field_text',
		'placepress',
		'placepress_section_map',
		['id'=>'default_latitude','label'=>esc_html__('Enter the default map latitude, e.g. 41.503240','wp_placepress')]
	);

	add_settings_field(
		'default_longitude',
		esc_html__('Default Longitude','wp_placepress'),
		'placepress_callback_field_text',
		'placepress',
		'placepress_section_map',
		['id'=>'default_longitude','label'=>esc_html__('Enter the default map longitude, e.g. -81.675249','wp_placepress')]
	);

	add_settings_field(
		'default_map_type',
		esc_html__('Default Map Type','wp_placepress'),
		'placepress_callback_field_select',
		'placepress',
		'placepress_section_map',
		['id'=>'default_map_type',
		'label'=>'Choose the default map type','options'=>array(
			'carto_voyager'=>esc_html__('Street (Carto Voyager)','wp_placepress'),
			'carto_light'=>esc_html__('Street (Carto Light)','wp_placepress'),
			'stamen_terrain'=>esc_html__('Terrain (Stamen)','wp_placepress'),
			'esri_world'=>esc_html__('Satellite (ESRI)','wp_placepress'),
		)]
	);

	add_settings_field(
		'default_zoom',
		esc_html__('Default Zoom Level','wp_placepress'),
		'placepress_callback_field_text_number',
		'placepress',
		'placepress_section_map',
		['id'=>'default_zoom','label'=>esc_html__('Choose a number between 0 (zoomed out) and 20 (zoomed in).','wp_placepress'),'min'=>0,'max'=>20]
	);

	add_settings_field(
		'marker_clustering',
		esc_html__('Marker Clustering','wp_placepress'),
		'placepress_callback_field_checkbox',
		'placepress',
		'placepress_section_map',
		['id'=>'marker_clustering','label'=>esc_html__('Enable clustering for crowded map markers','wp_placepress')]
	);

	// add_settings_field(
	// 	'mapbox_key',
	// 	esc_html__('Mapbox Key','wp_placepress'),
	// 	'placepress_callback_field_text',
	// 	'placepress',
	// 	'placepress_section_mapbox',
	// 	['id'=>'mapbox_key','label'=>esc_html__('Enter your Mapbox API access token to enable Mapbox options.','wp_placepress')]
	// );
	//
	// add_settings_field(
	// 	'mapbox_satellite',
	// 	esc_html__('Mapbox Satellite','wp_placepress'),
	// 	'placepress_callback_field_checkbox',
	// 	'placepress',
	// 	'placepress_section_mapbox',
	// 	['id'=>'mapbox_satellite','label'=>esc_html__('Enable Mapbox Satellite Streets layer','wp_placepress')]
	// );
	//
	// add_settings_field(
	// 	'maki_markers',
	// 	esc_html__('Maki Markers','wp_placepress'),
	// 	'placepress_callback_field_checkbox',
	// 	'placepress',
	// 	'placepress_section_mapbox',
	// 	['id'=>'maki_markers','label'=>esc_html__('Enable Maki Markers','wp_placepress')]
	// );
	//
	// add_settings_field(
	// 	'maki_markers_color',
	// 	esc_html__('Maki Markers Color','wp_placepress'),
	// 	'placepress_callback_field_text',
	// 	'placepress',
	// 	'placepress_section_mapbox',
	// 	['id'=>'maki_markers_color','label'=>sprintf(__('Enter an HTML hexadecimal color code (e.g. %s).','wp_placepress'),'<code>#000000</code>')]
	// );

	add_settings_field(
		'enable_locations',
		esc_html__('Locations','wp_placepress'),
		'placepress_callback_field_checkbox',
		'placepress',
		'placepress_section_content',
		['id'=>'enable_locations','label'=>esc_html__('Enable the Locations post type (req. for location map block and global map block)','wp_placepress')]
	);

	add_settings_field(
		'enable_tours',
		esc_html__('Tours','wp_placepress'),
		'placepress_callback_field_checkbox',
		'placepress',
		'placepress_section_content',
		['id'=>'enable_tours','label'=>esc_html__('Enable the Tours post type (req. for tour blocks)','wp_placepress')]
	);

}

/*
** DEFAULTS
*/
function placepress_options_default(){
	return array(
		'default_latitude'=>41.503240,
		'default_longitude'=>-81.675249,
		'default_map_type'=>'carto_voyager',
		'default_zoom'=>12,
		'mapbox_key'=>null,
		'mapbox_satellite'=>false,
		'maki_markers'=>false,
		'maki_markers_color'=>null,
		'marker_clustering'=>false,
		'enable_tours'=>true,
		'enable_locations'=>true,
	);
}

add_action( 'init', 'register_options_defaults' );
function register_options_defaults(){
	foreach (placepress_options_default() as $key => $value) {
		add_option( $key, $value );
	}
}


/*
** CALLBACKS
*/

function placepress_callback_section_map(){
	echo '<p>'.esc_html__('Customize default settings for PlacePress maps.','wp_placepress').'</p>';
}

function placepress_callback_section_mapbox(){
	echo '<p>'.sprintf(__('All Mapbox options require an API access token. Get your token at %s (some Mapbox some functionality is rate-limited).','wp_placepress'), '<a target="_blank" href="https://www.mapbox.com/studio/account/tokens/">www.mapbox.com</a>').'</p>';
}

function placepress_callback_section_content(){
	echo '<p>'.esc_html__('Enable or disable custom post types.','wp_placepress').'</p>';
}

// Text
function placepress_callback_field_text($args){
	$options = get_option('placepress_options',placepress_options_default());

	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';

	$value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

	echo '<input id="placepress_options_'.$id.'" name="placepress_options['.$id.']" type="text" size="40" value="'.$value.'"><br>';
	echo '<label for="placepress_options_'.$id.'">'.$label.'</label>';
}

// Text -- Number
function placepress_callback_field_text_number($args){
	$options = get_option('placepress_options',placepress_options_default());

	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';
	$min = isset( $args['min'] ) ? $args['min'] : 0;
	$max = isset( $args['max'] ) ? $args['max'] : 100;

	$value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

	echo '<input id="placepress_options_'.$id.'" name="placepress_options['.$id.']" type="number" min="'.$min.'" max="'.$max.'" value="'.$value.'"><br>';
	echo '<label for="placepress_options_'.$id.'">'.$label.'</label>';
}

// Textarea
function placepress_callback_field_textarea($args){
	$options = get_option('placepress_options',placepress_options_default());

	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';

	$allowed_tags = wp_kses_allowed_html('post');

	$value = isset( $options[$id] ) ? wp_kses( stripslashes_deep( $options[$id] ), $allowed_tags ) : '';

	echo '<textarea id="placepress_options_'.$id.'" name="placepress_options['.$id.']" rows="5" cols="50">'.$value.'</textarea><br>';
	echo '<label for="placepress_options_'.$id.'">'.$label.'</label>';
}

// Radio
function placepress_callback_field_radio($args){
	$options = get_option('placepress_options',placepress_options_default());

	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';

	$radio_options = isset( $args['options'] ) ? $args['options'] : array();

	$selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

	foreach($radio_options as $value=>$label){
		$checked = checked( $selected_option === $value,true,false );
		echo '<label><input name="placepress_options['.$id.']" type="radio" value="'.$value.'" '.$checked.'>';
		echo '<span>'.$label.'</span></label><br>';
	}
}

// Checkbox
function placepress_callback_field_checkbox($args){
	$options = get_option('placepress_options',placepress_options_default());

	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';

	$checked = isset( $options[$id] ) ? checked( $options[$id],1,false ) : '';
	echo '<input id="placepress_options_'.$id.'" name="placepress_options['.$id.']" type="checkbox" value="1" '.$checked.'>';
	echo '<label for="placepress_options['.$id.']">'.$label.'</label>';
}

// Select
function placepress_callback_field_select($args){
	$options = get_option('placepress_options',placepress_options_default());

	$id	= isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';

	$select_options = isset( $args['options'] ) ? $args['options'] : array();

	$selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

	echo '<select id="placepress_options_'.$id.'" name="placepress_options['.$id.']">';
	foreach($select_options as $value=>$option){
		$selected = selected( $selected_option === $value,true,false );
		echo '<option value="'.$value.'" '.$selected.'>'.$option.'</option>';
	}
	echo '</select> ';
	echo '<label for="placepress_options_'.$id.'">'.$label.'</label>';
}

/*
** VALIDATE
*/
function placepress_callback_validate_options($input){

	$defaults=placepress_options_default();

	if( isset( $input['default_latitude'] )){
		$v=trim($input['default_latitude']);
		if( is_numeric($v) && ($v >= -90) && ($v <= 90) ){
			$input['default_latitude'] = sanitize_text_field(floatval($v));
		}else{
			add_settings_error(
				'default_latitude',
				'default_latitude',
				sprintf(__('You entered "%s" for Default Latitude. Please enter a number between -90 and 90. Reverting to default. Please try again.','wp_placepress'),$v),
				'error' );
			$input['default_latitude'] = $defaults['default_latitude'];
		}
	}

	if( isset( $input['default_longitude'] )){
		$v=trim($input['default_longitude']);
		if( is_numeric($v) && ($v >= -180) && ($v <= 180) ){
			$input['default_longitude'] = sanitize_text_field(floatval($v));
		}else{
			add_settings_error(
				'default_longitude',
				'default_longitude',
				sprintf(__('You entered "%s" for Default Longitude. Please enter a number between -180 and 180. Reverting to default. Please try again.','wp_placepress'),$v),
				'error' );
			$input['default_longitude'] = $defaults['default_longitude'];
		}
	}


	if( isset( $input['default_zoom'] )){
		$v=trim($input['default_zoom']);
		if( is_numeric($v) && ($v >= 0) && ($v <= 20) ){
			$input['default_zoom'] = sanitize_text_field( intval($v) );
		}else{
			add_settings_error(
				'default_zoom',
				'default_zoom',
				sprintf(__('You entered "%s" for Default Zoom. Please enter a number between 0 and 20. Reverting to default. Please try again.','wp_placepress'),$v),
				'error' );
			$input['default_zoom'] = $defaults['default_zoom'];
		}

	}

	if( isset( $input['mapbox_key'] )){
		$input['mapbox_key'] = sanitize_text_field( $input['mapbox_key'] );
	}

	if( isset( $input['maki_markers_color'] )){
		$input['maki_markers_color'] = sanitize_text_field( $input['maki_markers_color'] );
	}

	if( ! isset( $input['default_map_type'] )){
		$input['default_map_type'] = null;
	}

	$map_types=array(
		'carto_voyager'=>esc_html__('Street (Carto Voyager)','wp_placepress'),
		'carto_light'=>esc_html__('Street (Carto Light)','wp_placepress'),
		'stamen_terrain'=>esc_html__('Terrain (Stamen)','wp_placepress'),
		'esri_world'=>esc_html__('Satellite (ESRI)','wp_placepress'),
	);

	if( ! array_key_exists( $input['default_map_type'], $map_types )){
		$input['default_map_type'] = null;
	}

	if( ! isset( $input['mapbox_satellite'] )){
		$input['mapbox_satellite'] = null;
	} $input['mapbox_satellite'] = $input['mapbox_satellite'] == 1 ? 1 : 0;

	if( ! isset( $input['maki_markers'] )){
		$input['maki_markers'] = null;
	} $input['maki_markers'] = $input['maki_markers'] == 1 ? 1 : 0;

	if( ! isset( $input['marker_clustering'] )){
		$input['marker_clustering'] = null;
	} $input['marker_clustering'] = $input['marker_clustering'] == 1 ? 1 : 0;

	return $input;
}
