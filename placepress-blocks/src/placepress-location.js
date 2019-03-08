
document.addEventListener("DOMContentLoaded", function(e) {
  (function(){

    // Extract Map Settings from HTML
    var getMapSettingsPP = function(){
      let mapDiv=(document.querySelector('.map-pp') || false);
      let mapSettings={};
      if(mapDiv){
        mapSettings.zoom = Number(mapDiv.getAttribute('data-zoom') || 16 );
        mapSettings.mapType = ( mapDiv.getAttribute('data-map-type') || "carto_light" );
        mapSettings.lat = ( mapDiv.getAttribute('data-lat') || false );
        mapSettings.lon = ( mapDiv.getAttribute('data-lon') || false );
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

  })()
});
