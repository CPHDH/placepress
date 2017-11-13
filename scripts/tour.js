var decodeEntities = (function() {
  var element = document.createElement('div');

  function decodeHTMLEntities (str) {
    if(str && typeof str === 'string') {
      str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
      str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
      element.innerHTML = str;
      str = element.textContent;
      element.textContent = '';
    }
    return str;
  }
  return decodeHTMLEntities;
})();

var locations = document.getElementById('curatescape-tour-map').getAttribute('data-locations');
locations=decodeEntities(locations);
if(locations){
	var markerArray = new Array();
	var map = L.map('curatescape-tour-map');	
	map.scrollWheelZoom.disable();
	L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	JSON.parse(locations).forEach(function(e){
		console.log(e);
		var coords = JSON.parse(e.coords);
		marker = new L.marker(coords).addTo(map);
		marker.on("click", function(e){
			position = marker.getLatLng();
			marker.bindPopup('['+position.lat+','+position.lng+']');
			e.preventDefault;
		});	
		markerArray.push(marker);
	});	
	var markerGroup = new L.featureGroup(markerArray);
	map.fitBounds(markerGroup.getBounds());	
}