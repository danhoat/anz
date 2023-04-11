<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class DeactivateBlockEditor
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
			if ( $post_type_meta->editor() !== 'block_editor' ) {
				$post_types[] = $post_type_meta->name();
			}
		}

		$this->post_types = $post_types;
	}

	/**
	 * @param    bool    $use_block_editor
	 * @param    \WP_Post    $post
	 * @return    bool
	 */
	public function __invoke( bool $use_block_editor, \WP_Post $post ): bool
	{
		if ( in_array( $post->post_type, $this->post_types, /* $strict = */ true ) ) {
			return false;
		}

		return $use_block_editor;
	}
}
