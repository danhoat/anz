<?php

namespace QMS4\QueryBuilder;

use QMS4\Param\Param;
use QMS4\QueryBuilder\ArgsMerge;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class QueryBuilder
{
	protected $builder_parts = array();

	/**
	 * @param    QueryBuilderPart[]
	 */
	public function __construct( array $builder_parts )
	{
		$this->builder_parts = $builder_parts;
	}

	public function build( Param $param )
	{
		$merge = new ArgsMerge();

		$args = array();
		foreach ( $this->builder_parts as $builder_part ) {
			$args = $merge->run(
				$args,
				$builder_part->build( $param )
			);
		}

		return $args;
	}

	// ====================================================================== //

	/**
	 * @param    QueryBuilderPart[]    $builder_parts
	 */
	public static function combine( array $builder_parts ): self
	{
		return new self( $builder_parts );
	}

	/**
	 * @param    QueryBuilderPart    $builder_par
	 */
	public function add_part( QueryBuilderPart $builder_part ): self
	{
		$this->builder_parts[] = $builder_part;

		return $this;
	}
}
