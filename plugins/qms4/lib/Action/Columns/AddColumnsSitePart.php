<?php

namespace QMS4\Action\Columns;


class AddColumnsSitePart
{
	/**
	 * @param    array<string,string>    $post_columns
	 * @return    array<string,string>
	 */
	public function __invoke( array $post_columns ): array
	{
		$post_columns = array_merge(
			array_slice( $post_columns, 0, -1 ),
			array( 'qms4__site_part__post_id' => 'ID' ),
			array( 'qms4__memo' => 'メモ' ),
			array_slice( $post_columns, -1 ),
		);

		return $post_columns;
	}
}
