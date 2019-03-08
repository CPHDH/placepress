
document.addEventListener("DOMContentLoaded", function(e) {
  (function(){

    // Extract Map Settings from HTML
    var getMapSettingsPP = function(){
      let mapDiv=(document.querySelector('.map-pp') || false);
      let mapSettings={};
      if(mapDiv){
        mapSettings.mapId = mapDiv.getAttribute('id');
        mapSettings.zoom = Number(mapDiv.getAttribute('data-zoom') || 16 );
        mapSettings.lat = ( mapDiv.getAttribute('data-lat') || false );
        mapSettings.lon = ( mapDiv.getAttribute('data-lon') || false );
        mapSettings.mapType = ( mapDiv.getAttribute('data-basemap') || "carto_light" );
        mapSettings.maki = ( mapDiv.getAttribute('data-maki')!==0 );
        mapSettings.makiColor = ( mapDiv.getAttribute('data-maki-color') || '#000000' );
        mapSettings.mbKey = ( mapDiv.getAttribute('data-mb-key') || false );
        if(mapSettings.lat && mapSettings.lon){
          return mapSettings;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }

    // Tile providers
    var getMapTileSets=function(){
          let tiles=[];
          tiles['osm']={
            url:'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
          },
          tiles['stamen_terrain']={
            url:'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}@2x.jpg',
            attribution:'<a href="http://www.openstreetmap.org/">OpenStreetMap</a> | <a href="http://stamen.com/">Stamen Design</a>',
          },
          tiles['carto_light']={
            url:'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}'+ (L.Browser.retina ? '@2x.png' : '.png'),
            attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>',
          },
          tiles['carto_dark']={
            url:'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}'+ (L.Browser.retina ? '@2x.png' : '.png'),
            attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>',
          },
          tiles['carto_voyager']={
            url:'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}'+ (L.Browser.retina ? '@2x.png' : '.png'),
            attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>',
          }
          return tiles;
    }

    // Init location map
    var mapInitLocation = function(settings){
      let tileSets=getMapTileSets();
      let basemap=tileSets[settings.mapType];

      if(settings){
        let map = L.map(settings.mapId,{
          scrollWheelZoom: false,
        }).setView([settings.lat,settings.lon], settings.zoom);
        L.tileLayer(basemap.url, {
            attribution: basemap.attribution
        }).addTo(map);

        if(typeof wp.editor !== 'undefined'){
          // editor
          let marker = L.marker([settings.lat,settings.lon], {draggable:'true'}).addTo(map);
          marker.on('dragend', function(e){
            var dragged = e.target;
            var position = dragged.getLatLng();
            console.log(position.lat,position.lng);
            // props.setAttributes( { lat: position.lat } )
            // props.setAttributes( { lon: position.lng } )
          });
          map.on('zoomend', function(e) {
              var newZoom = map.getZoom();
              console.log(newZoom);
              // props.setAttributes( { zoom: position.zoom } )
          });
        }else{
          // public
          let marker = L.marker([settings.lat,settings.lon]).addTo(map);
        }

      }

    }

    mapInitLocation(getMapSettingsPP());

  })()
});
