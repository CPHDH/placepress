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
    lat: {
        type: 'string',
        selector: 'div.map-pp',
        source: 'attribute',
        attribute: 'data-lat',
    },
    lon: {
        type: 'string',
        selector: 'div.map-pp',
        source: 'attribute',
        attribute: 'data-lon',
    },
    zoom: {
        type: 'string',
        selector: 'div.map-pp',
        source: 'attribute',
        attribute: 'data-zoom',
    },

  },
	edit( props ) {
    const { attributes: { caption, zoom, coords }, className, setAttributes } = props;
    const onChangeCaption = caption => { setAttributes( { caption } ) };

    let defaults = placepress_plugin_settings.placepress_defaults;

    if(!zoom) props.setAttributes( { zoom: defaults.default_zoom } );
    if(!lat) props.setAttributes( { lat: defaults.default_latitude } );
    if(!lon) props.setAttributes( { lon: defaults.default_longitude } );

    return (
      <div className={ props.className } aria-label={__('Interactive Map')} role="region">
        <TextControl
          className="query-pp"
          tagName="input"
          placeholder={ __('Type a query and press Enter/Return.','wp_placepress') }
          />
        <figure>
          <div class="map-pp"
            data-lat={ lat }
            data-lon={ lon }
            data-zoom={ zoom }
            data-mb-key={ defaults.mapbox_key }
            data-maki={ defaults.maki_markers }
            data-maki-color={ defaults.maki_markers_color }
            data-map-type={ defaults.default_map_type }></div>
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
	save( props ) {
    const className = getBlockDefaultClassName('placepress/block-map-location');
    const { attributes } = props;
    let defaults = placepress_plugin_settings.placepress_defaults;

    return (
      <div className={ props.className } aria-label={__('Interactive Map')} role="region">
        <figure>
          <div class="map-pp"
            data-lat={ attributes.lat }
            data-lon={ attributes.lon }
            data-zoom={ attributes.zoom }
            data-mb-key={ defaults.mapbox_key }
            data-maki={ defaults.maki_markers }
            data-maki-color={ defaults.maki_markers_color }
            data-map-type={ defaults.default_map_type }></div>
          <figcaption class="map-caption-pp">{ attributes.caption }</figcaption>
        </figure>
      </div>
		);
	},
} );
