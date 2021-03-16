const { __ } = wp.i18n;

const v1_3 = {
	// Deprecations for v.1.3 (adds customizable info window)
	supports: {
		align: ["center", "wide", "full"],
	},
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
	save(props) {
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
						data-lat={props.attributes.lat}
						data-lon={props.attributes.lon}
						data-zoom={props.attributes.zoom}
						data-mb-key={props.attributes.mapbox_key}
						data-maki={props.attributes.maki_markers}
						data-maki-color={props.attributes.maki_markers_color}
						data-basemap={props.attributes.basemap}
						data-type="single-location"
					/>
					<figcaption className="map-caption-pp">
						{props.attributes.caption}
					</figcaption>
				</figure>
			</div>
		);
	},
};

const d = [v1_3];

export default d;
