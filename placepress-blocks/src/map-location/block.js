import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { PlainText, InspectorControls } = wp.editor;

registerBlockType( 'placepress/block-map-location', {
 	title: __( 'Map: Location' ),
 	icon: 'location',
 	category: 'placepress',
 	keywords: [
 		__( 'Map' ),
    __( 'Location' ),
 		__( 'PlacePress' ),
 	],
  supports:{
    anchor:true,
    html: false,
    multiple: false,
    reusable: false,
  },
  description: __( 'A block for adding a location map.' ),
  attributes: {
    // @TODO: enable metaboxes for...
    //  _block-map-location-coordinates
    //  _block-map-location-caption
    //  _block-map-location-zoom
    content: {
        type: 'string',
        // source: 'meta',
        // meta: '_block-map-location-coordinates',
        selector: '.map-location-pp',
      }
  },
	edit: function( props ) {
    const { className, setAttributes } = props;
    const { attributes } = props;

    function changeContent(changes) {
        setAttributes({
            content: changes
        })
    }
		return (
			<div className={ props.className }>
      <PlainText
          className="map-location-pp"
          tagName="p"
          placeholder={ __("Enter coordinates here.", 'wp_placepress') }
          value={attributes.content}
          onChange={changeContent}
          />
			</div>
		);
	},
	save: function( props ) {
    const className = getBlockDefaultClassName('placepress/block-map-location');
    const { attributes } = props;

		return (
      <div className={className}>
      <p class="map-location-pp">{attributes.content}</p>
      </div>
		);
	},
} );
