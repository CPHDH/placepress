import "./style.scss";
import "./editor.scss";

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
	Button,
	Dashicon,
	Flex,
	FlexItem,
	TextareaControl,
	Modal,
	IsolatedEventContainer,
} = wp.components;
const { MediaUpload, MediaUploadCheck, InnerBlocks } = wp.blockEditor;
const { useState } = wp.element;

registerBlockType("placepress/block-tour-stop", {
	title: __("Tour Stop"),
	icon: "location",
	category: "placepress",
	keywords: [__("Map"), __("Tour"), __("PlacePress"), __("Stop")],
	supports: {
		anchor: true,
		html: false,
		multiple: true,
		reusable: false,
		align: true,
		align: ["wide", "full"],
	},
	description: __("A block for adding a tour stop section header."),
	attributes: {
		background: {
			default: "",
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-background",
		},
		lat: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-lat",
		},
		lon: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-lon",
		},
		zoom: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-zoom",
		},
		basemap: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-basemap",
		},
		caption: {
			type: "string",
			source: "text",
			selector: ".tour-stop-caption-pp",
		},
	},
	edit(props) {
		const {
			attributes: { background, zoom, lat, lon, basemap, caption },
			className,
		} = props;

		const notices = wp.data.dispatch("core/notices");

		const mapdefaults = placepress_plugin_settings.placepress_defaults;
		const userMapConfig = {
			lat: lat ? lat : mapdefaults.default_latitude,
			lon: lon ? lon : mapdefaults.default_longitude,
			zoom: zoom ? zoom : mapdefaults.default_zoom,
			basemap: basemap ? basemap : mapdefaults.default_map_type,
		};

		const HEADING = [
			[
				"core/heading",
				{
					level: 2,
					placeholder: __("Enter a title for this stop", "wp_placepress"),
				},
			],
		];

		const PPMapUI = () => {
			const tileSets = window.getMapTileSets();
			const currentTileSet = tileSets[userMapConfig.basemap];

			const uiLocationMapPP = function () {
				const map = L.map("placepress-tour-map", {
					layers: currentTileSet,
					scrollWheelZoom: false,
				}).setView([userMapConfig.lat, userMapConfig.lon], userMapConfig.zoom);

				const marker = L.marker([userMapConfig.lat, userMapConfig.lon], {
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
					// pending save
					userMapConfig.lat = ll.lat;
					userMapConfig.lon = ll.lng;

					map.setView([ll.lat, ll.lng], map.getZoom(), { animation: true });
				});

				// user actions: ZOOM
				map.on("zoomend", function (e) {
					const z = e.target.getZoom();
					// pending save
					userMapConfig.zoom = z;
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
											// pending save
											userMapConfig.lat = result.lat;
											userMapConfig.lon = result.lon;

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
					console.log(key);
					if (key) {
						// pending save
						userMapConfig.basemap = key;
					}
				});
			};

			const onBlockLoad = function (e) {
				uiLocationMapPP();
			};

			return (
				<IsolatedEventContainer>
					<figure>
						<div className="map-pp" id="placepress-tour-map"></div>
					</figure>
					<img // @TODO: find a replacement for this hack to fire the map script when block is added
						className="onload-hack-pp"
						height="0"
						width="0"
						onLoad={onBlockLoad}
						src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1' %3E%3Cpath d=''/%3E%3C/svg%3E"
					/>
				</IsolatedEventContainer>
			);
		};

		const CoordsModal = () => {
			const [isOpen, setOpen] = useState(false);
			const openModal = () => {
				//console.log("open");
				setOpen(true);
			};
			const closeModal = () => {
				//console.log("close");
				setOpen(false);
			};
			const saveModal = () => {
				//console.log("save", userMapConfig);
				props.setAttributes({
					zoom: userMapConfig.zoom,
					lat: userMapConfig.lat,
					lon: userMapConfig.lon,
					basemap: userMapConfig.basemap,
				});

				setOpen(false);
			};

			return (
				<div>
					<Button isSecondary onClick={openModal}>
						<Dashicon icon="location" />{" "}
						{__("Set Coordinates", "wp_placepress")}
					</Button>
					{isOpen && (
						<Modal
							title={__("Set Map Coordinates", "wp_placepress")}
							onRequestClose={closeModal}
						>
							<PPMapUI />
							<Button isPrimary onClick={saveModal}>
								{__("Save Coordinates", "wp_placepress")}
							</Button>
						</Modal>
					)}
				</div>
			);
		};

		const MediaModal = () => {
			return (
				<MediaUploadCheck>
					<MediaUpload
						onSelect={(background) =>
							props.setAttributes({
								background: background.url ? background.url : "",
								caption: background.caption ? background.caption : "",
							})
						}
						allowedTypes="image"
						value={background}
						render={({ open }) => (
							<Button isSecondary onClick={open}>
								<Dashicon icon="format-image" />{" "}
								{__("Choose Image", "wp_placepress")}
							</Button>
						)}
					/>
				</MediaUploadCheck>
			);
		};

		return (
			<div
				className={className}
				aria-label={__("Tour Stop", "wp_placepress")}
				role="region"
			>
				<figure>
					<div
						class="figure-inner"
						style={{
							backgroundImage: "url(" + background + ")",
						}}
					>
						<Flex>
							<FlexItem>
								<MediaModal />
							</FlexItem>
							<FlexItem>
								<CoordsModal />
							</FlexItem>
						</Flex>
						<div className="pp-tour-stop-section-header-container">
							<div class="pp-marker-icon-center">
								<Dashicon icon="location" />
								{lat && lon && (
									<span class="onhover">
										{__("View On Map", "wp_placepress")}
									</span>
								)}
							</div>
							<div className="pp-tour-stop-title">
								<InnerBlocks template={HEADING} templateLock="all" />
							</div>
						</div>
					</div>
					<figcaption className="tour-stop-caption-pp">{caption}</figcaption>
				</figure>
			</div>
		);
	},
	save(props) {
		const { attributes } = props;

		return (
			<div
				className={props.className}
				aria-label={__("Tour Stop", "wp_placepress")}
				role="region"
			>
				<figure>
					<div
						class="figure-inner"
						style={"background-image:url(" + attributes.background + ")"}
					>
						<div
							class="pp-tour-stop-section-header-container"
							data-background={attributes.background}
							data-lat={attributes.lat}
							data-lon={attributes.lon}
							data-zoom={attributes.zoom}
							data-basemap={attributes.basemap}
						>
							<div class="pp-marker-icon-center">
								<Dashicon icon="location" />
								{attributes.lat && attributes.lon && (
									<span class="onhover">
										{__("View On Map", "wp_placepress")}
									</span>
								)}
							</div>
							<div className="pp-tour-stop-title">
								<InnerBlocks.Content />
							</div>
						</div>
					</div>
					<figcaption className="tour-stop-caption-pp">
						{attributes.caption}
					</figcaption>
				</figure>
			</div>
		);
	},
});
