import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { PlainText, InspectorControls } = wp.editor;

registerBlockType( 'placepress/block-map-tour', {
	title: __( 'Tour Map' ),
	icon: 'location',
	category: 'placepress',
	keywords: [ __( 'Map' ), __( 'Tour' ), __( 'PlacePress' ) ],
	supports: {
		anchor: true,
		html: false,
		multiple: false,
		reusable: false,
	},
	description: __( 'A block for adding a tour map.' ),
	attributes: {
		// @TODO: Enable metaboxes for _block-map-tour-items
		content: {
			type: 'string',
			// source: 'meta',
			// meta: '_block-map-tour-items',
			selector: '.map-location-pp',
		},
	},
	edit: function( props ) {
		const { className, setAttributes } = props;
		const { attributes } = props;

		function changeContent( changes ) {
			setAttributes( {
				content: changes,
			} );
		}
		return (
			<div className={ props.className }>
				<PlainText
					className="map-tour-pp"
					tagName="p"
					placeholder={ __( 'Enter something else here.', 'wp_placepress' ) }
					value={ attributes.content }
					onChange={ changeContent }
				/>
			</div>
		);
	},
	save: function( props ) {
		const className = getBlockDefaultClassName( 'placepress/block-map-tour' );
		const { attributes } = props;

		return (
			<div className={ className }>
				<p className="map-tour-pp">{ attributes.content }</p>
			</div>
		);
	},
} );
