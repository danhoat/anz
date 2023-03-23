<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class SetRevisionsToKeep
{
	/** @var    array<string,int> */
	private $revisions_to_keep;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$revisions_to_keep = array();
		foreach ( $post_type_metas as $post_type_meta ) {
			$revisions_to_keep[ $post_type_meta->name() ] = $post_type_meta->revisions_to_keep();
		}

		$this->revisions_to_keep = $revisions_to_keep;
	}

	/**
	 * @param    int    $num
	 * @param    \WP_Post    $post
	 * @return    int
	 */
	public function __invoke( int $num, \WP_Post $post ): int
	{
		if ( ! isset( $this->revisions_to_keep[ $post->post_type ] ) ) {
			return $num;
		}

		$revisions_to_keep = $this->revisions_to_keep[ $post->post_type ];

		if ( $revisions_to_keep < 0 ) {
			return true;
		} elseif ( $revisions_to_keep === 0 ) {
			return false;
		} else {
			return $revisions_to_keep;
		}
	}
}
