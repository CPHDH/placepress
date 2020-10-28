document.addEventListener("DOMContentLoaded", function (e) {
  // TILES
  const tileSets = window.getMapTileSets();
  const defaults =
    typeof placepress_plugin_options !== "undefined"
      ? placepress_plugin_options
      : null;

  // SETTINGS
  const settings = [];
  settings.lat =
    document.querySelector("input#placepress_options_default_latitude").value
      .length > 0
      ? document.querySelector("input#placepress_options_default_latitude")
          .value
      : defaults.default_latitude;

  settings.lon =
    document.querySelector("input#placepress_options_default_longitude").value
      .length > 0
      ? document.querySelector("input#placepress_options_default_longitude")
          .value
      : defaults.default_longitude;

  settings.basemap = document.querySelector(
    "select#placepress_options_default_map_type"
  )
    ? document.querySelector("select#placepress_options_default_map_type").value
    : defaults.default_map_type;

  settings.zoom = document.querySelector(
    "input#placepress_options_default_zoom"
  )
    ? document.querySelector("input#placepress_options_default_zoom").value
    : defaults.default_zoom;

  // HIDE FORM FIELDS
  let map_replace = document.querySelectorAll(".map_replace");
  map_replace.forEach((el, i) => {
    el.setAttribute("hidden", "true");
  });

  // INTERACTIVE MAP UI
  map = L.map("map_ui_container", {
    scrollWheelZoom: false,
    layers: tileSets[settings.basemap],
  }).setView([settings.lat, settings.lon], settings.zoom);

  const marker = L.marker([settings.lat, settings.lon], {
    draggable: "true",
  }).addTo(map);

  // user actions: CLICK
  marker.on("click", function (e) {
    const ll = e.target.getLatLng();
    const popup = L.popup().setContent(ll.lat + "," + ll.lng);
    e.target.unbindPopup().bindPopup(popup).openPopup();
    map.panTo(e.target.getLatLng());
  });

  // user actions: DRAG
  marker.on("dragend", function (e) {
    const ll = e.target.getLatLng();
    document.querySelector("input#placepress_options_default_latitude").value =
      ll.lat;
    document.querySelector("input#placepress_options_default_longitude").value =
      ll.lng;

    map.setView([ll.lat, ll.lng], ll.zoom, { animation: true });
  });

  // user actions: ZOOM
  map.on("zoomend", function (e) {
    const z = map.getZoom();
    document.querySelector("input#placepress_options_default_zoom").value = z;
  });

  // user actions: SEARCH
  L.Control.Geocode = L.Control.extend({
    onAdd: function (map) {
      const container = L.DomUtil.create("div", "map-search-pp");
      const form = L.DomUtil.create("form", "editor-form", container);
      const input = L.DomUtil.create("input", "editor-input", form);
      input.style.width = "100%";
      input.style.border = "1px solid #ccc";
      input.style.padding = "7px";
      input.style.borderRadius = "3px";
      input.placeholder = "Enter a query and press Return/Enter ⏎";
      form.style.width = "100%";

      L.DomEvent.addListener(
        form,
        "submit",
        L.DomEvent.preventDefault
      ).addListener(form, "submit", function (e) {
        const q = e.target[0].value;
        if (q) {
          const request = new XMLHttpRequest();
          request.open(
            "GET",
            "https://nominatim.openstreetmap.org/search?format=json&limit=1&q=" +
              q,
            true
          );
          request.onload = function () {
            if (request.status >= 200 && request.status < 400) {
              const data = JSON.parse(this.response);
              const result = data[0];
              if (typeof result !== "undefined" && result.lat && result.lon) {
                document.querySelector(
                  "input#placepress_options_default_latitude"
                ).value = result.lat;
                document.querySelector(
                  "input#placepress_options_default_longitude"
                ).value = result.lon;
                // pan map
                map.panTo([result.lat, result.lon]);
                // update marker location in UI
                marker.setLatLng([result.lat, result.lon]);
              } else {
                alert(
                  "PlacePress: Your search query did not return any results. Please try again."
                );
              }
            } else {
              alert(
                "PlacePress: There was an error communicating with the Nominatim server. Please check your network connection and try again."
              );
            }
          };
          request.send();
        }
      });

      return form;
    },

    onRemove: function (map) {
      // Nothing to do here
    },
  });
  L.control.geocode = function (opts) {
    return new L.Control.Geocode(opts);
  };
  L.control.geocode({ position: "topright" }).addTo(map);

  // user actions: LAYERS
  const layerNames = {
    "Street (Carto Voyager)": tileSets.carto_voyager,
    "Street (Carto Light)": tileSets.carto_light,
    "Terrain (Stamen)": tileSets.stamen_terrain,
    "Satellite (ESRI)": tileSets.esri_world,
  };
  const layerControls = L.control.layers(layerNames).addTo(map);
  map.on("baselayerchange ", function (e) {
    const key = e.layer.options.placepress_key;
    if (key) {
      document.querySelector(
        "select#placepress_options_default_map_type"
      ).value = key;
    }
  });
  // Help buttons
  function simulateEvent(el, etype) {
    if (el.fireEvent) {
      el.fireEvent("on" + etype);
    } else {
      var evObj = document.createEvent("Events");
      evObj.initEvent(etype, true, false);
      el.dispatchEvent(evObj);
    }
  }
  const placepress_help = document.querySelectorAll(
    "span.placepress.dashicons-editor-help"
  );
  for (const icon of placepress_help) {
    icon.addEventListener("click", function (event) {
      if (window.jQuery) {
        // smooth scroll if available
        jQuery("html, body").animate({ scrollTop: 0 }, 300);
      } else {
        window.scrollTo(0, 0);
      }
      const wp_help = document.getElementById("contextual-help-link");
      // only open help menu if it's not already open
      if (!wp_help.classList.contains("screen-meta-active")) {
        setTimeout(
          () => {
            simulateEvent(wp_help, "click");
          },
          window.jQuery ? 300 : 0
        );
      }
    });
  }
});
