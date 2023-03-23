<?php

namespace QMS4\Area\MapReduce;

use QMS4\PostForest\MapReduce\ReducerInterface;

class AreaReducer implements ReducerInterface
{
	/**
	 * @param    array<string,mixed>    $self_value
	 * @param    array[]    $child_values
	 * @return    array<string,mixed>
	 */
	public function reduce( array $self_value, array $child_values ): array
	{
		return array(
			'length' => $this->length( $self_value, $child_values ),
			'ref_count' => $this->ref_count( $self_value, $child_values ),
			'child_ids' => $this->child_ids( $self_value, $child_values ),
		);
	}

	/**
	 * @param    array<string,mixed>    $self_value
	 * @param    array[]    $child_values
	 * @return    int
	 */
	private function length( array $self_value, array $child_values ): int
	{
		$values = array_column( $child_values, 'length' );

		switch ( count( $values ) ) {
			case 0: return 1;
			case 1: return $values[ 0 ];
			default: return max( $values ) + 1;
		}
	}

	/**
	 * @param    array<string,mixed>    $self_value
	 * @param    array[]    $child_values
	 * @return    array<string,int>
	 */
	private function ref_count( array $self_value, array $child_values ): array
	{
		$value = $self_value[ 'ref_count' ];
		$values = array_column( $child_values, 'ref_count' );

		$ref_count = array();
		foreach ( $value as $post_type => $count ) {
			$counts = array_column( $values, $post_type );
			$ref_count[ $post_type ] = $count + array_sum( $counts );
		}

		return $ref_count;
	}

	/**
	 * @param    array<string,mixed>    $self_value
	 * @param    array[]    $child_values
	 * @return    int[]
	 */
	private function child_ids( array $self_value, array $child_values ): array
	{
		$value = $self_value[ 'child_ids' ];
		$values = array_column( $child_values, 'child_ids' );

		$child_ids = $value;
		foreach ( $values as $_value ) {
			$child_ids = array_merge( $child_ids, $_value );
		}

		return $child_ids;
	}
}
