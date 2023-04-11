<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class TermQuery extends QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'term' => array(),
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		$conds = array_filter( $param[ 'term' ] );

		if ( empty( $conds )) { return array(); }


		$tax_query = array();
		foreach ( $conds as $taxonomy => $query_str ) {
			$taxonomies = array();
			foreach ( $param[ 'post_type' ] as $post_type ) {
				$taxonomies[] = "{$post_type}__{$taxonomy}";
			}

			list( $in, $not_in ) = $this->terms( $query_str );

			$tax_query[] = $this->build_queries( $taxonomies, $in, $not_in );
		}

		return array(
			'tax_query' => $tax_query,
		);
	}

	/**
	 * @param    string|string[]    $query_str
	 * @return    array[]
	 */
	private function terms( $query_str ): array
	{
		$query_str = is_array( $query_str ) ? $query_str : array( $query_str );

		$query_strs = array();
		foreach ( $query_str as $str ) {
			$query_strs = array_merge( $query_strs , explode( ',', $str ) );
		}

		$in = array();
		$not_in = array();
		foreach ( $query_strs as $query ) {
			$query = trim( $query );

			if ( strlen( $query ) >= 2 && $query[0] === '-' ) {
				// $cat_str がマイナス記号 '-' から始まる場合はカテゴリ除外リスト $not_in に入れる
				$not_in[] = substr( $query, 1 );
			} else {
				// さもなくばカテゴリ指定リスト $in に入れる
				$in[] = $query;
			}
		}

		return array( $in, $not_in );
	}

	/**
	 * @param    string[]    $taxonomies
	 * @param    string[]    $in_slugs
	 * @param    string[]    $not_in_slugs
	 * @return    array
	 */
	private function build_queries( array $taxonomies, array $in_slugs, array $not_in_slugs ): array
	{
		$in_queries = array();
		$not_in_queries = array();

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! empty( $in_slugs ) ) {
				$in_queries[] = array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $in_slugs,
					'operator' => 'IN',
				);
			}

			if ( ! empty( $not_in_slugs ) ) {
				$not_in_queries[] = array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $not_in_slugs,
					'operator' => 'NOT IN',
				);
			}
		}

		$queries = array();

		if ( ! empty( $in_queries ) ) {
			$queries[] = array_merge(
				array( 'relation' => 'OR' ),
				$in_queries
			);
		}

		if ( ! empty( $not_in_queries ) ) {
			$queries[] = array_merge(
				array( 'relation' => 'OR' ),
				$not_in_queries
			);
		}

		return $queries;
	}
}
