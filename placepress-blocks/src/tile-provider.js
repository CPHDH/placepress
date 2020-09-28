// Tile provider
(function () {
	window.getControlLayers = function () {
		const tiles = this.getMapTileSets();
		const layers = {
			"Street (Carto Voyager)": tiles.carto_voyager,
			"Street (Carto Light)": tiles.carto_light,
			"Terrain (Stamen)": tiles.stamen_terrain,
			"Satellite (ESRI)": tiles.esri_world,
		};
		return layers;
	};
	window.getMapTileSets = function () {
		const tiles = [];
		tiles.stamen_terrain = L.tileLayer(
			"https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}{retina}.jpg",
			{
				attribution:
					'<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="http://stamen.com/">Stamen Design</a>',
				retina: L.Browser.retina ? "@2x" : "",
				placepress_key: "stamen_terrain",
				minZoom: 0,
				maxZoom: 18,
			}
		);
		tiles.carto_light = L.tileLayer(
			"https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{retina}.png",
			{
				attribution:
					'<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="https://carto.com/attributions">CARTO</a>',
				retina: L.Browser.retina ? "@2x" : "",
				placepress_key: "carto_light",
				maxZoom: 19,
			}
		);
		tiles.carto_voyager = L.tileLayer(
			"https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{retina}.png",
			{
				attribution:
					'<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="https://carto.com/attributions">CARTO</a>',
				retina: L.Browser.retina ? "@2x" : "",
				placepress_key: "carto_voyager",
				maxZoom: 19,
			}
		);
		tiles.esri_world = L.tileLayer(
			"https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
			{
				attribution:
					'<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
				placepress_key: "esri_world",
			}
		);

		return tiles;
	};
})();
