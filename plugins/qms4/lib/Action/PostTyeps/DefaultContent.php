<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class DefaultContent
{
	/** @var    PostTypeMeta[] */
	private $post_type_metas;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$this->post_type_metas = $post_type_metas;
	}

	/**
	 * @param    string    $post_content
	 * @param    \WP_Post    $post
	 * @return    string
	 */
	public function __invoke( string $post_content, \WP_Post $post ): string
	{
		$post_type_meta = $this->find( $post->post_type );

		if ( is_null( $post_type_meta ) ) { return $post_content; }

		$query = new \WP_Query( array(
			'post_type' => 'qms4__block_template',
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => 1,
			'meta_query' => array(
				array(
					'key' => 'qms4__rel_post_type',
					'value' => $post_type_meta->id(),
				),
			),
		) );

		if ( ! $query->found_posts ) { return $post_content; }

		return $query->posts[ 0 ]->post_content;
	}

	// ====================================================================== //

	/**
	 * @param    string    $post_type
	 * @return    PostTypeMeta|null
	 */
	public function find( string $post_type ): ?PostTypeMeta
	{
		foreach ( $this->post_type_metas as $post_type_meta ) {
			if ( $post_type_meta->name() === $post_type ) {
				return $post_type_meta;
			}
		}

		return null;
	}
}
