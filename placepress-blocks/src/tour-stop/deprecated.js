const { __ } = wp.i18n;

const v1_3 = {
	// Deprecations for v.1.3 (adds customizable info window)
	supports: {
		align: ["center", "wide", "full"],
	},
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
	save(props) {
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
								<svg
									aria-hidden="true"
									role="img"
									xmlns="http://www.w3.org/2000/svg"
									width="20"
									height="20"
									viewBox="0 0 20 20"
									class="dashicon dashicons-location"
								>
									<path d="M10 2C6.69 2 4 4.69 4 8c0 2.02 1.17 3.71 2.53 4.89.43.37 1.18.96 1.85 1.83.74.97 1.41 2.01 1.62 2.71.21-.7.88-1.74 1.62-2.71.67-.87 1.42-1.46 1.85-1.83C14.83 11.71 16 10.02 16 8c0-3.31-2.69-6-6-6zm0 2.56c1.9 0 3.44 1.54 3.44 3.44S11.9 11.44 10 11.44 6.56 9.9 6.56 8 8.1 4.56 10 4.56z"></path>
								</svg>

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
};

const d = [v1_3];

export default d;
