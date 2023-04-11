<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;
use QMS4\QueryBuilder\QueryBuilder;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class OffsetQuery extends QueryBuilderPart
{
	/** @var    QueryBuilder */
	private $query_builder;

	public function __construct( QueryBuilder $query_builder )
	{
		$this->query_builder = $query_builder;
	}

	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'offset' => 0,
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		if ( $param[ 'offset' ] <= 0 ) { return array(); }

		$query_args = $this->query_builder->build( $param );
		$query_args = array_merge(
			$query_args,
			array(
				'posts_per_page' => $param[ 'offset' ],
				'paged' => 1,
				'offset' => 0,
				'fields' => 'ids',
			)
		);

		$query = new \WP_Query( $query_args );

		return array(
			'post__not_in' => $query->posts,
		);
	}
}
