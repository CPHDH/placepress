<?php
if( !defined('ABSPATH') ){
	exit;
}
add_action(
	'rest_api_init',
	function () {

		if ( ! function_exists( 'use_block_editor_for_post_type' ) ) {
			require ABSPATH . 'wp-admin/includes/post.php';
		}

		// add PP Location Block to the WordPress REST API
		$post_types = get_post_types_by_support( [ 'editor' ] );
		foreach ( $post_types as $post_type ) {
			if ( use_block_editor_for_post_type( $post_type ) ) {
				register_rest_field(
					$post_type,
					'blocks',
					[
						'get_callback' => function ( array $post ) {
							$raw_blocks= parse_blocks( $post['content']['raw'] );
							$whitelisted_blocks = [];
							foreach ($raw_blocks as $raw_block) {
								if( $raw_block['blockName']=='placepress/block-map-location' ){
									array_push($whitelisted_blocks, $raw_block);
								}
							}
							return $whitelisted_blocks;
						},
					]
				);
			}
		}
	}
);
