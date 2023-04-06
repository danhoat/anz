<?php

namespace QMS4\Block;

use QMS4\Block\Renderer\PostListItemRenderer;


class PostList
{
	/** @var    string */
	private $name = 'post-list';

	public function register()
	{
		register_block_type(
			QMS4_DIR . "/blocks/build/{$this->name}",
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);

		register_block_type( QMS4_DIR . '/blocks/build/post-list-area' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-html' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-post-thumbnail' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-post-title' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-post-excerpt' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-post-date' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-post-modified' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-post-author' );
		register_block_type( QMS4_DIR . '/blocks/build/post-list-terms' );
	}

	/**
	 * @param    array<string,mixed>    $attributes
	 * @param    string|null    $content
	 * @return    string
	 */
	public function render( array $attributes, ?string $content ): string
	{

		$custom_style = isset($attributes['customStyle']) ? $attributes['customStyle'] : 'default_style';

		$layout = $attributes['layout'];
		$className = isset( $attributes['className']) ? $attributes['className'] : 'block_style';;

		$num_columns_pc = $attributes[ 'numColumnsPc' ];
		$num_columns_sp = $attributes[ 'numColumnsSp' ];
		$num_posts_pc = $attributes[ 'numPostsPc' ];
		$num_posts_sp = $attributes[ 'numPostsSp' ];

		$post_type = $attributes[ 'postType' ];
		$orderby = $attributes[ 'orderby' ];
		$order = $attributes[ 'order' ];

		$taxonomy_filters = $attributes[ 'taxonomyFilters' ];
		$exclude_post_ids = $attributes[ 'excludePostIds' ] ?? '';
		$include_post_ids = $attributes[ 'includePostIds' ] ?? '';

		$link_target = $attributes[ 'linkTarget' ];
		$link_target_custom = $attributes[ 'linkTargetCustom' ];


		$param = array();

		$param[ 'count' ] = max( $num_posts_pc, $num_posts_sp );

		$param[ 'orderby' ] = $orderby;
		$param[ 'order' ] = $order;

		$param[ 'taxonomy_filters' ] = $taxonomy_filters;
		if ( ! empty( $_GET[ 'area' ] ) ) {
			$param[ 'area' ] = $_GET[ 'area' ];
		}
		$param[ 'post__not_in' ] = trim( $exclude_post_ids ) == false
			? array()
			: array_map( 'trim', explode( ',', $exclude_post_ids ) );
		$param[ 'post__in' ] = trim( $include_post_ids ) == false
			? array()
			: array_map( 'trim', explode( ',', $include_post_ids ) );

		$list = qms4_list( $post_type, $param );

		if ( empty( $content ) ) {

			$renderer = PostListItemRenderer::from_block_instance_array( $attributes[ 'innerBlocks' ] );
			$server_side_rendering = true;
		} else {
			$renderer = PostListItemRenderer::from_ndjson( $content );
			$server_side_rendering = false;
		}

		ob_start();
		require( QMS4_DIR . "/blocks/templates/{$this->name}.php" );
		return ob_get_clean();
	}
}
