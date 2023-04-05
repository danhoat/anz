<?php

namespace QMS4\Action\Columns;

use QMS4\PostMeta\RelPostType;
use QMS4\PostTypeMeta\DefaultPostTypeMeta;
use QMS4\PostTypeMeta\PostTypeMetaFactory;


class DisplayColumnRelPostType
{
	/**
	 * @param    string    $column_name
	 * @param    int    $post_id
	 * @return    void
	 */
	public function __invoke( string $column_name, int $post_id ): void
	{
		if ( $column_name !== 'qms4__rel_post_type' ) { return; }

		$rel_post_id = get_post_meta( $post_id, RelPostType::KEY, /* $single = */ true );

		$factory = new PostTypeMetaFactory();
		$post_type_meta = $factory->from_post_id( $rel_post_id );

		$post_type_label = $post_type_meta instanceof DefaultPostTypeMeta
			? '(投稿タイプとの紐付け無し)'
			: $post_type_meta->label();

		$edit_page_url = admin_url( '/post.php' ) . '?post=' . $post_id . '&action=edit';

		echo trim( '
			<strong>
				<a href="' . $edit_page_url . '">' . $post_type_label . '</a>
			</strong>
		' );
	}
}
