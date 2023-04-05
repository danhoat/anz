<?php

namespace QMS4\Action\RoleCapability;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\RoleCapability\Administrator;
use QMS4\RoleCapability\Atsumaru;
use QMS4\RoleCapability\Author;
use QMS4\RoleCapability\Contributor;
use QMS4\RoleCapability\Editor;


class CloneCapabilities
{
	/**
	 * @param    int    $new_post_id
	 * @param    \WP_Post    $post
	 * @param    string    $status
	 * @return    void
	 */
	public function __invoke(
		int $new_post_id,
		\WP_Post $post,
		string $status
	): void
	{
		if ( $post->post_type !== 'qms4' ) { return; }

		if ( ! get_post_meta( $post->ID, 'capability_configured', true ) ) {
			return;
		}

		$post_type_meta = PostTypeMeta::from_post_id( $post->ID );
		$post_type = $post_type_meta->name();
		$id = $post_type_meta->id();
		$capability_type = "{$post_type}_{$id}";

		$new_post_type_meta = PostTypeMeta::from_post_id( $new_post_id );
		$new_post_type = $new_post_type_meta->name();
		$new_id = $new_post_type_meta->id();
		$new_capability_type = "{$new_post_type}_{$new_id}";

		if ( empty( $post_type ) || empty( $new_post_type ) ) { return; }

		// 管理者
		$administrator = new Administrator();
		$administrator->clone_caps( $capability_type, $new_capability_type );

		if ( get_role( 'atsumaru' ) ) {
			// あつまる従業員
			$atsumaru = new Atsumaru();
			$atsumaru->clone_caps( $capability_type, $new_capability_type );
		}

		// 編集者
		$editor = new Editor();
		$editor->clone_caps( $capability_type, $new_capability_type );

		// 投稿者
		$author = new Author();
		$author->clone_caps( $capability_type, $new_capability_type );

		// 寄稿者
		$contributor = new Contributor();
		$contributor->clone_caps( $capability_type, $new_capability_type );
	}
}
