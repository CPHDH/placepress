<?php
class Curatescape_Meta_Box {
		
	public function __construct($id, $post_type, $metabox_title, $fields, $appendFile) {
		
		$this->id = $id;
		$this->post_type = $post_type;
		$this->fields = $fields;
		$this->metabox_title = $metabox_title;
		$this->appendFile = $appendFile ? $appendFile : false;

		add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
		add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );

	}
	

	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {
		
		add_meta_box(
			$this->id,
			$this->metabox_title,
			array( $this, 'render_metabox' ),
			$this->post_type,
			'normal',
			'high'
		);

	}

	public function render_metabox( $post ) {


		// Add nonce for security and authentication.
		$nonce_action = $this->id.'_nonce_action';
		$nonce_name = $this->id.'_nonce';
		wp_nonce_field( $nonce_action, $nonce_name );
		
		$html = null;
		foreach($this->fields as $field){
			
			$value = get_post_meta( $post->ID, $field['name'], true );
			if( empty( $value ) ) $value = '';
			
			$repeatable=$field['repeatable'];
			$repeatable_button=( $repeatable > 0 ) ? '<br><div class="repeatable_button"><span class="dashicons dashicons-plus-alt"></span>Add</div><br>' : null;
			
			$ui_class= $field['custom_ui'] ? 'hidden custom_ui' : null;
			
			$html .= '<tr id="'.$field['name'].'_row" class="'.$ui_class.'">';
			switch ($field['type']) {
				
				/* TEXT - repeatable */
			    case 'text':
				    if($repeatable){	    			    
					    $input=null;
					    for($i=0; $i<=$repeatable; $i++){
						    $user_value=isset($value[$i]) ? $value[$i] : null;
						    $visibility = (!$user_value && !$i==0) ? 'hidden' : 'visible'; // only show first field and fields with data
						    $input .= '<input type="text" id="'.$field['name'].'['.$i.']'.'" name="'.$field['name'].'['.$i.']'.'" class="'.$field['name'].'_field_'.$i.' '.$visibility.'" placeholder="" value="' .$user_value. '">';
					    }
				    }else{
					    $input = '<input type="text" id="'.$field['name'].'" name="'.$field['name'].'" class="'.$field['name'].'_field" placeholder="" value="' . $value. '">';
				    }
			        $html .= '<th>'.
			        '<label for="'.$field['name'].'" class="'.$field['name'].'_label">'.$field['label'].'</label>'.
			        '</th><td>'.$input.$repeatable_button.'<br><span class="description">'.$field['helper'].'</span></td>';
			        continue 2;
			        
			    /* TEXTAREA - repeatable */    
			    case 'textarea':
				    if($repeatable){	    			    
					    $input=null;
					    for($i=0; $i<=$repeatable; $i++){
						    $user_value=isset($value[$i]) ? $value[$i] : null;
						    $visibility = (!$user_value && !$i==0) ? 'hidden' : 'visible'; // only show first field and fields with data
						    $input .= '<textarea id="'.$field['name'].'['.$i.']'.'" name="'.$field['name'].'['.$i.']'.'" class="'.$field['name'].'_field_'.$i.' '.$visibility.'"'. 
			        	'placeholder="">'.$user_value.'</textarea>';
					    }
				    }else{
					    $input = '<textarea id="'.$field['name'].'" name="'.$field['name'].'" class="'.$field['name'].'_field"'. 
			        	'placeholder="">'.$value.'</textarea>';
				    }			    
			        $html .= '<th>'.
			        '<label for="'.$field['name'].'" class="'.$field['name'].'_label">'.$field['label'].'</label>'.
			        '</th><td>'.$input.$repeatable_button.'<br><span class="description">'.$field['helper'].'</span></td>';
			        continue 2;
			    
			    /* SELECT */    
			    case 'select':
			    	$options = $field['options'];
			    	if( count($options) > 0 ){
				    	$options_html=null;
				    	foreach($options as $option){
					    	$options_html .= '<option value="'.$option['name'].'" ' . selected( $value, $option['name'], false ) . '> '.$option['label'].'</option>';
				    	}
				        $html .= '<th>'.
				        '<label for="'.$field['name'].'" class="'.$field['name'].'_label">'.$field['label'].'</label>'.
				        '</th><td>'.
				        '<select id="'.$field['name'].'" name="'.$field['name'].'" class="'.$field['name'].'_field">'.$options_html.'</select><br><span class="description">'.$field['helper'].'</span></td>';			    	
			    	}
			        continue 2;
			    
			    /* CHECKBOX */
			    case 'checkbox':
			        $html .= '<th>'.
			        '<label for="'.$field['name'].'" class="'.$field['name'].'_label">'.$field['label'].'</label>'.
			        '</th><td>'.
			        '<input type="checkbox" id="'.$field['name'].'" name="'.$field['name'].'" class="'.$field['name'].'_field"'.
			        	'value="' . $value . '" ' . checked( $value, 'checked', false ) . '>'.
			        	'<span class="description">'.$field['helper'].'</span></td>';
			        continue 2;
			        
			}
			$html .= '</tr>';

		}
		
		// Form fields.
		echo '<table class="form-table">'.$html.'</table>';
		
		// Include external file for any fields requiring a custom UI
		if($this->appendFile){
			include $this->appendFile;
		}

	}

	public function save_metabox( $post_id, $post ) {

		// Nonce for security and authentication
		$nonce_name   = isset($_POST[$this->id.'_nonce']) ? $_POST[$this->id.'_nonce'] : null;
		$nonce_action = $this->id.'_nonce_action';

		// Nonce is set
		if ( ! isset( $nonce_name ) )
			return;

		// Nonce is valid
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// User has permissions 
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Not an autosave
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Not a revision
		if ( wp_is_post_revision( $post_id ) )
			return;
			
		// Check if there was a multisite switch before
		if ( is_multisite() && ms_is_switched() )
			return;


		// Sanitize user input and update database		
		foreach($this->fields as $field){
			$repeatable=$field['repeatable'];
			switch ($field['type']) {
				
			    case 'text':
			    if($repeatable){
				    $new=array();
				    for($i=0; $i<=$repeatable; $i++){
					    $arr=$_POST[$field['name']];
					    $new[$i] = $arr[$i] ? sanitize_text_field( $arr[$i]  ) : null;
				    }
				    update_post_meta( $post_id, $field['name'], $new );	
			    }else{
				    $new = isset( $_POST[ $field['name'] ] ) ? sanitize_text_field( $_POST[ $field['name'] ] ) : '';
				    update_post_meta( $post_id, $field['name'], $new );				    
			    }
			    continue 2;
			    				
			    case 'textarea':
			    if($repeatable){
				    $new=array();
				    for($i=0; $i<=$repeatable; $i++){
					    $arr=$_POST[$field['name']];
					    $new[$i] = $arr[$i] ? sanitize_text_field( $arr[$i]  ) : null;
				    }
				    update_post_meta( $post_id, $field['name'], $new );					    
			    }else{
				    $new = isset( $_POST[ $field['name'] ] ) ? sanitize_text_field( $_POST[ $field['name'] ] ) : '';
				    update_post_meta( $post_id, $field['name'], $new );				    
			    }
			    continue 2;

			    case 'select':
			    $new = isset( $_POST[ $field['name'] ] ) ? $_POST[ $field['name'] ] : '';
			    update_post_meta( $post_id, $field['name'], $new );
			    continue 2;

			    case 'checkbox':
			    $new = isset( $_POST[ $field['name'] ] ) ? 'checked' : '';
			    update_post_meta( $post_id, $field['name'], $new );
			    continue 2;			    			    
			}

		}
	}

}	
	
	 
// Init metaboxes
if(is_admin()){
	
	new Curatescape_Meta_Box('curatescape_tour_details',
		'tours', 
		__('Tour Details'),
		array(
			array(
				'label'		=> __('Credits'),
				'name'		=> 'tour_credits',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('Enter the name of the person(s) or organization responsible for the content of the tour.'),
				'repeatable'=> 0,
				),	
			array(
				'label'		=> __('Postscript Text'),
				'name'		=> 'tour_postscript',
				'type'		=> 'textarea',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('Add postscript text to the end of the tour, for example, to thank a sponsor or add directional information.'),
				'repeatable'=> 0,
				),	
			array(
				'label'		=> __('Tour Locations/Stories'),
				'name'		=> 'tour_locations',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> true, // this hidden form field will save Location post IDs as an ordered array
				'helper'	=> __('Choose locations for this tour. You can <a href="/wp-admin/edit.php?post_type=stories">add and edit Locations/Stories here</a>.'),
				'repeatable'=> 0,
				),					
			),'custom_ui/tour.php'
	);

	new Curatescape_Meta_Box('story_media',
		'stories',
		__('Media Files'),
		array(
			array(
				'label'		=> __('Choose Media'),
				'name'		=> 'story_media',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('Choose files from the Media Library.'),
				'repeatable'=> 32,
				)
		), null
	);		

	new Curatescape_Meta_Box('story_story_header',
		'stories',
		__('Story Header'),
		array(
			array(
				'label'		=> __('Subtitle'),
				'name'		=> 'story_subtitle',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('Enter a subtitle for the tour.'),
				'repeatable'=> 0,
				),
			array(
				'label'		=> __('Lede'),
				'name'		=> 'story_lede',
				'type'		=> 'textarea',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('A brief introductory section that is intended to entice the reader to read the full entry.'),
				'repeatable'=> 0,
				),
			array(
				'label'		=> __('Custom Byline'),
				'name'		=> 'story_byline',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('The name of the author(s) this entry. To add an automatically linked author, type @ followed by the author\'s username, e.g. @admin, or use <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">markdown</a> to create a custom link e.g. to link to Google, use <pre>[google](https://google.com)</pre>. '),
				'repeatable'=> 0,
				)							
		), null
	);
	

	new Curatescape_Meta_Box('story_factoid',
		'stories',
		__('Factoid'),
		array(
			array(
				'label'		=> __('Factoid'),
				'name'		=> 'story_factoid',
				'type'		=> 'textarea',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('One or more facts or pieces of information related to the entry, often presented as a list. Examples include architectural metadata, preservation status, FAQs, pieces of trivia, etc. Use <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">markdown</a> to add formatting as needed.'),
				'repeatable'=> 16,
				)
		), null
	);	

	new Curatescape_Meta_Box('story_related_resources',
		'stories',
		__('Related Resources'),
		array(
			array(
				'label'		=> __('Related Resources'),
				'name'		=> 'story_related_resources',
				'type'		=> 'textarea',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('The name of or link to a related resource, often used for citation information. Use <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">markdown</a> to add formatting as needed.'),
				'repeatable'=> 16,
				)
		), null
	);	

	
	new Curatescape_Meta_Box('story_location_details',
		'stories',
		__('Location Details'),
		array(
			array(
				'label'		=> __('Street Address'),
				'name'		=> 'story_street_address',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('A detailed street/mailing address for a physical location.'),
				'repeatable'=> 0,
				),
			array(
				'label'		=> __('Access Information'),
				'name'		=> 'story_access_information',
				'type'		=> 'textarea',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('Information regarding physical access to a location, including restrictions (e.g. "Private Property"), walking directions (e.g. "To reach the peak, take the trail on the left"), or other useful details (e.g. "Location is approximate").'),
				'repeatable'=> 0,
				),
			array(
				'label'		=> __('Official Website'),
				'name'		=> 'story_official_website',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> false,
				'helper'	=> __('An official website related to the entry. Use <a href="https://guides.github.com/features/mastering-markdown/" target="_blank">markdown</a> to create an active link, e.g. to link to Google use <pre>[google](https://google.com)</pre>.'),
				'repeatable'=> 0,
				),				
			array(
				'label'		=> __('Map Coordinates'),
				'name'		=> 'location_coordinates',
				'type'		=> 'text',
				'options'	=> null,
				'custom_ui'	=> true, // this hidden form field will save coordinates as an array
				'helper'	=> __('Use the map to add geo-coordinates for this location.'),
				'repeatable'=> 0,
				)								
		), 'custom_ui/story.php'
	);


								
}