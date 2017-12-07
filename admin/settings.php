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
		esc_html__('Map Settings - General','wp_curatescape'),
		'curatescape_callback_section_map',
		'curatescape'
	);

	add_settings_section(
		'curatescape_section_mapbox',
		esc_html__('Map Settings - Mapbox','wp_curatescape'),
		'curatescape_callback_section_mapbox',
		'curatescape'
	);	

	add_settings_section(
		'curatescape_section_content',
		esc_html__('Content Settings','wp_curatescape'),
		'curatescape_callback_section_content',
		'curatescape'
	);
	
	add_settings_section(
		'curatescape_section_other',
		esc_html__('Additional Settings','wp_curatescape'),
		'curatescape_callback_section_other',
		'curatescape'
	);	
	
	/*
	** Fields
	*/
	add_settings_field(
		'default_coordinates',
		esc_html__('Default Coordinates','wp_curatescape'),
		'curatescape_callback_field_text',
		'curatescape',
		'curatescape_section_map',
		['id'=>'default_coordinates','label'=>esc_html__('Enter the default map coordinates, e.g. [41.503240, -81.675249]','wp_curatescape')]
	);	

	add_settings_field(
		'default_map_type',
		esc_html__('Default Map Type','wp_curatescape'),
		'curatescape_callback_field_select',
		'curatescape',
		'curatescape_section_map',
		['id'=>'default_map_type',
		'label'=>'Choose the default map type','options'=>array(
			'carto_light'=>esc_html__('Street (Carto Light)','wp_curatescape'),
			'stamen_terrain'=>esc_html__('Terrain (Stamen)','wp_curatescape'),
			'osm'=>esc_html__('Standard (Open Street Maps)','wp_curatescape'),
		)]
	);		

	add_settings_field(
		'default_zoom',
		esc_html__('Default Zoom Level','wp_curatescape'),
		'curatescape_callback_field_text_number',
		'curatescape',
		'curatescape_section_map',
		['id'=>'default_zoom','label'=>esc_html__('Choose a number between 0 (zoomed all the way out) and 20 (zoomed all the way in).','wp_curatescape'),'min'=>0,'max'=>20]
	);

	add_settings_field(
		'marker_clustering',
		esc_html__('Marker Clustering','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_map',
		['id'=>'marker_clustering','label'=>esc_html__('Enable clustering for crowded map markers','wp_curatescape')]
	);	

	add_settings_field(
		'mapbox_key',
		esc_html__('Mapbox Key','wp_curatescape'),
		'curatescape_callback_field_text',
		'curatescape',
		'curatescape_section_mapbox',
		['id'=>'mapbox_key','label'=>esc_html__('Enter your Mapbox API access token to enable Mapbox options.','wp_curatescape')]
	);	

	add_settings_field(
		'mapbox_satellite',
		esc_html__('Mapbox Satellite','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_mapbox',
		['id'=>'mapbox_satellite','label'=>esc_html__('Enable Mapbox Satellite Streets layer','wp_curatescape')]
	);	
	
	add_settings_field(
		'maki_markers',
		esc_html__('Maki Markers','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_mapbox',
		['id'=>'maki_markers','label'=>esc_html__('Enable Maki Markers','wp_curatescape')]
	);	

	add_settings_field(
		'maki_markers_color',
		esc_html__('Maki Markers Color','wp_curatescape'),
		'curatescape_callback_field_text',
		'curatescape',
		'curatescape_section_mapbox',
		['id'=>'maki_markers_color','label'=>esc_html__('Enter an HTML hexadecimal color code (e.g. #000000).','wp_curatescape')]
	);		

	add_settings_field(
		'disable_tours',
		esc_html__('Disable Tours','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_other',
		['id'=>'disable_tours','label'=>esc_html__('Disable Tours','wp_curatescape')]
	);

	add_settings_field(
		'content_subtitle',
		esc_html__('Subtitle','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_content',
		['id'=>'content_subtitle','label'=>esc_html__('Automatically add Subtitle','wp_curatescape')]
	);

	add_settings_field(
		'content_lede',
		esc_html__('Lede','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_content',
		['id'=>'content_lede','label'=>esc_html__('Automatically add Lede','wp_curatescape')]
	);
	
	add_settings_field(
		'content_related_sources',
		esc_html__('Related Sources','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_content',
		['id'=>'content_related_sources','label'=>esc_html__('Automatically add Related Sources','wp_curatescape')]
	);
	
	add_settings_field(
		'content_media_gallery',
		esc_html__('Media','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_content',
		['id'=>'content_media_gallery','label'=>esc_html__('Automatically add Photo Gallery and Audio/Video Playlists','wp_curatescape')]
	);

	add_settings_field(
		'content_map',
		esc_html__('Map','wp_curatescape'),
		'curatescape_callback_field_checkbox',
		'curatescape',
		'curatescape_section_content',
		['id'=>'content_map','label'=>esc_html__('Automatically add Map','wp_curatescape')]
	);

}

/*
** DEFAULTS
*/	
function curatescape_options_default(){
	return array(
		'default_coordinates'=>'[41.503240, -81.675249]',
		'default_map_type'=>'carto_light',
		'default_zoom'=>3,
		'disable_tours'=>false,
		'content_subtitle'=>true,
		'content_lede'=>true,
		'content_related_sources'=>true,
		'content_media_gallery'=>true,
		'content_map'=>true,
		'mapbox_key'=>null,
		'mapbox_satellite'=>false,
		'maki_markers'=>false,
		'maki_markers_color'=>null,
		'marker_clustering'=>false,
	);
}

/*
** CALLBACKS
*/

function curatescape_callback_section_map(){
	echo '<p>'.esc_html__('Customize default settings for Curatescape maps.','wp_curatescape').'</p>';
}

function curatescape_callback_section_mapbox(){
	echo '<p>'.sprintf(__('All Mapbox options require an API access token. Get your token at %s (some Mapbox some functionality is rate-limited).','wp_curatescape'), '<a target="_blank" href="https://www.mapbox.com/studio/account/tokens/">www.mapbox.com</a>').'</p>';
}

function curatescape_callback_section_content(){
	echo '<p>'.sprintf(__('Curatescape attempts to add custom fields to your theme automatically using WordPress plugin filters. If you have compatibility issues with your theme or would like to customize where custom content appears, you can use the settings below to disable content filters. A full list of shortcodes and helper functions is available at %s','wp_curatescape'),'<a href="https://github.com/CPHDH/wp_curatescape#readme">github.com/CPHDH/wp_curatescape</a>').'</p>';
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
		$v = str_replace(' ', '', $input['default_coordinates']);
		
		if( strlen( $v ) ){
			// if user input exists, create JSON object
			$jsonObject = json_decode( $v );
		}else{
			// set JSON object to null and fail
			$jsonObject = null;
		}
		
		if( strlen( $v ) && $jsonObject === null ) {
			// if user input exists but isn't in JSON format, add square braces, cross fingers, and try again
			$v='['.$v.']';
			$jsonObject = json_decode( $v );
		}
		
		if( count($jsonObject) && (count( $jsonObject ) !== count( array_filter( $jsonObject, 'is_numeric' ) )) ){
			// if the JSON array is not numeric, set object to null and fail
			$jsonObject = null;
		}
		
		if( ( $jsonObject === null || count($jsonObject) !== 2 ) ){
			// if we still don't have a JSON array with two numeric values, give up, reset to default, and show error
			$userInput = strlen( trim($v) ) ? $v : __('(empty)','wp_curatescape');
			add_settings_error( 
				'default_coordinates', 
				'default_coordinates', 
				sprintf(__('You entered %s for Default Coordinates. Please enter valid coordinates. Reverting to plugin default coordinates. Please try again.','wp_curatescape'),$userInput), 
				'error' );
			$input['default_coordinates'] = $defaults['default_coordinates'];
		}else{
			$input['default_coordinates'] = sanitize_text_field( $v );
		}
	}
	
	if( isset( $input['default_zoom'] )){
		$input['default_zoom'] = sanitize_text_field( $input['default_zoom'] );
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
		'carto_light'=>esc_html__('Street (Carto Light)','wp_curatescape'),
		'stamen_terrain'=>esc_html__('Terrain (Stamen)','wp_curatescape'),
		'osm'=>esc_html__('Street (Open Street Maps)','wp_curatescape'),
	);
	
	if( ! array_key_exists( $input['default_map_type'], $map_types )){
		$input['default_map_type'] = null;
	}

	if( ! isset( $input['disable_tours'] )){
		$input['disable_tours'] = null;
	} $input['disable_tours'] = $input['disable_tours'] == 1 ? 1 : 0;

	if( ! isset( $input['content_map'] )){
		$input['content_map'] = null;
	} $input['content_map'] = $input['content_map'] == 1 ? 1 : 0;

	if( ! isset( $input['content_media_gallery'] )){
		$input['content_media_gallery'] = null;
	} $input['content_media_gallery'] = $input['content_media_gallery'] == 1 ? 1 : 0;

	if( ! isset( $input['content_subtitle'] )){
		$input['content_subtitle'] = null;
	} $input['content_subtitle'] = $input['content_subtitle'] == 1 ? 1 : 0;	

	if( ! isset( $input['content_lede'] )){
		$input['content_lede'] = null;
	} $input['content_lede'] = $input['content_lede'] == 1 ? 1 : 0;	

	if( ! isset( $input['content_related_sources'] )){
		$input['content_related_sources'] = null;
	} $input['content_related_sources'] = $input['content_related_sources'] == 1 ? 1 : 0;	

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
