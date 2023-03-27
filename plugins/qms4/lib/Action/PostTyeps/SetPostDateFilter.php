<?php

namespace QMS4\Action\PostTyeps;


class SetPostDateFilter
{
	/**
	 * @param    \WP_Query    $query
	 */
	public function __invoke( \WP_Query $query ): void
	{
		if ( is_admin() ) { return; }
		if ( ! $query->is_main_query() ) { return; }
		if (
			empty( $query->query_vars[ 'ym' ] )
			|| ! preg_match( '/^\d{6}$/', $query->query_vars[ 'ym' ] )
		) { return; }

		$year = substr( $query->query_vars[ 'ym' ], 0, 4 );
		$month = substr( $query->query_vars[ 'ym' ], 4);

		if ( empty( $query->get( 'date_query' ) ) ) {
			$query->set( 'date_query', array(
				array(
					'year' => $year,
					'monthnum' => $month,
				),
			) );
		} else {
			$new_date_query = array_merge(
				$query->get( 'date_query' ),
				array(
					'year' => $year,
					'monthnum' => $month,
				)
			);

			$query->set( 'date_query', $new_date_query );
		}
	}
}
