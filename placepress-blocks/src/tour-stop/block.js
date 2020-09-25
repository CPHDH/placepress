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

		const HEADING = [
			[
				"core/heading",
				{
					level: 2,
					placeholder: __("Enter a title for this stop", "wp_placepress"),
				},
			],
		];

		const CoordsModal = () => {
			const [isOpen, setOpen] = useState(false);
			const openModal = () => setOpen(true);
			const closeModal = () => setOpen(false);

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
							<h3>The map goes here</h3>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a
								diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula
								ac quam viverra nec consectetur ante hendrerit. Donec et mollis
								dolor. Praesent et diam eget libero egestas mattis sit amet
								vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia
								consectetur. Donec ut libero sed arcu vehicula ultricies a non
								tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
								Aenean ut gravida lorem. Ut turpis felis, pulvinar a semper sed,
								adipiscing id dolor. Pellentesque auctor nisi id magna consequat
								sagittis.{" "}
							</p>
							<Button isPrimary onClick={closeModal}>
								Save Coordinates
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
