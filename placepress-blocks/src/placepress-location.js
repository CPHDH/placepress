document.addEventListener( 'DOMContentLoaded', function( e ) {
	( function() {
		// Extract Map Settings from HTML
		const getDataAttributesPP = function() {
			const mapDiv = document.querySelector( '.map-pp' ) || false;
			const settings = {};
			if ( mapDiv ) {
				settings.mapId = mapDiv.getAttribute( 'id' );
				settings.type = mapDiv.getAttribute( 'data-type' );
				settings.zoom = Number( mapDiv.getAttribute( 'data-zoom' ) );
				settings.lat = Number( mapDiv.getAttribute( 'data-lat' ) );
				settings.lon = Number( mapDiv.getAttribute( 'data-lon' ) );
				settings.style = mapDiv.getAttribute( 'data-basemap' );
				settings.maki = mapDiv.getAttribute( 'data-maki' );
				settings.makiColor = mapDiv.getAttribute( 'data-maki-color' );
				settings.mbKey = mapDiv.getAttribute( 'data-mb-key' );
				if ( settings.lat && settings.lon ) {
					return settings;
				}
				return false;
			}
			return false;
		};
		// Helpers
		const findObjectByKey = function( array, key, value ) {
			for ( let i = 0; i < array.length; i++ ) {
				if ( array[ i ][ key ] === value ) {
					return array[ i ];
				}
			}
			return null;
		};
		// Geolocation
		const getUserLocation = function( map ) {
			return navigator.geolocation.getCurrentPosition( function( pos ) {
				const userLocation = [ pos.coords.latitude, pos.coords.longitude ];
				// add/update user location indicator
				if ( typeof userMarker === 'undefined' ) {
					var userMarker = new L.circleMarker( userLocation, {
						title: 'Geolocation',
						radius: 8,
						fillColor: '#4a87ee',
						color: '#ffffff',
						weight: 3,
						opacity: 1,
						fillOpacity: 0.8,
					} ).addTo( map );
				} else {
					userMarker.setLatLng( userLocation );
				}

				userMarker.on( 'click', function( e ) {
					map.panTo( e.target.getLatLng() );
				} );

				const mapBounds = map.getBounds();
				const newBounds = new L.LatLngBounds(
					mapBounds,
					new L.LatLng( pos.coords.latitude, pos.coords.longitude )
				);
				map.fitBounds( newBounds );
			} );
		};
		// API XMLHttpRequest
		const addLocationsRequest = function( map, markersLayer ) {
			const locations_json =
				location.protocol +
				'//' +
				location.hostname +
				'/wp-json/wp/v2/locations'; // @TODO: non-pretty permalinks
			const request = new XMLHttpRequest();
			request.open( 'GET', locations_json, true );
			request.onload = function() {
				if ( request.status >= 200 && request.status < 400 ) {
					const data = JSON.parse( this.response );
					if ( typeof data !== 'undefined' ) {
						const totalPages = Number(
							request.getResponseHeader( 'X-WP-TotalPages' )
						);

						data.forEach( function( post ) {
							const coords = post.meta.api_coordinates_pp.split( ',' );
							if (
								findObjectByKey(
									post.blocks,
									'blockName',
									'placepress/block-map-location'
								) &&
								coords.length == 2
							) {
								const marker = L.marker( coords, {
									title: post.title.rendered,
									url: post.link,
									coords: coords,
								} );
								// user actions: CLICK
								marker.on( 'click', function( e ) {
									const popup = L.popup().setContent(
										'<a href="' +
											e.target.options.url +
											'">' +
											e.target.options.title +
											'</a>'
									);
									e.target
										.unbindPopup()
										.bindPopup( popup )
										.openPopup();
									map.panTo( e.target.options.coords );
								} );
								markersLayer.push( marker );
							}
						} );

						const markersGroup = L.featureGroup( markersLayer ).addTo( map );
						map.fitBounds( markersGroup.getBounds() );
					} else {
						console.warn(
							'PlacePress: Your request did not return any Locations. Please ensure that you have Location posts that use the PlacePress Location Map block.'
						);
					}
				} else {
					console.warn(
						'PlacePress: There was an error fetching Location posts using the WordPRess REST API. Please check your network connection and try again'
					);
				}
			};
			request.send();
		};

		// Geolocation Controls
		const addGeolocationControls = function( map ) {
			const geolocationControl = L.control( { position: 'bottomleft' } );
			geolocationControl.onAdd = function( map ) {
				const div = L.DomUtil.create(
					'div',
					'leaflet-control leaflet-control-geolocation'
				);
				const btn = L.DomUtil.create( 'a', 'placepress-geolocation', div );
				btn.title = 'Geolocation';
				const icn =
					'<svg id="geolocation" height="35px" width="35px" viewBox="0 0 1024 1024"  xmlns="http://www.w3.org/2000/svg"><path id="inner" d="m512.001 302.46c-115.762 0-209.541 93.808-209.541 209.541 0 115.761 93.779 209.541 209.541 209.541 115.819 0 209.538-93.779 209.538-209.541 0-115.733-93.719-209.541-209.538-209.541z"/><path id="outer" d="m838.411 482.066c-14.439-157.447-138.854-281.92-296.476-296.274v-122.806h-59.869v122.807c-157.622 14.353-282.036 138.826-296.478 296.273h-122.602v59.869h122.602c14.442 157.389 138.856 281.861 296.479 296.302v122.777h59.869v-122.777c157.621-14.44 282.036-138.913 296.476-296.302h122.603v-59.869zm-326.41 299.341c-148.736 0-269.409-120.671-269.409-269.407 0-148.766 120.673-269.409 269.409-269.409 148.792 0 269.406 120.644 269.406 269.409 0 148.737-120.614 269.407-269.406 269.407z"/></svg>';

				btn.innerHTML = icn;

				L.DomEvent.addListener(
					btn,
					'click',
					L.DomEvent.preventDefault
				).addListener( btn, 'click', function( e ) {
					getUserLocation( map );
				} );
				return div;
			};
			geolocationControl.addTo( map );
		};

		// SINGLE LOCATION MAP
		const displayLocationMapPP = function( settings ) {
			const tileSets = window.getMapTileSets();
			const allLayers = window.getControlLayers();
			const basemap = tileSets[ settings.style ];

			if ( settings ) {
				const map = L.map( settings.mapId, {
					scrollWheelZoom: false,
					layers: basemap,
				} ).setView( [ settings.lat, settings.lon ], settings.zoom );

				const marker = L.marker( [ settings.lat, settings.lon ] ).addTo( map );
				marker.on( 'click', function( e ) {
					const popup = L.popup().setContent( settings.lat + ',' + settings.lon );
					e.target
						.unbindPopup()
						.bindPopup( popup )
						.openPopup();
					map.panTo( e.target.getLatLng() );
				} );

				// controls
				L.control.layers( allLayers ).addTo( map );
				const isSecure = window.location.protocol == 'https:' ? true : false;
				if ( isSecure && navigator.geolocation ) {
					addGeolocationControls( map );
				}
			}
		};
		// GLOBAL LOCATIONS MAP
		const displayGlobalMapPP = function( settings ) {
			const tileSets = window.getMapTileSets();
			const allLayers = window.getControlLayers();
			const currentTileSet = tileSets[ settings.style ];
			const markersLayer = [];
			const map = L.map( 'placepress-map', {
				layers: currentTileSet,
				scrollWheelZoom: false,
			} ).setView( [ settings.lat, settings.lon ], settings.zoom );

			// controls
			const layerControls = L.control.layers( allLayers ).addTo( map );
			const isSecure = window.location.protocol == 'https:' ? true : false;
			if ( isSecure && navigator.geolocation ) {
				addGeolocationControls( map );
			}

			// add location markers
			addLocationsRequest( map, markersLayer );
		};

		if ( typeof wp.editor === 'undefined' ) {
			const settings = getDataAttributesPP();
			switch ( settings.type ) {
				case 'single-location':
					displayLocationMapPP( settings );
					break;
				case 'global':
					displayGlobalMapPP( settings );
					break;
			}
		}
	}() );
} );
