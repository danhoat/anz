<?php

namespace QMS4\QueryBuilderPart;
use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class PostInQuery extends QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'post__in' => array(),
			'post__not_in' => array(),
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		$post__in = $param[ 'post__in' ];
		if ( ! is_array( $post__in ) ) {
			$post__in = empty( $post__in ) ? array() : array( $post__in );
		}

		$post__not_in = $param[ 'post__not_in' ];
		if ( ! is_array( $post__not_in ) ) {
			$post__not_in = empty( $post__not_in ) ? array() : array( $post__not_in );
		}


		if ( ! empty( $post__in ) && ! empty( $post__not_in ) ) {
			$post__in = array_diff( $post__in, $post__not_in );

			return array( 'post__in' => $post__in );
		}

		if ( ! empty( $post__in ) ) {
			return array( 'post__in' => $post__in );
		}

		if ( ! empty( $post__not_in ) ) {
			return array( 'post__not_in' => $post__not_in );
		}

		return array();
	}
}
