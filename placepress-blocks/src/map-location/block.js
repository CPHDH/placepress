import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { PlainText, InspectorControls, PanelBody } = wp.editor;
const { TextareaControl, TextControl } = wp.components;
const { withState } = wp.compose;


registerBlockType( 'placepress/block-map-location', {
 	title: __( 'Location Map' ),
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
    caption: {
        type: 'string',
        source: 'text',
        selector: '.map-caption-pp'
    },
    coords: {
        type: 'string',
        selector: 'div.map-pp',
        source: 'attribute',
        attribute: 'data-coords',
    },
    zoom: {
        type: 'string',
        selector: 'div.map-pp',
        source: 'attribute',
        attribute: 'data-zoom',
    },

  },
	edit: function( props ) {
    const { attributes: { caption, zoom, coordinates }, className, setAttributes } = props;
    const onChangeCaption = caption => { setAttributes( { caption } ) };

    return (
      <div className={ props.className }>
        <TextControl
          className="query-pp"
          tagName="input"
          placeholder={ __('Type a query and press Enter/Return.', 'wp_placepress') }
          />
        <figure>
          <div class="map-pp" data-coords="0,0" data-zoom="0"></div>
          <TextareaControl
              rows="2"
              className="map-caption-pp"
              tagName="figcaption"
              placeholder={ __('Type a caption for the map (optional).', 'wp_placepress') }
              value={ caption }
              onChange={ onChangeCaption }
              />
        </figure>
      </div>
    );
	 },
	save: function( props ) {
    const className = getBlockDefaultClassName('placepress/block-map-location');
    const { attributes } = props;

		return (
      <div className={ props.className }>
        <figure>
          <div class="map-pp" data-coords={ attributes.coords } data-zoom={ attributes.zoom }></div>
          <figcaption class="map-caption-pp">{ attributes.caption }</figcaption>
        </figure>
      </div>
		);
	},
} );
