import "./style.scss";
import "./editor.scss";

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
	Button,
	Dashicon,
	Flex,
	FlexItem,
	Modal,
	IsolatedEventContainer,
	withNotices,
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
		align: ["wide", "full", "center"],
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
		mb_key: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-mb-key",
		},
		maki: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-maki",
		},
		maki_color: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-maki-color",
		},
	},
	edit(props) {
		const {
			attributes: {
				background,
				zoom,
				lat,
				lon,
				basemap,
				caption,
				mb_key,
				maki,
				maki_color,
			},
			className,
		} = props;

		const mapdefaults = placepress_plugin_settings.placepress_defaults;
		const userMapConfig = {
			lat: lat ? lat : mapdefaults.default_latitude,
			lon: lon ? lon : mapdefaults.default_longitude,
			zoom: zoom ? zoom : mapdefaults.default_zoom,
			basemap: basemap ? basemap : mapdefaults.default_map_type,
			mb_key: mb_key ? mb_key : mapdefaults.mapbox_key,
			maki: maki ? maki : mapdefaults.maki_markers,
			maki_color: maki_color ? maki_color : mapdefaults.maki_markers_color,
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

		const PPMapUI = withNotices(({ noticeOperations, noticeUI }) => {
			const tileSets = window.getMapTileSets();
			const currentTileSet = tileSets[userMapConfig.basemap];

			const uiSetCoordsPP = function () {
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
						input.style.padding = "5px 10px";
						input.style.borderRadius = "3px";
						input.tabindex = "0";
						input.type = "text";
						input.placeholder = __(
							"Enter a query and press Return/Enter ⏎",
							"wp_placepress"
						);
						form.style.width = "100%";

						input.addEventListener("click", (e) => {
							if (e.isTrusted) {
								input.focus(); // fixes safari bug
							}
						});

						L.DomEvent.addListener(
							form,
							"submit",
							L.DomEvent.preventDefault
						).addListener(form, "submit", function (e) {
							const q = e.target[0].value;
							if (q) {
								noticeOperations.removeAllNotices();
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
											noticeOperations.createErrorNotice(
												__(
													"PlacePress: Your search query did not return any results. Please try again.",
													"wp_placepress"
												),
												{ id: "placepress-no-result" }
											);
										}
									} else {
										noticeOperations.createErrorNotice(
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
					onRemove: function () {
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
				L.control.layers(layerNames).addTo(map);
				map.on("baselayerchange ", function (e) {
					const key = e.layer.options.placepress_key;
					if (key) {
						// pending save
						userMapConfig.basemap = key;
					}
				});
			};

			const onBlockLoad = function () {
				uiSetCoordsPP();
			};

			return (
				<IsolatedEventContainer>
					<figure>
						<div className="map-pp" id="placepress-tour-map"></div>
						{noticeUI}
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
		});

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

		const deleteBackground = () => {
			props.setAttributes({
				background: "",
				caption: "",
			});
		};

		const deleteCoords = () => {
			props.setAttributes({
				lat: "",
				lon: "",
				zoom: "",
				basemap: "",
			});
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
								<Flex>
									<FlexItem>
										<MediaModal />
									</FlexItem>
									<FlexItem>
										{background && (
											<Button
												label="Remove Image"
												showTooltip
												isDestructive
												onClick={deleteBackground}
											>
												<Dashicon icon="no" />
											</Button>
										)}
									</FlexItem>
								</Flex>
							</FlexItem>
							<FlexItem>
								<Flex>
									<FlexItem>
										{lat && lon && (
											<Button
												label="Remove Coordinates"
												showTooltip
												isDestructive
												onClick={deleteCoords}
											>
												<Dashicon icon="no" />
											</Button>
										)}
									</FlexItem>
									<FlexItem>
										<CoordsModal />
									</FlexItem>
								</Flex>
							</FlexItem>
						</Flex>
						<div className="pp-tour-stop-section-header-container">
							<div
								className={`pp-marker-icon-center ${
									lat && lon ? "has-map" : "no-map"
								}`}
							>
								<Dashicon icon="location" />
								<span class="onhover">
									{(lat && lon && __("View On Map", "wp_placepress")) ||
										"&nbsp;"}
								</span>
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
							data-mb-key={attributes.mb_key}
							data-maki={attributes.maki}
							data-maki-color={attributes.maki_color}
						>
							<div
								className={`pp-marker-icon-center ${
									attributes.lat && attributes.lon ? "has-map" : "no-map"
								}`}
							>
								<Dashicon icon="location" />

								<span class="onhover">
									{(attributes.lat &&
										attributes.lon &&
										__("View On Map", "wp_placepress")) ||
										"&nbsp;"}
								</span>
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
