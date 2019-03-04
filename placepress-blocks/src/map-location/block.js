/**
 * BLOCK: placepress-blocks
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { PlainText, InspectorControls } = wp.editor;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'placepress/block-map-location', {
 	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
 	title: __( 'Map: Location' ), // Block title.
 	icon: 'location', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
 	category: 'placepress', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
 	keywords: [
 		__( 'Map' ),
    __( 'Location' ),
 		__( 'PlacePress' ),
 	],
  supports:{
    anchor:true,
    html: false,
    multiple: false,
    reusable: false,
  },
  description: __( 'A block for adding a location map.' ),
  attributes: {
    content: {
        type: 'string',
        selector: '.map-location-pp',
      }
  },
	edit: function( props ) {
    const { className, setAttributes } = props;
    const { attributes } = props;

    function changeContent(changes) {
        setAttributes({
            content: changes
        })
    }
		return (
			<div className={ props.className }>
      <PlainText
          className="map-location-pp"
          tagName="p"
          placeholder={ __("Enter coordinates here.", 'wp_placepress') }
          value={attributes.content}
          onChange={changeContent}
          />
			</div>
		);
	},
	save: function( props ) {
    const className = getBlockDefaultClassName('placepress/block-map-location');
    const { attributes } = props;

		return (
      <div className={className}>
      <p class="map-location-pp">{attributes.content}</p>
      </div>
		);
	},
} );
