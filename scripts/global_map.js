var center = document.getElementById('curatescape-global-map').getAttribute('data-center');
var zoom = document.getElementById('curatescape-global-map').getAttribute('data-zoom');

var HttpClient = function() {
    this.get = function(aUrl, aCallback) {
        var anHttpRequest = new XMLHttpRequest();
        anHttpRequest.onreadystatechange = function() { 
            if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
                aCallback(anHttpRequest.responseText);
        }

        anHttpRequest.open( "GET", aUrl, true );            
        anHttpRequest.send( null );
    }
}

var map = L.map('curatescape-global-map', {
    center: JSON.parse(center),
    zoom: parseInt(zoom),
});	
map.scrollWheelZoom.disable();

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var wp_rest_api_stories=window.location.protocol+'//'+window.location.hostname+'/wp-json/wp/v2/stories';
var client = new HttpClient();
client.get(wp_rest_api_stories, function(response) {
    data=JSON.parse(response);
    //console.log(data); 
    var markerArray = new Array();
    data.forEach(function(story){
	    var coords = JSON.parse(story.meta.location_coordinates);
	    var title = story.title.rendered;
	    var subtitle = story.meta.story_subtitle;
	    var permalink = story.link;
		if(coords){
			var marker = new L.marker(coords).addTo(map);
			marker.on("click", function(e){
				position = marker.getLatLng();
				marker.bindPopup('['+position.lat+','+position.lng+']');
				e.preventDefault;
			});	
			markerArray.push(marker);
		}
		var markerGroup = new L.featureGroup(markerArray);
		map.fitBounds(markerGroup.getBounds());		    
    });
});