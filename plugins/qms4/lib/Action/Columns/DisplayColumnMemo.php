<?php

namespace QMS4\Action\Columns;

use QMS4\PostMeta\Memo;


class DisplayColumnMemo
{
	/**
	 * @param    string    $column_name
	 * @param    int    $post_id
	 * @return    void
	 */
	public function __invoke( string $column_name, int $post_id ): void
	{
		if ( $column_name !== 'qms4__memo' ) { return; }

		$memo = get_post_meta( $post_id, Memo::KEY, /* $single = */ true );

		echo '<p>' . nl2br( esc_html( $memo ), /* $is_xhtml = */ false ) . '</p>';
	}
}
