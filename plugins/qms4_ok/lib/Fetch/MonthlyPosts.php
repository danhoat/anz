<?php

namespace QMS4\Fetch;


class MonthlyPosts
{
	/** @var    string */
	private $post_type;

	/**
	 * @param    string    $post_type
	 */
	public function __construct( string $post_type )
	{
		$this->post_type = $post_type;
	}

	/**
	 * @param    int    $year
	 * @return    array[]
	 */
	public function fetch( int $year ): array
	{
		$rows = $this->query( $this->post_type, $year );

		$months = $this->default_months( $year );
		foreach ( $rows as $row ) {
			if ( isset( $months[ $row->post_month ] ) ) {
				$months[ $row->post_month ][ 'count' ] = (int) $row->count;
			}
		}

		return array_values( $months );
	}

	// ====================================================================== //

	/**
	 * @param    string    $post_type
	 * @param    int    $year
	 * @return    object[]
	 */
	private function query( string $post_type, int $year ): array
	{
		global $wpdb;

		$from = "{$year}-01-01 00:00:00";
		$to = "{$year}-12-31 23:59:59";

		$query = "
			SELECT
				YEAR(`post_date`)   AS post_year
				,MONTH(`post_date`) AS post_month
				,COUNT(`ID`)        AS count

			FROM $wpdb->posts

			WHERE
				1
				AND `post_type` = %s
				AND `post_status` = 'publish'
				AND (`post_date` BETWEEN %s AND %s)

			GROUP BY
				post_year
				,post_month
		";
		$stmt = $wpdb->prepare( $query, $post_type, $from, $to );

		return $wpdb->get_results( $stmt );
	}

	/**
	 * @param    int    $year
	 * @return    array[]
	 */
	private function default_months( int $year ): array
	{
		$this_year = date( 'Y' );
		$this_month = date( 'm' );

		$months = array();
		foreach ( range( 1, 12 ) as $month ) {
			if ( $year == $this_year && $month > $this_month) { continue; }

			$months[ $month ] = array(
				'year' => (int) $year,
				'month' => $month,
				'count' => 0,
			);
		}

		return $months;
	}
}
