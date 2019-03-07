
document.addEventListener("DOMContentLoaded", function(e) {
  (function(){

    // Extract Map Settings from HTML
    var getMapSettingsPP = function(){
      let mapDiv=(document.querySelector('.map-pp') || false);
      let mapSettings={};
      if(mapDiv){
        mapSettings.zoom = Number(mapDiv.getAttribute('data-zoom') || 16 );
        mapSettings.coords = ( mapDiv.getAttribute('data-coords') || false );
        mapSettings.mapType = ( mapDiv.getAttribute('data-map-type') || "carto_light" );
        mapSettings.maki = ( mapDiv.getAttribute('data-maki')!==0 );
        mapSettings.makiColor = ( mapDiv.getAttribute('data-maki-color') || '#000000' );
        mapSettings.mbKey = ( mapDiv.getAttribute('data-mb-key') || false );
        if(mapSettings.coords){
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
