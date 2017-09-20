<br>
<div id="admin-location-search-container">
	<input id="admin-location-search" type="text" placeholder="Search for a location">
	<input type="submit" value="Submit Search" type="button" class="button" onclick="return lookup_location()">
</div>
<div id="admin-story-map">
	
</div>

<p class="description">Use the map to add geo-coordinates for this location. Place a marker manually or use the search bar to enter an address, coordinates, or other location query.</p>


<script>	
	var map,position,marker,zoom,coords;
	
	var default_coords=<?php echo DEFAULT_COORDINATES;?>;
	var default_zoom=parseInt(<?php echo DEFAULT_ZOOM;?>);
	
	var current_coords=jQuery('#location_coordinates').val();
	if(current_coords){
		current_coords = JSON.parse(current_coords);
		coords=current_coords;
	}else{
		coords=default_coords;
	}

	var current_zoom=jQuery('#location_zoom').val();
	if(current_zoom){
		current_zoom = parseInt(current_zoom);
		zoom=current_zoom;
	}else{
		zoom=default_zoom;
	}
	
	map = L.map('admin-story-map').setView(coords, zoom);
	map.scrollWheelZoom.disable();
	L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
	
	jQuery('#admin-story-map').append('<div id="map-message-overlay"></div>');
	
	if(current_coords){
		// current location
		add_new_marker(coords);
		map.setView(coords, zoom);
		map.on('click', function(e){
			position = e.latlng;
			add_new_marker(position);	
		});
				
	}else{
		// new location on click
		map.on('click', function(e){
			position = e.latlng;
			if(typeof(marker)==='undefined'){
				add_new_marker(position);
			}else{
				update_marker(marker);
			}
		});
	}
	
	map.on("zoomend",function(e){
		zoom=map.getZoom();
		update_saved_zoom(zoom);
	});
	
	
	function update_saved_zoom(zoom){
		jQuery('#location_zoom').val(zoom);
	}

	function update_saved_coords(lat,lng){
		jQuery('#location_coordinates').val('['+lat+','+lng+']');
	}
	
	function add_new_marker(position){
		marker = new L.marker(position,{draggable:'true'}).addTo(map);
		marker_actions(marker,position.lat,position.lng);
	}
	
	function update_marker(marker){
		marker.setLatLng(position);
		marker_actions(marker, position.lat, position.lng);
	}
	
	function marker_actions(marker,lat,lng){
		
		if(typeof(lat,lng)!=='undefined'){
			update_saved_coords(lat,lng);
			update_saved_zoom(zoom);
			map.panTo(new L.LatLng(lat,lng));
		}
				
		marker.on("dragend", function(e) {
		    position = marker.getLatLng();
		    update_saved_coords(position.lat, position.lng);
		    map.panTo(new L.LatLng(position.lat, position.lng));
		});		
		
		marker.on("click", function(e){
			position = marker.getLatLng();
			marker.bindPopup('['+position.lat+','+position.lng+']');
			e.preventDefault;
		});
				
	}
	
	function map_toast(msg,duration){
	    jQuery('#map-message-overlay').text(msg).fadeIn(300,function(){
		    setTimeout(function(){
			    jQuery('#map-message-overlay').fadeOut();
		    }, duration);
	    });		
	}
	
	function lookup_location(){
		var query= jQuery('#admin-location-search').val();
		jQuery.ajax({
		  url: 'http://nominatim.openstreetmap.org/?format=json&addressdetails=1&q='+encodeURI(query)+'&format=json&limit=1&email=digitalhumanities@csuohio.edu',
		}).done(function( response ) {
			pos=response[0];
		    if(pos){
			    map.panTo(new L.LatLng(pos.lat, pos.lon));
			    map_toast('Zoom in to location and click to add marker', 2000);
		    }else{
			    map_toast('No Results Found', 2000);
		    }
		});		
		return false;
	}
</script>