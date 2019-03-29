document.addEventListener( 'DOMContentLoaded', function( e ) {
	( function() {
		// Extract Map Settings from HTML
		const getDataAttributesPP = function() {
			const mapDiv = document.querySelector( '.map-pp' ) || false;
			const mapSettings = {};
			if ( mapDiv ) {
				mapSettings.mapId = mapDiv.getAttribute( 'id' );
				mapSettings.zoom = Number( mapDiv.getAttribute( 'data-zoom' ) || 16 );
				mapSettings.lat = mapDiv.getAttribute( 'data-lat' ) || false;
				mapSettings.lon = mapDiv.getAttribute( 'data-lon' ) || false;
				mapSettings.mapType =
					mapDiv.getAttribute( 'data-basemap' ) || 'carto_light';
				mapSettings.maki = mapDiv.getAttribute( 'data-maki' ) !== 0;
				mapSettings.makiColor =
					mapDiv.getAttribute( 'data-maki-color' ) || '#000000';
				mapSettings.mbKey = mapDiv.getAttribute( 'data-mb-key' ) || false;
				if ( mapSettings.lat && mapSettings.lon ) {
					return mapSettings;
				}
				return false;
			}
			return false;
		};

		// Init location map
		const displayLocationMapPP = function( settings ) {
			const tileSets = window.getMapTileSets();
			const allLayers = window.getControlLayers();
			const basemap = tileSets[ settings.mapType ];

			if ( settings ) {
				const map = L.map( settings.mapId, {
					scrollWheelZoom: false,
					layers: basemap,
				} ).setView( [ settings.lat, settings.lon ], settings.zoom );

				const marker = L.marker( [ settings.lat, settings.lon ] ).addTo( map );
				function onMarkerClick( e ) {
					marker.bindPopup( settings.lat + ',' + settings.lon );
				}
				marker.on( 'click', onMarkerClick );

				L.control.layers( allLayers ).addTo( map );

				// Geolocation controls
				const isSecure = window.location.protocol == 'https:' ? true : false;
				if ( ! isSecure ) {
					console.warn(
						__(
							'Geolocation is not available over insecure origins. Please enable HTTPS.',
							'wp_placepress'
						)
					);
				} else if ( navigator.geolocation ) {
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
							navigator.geolocation.getCurrentPosition( function( pos ) {
								const userLocation = [
									pos.coords.latitude,
									pos.coords.longitude,
								];
								// add/update user location indicator
								if ( typeof userMarker === 'undefined' ) {
									var userMarker = new L.circleMarker( userLocation, {
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

								const mapBounds = map.getBounds();
								const newBounds = new L.LatLngBounds(
									mapBounds,
									new L.LatLng( pos.coords.latitude, pos.coords.longitude )
								);
								map.fitBounds( newBounds );
							} );
						} );
						return div;
					};
					geolocationControl.addTo( map );
				}
			}
		};
		if ( typeof wp.editor === 'undefined' ) {
			displayLocationMapPP( getDataAttributesPP() );
		}
	}() );
} );
