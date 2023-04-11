<?php

namespace QMS4\Action\RoleCapability;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\RoleCapability\Administrator;
use QMS4\RoleCapability\Atsumaru;
use QMS4\RoleCapability\Author;
use QMS4\RoleCapability\Contributor;
use QMS4\RoleCapability\Editor;


class RemoveCapabilities
{
	/**
	 * @param    int    $post_id
	 * @param    \WP_Post    $post
	 * @return    void
	 */
	public function __invoke( int $post_id, \WP_Post $post ): void
	{
		if ( $post->post_type !== 'qms4' || wp_is_post_revision( $post_id ) ) {
			return;
		}

		$post_type_meta = PostTypeMeta::from_post_id( $post_id );

		$post_type = $post_type_meta->name();
		$id = $post_type_meta->id();
		$capability_type = "{$post_type}_{$id}";

		if ( empty( $post_type ) ) { return; }

		// 管理者
		$administrator = new Administrator();
		$administrator->remove_caps( $capability_type );

		if ( get_role( 'atsumaru' ) ) {
			// あつまる従業員
			$atsumaru = new Atsumaru();
			$atsumaru->remove_caps( $capability_type );
		}

		// 編集者
		$editor = new Editor();
		$editor->remove_caps( $capability_type );

		// 投稿者
		$author = new Author();
		$author->remove_caps( $capability_type );

		// 寄稿者
		$contributor = new Contributor();
		$contributor->remove_caps( $capability_type );
	}
}
