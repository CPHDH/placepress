import "./style.scss";
import "./editor.scss";

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { RichText, InspectorControls, InnerBlocks } = wp.editor;
const BLOCKS_TEMPLATE = [
	[
		"core/group",
		[],
		[
			["core/separator"],
			[
				"core/heading",
				{ level: 2, placeholder: "Enter a title for this location" },
			],
			[
				"core/heading",
				{ level: 3, placeholder: "Enter a subtitle for this location" },
			],
			["core/image", {}],
			["core/paragraph", { placeholder: "Enter some text for this location" }],
			["core/audio", {}],
		],
	],
];
const el = wp.element.createElement;

registerBlockType("placepress/block-tour-location", {
	title: __("Tour Location"),
	icon: "location",
	category: "placepress",
	keywords: [__("Map"), __("Tour"), __("PlacePress")],
	supports: {
		anchor: true,
		html: false,
		multiple: true,
	},
	description: __("A block for adding a tour location."),
	attributes: {
		// @TODO: Enable metaboxes for _block-map-tour-items
		content: {
			type: "string",
			// source: 'meta',
			// meta: '_block-map-tour-items',
			selector: ".map-location-pp",
		},
	},
	edit: (props) => {
		return el(InnerBlocks, {
			template: BLOCKS_TEMPLATE,
			templateLock: false,
		});
	},
	save: (props) => {
		return el(InnerBlocks.Content, {});
	},
});
