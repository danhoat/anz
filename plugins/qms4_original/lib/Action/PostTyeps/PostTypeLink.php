<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class PostTypeLink
{
	/** @var    string[] */
	private $post_types;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$post_types = array();
		foreach ( $post_type_metas as $post_type_meta ) {
			if ( $post_type_meta->permalink_type() === 'post_id' ) {
				$post_types[] = $post_type_meta->name();
			}
		}

		$this->post_types = $post_types;
	}

	/**
	 * @param    string    $post_link
	 * @param    \WP_Post    $post
	 * @return    string
	 */
	public function __invoke( string $post_link, \WP_Post $post ): string
	{
		if ( in_array( $post->post_type, $this->post_types, /* $strict = */ true ) ) {
			return home_url( "/{$post->post_type}/{$post->ID}/" );
		}

		return $post_link;
	}
}
