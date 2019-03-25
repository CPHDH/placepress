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
			const basemap = tileSets[ settings.mapType ];

			if ( settings ) {
				const map = L.map( settings.mapId, {
					scrollWheelZoom: false,
				} ).setView( [ settings.lat, settings.lon ], settings.zoom );
				L.tileLayer( basemap.url, {
					attribution: basemap.attribution,
				} ).addTo( map );

				const marker = L.marker( [ settings.lat, settings.lon ] ).addTo( map );
			}
		};
		if ( typeof wp.editor === 'undefined' ) {
			displayLocationMapPP( getDataAttributesPP() );
		}
	}() );
} );
