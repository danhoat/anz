<?php

namespace QMS4\Action\Columns;


class AddColumnsBlockTemplate
{
	/**
	 * @param    array<string,string>    $post_columns
	 * @return    array<string,string>
	 */
	public function __invoke( array $post_columns ): array
	{
		unset( $post_columns[ 'title' ] );

		$post_columns = array_merge(
			array_slice( $post_columns, 0, 1 ),
			array( 'qms4__rel_post_type' => '投稿タイプ' ),
			array( 'qms4__memo' => 'メモ' ),
			array_slice( $post_columns, 1 ),
		);

		return $post_columns;
	}
}
