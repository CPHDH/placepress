import "./style.scss";
import "./editor.scss";

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { PlainText, InspectorControls, PanelBody } = wp.editor;
const {
	Button,
	TextareaControl,
	TextControl,
	Dashicon,
	Card,
	CardBody,
	Flex,
	FlexItem,
	FlexBlock,
} = wp.components;
const { useState } = wp.element;
const { MediaUpload, MediaUploadCheck } = wp.blockEditor;

console.log("Components:", wp.components);

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
		title: {
			type: "string",
			selector: "div.pp-tour-stop-section-header-container",
			source: "attribute",
			attribute: "data-title",
		},
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
	},
	edit(props) {
		const {
			attributes: { title, background, zoom, lat, lon, basemap },
			className,
			setAttributes,
		} = props;

		const notices = wp.data.dispatch("core/notices");

		const onBlockLoad = function (e) {
			console.log(e);
		};

		const onChangeTitle = (title) => {
			setAttributes({ title });
		};

		const onChangeBackground = (background) => {
			setAttributes({ background });
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
		if (!basemap) {
			props.setAttributes({ basemap: defaults.default_map_type });
		}

		return (
			<div
				className={props.className}
				aria-label={__("Tour Stop", "wp_placepress")}
				role="region"
				style={{
					backgroundImage: "url(" + background + ")",
				}}
			>
				<Flex>
					<FlexItem>
						<MediaUploadCheck>
							<MediaUpload
								onSelect={(background) =>
									props.setAttributes({
										background: background.url ? background.url : "",
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
					</FlexItem>
					<FlexItem>
						<Button isSecondary>
							<Dashicon icon="location" />{" "}
							{__("Set Coordinates", "wp_placepress")}
						</Button>
					</FlexItem>
				</Flex>
				<div className="pp-tour-stop-section-header-container">
					<div class="pp-marker-icon-center">
						<Dashicon icon="location" />
					</div>
					<h2>{title}</h2>
				</div>
				<TextControl
					className="map-title-pp"
					tagName="p"
					placeholder={__(
						"Enter the title as it should appear in the map.",
						"wp_placepress"
					)}
					value={title}
					onChange={onChangeTitle}
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
				aria-label={__("Tour Stop", "wp_placepress")}
				role="region"
			>
				<div
					class="pp-tour-stop-section-header-container"
					data-background={attributes.background}
					data-title={attributes.title}
					data-lat={attributes.lat}
					data-lon={attributes.lon}
					data-zoom={attributes.zoom}
					data-basemap={attributes.basemap}
					style={"background-image:url(" + attributes.background + ")"}
				>
					<div class="pp-marker-icon-center">
						<Dashicon icon="location" />
					</div>
					<h2>{attributes.title}</h2>
				</div>
			</div>
		);
	},
});
