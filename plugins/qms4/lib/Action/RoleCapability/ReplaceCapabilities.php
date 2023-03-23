<?php

namespace QMS4\Action\RoleCapability;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\RoleCapability\Administrator;
use QMS4\RoleCapability\Atsumaru;
use QMS4\RoleCapability\Author;
use QMS4\RoleCapability\Contributor;
use QMS4\RoleCapability\Editor;


class ReplaceCapabilities
{
	/**
	 * @param    int|string    $post_id
	 * @return    void
	 */
	public function __invoke( $post_id ): void
	{
		if (
			get_post_type( $post_id ) !== 'qms4'
			|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			|| wp_is_post_revision( $post_id )
		) { return; }

		if ( ! get_post_meta( $post_id, 'capability_configured', true ) ) {
			return;
		}

		if ( empty( $_POST[ 'acf' ][ 'field_62675ce59dd3e' ] ) ) { return; }

		$post_type_meta = PostTypeMeta::from_post_id( $post_id );
		$id = $post_type_meta->id();

		$post_type = $post_type_meta->name();
		$capability_type = "{$post_type}_{$id}";

		$new_post_type = trim( $_POST[ 'acf' ][ 'field_62675ce59dd3e' ] );
		$new_capability_type = "{$new_post_type}_{$id}";

		if ( $post_type != $new_post_type ) {
			// 管理者
			$administrator = new Administrator();
			$administrator->replace_caps( $capability_type, $new_capability_type );

			if ( get_role( 'atsumaru' ) ) {
				// あつまる従業員
				$atsumaru = new Atsumaru();
				$atsumaru->replace_caps( $capability_type, $new_capability_type );
			}

			// 編集者
			$editor = new Editor();
			$editor->replace_caps( $capability_type, $new_capability_type );

			// 投稿者
			$author = new Author();
			$author->replace_caps( $capability_type, $new_capability_type );

			// 寄稿者
			$contributor = new Contributor();
			$contributor->replace_caps( $capability_type, $new_capability_type );
		}
	}
}
