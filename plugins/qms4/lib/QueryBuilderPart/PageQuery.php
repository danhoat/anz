<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class PageQuery extends QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'count' => 12,
			'page' => 1,
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		return array(
			'posts_per_page' => $param[ 'count' ],
			'paged' => $param[ 'page' ],
		);
	}
}
