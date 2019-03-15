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
	keywords: [ __( 'Map' ), __( 'Location' ), __( 'PlacePress' ) ],
	supports: {
		anchor: true,
		html: false,
		multiple: false,
		reusable: false,
	},
	description: __( 'A block for adding a location map.' ),
	attributes: {
		caption: {
			type: 'string',
			source: 'text',
			selector: '.map-caption-pp',
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
		mb_key: {
			type: 'string',
			selector: 'div.map-pp',
			source: 'attribute',
			attribute: 'data-mb-key',
		},
		maki: {
			type: 'string',
			selector: 'div.map-pp',
			source: 'attribute',
			attribute: 'data-zoom',
		},
		maki_color: {
			type: 'string',
			selector: 'div.map-pp',
			source: 'attribute',
			attribute: 'data-maki-color',
		},
		basemap: {
			type: 'string',
			selector: 'div.map-pp',
			source: 'attribute',
			attribute: 'data-basemap',
		},
		query: {
			type: 'string',
			selector: 'input.query-pp',
			source: 'text',
		},
	},
	edit( props ) {
		const {
			attributes: {
				caption,
				zoom,
				lat,
				lon,
				mb_key,
				maki,
				maki_color,
				basemap,
				query,
			},
			className,
			setAttributes,
		} = props;
		const onChangeCaption = caption => {
			setAttributes( { caption } );
		};

		const onSubmitQuery = function( e ) {
			e.preventDefault();
			console.log( query );
		};

		const defaults = placepress_plugin_settings.placepress_defaults;
		if ( ! zoom ) {
			props.setAttributes( { zoom: defaults.default_zoom } );
		}
		if ( ! lat ) {
			props.setAttributes( { lat: defaults.default_latitude } );
		}
		if ( ! lon ) {
			props.setAttributes( { lon: defaults.default_longitude } );
		}
		if ( ! mb_key ) {
			props.setAttributes( { mb_key: defaults.mapbox_key } );
		}
		if ( ! maki ) {
			props.setAttributes( { maki: defaults.maki_markers } );
		}
		if ( ! maki_color ) {
			props.setAttributes( { maki_color: defaults.maki_markers_color } );
		}
		if ( ! basemap ) {
			props.setAttributes( { basemap: defaults.default_map_type } );
		}

		return (
			<div
				className={ props.className }
				aria-label={ __( 'Interactive Map' ) }
				role="region"
			>
				<form className="inline-input" onSubmit={ onSubmitQuery }>
					<TextControl
						className="query-pp"
						tagName="input"
						placeholder={ __(
							'Type a query and press Enter/Return.',
							'wp_placepress'
						) }
						onChange={ input => setAttributes( { query: input } ) }
					/>
				</form>

				<figure>
					<div
						className="map-pp"
						id="placepress-map"
						data-lat={ lat }
						data-lon={ lon }
						data-zoom={ zoom }
						data-mb-key={ mb_key }
						data-maki={ maki }
						data-maki-color={ maki_color }
						data-basemap={ basemap }
					/>
					<TextareaControl
						rows="2"
						className="map-caption-pp"
						tagName="figcaption"
						placeholder={ __(
							'Type a caption for the map (optional).',
							'wp_placepress'
						) }
						value={ caption }
						onChange={ onChangeCaption }
					/>
				</figure>
			</div>
		);
	},
	save( props ) {
		const className = getBlockDefaultClassName( 'placepress/block-map-location' );
		const { attributes } = props;

		return (
			<div
				className={ props.className }
				aria-label={ __( 'Interactive Map' ) }
				role="region"
			>
				<figure>
					<div
						className="map-pp"
						id="placepress-map"
						data-lat={ attributes.lat }
						data-lon={ attributes.lon }
						data-zoom={ attributes.zoom }
						data-mb-key={ attributes.mapbox_key }
						data-maki={ attributes.maki_markers }
						data-maki-color={ attributes.maki_markers_color }
						data-basemap={ attributes.basemap }
					/>
					<figcaption className="map-caption-pp">{ attributes.caption }</figcaption>
				</figure>
			</div>
		);
	},
} );
