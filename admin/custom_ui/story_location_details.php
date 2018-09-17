<br>
<div id="admin-location-search-container">
	<input id="admin-location-search" type="text" placeholder="<?php echo esc_html__('Search for a location or click map...','wp_curatescape');?>">
	<input type="submit" value="<?php echo esc_html__('Submit Search','wp_curatescape');?>" type="button" class="button" onclick="return lookup_location()">
</div>
<div id="admin-story-map">
	
</div>

<span class="description"><?php echo esc_html__('Use the map to add geo-coordinates for this location. Place a marker manually by clicking the map or use the search bar to enter an address, coordinates, or other location query. Drag and drop the marker to change location and use the zoom controls to save a custom zoom level.','wp_curatescape');?></span>


<script>	
	
	// Settings
	var mapID='admin-story-map';
	var default_layer='<?php echo curatescape_setting('default_map_type');?>';
	var token = '<?php echo curatescape_setting('mapbox_key');?>';
	
	var map,position,marker,search_result_area,zoom,coords;
	
	var message_no_results='<?php echo esc_html__('No Results Found','wp_curatescape');?>';
	var message_generic_error='<?php echo esc_html__('Something went wrong! Please try again.','wp_curatescape');?>';
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
		zoom=Math.max(parseInt(default_zoom),0);
	}
	
	
	// Do Map
	var map = L.map(mapID, {
	    center: coords,
	    zoom: zoom,
	});	

	map.scrollWheelZoom.disable();
	
	var stamen_terrain = L.tileLayer('//stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}@2x.jpg', {
		attribution: '<a href="http://www.openstreetmap.org/">OpenStreetMap</a> | <a href="http://stamen.com/">Stamen Design</a>'
	});
	
	var carto_street_light = L.tileLayer('//{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}@2x.png', {
	    attribution: '<a href="https://www.openstreetmap.org/">Open Street Map</a> | <a href="http://cartodb.com/attributions">CartoDB</a>',
	});
	
	var osm = L.tileLayer('//{s}.tile.osm.org/{z}/{x}/{y}.png', {
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
	
	
	
	// Functionality
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
			url: '//nominatim.openstreetmap.org/?q='+encodeURI(query)+'&format=json&addressdetails=1&limit=1&email=digitalhumanities@csuohio.edu',
		}).done(function( response ) {
			btn.prop('disabled',false);
			pos=response[0];
		    if(pos){
			    show_search_result_area(pos);
			    map.panTo(new L.LatLng(pos.lat, pos.lon));
		    }else{
			    map_toast(message_no_results, 2000);
		    }
		}).fail(function(e){
			map_toast(message_generic_error, 2000);
			btn.prop('disabled',false);
		});		
		return false;
	}

</script>