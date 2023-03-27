<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class ShowSidebar
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
	 * @param    bool    $is_show
	 * @return    bool
	 */
	public function __invoke( bool $is_show ): bool
	{
		if ( is_archive() ) {
			$post_type = get_query_var( 'post_type' );
			$post_type_meta = $this->find( $post_type );

			$show_sidebar = $post_type_meta
				? $post_type_meta->show_sidebar_archive()
				: null;

			switch ( true ) {
				case is_null( $show_sidebar ): return $is_show;
				case $show_sidebar: return true;
				default: return false;
			}
		} elseif ( is_single() ) {
			global $post;
			$post_type_meta = $this->find( $post->post_type );

			$show_sidebar = $post_type_meta
				? $post_type_meta->show_sidebar_single()
				: null;

			switch ( true ) {
				case is_null( $show_sidebar ): return $is_show;
				case $show_sidebar: return true;
				default: return false;
			}
		} else {
			return $is_show;
		}
	}

	// ====================================================================== //

	/**
	 * @param    string    $post_type
	 * @return    PostTypeMeta|null
	 */
	private function find( string $post_type ): ?PostTypeMeta
	{
		foreach ( $this->post_type_metas as $post_type_meta ) {
			if ( $post_type_meta->name() === $post_type ) {
				return $post_type_meta;
			}
		}

		return null;
	}
}
