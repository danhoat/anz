<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;


abstract class QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	abstract protected function default_param(): array;

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	abstract protected function query_args( Param $param ): array;

	// ====================================================================== //

	public function build( Param $param )
	{
		$param = $param->fill( $this->default_param() );

		return $this->query_args( $param );
	}
}
