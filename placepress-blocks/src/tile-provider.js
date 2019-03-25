// Tile provider
( function() {
	window.getMapTileSets = function() {
		const tiles = [];
		( tiles.osm = {
			url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			attribution:
				'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		} ),
		( tiles[ 'stamen_terrain' ] = {
			url:
					'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}@2x.jpg',
			attribution:
					'<a href="http://www.openstreetmap.org/">OpenStreetMap</a> | <a href="http://stamen.com/">Stamen Design</a>',
		} ),
		( tiles[ 'carto_light' ] = {
			url:
					'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}' +
					( L.Browser.retina ? '@2x.png' : '.png' ),
			attribution:
					'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>',
		} ),
		( tiles[ 'carto_dark' ] = {
			url:
					'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}' +
					( L.Browser.retina ? '@2x.png' : '.png' ),
			attribution:
					'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>',
		} ),
		( tiles[ 'carto_voyager' ] = {
			url:
					'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}' +
					( L.Browser.retina ? '@2x.png' : '.png' ),
			attribution:
					'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>',
		} );
		return tiles;
	};
}() );
