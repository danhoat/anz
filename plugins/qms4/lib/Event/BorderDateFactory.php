<?php

namespace QMS4\Event;

use QMS4\Event\BorderDate;


class BorderDateFactory
{
	/**
	 * @param    string    $post_type
	 * @return    BorderDate
	 */
	public function from_post_type( string $post_type ): BorderDate
	{
		$cal_base_date = $this->cal_base_date( $post_type );

		$today = new \DateTimeImmutable( 'now', wp_timezone() );
		if ( $cal_base_date == 0 ) {
			$border_date = $today;
		} elseif ( $cal_base_date > 0 ) {
			$interval = new \DateInterval( 'P' . $cal_base_date . 'D' );
			$border_date = $today->add( $interval );
		} else {
			$interval = new \DateInterval( 'P' . abs( $cal_base_date ) . 'D' );
			$border_date = $today->sub( $interval );
		}

		return new BorderDate( $border_date );
	}

	public function fist_date_of_next_month(){

		$otherday = new \DateTimeImmutable( 'first day of next month', wp_timezone() );

		return new BorderDate( $otherday );
	}

	function fist_date_of_left_month($ymd){

		$date = new \DateTimeImmutable($ymd);
		//$new_date = $date->modify('+ 2 months');

		return new BorderDate( $date );

	}
	function fist_date_of_right_month($ymd){

		$date = new \DateTimeImmutable($ymd);
		$new_date = $date->modify('+1 month');

		return new BorderDate( $new_date );

	}

	/**
	 * @param    string    $post_type
	 * @return    int
	 */
	private function cal_base_date( string $post_type ): int
	{
		global $wpdb;

		$sql = "
			SELECT
				 P.`ID` AS 'ID'
				,P.`post_title` AS 'post_title'
				,POST_TYPE.`meta_value` AS 'post_name'
				,FUNC_TYPE.`meta_value` AS 'func_type'
				,CAL_BASE_DATE.`meta_value` AS 'cal_base_date'
			FROM {$wpdb->posts} AS P
			INNER JOIN {$wpdb->postmeta} AS POST_TYPE
				ON
					P.`ID` = POST_TYPE.`post_id`
					AND POST_TYPE.`meta_key` = 'qms4__post_type__name'
			INNER JOIN {$wpdb->postmeta} AS FUNC_TYPE
				ON
					P.`ID` = FUNC_TYPE.`post_id`
					AND FUNC_TYPE.`meta_key` = 'qms4__post_type__func_type'
					AND FUNC_TYPE.`meta_value` = 'event'
			LEFT OUTER JOIN {$wpdb->postmeta} AS CAL_BASE_DATE
				ON
					P.`ID` = CAL_BASE_DATE.`post_id`
					AND CAL_BASE_DATE.`meta_key` = 'qms4__post_type__cal_base_date'
			WHERE
				P.`post_type` = 'qms4'
				AND P.`post_status` = 'publish'
				AND POST_TYPE.`meta_value` = %s
			;
		";

		$row = $wpdb->get_row( $wpdb->prepare( $sql, $post_type ) );

		if ( is_null( $row ) ) {
			return 0;
			throw new \LogicException();
		}

		return $row->cal_base_date ?: 0;
	}
}
