document.addEventListener("DOMContentLoaded", function (e) {
	(function () {
		// Is Archive Map
		const isPPArchive = function () {
			const archive_map =
				document.querySelector("#placepress-map_archive") || false;
			return archive_map;
		};

		// Extract Location Map Settings from HTML
		const getDataAttributesPPLocation = function () {
			const locations = document.querySelectorAll(".map-pp") || false;
			const settings = [];
			if (locations) {
				locations.forEach((location, i) => {
					s = {};
					s.mapId = location.getAttribute("id");
					if (s.mapId) {
						let newId = s.mapId + "_" + i;
						location.setAttribute("id", newId);
						s.mapId = newId;
					}
					s.type = location.getAttribute("data-type");
					s.zoom = Number(location.getAttribute("data-zoom"));
					s.lat = Number(location.getAttribute("data-lat"));
					s.lon = Number(location.getAttribute("data-lon"));
					s.style = location.getAttribute("data-basemap");
					s.maki = location.getAttribute("data-maki");
					s.makiColor = location.getAttribute("data-maki-color");
					s.mbKey = location.getAttribute("data-mb-key");
					if (s.lat && s.lon) {
						settings[i] = s;
					}
				});
			}
			return settings.length ? settings : false;
		};

		// Extract Tour Map Settings from HTML
		const getDataAttributesPPTour = function () {
			const tour_stops =
				document.querySelectorAll(".pp-tour-stop-section-header-container") ||
				false;
			const settings = [];
			if (tour_stops) {
				tour_stops.forEach((tour_stop, i) => {
					const s = {};
					s.zoom = Number(tour_stop.getAttribute("data-zoom"));
					s.lat = Number(tour_stop.getAttribute("data-lat"));
					s.lon = Number(tour_stop.getAttribute("data-lon"));
					s.style = tour_stop.getAttribute("data-basemap");
					s.maki = tour_stop.getAttribute("data-maki");
					s.makiColor = tour_stop.getAttribute("data-maki-color");
					s.mbKey = tour_stop.getAttribute("data-mb-key");
					s.postId = tour_stop.getAttribute("data-post-id");
					s.background = tour_stop.getAttribute("data-background");
					if (tour_stop.querySelector(".pp-tour-stop-title").hasChildNodes()) {
						s.title = tour_stop.querySelector(
							".pp-tour-stop-title"
						).children[0].innerText;
					}
					if (s.lat && s.lon) {
						settings[i] = s;
						tour_stop.setAttribute("id", "pp_" + i);
					}
				});
			}
			return settings.length ? settings : false;
		};

		// Element is in Viewport
		const isInViewport = function (elem) {
			var bounding = elem.getBoundingClientRect();
			return (
				bounding.top >= 0 &&
				bounding.left >= 0 &&
				bounding.bottom <=
					(window.innerHeight || document.documentElement.clientHeight) &&
				bounding.right <=
					(window.innerWidth || document.documentElement.clientWidth)
			);
		};

		const getArchiveLocationType = () => {
			let path = window.location.pathname.split("/");
			return path[2] || false; // example.com/location-types/cities => returns cities
		};

		// API XMLHttpRequest
		const addGlobalMarkersViaAPI = function (
			map,
			markersLayer,
			isArchive = false
		) {
			const location_type = getArchiveLocationType();
			const locations_json =
				location.protocol +
				"//" +
				location.hostname +
				"?feed=placepress_locations_public";
			const request = new XMLHttpRequest();
			request.open("GET", locations_json, true);
			request.onload = function () {
				if (request.status >= 200 && request.status < 400) {
					let data = JSON.parse(this.response);
					if (typeof data !== "undefined") {
						data
							.filter((data) =>
								isArchive && location_type
									? data.type.includes(location_type)
									: data
							)
							.forEach(function (post) {
								const coords = post.api_coordinates_pp.split(",");
								if (coords.length == 2) {
									const marker = L.marker(coords, {
										id: post.id,
										title: post.title,
										permalink: post.permalink,
										coords: coords,
										thumbnail: post.thumbnail,
									});
									// user actions: CLICK
									marker.on("click", function (e) {
										const popup = L.popup().setContent(
											'<a href="' +
												e.target.options.permalink +
												'" class="map-thumb" style="background-image:linear-gradient(to bottom,rgba(0,0,0,0) 50%,rgba(0,0,0,0.7) 70%,rgba(0,0,0,1) 100%),url(' +
												e.target.options.thumbnail +
												')">' +
												'<span class="map-title" href="' +
												e.target.options.permalink +
												'">' +
												e.target.options.title +
												"</span>" +
												"</a>"
										);
										e.target.unbindPopup().bindPopup(popup).openPopup();
									});
									markersLayer.push(marker);

									// vertical center on popup open
									map.on("popupopen", function (e) {
										const px = map.project(e.popup._latlng);
										px.y -= e.popup._container.clientHeight / 2;
										map.panTo(map.unproject(px), { animate: true });
									});
								}
							});
						if (typeof L.markerClusterGroup === "function") {
							const clusterGroup = L.markerClusterGroup();
							clusterGroup.addLayers(markersLayer).addTo(map);
							map.fitBounds(clusterGroup.getBounds(), { padding: [50, 50] });
						} else {
							const markersGroup = L.featureGroup(markersLayer).addTo(map);
							map.fitBounds(markersGroup.getBounds(), { padding: [50, 50] });
						}
					} else {
						console.warn(
							"PlacePress: Your request did not return any Locations. Please ensure that you have Location posts that use the PlacePress Location Map block."
						);
					}
				} else {
					console.warn(
						"PlacePress: There was an error fetching Location posts using the WordPRess REST API. Please check your network connection and try again."
					);
				}
			};
			request.send();
		};

		// Fit Bounds Control
		const fitBoundsControls = function (map, bounds) {
			const fitBoundsControl = L.control({ position: "bottomleft" });
			fitBoundsControl.onAdd = function (map) {
				const div = L.DomUtil.create(
					"div",
					"leaflet-control leaflet-control-bounds"
				);
				const btn = L.DomUtil.create("a", "placepress-bounds", div);
				btn.title = "Fit All Markers";
				const icn =
					'<svg id="bounds" height="35px" width="35px" viewBox="0 0 1024 1024"  xmlns="http://www.w3.org/2000/svg"><path height="35" width="35" d="M396.795 396.8H320V448h128V320h-51.205zM396.8 115.205V192H448V64H320v51.205zM115.205 115.2H192V64H64v128h51.205zM115.2 396.795V320H64v128h128v-51.205z"/></svg>';
				btn.innerHTML = icn;

				L.DomEvent.addListener(
					btn,
					"click",
					L.DomEvent.preventDefault
				).addListener(btn, "click", function (e) {
					map.fitBounds(bounds);
				});
				return div;
			};
			fitBoundsControl.addTo(map);
		};

		// Geolocation Controls
		const geolocationControls = function (map) {
			const geolocationControl = L.control({ position: "bottomleft" });
			geolocationControl.onAdd = function (map) {
				const div = L.DomUtil.create(
					"div",
					"leaflet-control leaflet-control-geolocation"
				);
				const btn = L.DomUtil.create("a", "placepress-geolocation", div);
				btn.title = "Geolocation";
				const icn =
					'<svg id="geolocation" height="35px" width="35px" viewBox="0 0 1024 1024"  xmlns="http://www.w3.org/2000/svg"><path id="inner" d="m512.001 302.46c-115.762 0-209.541 93.808-209.541 209.541 0 115.761 93.779 209.541 209.541 209.541 115.819 0 209.538-93.779 209.538-209.541 0-115.733-93.719-209.541-209.538-209.541z"/><path id="outer" d="m838.411 482.066c-14.439-157.447-138.854-281.92-296.476-296.274v-122.806h-59.869v122.807c-157.622 14.353-282.036 138.826-296.478 296.273h-122.602v59.869h122.602c14.442 157.389 138.856 281.861 296.479 296.302v122.777h59.869v-122.777c157.621-14.44 282.036-138.913 296.476-296.302h122.603v-59.869zm-326.41 299.341c-148.736 0-269.409-120.671-269.409-269.407 0-148.766 120.673-269.409 269.409-269.409 148.792 0 269.406 120.644 269.406 269.409 0 148.737-120.614 269.407-269.406 269.407z"/></svg>';

				btn.innerHTML = icn;
				let userMarker;

				L.DomEvent.addListener(
					btn,
					"click",
					L.DomEvent.preventDefault
				).addListener(btn, "click", function (e) {
					navigator.geolocation.getCurrentPosition(function (pos) {
						const userLocation = [pos.coords.latitude, pos.coords.longitude];
						// add/update user location indicator
						if (typeof userMarker === "undefined") {
							userMarker = new L.circleMarker(userLocation, {
								title: "Geolocation",
								radius: 8,
								fillColor: "#4a87ee",
								color: "#ffffff",
								weight: 3,
								opacity: 1,
								fillOpacity: 0.8,
							}).addTo(map);
						} else {
							userMarker.setLatLng(userLocation);
						}

						userMarker.on("click", function (e) {
							map.panTo(e.target.getLatLng());
						});

						const mapBounds = map.getBounds();
						const newBounds = new L.LatLngBounds(
							mapBounds,
							new L.LatLng(pos.coords.latitude, pos.coords.longitude)
						);
						map.fitBounds(newBounds);
					});
				});
				return div;
			};
			geolocationControl.addTo(map);
		};

		// Standalone Marker (not for Global Map)
		const addSingleMarker = (settings, map, isTour = false) => {
			const marker = L.marker([settings.lat, settings.lon]).addTo(map);
			marker.on("click", function (e) {
				let title =
					settings.title && isTour
						? '<div class="pp-title">' + settings.title + "</div>"
						: "";
				const popup = L.popup().setContent(
					'<div class="pp-container ' +
						(isTour ? "tour" : "") +
						'" style="background-image:linear-gradient(to bottom,rgba(0,0,0,0),rgba(256,256,256,.65) 30%,rgba(256,256,256,1) 50%),url(' +
						(settings.background || "") +
						')">' +
						title +
						'<a class="pp-directions-button" target="_blank" rel="noopener" href="http://maps.google.com/maps?daddr=' +
						settings.lat +
						"," +
						settings.lon +
						'">Get Directions</a>' +
						'<div class="pp-coords-caption">' +
						settings.lat +
						"," +
						settings.lon +
						"</div>" +
						"</div>"
				);
				e.target.unbindPopup().bindPopup(popup).openPopup();
			});
			// vertical center on popup open
			map.on("popupopen", function (e) {
				const px = map.project(e.popup._latlng);
				px.y -= e.popup._container.clientHeight / 2;
				map.panTo(map.unproject(px), { animate: true });
			});
		};

		// Adds controls: geolocation and layers
		const addAdditionalControls = (tileSets, map, bounds = null) => {
			// layer controls
			const layerNames = {
				"Street (Carto Voyager)": tileSets.carto_voyager,
				"Street (Carto Light)": tileSets.carto_light,
				"Terrain (Stamen)": tileSets.stamen_terrain,
				"Satellite (ESRI)": tileSets.esri_world,
			};
			L.control.layers(layerNames).addTo(map);
			// geolocation controls
			const isSecure = window.location.protocol == "https:" ? true : false;
			if (isSecure && navigator.geolocation) {
				geolocationControls(map);
			}
			// fit bounds controls
			if (bounds) {
				fitBoundsControls(map, bounds);
			}
		};

		// FLOATING TOUR MAP
		const updateFloatingMapPP = (
			settings,
			current,
			tileSets,
			fitBounds = false
		) => {
			var bounds = new L.LatLngBounds();

			map = L.map("floating-tour-map-pp", {
				scrollWheelZoom: false,
				layers: tileSets[settings[current].style],
			}).setView(
				[settings[current].lat, settings[current].lon],
				settings[current].zoom
			);
			settings.forEach((marker) => {
				addSingleMarker(marker, map, true);
				bounds.extend([marker.lat, marker.lon]);
			});

			if (fitBounds) {
				map.fitBounds(bounds);
			}

			addAdditionalControls(tileSets, map, bounds);

			return map;
		};

		const displayFloatingMapPP = (settings) => {
			let initial = true; // default view is fit bounds
			let current = 0;
			let inview = 0;
			let map = null;
			const tileSets = window.getMapTileSets();

			const floater = document.createElement("div");
			floater.setAttribute("id", "floating-tour-map-pp");

			const openFloatingMapPP = new Event("openFloatingMapPP");
			floater.addEventListener("openFloatingMapPP", (e) => {
				e.target.setAttribute("class", "enhance");
				map.remove();
				setTimeout(() => {
					map = updateFloatingMapPP(settings, current, tileSets, initial);
				}, 501);
			});

			const closeFloatingMapPP = new Event("closeFloatingMapPP");
			floater.addEventListener("closeFloatingMapPP", (e) => {
				e.target.removeAttribute("class", "enhance");
				map.remove();
				setTimeout(() => {
					map = updateFloatingMapPP(settings, current, tileSets, initial);
				}, 501);
			});

			floater.onclick = () => {
				if (!floater.classList.contains("enhance")) {
					floater.dispatchEvent(openFloatingMapPP);
				}
			};

			const close = document.createElement("div");
			close.setAttribute("id", "close-floating-tour-map-pp");
			close.innerHTML = "Close Map";
			close.onclick = () => {
				if (floater.classList.contains("enhance")) {
					floater.dispatchEvent(closeFloatingMapPP);
				}
			};

			const backdrop = document.createElement("div");
			backdrop.setAttribute("id", "backdrop-floating-tour-map-pp");
			backdrop.onclick = () => {
				if (floater.classList.contains("enhance")) {
					floater.dispatchEvent(closeFloatingMapPP);
				}
			};

			document.onkeydown = function (e) {
				if (floater.classList.contains("enhance")) {
					if ("key" in e && (e.key === "Escape" || e.key === "Esc")) {
						floater.dispatchEvent(closeFloatingMapPP);
					}
				}
			};

			document.querySelector("body").append(floater, close, backdrop);

			const map_icons = document.querySelectorAll(
				".pp-marker-icon-center.has-map"
			);
			map_icons.forEach((icon, i) => {
				icon.onclick = () => {
					current = i;
					if (!floater.classList.contains("enhance")) {
						floater.dispatchEvent(openFloatingMapPP);
					}
					floater.focus();
				};
			});

			map = updateFloatingMapPP(settings, current, tileSets, true);

			const stops = document.querySelectorAll(
				".pp-tour-stop-section-header-container"
			);

			window.addEventListener(
				"scroll",
				function (event) {
					stops.forEach((stop) => {
						if (isInViewport(stop)) {
							inview = Number(stop.getAttribute("id").replace("pp_", ""));
							if (initial == true || inview !== current) {
								map.invalidateSize();
								map.setView(
									[settings[inview].lat, settings[inview].lon],
									settings[inview].zoom
								);
								if (settings[current].style !== settings[inview].style) {
									map.removeLayer(tileSets[settings[current].style]);
									map.addLayer(tileSets[settings[inview].style]);
								}
								initial = false;
								current = inview;
							}
						}
					});
				},
				false
			);
		};

		// SINGLE LOCATION MAP
		const displayLocationMapPP = function (settings) {
			const tileSets = window.getMapTileSets();
			const basemap = tileSets[settings.style];

			if (settings) {
				const map = L.map(settings.mapId, {
					scrollWheelZoom: false,
					layers: basemap,
				}).setView([settings.lat, settings.lon], settings.zoom);

				// enable scrollwheel zoom if user interacts with the map
				map.once("focus", function () {
					map.scrollWheelZoom.enable();
				});

				addSingleMarker(settings, map);

				addAdditionalControls(tileSets, map);
			}
		};

		// GLOBAL LOCATIONS MAP
		const displayGlobalMapPP = function (settings, isArchive = false) {
			const tileSets = window.getMapTileSets();
			const currentTileSet = tileSets[settings.style];
			const markersLayer = [];
			const map = L.map(settings.mapId, {
				layers: currentTileSet,
				scrollWheelZoom: false,
			}).setView([settings.lat, settings.lon], settings.zoom);

			// enable scrollwheel zoom if user interacts with the map
			map.once("focus", function () {
				map.scrollWheelZoom.enable();
			});

			// controls
			addAdditionalControls(tileSets, map);

			// add location markers
			addGlobalMarkersViaAPI(map, markersLayer, isArchive);
		};

		// MAIN
		if (typeof wp.editor === "undefined") {
			const page = document.querySelector("body").classList;
			if ((settings = getDataAttributesPPLocation())) {
				// LOCATIONS
				settings.forEach((s, i) => {
					switch (s.type) {
						case "single-location":
							displayLocationMapPP(s);
							break;
						case "global":
							displayGlobalMapPP(s);
							break;
						case "archive":
							displayGlobalMapPP(s, true);
							break;
					}
				});
			} else if ((settings = getDataAttributesPPTour())) {
				// TOURS
				if (page.length && page.contains("single") && settings.length) {
					// single tour
					setTimeout(() => {
						displayFloatingMapPP(settings);
					}, 1000);
				} else if (page.length && page.contains("archive") && settings.length) {
					// tours archive
					let map_icons = document.querySelectorAll(
						".pp-marker-icon-center.has-map"
					);
					map_icons.forEach((icon) => {
						let b = icon.parentElement;
						let pid = b.getAttribute("data-post-id");
						let url = "?page_id=" + pid;
						icon.onclick = () => {
							console.log(url);
							window.location = url;
						};
					});
				} else {
					if (settings.length) {
						console.warn(
							"Unknown tour page. PlacePress scripts will not load.",
							"\n\nPlease ensure that your body tag includes default classes, including 'single' (for individual tour pages) and 'archive' (for the tours post type archive).",
							"\n\nSee: https://developer.wordpress.org/reference/functions/body_class/"
						);
					}
				}
			}
		}
	})();
});
