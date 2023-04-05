<?php

namespace QMS4\Action\PostTyeps;

use QMS4\QueryString\QueryString;
use QMS4\TaxonomyMeta\TaxonomyMetaFactory;


class SetTaxonomyFilter
{
	/** @var    array<string,array> */
	private $taxonomy_metas;

	/** @var    QueryString */
	private $query_string;

	public function __construct()
	{
		$factory = new TaxonomyMetaFactory();

		$taxonomy_metas = array();
		$query_vars = array();
		foreach ( $factory->all() as $taxonomy_meta ) {
			// クエリ文字列が設定されていないタクソノミーは絞り込み対象にしない
			if ( empty( $taxonomy_meta->query() ) ) { continue; }

			$post_type = $taxonomy_meta->object_name();

			if ( ! isset( $taxonomy_metas[ $post_type ] ) ) {
				$taxonomy_metas[ $post_type ] = array();
			}

			$taxonomy_metas[ $post_type ][] = $taxonomy_meta;
			$query_vars[] = $taxonomy_meta->query();
		}
		$query_vars = array_unique( $query_vars );

		$this->taxonomy_metas = $taxonomy_metas;
		$this->query_string = QueryString::from_global_get( $_GET, ...$query_vars );
	}

	/**
	 * @param    \WP_Query    $query
	 * @return    void
	 */
	public function __invoke( \WP_Query $query ): void
	{
		if ( is_admin() ) { return; }
		if ( ! $query->is_main_query() ) { return; }
		if ( ! $query->is_archive() ) { return; }

		$post_type = $query->get( 'post_type' );
		$post_type = is_array( $post_type ) ? $post_type[ 0 ] : $post_type;

		if ( empty( $this->taxonomy_metas[ $post_type ] ) ) { return; }

		$tax_query = array();
		foreach ( $this->taxonomy_metas[ $post_type ] as $taxonomy_meta ) {
			$query_var = $taxonomy_meta->query();

			// if ( empty( $query->query_vars[ $query_var ] ) ) { continue; }

			$query_cond = $this->query_string->get( $query_var );
			$in = $query_cond->in();
			$not_in = $query_cond->not_in();

			if ( empty( $in ) && empty( $not_in ) ) { continue; }

			$query_part = array();

			if ( ! empty( $in ) && ! empty( $not_in ) ) {
				$query_part['relation'] = 'AND';
			}

			if ( ! empty( $in ) ) {
				$query_part[] = array(
					'taxonomy' => $taxonomy_meta->taxonomy(),
					'field' => 'slug',
					'terms' => $in,
					'operator' => 'IN',
				);
			}

			if ( ! empty( $not_in ) ) {
				$query_part[] = array(
					'taxonomy' => $taxonomy_meta->taxonomy(),
					'field' => 'slug',
					'terms' => $not_in,
					'operator' => 'NOT IN',
				);
			}

			$tax_query[] = $query_part;
		}

		if ( empty( $tax_query ) ) { return; }

		if ( empty( $query->get( 'tax_query' ) ) ) {
			$query->set( 'tax_query', $tax_query );
		} else {
			$new_tax_query = array_merge(
				$query->get( 'tax_query' ),
				$tax_query
			);

			$query->set( 'tax_query', $new_tax_query );
		}
	}
}
