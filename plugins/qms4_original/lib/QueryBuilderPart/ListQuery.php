<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class ListQuery extends QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'post_type' => array( 'post' ),
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'count' => -1,
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		return array(
			'post_type' => $param[ 'post_type' ],
			'post_status' => $param[ 'post_status' ],
			'orderby' => $param[ 'orderby' ],
			'order' => $param[ 'order' ],
			'posts_per_page' => $param[ 'count' ],
		);
	}
}
