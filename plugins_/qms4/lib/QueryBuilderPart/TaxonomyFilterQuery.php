<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class TaxonomyFilterQuery extends QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'taxonomy_filters' => array(),
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		if ( empty( $param[ 'taxonomy_filters' ] ) ) { return array(); }

		$tax_query = array();

		foreach ( $param[ 'taxonomy_filters' ] as $cond ) {
			if ( empty( $cond[ 'taxonomy' ] ) ) { continue; }
			if ( empty( $cond[ 'terms' ] ) ) { continue; }

			$tax_query[] = array(
				'taxonomy' => $cond[ 'taxonomy' ],
				'field' => 'slug',
				'terms' => array_map( 'urldecode', $cond[ 'terms' ] ),
				'operator' => $cond[ 'operator' ],
			);
		}

		if ( count( $tax_query ) >= 2 ) {
			$tax_query = array_merge(
				array( 'relation' => 'AND' ),
				$tax_query
			);
		}

		return array(
			'tax_query' => $tax_query,
		);
	}
}
