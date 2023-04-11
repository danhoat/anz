<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;
use QMS4\PostMeta\Area;
use QMS4\Util\FindInPostTree;


class AreaQuery extends QueryBuilderPart
{
	/**
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array(
			'area' => '',
		);
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		if ( empty( $param[ 'area' ] )) { return array(); }

		list( $in__slugs, $not_in__slugs ) = $this->query_slugs( $param[ 'area' ] );
		list( $in__ids, $not_in__ids ) = $this->area_post_ids( $in__slugs, $not_in__slugs );

		if ( ! empty( $in__ids ) && ! empty( $not_in__ids ) ) {
			$in__ids = array_diff( $in__ids, $not_in__ids );

			return array(
				'meta_query' => array(
					array(
						'key' => Area::KEY,
						'value' => $in__ids,
						'compare' => 'IN',
					),
				),
			);
		} elseif ( ! empty( $in__ids ) && empty( $not_in__ids ) ) {
			return array(
				'meta_query' => array(
					array(
						'key' => Area::KEY,
						'value' => $in__ids,
						'compare' => 'IN',
					),
				),
			);
		} elseif ( empty( $in__ids ) && ! empty( $not_in__ids ) ) {
			return array(
				'meta_query' => array(
					array(
						'key' => Area::KEY,
						'value' => $not_in__ids,
						'compare' => 'NOT IN',
					),
				),
			);
		}
	}

	// ====================================================================== //

	/**
	 * @param    string|string[]    $query_str
	 * @return    array[]
	 */
	private function query_slugs( $query_str )
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
	 * @param    string[]    $in__slugs
	 * @param    string[]    $not_in__slugs
	 * @return    array[]
	 */
	private function area_post_ids( array $in__slugs, array $not_in__slugs ): array
	{
		$query = new \WP_Query( array(
			'post_type' => 'qms4__area_master',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		) );

		$tree = new FindInPostTree( $query->posts );

		$in__ids = $tree->find( function ( \WP_Post $wp_post ) {
			return urldecode( $wp_post->post_name );
		}, $in__slugs );

		$not_in__ids = $tree->find( function ( \WP_Post $wp_post ) {
			return urldecode( $wp_post->post_name );
		}, $not_in__slugs );

		return array( $in__ids, $not_in__ids );
	}
}
