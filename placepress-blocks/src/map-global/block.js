import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType, getBlockDefaultClassName } = wp.blocks;
const { PlainText, InspectorControls } = wp.editor;

registerBlockType( 'placepress/block-map-global', {
 	title: __( 'Global Map' ),
 	icon: 'location-alt',
 	category: 'placepress',
 	keywords: [
 		__( 'Map' ),
    __( 'Global' ),
 		__( 'PlacePress' ),
 	],
  supports:{
    anchor:true,
    html: false,
    multiple: false,
    reusable: false,
  },
  description: __( 'A block for adding the global map.' ),
  attributes: {
    content: {
        type: 'string',
        selector: '.map-global-pp',
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
          className="map-global-pp"
          tagName="p"
          placeholder={ __("Enter something here.", 'wp_placepress') }
          value={attributes.content}
          onChange={changeContent}
          />
			</div>
		);
	},
	save: function( props ) {
    const className = getBlockDefaultClassName('placepress/block-map-global');
    const { attributes } = props;

		return (
      <div className={className}>
      <p class="map-global-pp">{attributes.content}</p>
      </div>
		);
	},
} );
