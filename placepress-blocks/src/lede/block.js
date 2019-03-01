/**
 * BLOCK: placepress/block-lede
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
registerBlockType( 'placepress/block-lede', {
	title: __( 'Lede' ),
	icon: 'format-aside',
	category: 'placepress',
	keywords: [
		__( 'Lede' ),
    __( 'Abstract' ),
		__( 'PlacePress' ),
	],
  description: __( 'A block for creating an introductory lede or abstract paragraph. Place it directly below the title or subtitle.' ),
  useOnce:true,
  attributes: {
    content: {
        type: 'string',
        selector: '.lede-pp',
    }
  },
  /**
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit( props ) {
    const { className, setAttributes } = props;
    const { attributes } = props;

    function changeContent(changes) {
        setAttributes({
            content: changes
        })
    }

		return (
      <div className={className}>
      <PlainText
          className="lede-pp"
          tagName="p"
          rows="4"
          placeholder={ __("Enter some introductory text here.", 'wp_placepress') }
          value={attributes.content}
          onChange={changeContent}
          />
      </div>
		);
	},

	/**
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save( props ) {
    const className = getBlockDefaultClassName('placepress/block-lede');
    const { attributes } = props;

		return (
      <div className={className}>
      <p class="lede-pp">{attributes.content}</p>
      </div>
		);
	},
} );
