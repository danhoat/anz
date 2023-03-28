<?php

namespace QMS4\Action\Columns;


class AddColumnsEvent
{
	/**
	 * @param    array<string,string>    $post_columns
	 * @return    array<string,string>
	 */
	public function __invoke( array $post_columns ): array
	{
		$post_columns = array_merge(
			array_slice( $post_columns, 0, -1 ),
			array( 'qms4__event_date' => '日程' ),
			array( 'qms4__event_timetable' => 'タイムテーブル' ),
			array_slice( $post_columns, -1 ),
		);

		return $post_columns;
	}
}
