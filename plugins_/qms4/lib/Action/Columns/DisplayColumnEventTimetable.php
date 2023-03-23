<?php

namespace QMS4\Action\Columns;

use QMS4\PostMeta\Timetable;
use QMS4\PostTypeMeta\PostTypeMeta;


class DisplayColumnEventTimetable
{
	/** @var    PostTypeMeta */
	private $post_type_meta;

	public function __construct( PostTypeMeta $post_type_meta )
	{
		$this->post_type_meta = $post_type_meta;
	}

	/**
	 * @param    string    $column_name
	 * @param    int    $post_id
	 * @return    void
	 */
	public function __invoke( string $column_name, int $post_id ): void
	{
		if ( $column_name !== 'qms4__event_timetable' ) { return; }

		$timetable = Timetable::get_post_meta( $post_id );

		// var_dump( $timetable );

		if ( ! $timetable ) {
			echo '<p>（登録無し）</p>';
			return;
		}

		$trs = array();
		foreach ( $timetable as $row ) {
			$trs[] = '<tr><td>' . $row[ 'label' ] . '</td><td>'
				. $row[ 'capacity' ] . '</td><td>' . $row[ 'comment' ] . '</td></tr>';
		}

		echo '<table>' . join( '', $trs ) . '</table>';
	}
}
