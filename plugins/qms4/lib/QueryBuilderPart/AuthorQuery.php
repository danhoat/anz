<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class AuthorQuery extends QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'author' => array(),
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		if ( empty( $param[ 'author' ] ) ) { return array(); }

		$author_ids = is_array( $param[ 'author' ] )
			? $param[ 'author' ]
			: array( $param[ 'author' ] );

		list( $in, $not_in ) = $this->pertition( $author_ids );


		$query = array();

		if ( !empty( $in ) ) {
			$query[ 'author__in' ] = $in;
		}

		if ( !empty( $not_in ) ) {
			$query[ 'author__not_in' ] = $not_in;
		}

		return $query;
	}

	/**
	 * @param    array    $author_ids
	 * @return    array[]
	 */
	private function pertition( array $author_ids ): array
	{
		$in = array();
		$not_in = array();
		foreach ( $author_ids as $author_id ) {
			if ( strpos( $author_id, '-' ) === 0 ) {
				$not_in[] = (int) substr( $author_id, 1 );
			} else {
				$in[] = (int) $author_id;
			}
		}

		return array( $in, $not_in );
	}
}
