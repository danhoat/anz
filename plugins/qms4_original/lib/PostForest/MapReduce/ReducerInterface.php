<?php

namespace QMS4\PostForest\MapReduce;


interface ReducerInterface
{
	/**
	 * @param    array<string,mixed>    $self_value
	 * @param    array[]    $child_values
	 */
	public function reduce( array $self_value, array $child_values ): array;
}
