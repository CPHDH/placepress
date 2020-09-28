import "./style.scss";
import "./editor.scss";

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { PlainText, InspectorControls, PanelBody } = wp.editor;
const { TextareaControl, TextControl } = wp.components;

registerBlockType("placepress/block-map-location", {
	title: __("Location Map"),
	icon: "location",
	category: "placepress",
	keywords: [__("Map"), __("Location"), __("PlacePress")],
	supports: {
		anchor: true,
		html: false,
		multiple: false,
		reusable: false,
		align: true,
		align: ["left", "center", "right", "wide", "full"],
	},
	description: __("A block for adding a location map."),
	attributes: {
		api_coordinates_pp: {
			type: "string",
			source: "meta",
			meta: "api_coordinates_pp",
		},
		caption: {
			type: "string",
			source: "text",
			selector: ".map-caption-pp",
		},
		lat: {
			type: "string",
			selector: "div.map-pp",
			source: "attribute",
			attribute: "data-lat",
		},
		lon: {
			type: "string",
			selector: "div.map-pp",
			source: "attribute",
			attribute: "data-lon",
		},
		zoom: {
			type: "string",
			selector: "div.map-pp",
			source: "attribute",
			attribute: "data-zoom",
		},
		mb_key: {
			type: "string",
			selector: "div.map-pp",
			source: "attribute",
			attribute: "data-mb-key",
		},
		maki: {
			type: "string",
			selector: "div.map-pp",
			source: "attribute",
			attribute: "data-zoom",
		},
		maki_color: {
			type: "string",
			selector: "div.map-pp",
			source: "attribute",
			attribute: "data-maki-color",
		},
		basemap: {
			type: "string",
			selector: "div.map-pp",
			source: "attribute",
			attribute: "data-basemap",
		},
	},
	edit(props) {
		const {
			attributes: {
				api_coordinates_pp,
				caption,
				zoom,
				lat,
				lon,
				mb_key,
				maki,
				maki_color,
				basemap,
			},
			className,
			setAttributes,
		} = props;

		const notices = wp.data.dispatch("core/notices");

		const onChangeCaption = (caption) => {
			setAttributes({ caption });
		};

		const onBlockLoad = function (e) {
			uiLocationMapPP();
		};

		// Init location map user interface
		const uiLocationMapPP = function () {
			const tileSets = window.getMapTileSets();
			const currentTileSet = tileSets[basemap];

			const map = L.map("placepress-map", {
				layers: currentTileSet,
				scrollWheelZoom: false,
			}).setView([lat, lon], zoom);

			const marker = L.marker([lat, lon], {
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
				props.setAttributes({ lat: ll.lat });
				props.setAttributes({ lon: ll.lng });
				props.setAttributes({
					api_coordinates_pp: ll.lat + "," + ll.lng,
				});

				map.setView([ll.lat, ll.lng], ll.zoom, { animation: true });
			});

			// user actions: ZOOM
			map.on("zoomend", function (e) {
				const z = map.getZoom();
				props.setAttributes({ zoom: z });
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
					input.placeholder = __(
						"Enter a query and press Return/Enter ⏎",
						"wp_placepress"
					);
					form.style.width = "100%";

					L.DomEvent.addListener(
						form,
						"submit",
						L.DomEvent.preventDefault
					).addListener(form, "submit", function (e) {
						const q = e.target[0].value;
						if (q) {
							notices.removeNotice("placepress-no-result");
							notices.removeNotice("placepress-no-response");
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
									if (
										typeof result !== "undefined" &&
										result.lat &&
										result.lon
									) {
										// update attributes
										props.setAttributes({ lat: result.lat });
										props.setAttributes({ lon: result.lon });
										props.setAttributes({
											api_coordinates_pp: result.lat + "," + result.lon,
										});

										// pan map
										map.panTo([result.lat, result.lon]);
										// update marker location in UI
										marker.setLatLng([result.lat, result.lon]);
									} else {
										notices.createWarningNotice(
											__(
												"PlacePress: Your search query did not return any results. Please try again.",
												"wp_placepress"
											),
											{ id: "placepress-no-result" }
										);
									}
								} else {
									notices.createErrorNotice(
										__(
											"PlacePress: There was an error communicating with the Nominatim server. Please check your network connection and try again.",
											"wp_placepress"
										),
										{ id: "placepress-no-response" }
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
					props.setAttributes({ basemap: key });
				}
			});
		};

		// set attributes
		const defaults = placepress_plugin_settings.placepress_defaults;
		if (!zoom) {
			props.setAttributes({ zoom: defaults.default_zoom });
		}
		if (!lat) {
			props.setAttributes({ lat: defaults.default_latitude });
		}
		if (!lon) {
			props.setAttributes({ lon: defaults.default_longitude });
		}
		if (!mb_key) {
			props.setAttributes({ mb_key: defaults.mapbox_key });
		}
		if (!maki) {
			props.setAttributes({ maki: defaults.maki_markers });
		}
		if (!maki_color) {
			props.setAttributes({ maki_color: defaults.maki_markers_color });
		}
		if (!basemap) {
			props.setAttributes({ basemap: defaults.default_map_type });
		}

		return (
			<div
				className={props.className}
				aria-label={__("Interactive Map", "wp_placepress")}
				role="region"
			>
				<figure>
					<div
						className="map-pp"
						id="placepress-map"
						data-lat={lat}
						data-lon={lon}
						data-zoom={zoom}
						data-mb-key={mb_key}
						data-maki={maki}
						data-maki-color={maki_color}
						data-basemap={basemap}
						data-type="single-location"
					/>
					<TextareaControl
						rows="2"
						className="map-caption-pp"
						tagName="figcaption"
						placeholder={__(
							"Type a caption for the map (optional).",
							"wp_placepress"
						)}
						value={caption}
						onChange={onChangeCaption}
					/>
				</figure>
				<img // @TODO: find a replacement for this hack to fire the map script when block is added
					className="onload-hack-pp"
					height="0"
					width="0"
					onLoad={onBlockLoad}
					src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1' %3E%3Cpath d=''/%3E%3C/svg%3E"
				/>
			</div>
		);
	},
	save(props) {
		const className = getBlockDefaultClassName("placepress/block-map-location");
		const { attributes } = props;

		return (
			<div
				className={props.className}
				aria-label={__("Interactive Map")}
				role="region"
			>
				<figure>
					<div
						className="map-pp"
						id="placepress-map"
						data-lat={attributes.lat}
						data-lon={attributes.lon}
						data-zoom={attributes.zoom}
						data-mb-key={attributes.mapbox_key}
						data-maki={attributes.maki_markers}
						data-maki-color={attributes.maki_markers_color}
						data-basemap={attributes.basemap}
						data-type="single-location"
					/>
					<figcaption className="map-caption-pp">
						{attributes.caption}
					</figcaption>
				</figure>
			</div>
		);
	},
});
