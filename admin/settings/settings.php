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
		'placepress_section_archives',
		esc_html__('Archive Settings: Maps','wp_placepress'),
		'placepress_callback_section_archive',
		'placepress'
	);

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
		['id'=>'default_latitude','class'=>'map_replace','label'=>esc_html__('Enter the default map latitude, e.g. 41.503240','wp_placepress')]
	);

	add_settings_field(
		'default_longitude',
		esc_html__('Default Longitude','wp_placepress'),
		'placepress_callback_field_text',
		'placepress',
		'placepress_section_map',
		['id'=>'default_longitude','class'=>'map_replace','label'=>esc_html__('Enter the default map longitude, e.g. -81.675249','wp_placepress')]
	);

	add_settings_field(
		'default_map_type',
		esc_html__('Default Map Type','wp_placepress'),
		'placepress_callback_field_select',
		'placepress',
		'placepress_section_map',
		['id'=>'default_map_type','class'=>'map_replace',
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
		['id'=>'default_zoom','class'=>'map_replace','label'=>esc_html__('Choose a number between 0 (zoomed out) and 20 (zoomed in).','wp_placepress'),'min'=>0,'max'=>20]
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

	add_settings_field(
		'enable_location_archive_map',
		esc_html__('Locations','wp_placepress'),
		'placepress_callback_field_checkbox',
		'placepress',
		'placepress_section_archives',
		['id'=>'enable_location_archive_map','label'=>__('Automatically display the global map on the Locations archive page, e.g. <code>/locations/</code> <br><br><em><strong>Theme Compatibility:</strong><br>Map will be appended using <a href="https://developer.wordpress.org/reference/hooks/get_the_post_type_description/" target="_blank">get_the_post_type_description()</a> hook.</em>','wp_placepress')]
	);

	add_settings_field(
		'placepress_section_archives',
		esc_html__('Location Types','wp_placepress'),
		'placepress_callback_field_checkbox',
		'placepress',
		'placepress_section_archives',
		['id'=>'enable_location_types_map','label'=>__('Automatically display the a map of matching locations on the Locations Types archive page, e.g. <code>/location-type/museums</code> <br><br><em><strong>Theme Compatibility:</strong><br>Requires Permalinks.<br>Map will be appended using <a href="https://developer.wordpress.org/reference/hooks/category_description/" target="_blank">category_description()</a> (or location_types_description) hook.</em>','wp_placepress')]
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
		'enable_location_types_map' => false,
		'enable_location_archive_map' => false,
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
	echo '<p>'.esc_html__('Customize default settings for PlacePress maps.','wp_placepress').'</p><div id="map_ui_container"></div>';
}

function placepress_callback_section_mapbox(){
	echo '<p>'.sprintf(__('All Mapbox options require an API access token. Get your token at %s (some Mapbox some functionality is rate-limited).','wp_placepress'), '<a target="_blank" href="https://www.mapbox.com/studio/account/tokens/">www.mapbox.com</a>').'</p>';
}

function placepress_callback_section_content(){
	echo '<p>'.esc_html__('Enable or disable custom post types.','wp_placepress').'</p>';
}

function placepress_callback_section_archive(){
	echo '<p>'.esc_html__('Enable or disable maps on Location and Location Type archive pages.','wp_placepress').'</p>';
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


/*
** SETTINGS CONTEXTUAL HELP TAB
*/

function add_context_menu_help_placepress(){
	$current_screen = get_current_screen();
	if($current_screen->id == 'settings_page_placepress'){

		$current_screen->add_help_tab(
			array(
				'id' => 'pp_help_tab1',
				'title' => __('Map Settings: General'),
				'content' => __('<p>Use the interactive map to configure the default display settings for your maps. These settings only affect the <em>defaults</em> when adding a map block. You can configure each map\'s individual display when creating content.</p>'.
				'<p><strong>Default Coordinates: </strong>Simply enter the name of a location in the search bar and press enter/return to move to a new location. You may drag and drop the map marker to refine the default map coordinates.</p>'.
				'<p><strong>Default Zoom Level: </strong>Use the +/- buttons on the map to zoom in and out (or double-click on a map to zoom in) to set default zoom level.</p>'.
				'<p><strong>Default Base Map: </strong> Use the layer controls on the map to set a default map style.</p>'.
				'<p><strong>Cluster Settings: </strong>Use the checkbox to enable marker clustering. This can be helpful if you plan to add a lot of locations that are close to one another or if your project covers a large geographic area. This setting only applies to the global map block and to optional maps on archive pages.</p>'
				)
			)
		);

		$current_screen->add_help_tab(
			array(
				'id' => 'pp_help_tab2',
				'title' => __('Content Settings: Post Types'),
				'content' => __('<p>PlacePress adds two custom post types: Locations and Tours. You can use both post types or just one if you prefer. Both post types work just like the default Posts that are built in to WordPress, with a few exceptions:</p>'.
				'<p><strong>Locations: </strong> The Location post type includes the Location Map block, which can be used once in each Location post. Using the block not only displays a customizable map on your Location post, it also allows to you use the Global Map block elsewhere on the site to display all your Locations on a single map.</p>'.
				'<p><strong>Tours: </strong>The Tour post type includes a Tour Stop block that can be used multiple times in each Tour post. It is designed to act as a sort of section header that contains embedded data (including map coordinates) for each stop on a tour. If your Tour Stop blocks contain coordinates, a floating map will be added to the tour and the section headers will be clickable to open a specific map location. It is recommended to add additional details below each Tour Stop block to give your users additional context. For example, you can add audio narration, walking directions, historical details, etc.</p>'
				)
			)
		);

		$current_screen->add_help_tab(
			array(
				'id' => 'pp_help_tab3',
				'title' => __('Archive Settings: Maps'),
				'content' => __('<p>Enabling these options will automatically add a map to the selected archive pages. The map will use the default display settings you configure on this page.</p>'.
				'<p><strong>Theme Compatibility: </strong>If your current theme displays descriptions for category archives, this option should work just fine. If not, you can leave these settings disabled or change/edit your theme. If the map size is too big or small, you can use CSS to make adjustments as needed.</p>'
				)
			)
		);

		$current_screen->add_help_tab(
			array(
				'id' => 'pp_help_tab4',
				'title' => __('About PlacePress'),
				'content' => __('<p>PlacePress is developed and maintained by the Center for Public History + Digital Humanities at Cleveland State University, with initial support from the National Endowment for the Humanities.</p>')
			)
		);

		$current_screen->add_help_tab(
			array(
				'id' => 'pp_help_tab5',
				'title' => __('Additional Resources'),
				'content' => '<ul>'.
				'<li><a target="_blank" href="https://wordpress.org/support/plugin/placepress/">'.__('PlacePress Support Forum').'</a></li>'.
				'<li><a target="_blank" href="https://wpplacepress.org/">'.__('PlacePress Website').'</a></li>'.
				'<li><a target="_blank" href="https://github.com/CPHDH/placepress">'.__('View PlacePress source code on GitHub').'</a></li>'.
				'<li><a target="_blank" href="https://twitter.com/wpplacepress">'.__('Follow PlacePress on Twitter').'</a></li>'.
				'<li><a target="_blank" href="https://csudigitalhumanities.org/">'.__('Center for Public History + Digital Humanities @ CSU').'</a></li>'.
				'</ul>'
			)
		);
	}
}
add_action('admin_head', 'add_context_menu_help_placepress');
