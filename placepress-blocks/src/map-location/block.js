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
			const request = new XMLHttpRequest();
			request.open(
				'GET',
				'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' +
					query,
				true
			);
			request.onload = function() {
				const data = JSON.parse( this.response );
				const result = data[ 0 ];
				if ( typeof result !== 'undefined' && result.lat && result.lon ) {
					setMarkerLocationViaSearch( result.lat, result.lon );
				} else {
					console.log( 'Nope!' );
				}
			};
			request.send();
		};

		const setMarkerLocationViaSearch = function( lat, lon ) {
			console.log( lat, lon );
		};

		const onBlockLoad = function( e ) {
			uiLocationMapPP();
		};

		// Init location map user interface
		const uiLocationMapPP = function() {
			const tileSets = window.getMapTileSets();
			const tileSet = tileSets[ basemap ];

			const map = L.map( 'placepress-map', {
				scrollWheelZoom: false,
			} ).setView( [ lat, lon ], zoom );
			L.tileLayer( tileSet.url, {
				attribution: tileSet.attribution,
			} ).addTo( map );

			// user actions
			const marker = L.marker( [ lat, lon ], {
				draggable: 'true',
			} ).addTo( map );
			marker.on( 'dragend', function( e ) {
				const ll = e.target.getLatLng();
				props.setAttributes( { lat: ll.lat } );
				props.setAttributes( { lon: ll.lng } );
				map.setView( [ ll.lat, ll.lng ], ll.zoom, { animation: true } );
			} );

			function onMarkerClick( e ) {
				const ll = e.target.getLatLng();
				marker.bindPopup( ll.lat + ',' + ll.lng );
			}
			marker.on( 'click', onMarkerClick );

			map.on( 'zoomend', function( e ) {
				const z = map.getZoom();
				props.setAttributes( { zoom: z } );
			} );
		};

		// set attributes
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
				<img // @TODO: find a replacement for this hack to fire the map script when block is added
					className="onload-hack-pp"
					height="0"
					width="0"
					onLoad={ onBlockLoad }
					src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1' %3E%3Cpath d=''/%3E%3C/svg%3E"
				/>
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
					<figcaption className="map-caption-pp">
						{ attributes.caption }
					</figcaption>
				</figure>
			</div>
		);
	},
} );
