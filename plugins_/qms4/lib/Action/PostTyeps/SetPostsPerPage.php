<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class SetPostsPerPage
{
	/** @var    int[] */
	private $posts_per_page;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$_posts_per_page = array();
		foreach ( $post_type_metas as $post_type_meta ) {
			$post_type = $post_type_meta->name();
			$posts_per_page = $post_type_meta->posts_per_page();

			if ( ! empty( $posts_per_page ) ) {
				$_posts_per_page[ $post_type ] = $posts_per_page;
			}
		}

		$this->posts_per_page = $_posts_per_page;
	}

	/**
	 * @param    \WP_Query    $query
	 * @return    void
	 */
	public function __invoke( \WP_Query $query ): void
	{
		if ( is_admin() ) { return; }
		if ( ! $query->is_main_query() ) { return; }

		$post_type = $query->get( 'post_type' );
		$post_type = is_array( $post_type ) ? $post_type[ 0 ] : $post_type;

		if ( isset( $this->posts_per_page[ $post_type ] ) ) {
			$query->set( 'posts_per_page', $this->posts_per_page[ $post_type ] );
		}
	}
}
