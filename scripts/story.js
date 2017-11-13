var coords = document.getElementById('curatescape-story-map').getAttribute('data-coords');
var zoom = document.getElementById('curatescape-story-map').getAttribute('data-zoom');
if(coords){
	var map = L.map('curatescape-story-map', {
	    center: JSON.parse(coords),
	    zoom: parseInt(zoom),
	});	

	map.scrollWheelZoom.disable();
	L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	
	marker = new L.marker(JSON.parse(coords)).addTo(map);
	marker.on("click", function(e){
		position = marker.getLatLng();
		marker.bindPopup('['+position.lat+','+position.lng+']');
		e.preventDefault;
	});		
}
