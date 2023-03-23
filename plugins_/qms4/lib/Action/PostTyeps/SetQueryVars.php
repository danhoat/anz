<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;


class SetQueryVars
{
	/**
	 * @param    string[]    $query_vars
	 * @return    string[]
	 */
	public function __invoke( array $query_vars ): array
	{
		$query_vars[] = 'ym';
		$query_vars[] = 'q';
		$query_vars[] = 'area';

		return array_unique( $query_vars );
	}
}
