<br>
<div id="admin-location-search-container">
	<input id="admin-location-search" type="text" placeholder="Search for a location"><input type="submit" value="Submit Search" type="button" class="button">
</div>
<div id="admin-story-map"></div>
<p class="description">Use the map to add geo-coordinates for this location. Place a marker manually or use the search bar to enter an address, coordinates, or other location query.</p>

<script>
	var map = L.map('admin-story-map').setView([51.5, -0.09], 16);
	map.scrollWheelZoom.disable();
	L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	
	L.marker([51.5, -0.09]).addTo(map)
	    .bindPopup('@TODO')
	    .openPopup();
</script>