<?php

namespace QMS4\Area;


class AreaIdsRepository
{
	/**
	 * エリアのスラッグをもとに自身の ID と子・子孫すべての ID を取得する
	 * 投稿一覧ページなどでエリアをもとにした絞り込み条件に使う
	 *
	 * @param    string|string[]    $slug
	 */
	public function find_by_slug( $slugs )
	{
		$slugs = is_array( $slugs ) ? $slugs : array( $slugs );
		$slugs = array_map( 'urlencode', $slugs );

		global $wpdb;

		$sql = "
			SELECT
				 P.`ID` AS 'ID'
				,P.`post_title` AS 'post_title'
				,P.`post_name` AS 'post_name'
				,PM.`meta_value` AS 'child_id'
			FROM {$wpdb->posts} AS P
			LEFT OUTER JOIN {$wpdb->postmeta} AS PM
				ON
					P.`ID` = PM.`post_id`
					AND PM.`meta_key` = 'qms4__child_ids'
			WHERE
				P.`post_type` = 'qms4__area_master'
				AND P.`post_status` = 'publish'
				AND P.`post_name` IN ( " . join( ', ', array_fill( 0, count( $slugs ), '%s' ) ) . " )
			;
		";

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				$sql,
				... $slugs
			)
		);

		$area_ids = array();
		foreach ( $rows as $row ) {
			$area_ids[] = (int) $row->ID;
			if ( ! is_null( $row->child_id ) ) { $area_ids[] = (int) $row->child_id; }
		}

		return array_values( array_unique( $area_ids ) );
	}
}
