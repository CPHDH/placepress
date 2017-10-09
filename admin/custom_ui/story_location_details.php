<br>
<div id="admin-location-search-container">
	<input id="admin-location-search" type="text" placeholder="Search for a location or click map...">
	<input type="submit" value="Submit Search" type="button" class="button" onclick="return lookup_location()">
</div>
<div id="admin-story-map">
	
</div>

<span class="description">Use the map to add geo-coordinates for this location. Place a marker manually by clicking the map or use the search bar to enter an address, coordinates, or other location query. Drag and drop the marker to change location and use the zoom controls to save a custom zoom level.</span>


<script>	
	var map,position,marker,search_result_area,zoom,coords;
	
	var default_coords=<?php echo curatescape_setting('default_coordinates');?>;
	var default_zoom=parseInt(<?php echo curatescape_setting('default_zoom');?>);
	
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
	jQuery('#admin-location-search').keypress(function(e){
        if(e.which == 10 || e.which == 13) {
            lookup_location();
            return false;	
        }
	});
		
	if(current_coords){
		// current location
		add_new_marker(coords);
		map.setView(coords, zoom);
		map.on('click', function(e){
			position = e.latlng;
			update_marker(marker);	
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
	
	function show_search_result_area(position){
		if(typeof(search_result_area) !== 'undefined') map.removeLayer(search_result_area);
		search_result_area = new L.circleMarker(position,{
			radius:20,
			stroke:true,
		}).addTo(map);
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
		
		if(typeof(search_result_area) !== 'undefined') map.removeLayer(search_result_area);
				
	}
	
	function map_toast(msg,duration){
	    jQuery('#map-message-overlay').text(msg).fadeIn(300,function(){
		    setTimeout(function(){
			    jQuery('#map-message-overlay').fadeOut();
		    }, duration);
	    });		
	}
	
	function lookup_location(){
		var btn=jQuery('#admin-location-search-container .button');
		btn.prop('disabled',true);
		var query= jQuery('#admin-location-search').val();
		jQuery.ajax({
			url: 'http://nominatim.openstreetmap.org/?q='+encodeURI(query)+'&format=json&addressdetails=1&limit=1&email=digitalhumanities@csuohio.edu',
		}).done(function( response ) {
			btn.prop('disabled',false);
			pos=response[0];
		    if(pos){
			    show_search_result_area(pos);
			    map.panTo(new L.LatLng(pos.lat, pos.lon));
		    }else{
			    map_toast('No Results Found', 2000);
		    }
		}).fail(function(e){
			map_toast('Something went wrong! Please try again.', 2000);
			btn.prop('disabled',false);
		});		
		return false;
	}

</script>