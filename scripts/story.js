// Id
var mapID = 'curatescape-story-map';

// Map Settings
var default_layer = document.getElementById(mapID).getAttribute('data-default-layer');
var center = document.getElementById(mapID).getAttribute('data-center');
var zoom = document.getElementById(mapID).getAttribute('data-zoom');
var token = document.getElementById(mapID).getAttribute('data-mapbox-token');
var satellite = document.getElementById(mapID).getAttribute('data-mapbox-satellite');
var maki = document.getElementById(mapID).getAttribute('data-maki');
var color = document.getElementById(mapID).getAttribute('data-maki-color');

// Story Settings
var coords = document.getElementById(mapID).getAttribute('data-coords');
var zoom = document.getElementById(mapID).getAttribute('data-zoom');
var thumb = document.getElementById(mapID).getAttribute('data-thumb');
var address = document.getElementById(mapID).getAttribute('data-address');

// Do Map
var map = L.map(mapID, {
    center: JSON.parse(center), 
    zoom: parseInt(zoom),
});	
map.scrollWheelZoom.disable();

var stamen_terrain = L.tileLayer('http://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}@2x.jpg', {
	attribution: '<a href="http://www.openstreetmap.org/">OpenStreetMap</a> | <a href="http://stamen.com/">Stamen Design</a>'
});

var carto_street_light = L.tileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}@2x.png', {
    attribution: '<a href="https://www.openstreetmap.org/">Open Street Map</a> | <a href="http://cartodb.com/attributions">CartoDB</a>',
});

var osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
});

var mapbox_sattelite_streets = L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}{retina}.png?access_token={accessToken}', {
    	attribution: '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="https://www.mapbox.com/feedback/">Mapbox</a>',
    	retina: (L.Browser.retina) ? '@2x' : '',
		accessToken: token,
});	

var toplayer;	
switch(default_layer){
	case 'carto_light':
	toplayer=carto_street_light;
	break;
	case 'stamen_terrain':
	toplayer=stamen_terrain;
	break;
	case 'osm':
	toplayer=osm;
	break;		
	default:
	toplayer=carto_street_light;				
}

toplayer.addTo(map);

// Layer controls
var allLayers={
	"Terrain":stamen_terrain,
	"Street":carto_street_light,
	"Open Street Map":osm,
};
if(token){
	allLayers["Satellite"]=mapbox_sattelite_streets;
}
L.control.layers(allLayers).addTo(map);	

// Maki
if(token && maki){
	function icon(token){ 
	    return L.MakiMarkers.icon({
	    	icon: 'circle', 
			color: color, 
			size: 'm',
			accessToken: token
			});	
	}	
	var markerconfig={ icon: icon(token) };
}else{
	var markerconfig={};
}

// Center marker and popup on open
map.on('popupopen', function(e) {
	// find the pixel location on the map where the popup anchor is
    var px = map.project(e.popup._latlng); 
    // find the height of the popup container, divide by 2, subtract from the Y axis of marker location
    px.y -= e.popup._container.clientHeight/2;
    // pan to new center
    map.panTo(map.unproject(px),{animate: true}); 
});	
					
// Do Markers
if(coords){
	var html ='<a class="curatescape_map_thumb" href="#" style="background-image:url('+thumb+')"></a>';
	html += address ? '<div class="curatescape_map_title">'+address+'</div>' : '<div class="curatescape_map_title">['+JSON.parse(coords)+']</div>';
	var marker = new L.marker(JSON.parse(coords),markerconfig).addTo(map);
	marker.on("click", function(e){
		position = marker.getLatLng();
		marker.bindPopup(html);
		e.preventDefault;
	});	
	map.panTo(marker.getLatLng());	
}

