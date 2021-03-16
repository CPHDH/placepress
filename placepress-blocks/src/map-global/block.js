import "./style.scss";
import "./editor.scss";

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { TextareaControl, PanelBody, Button } = wp.components;
import { InspectorControls } from "@wordpress/block-editor";

registerBlockType("placepress/block-map-global", {
	title: __("Global Map"),
	icon: "location-alt",
	category: "placepress",
	keywords: [__("Map"), __("Global"), __("PlacePress")],
	supports: {
		anchor: true,
		html: false,
		multiple: false,
		reusable: false,
		align: true,
		align: ["center", "wide", "full"],
	},
	transforms: {
		from: [
			{
				type: "block",
				blocks: ["placepress/block-map-global-type"],
				transform: (attributes) => {
					return wp.blocks.createBlock(
						"placepress/block-map-global",
						attributes
					);
				},
			},
		],
	},
	description: __("A block for adding the global map."),
	attributes: {
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
				caption,
				zoom,
				lat,
				lon,
				mb_key,
				maki,
				maki_color,
				basemap,
			},
			setAttributes,
		} = props;

		const notices = wp.data.dispatch("core/notices");

		const onBlockLoad = function () {
			globalMapPP();
		};

		const onChangeCaption = (caption) => {
			setAttributes({ caption });
		};

		const globalMapPP = function () {
			const tileSets = window.getMapTileSets();
			const currentTileSet = tileSets[basemap];
			const markers = [];
			const map = L.map("placepress-map", {
				layers: currentTileSet,
				scrollWheelZoom: false,
			}).setView([lat, lon], zoom);

			// user actions: LAYERS
			const layerNames = {
				"Street (Carto Voyager)": tileSets.carto_voyager,
				"Street (Carto Light)": tileSets.carto_light,
				"Terrain (Stamen)": tileSets.stamen_terrain,
				"Satellite (ESRI)": tileSets.esri_world,
			};
			L.control.layers(layerNames).addTo(map);
			map.on("baselayerchange ", function (e) {
				const key = e.layer.options.placepress_key;
				if (key) {
					props.setAttributes({ basemap: key });
				}
			});

			// API request
			const locations_json =
				defaults.site_url + "?feed=placepress_locations_public";
			const request = new XMLHttpRequest();
			request.open("GET", locations_json, true);
			request.onload = function () {
				if (request.status >= 200 && request.status < 400) {
					const data = JSON.parse(this.response);
					if (typeof data !== "undefined" && data.length > 0) {
						notices.removeNotice("placepress-no-result");
						notices.removeNotice("placepress-no-response");

						data.forEach(function (post) {
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
								markers.push(marker);

								// vertical center on popup open
								map.on("popupopen", function (e) {
									const px = map.project(e.popup._latlng);
									px.y -= e.popup._container.clientHeight / 2;
									map.panTo(map.unproject(px), { animate: true });
								});
							}
						});
						if (markers.length) {
							if (typeof L.markerClusterGroup === "function") {
								const clusterGroup = L.markerClusterGroup();
								clusterGroup.addLayers(markers).addTo(map);
								map.fitBounds(clusterGroup.getBounds(), { padding: [50, 50] });
							} else {
								const markersGroup = L.featureGroup(markers).addTo(map);
								map.fitBounds(markersGroup.getBounds(), { padding: [50, 50] });
							}
						}
					} else {
						notices.createWarningNotice(
							__(
								"PlacePress: Your request did not return any public Locations. Please ensure that you have published Location posts that use the PlacePress Location Map block.",
								"wp_placepress"
							),
							{ id: "placepress-no-result" }
						);
					}
				} else {
					notices.createErrorNotice(
						__(
							"PlacePress: There was an error fetching Location posts using the WordPress REST API. Please check your network connection and try again.",
							"wp_placepress"
						),
						{ id: "placepress-no-response" }
					);
				}
			};
			request.send();
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
						data-type="global"
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
				<InspectorControls>
					<PanelBody title={__("PlacePress Help")} initialOpen={false}>
						<div>
							<Button
								href="https://wpplacepress.org/about/getting-started/"
								target="_blank"
								icon="external"
							>
								{__("User Guide")}&nbsp;
							</Button>
						</div>

						<div>
							<Button
								href={
									defaults.site_url +
									"/wp-admin/options-general.php?page=placepress"
								}
								target="_blank"
								icon="admin-settings"
							>
								{__("Plugin Settings")}&nbsp;
							</Button>
						</div>

						<div>
							<Button
								href="https://wordpress.org/support/plugin/placepress/"
								target="_blank"
								icon="feedback"
							>
								{__("Feedback/Support")}&nbsp;
							</Button>
						</div>
					</PanelBody>
				</InspectorControls>
			</div>
		);
	},
	save(props) {
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
						data-type="global"
					/>
					<figcaption className="map-caption-pp">
						{attributes.caption}
					</figcaption>
				</figure>
			</div>
		);
	},
});
