<?php

namespace QMS4\Action\PostTyeps;


class SetKeywordSearchFilter
{
	public function __invoke( string $where, \WP_Query $query ): string
	{
		if ( is_admin() ) { return $where; }
		if ( ! $query->is_main_query() ) { return $where; }

		if ( empty( $query->query_vars[ 'q' ] ) ) { return $where; }

		global $wpdb;

		$search_words = mb_convert_kana( $query->query_vars[ 'q' ], 's' );
		$search_words = preg_split( '/[\s]+/', $search_words, -1, PREG_SPLIT_NO_EMPTY );

		$sql = "(
			{$wpdb->posts}.post_title LIKE %s
			OR {$wpdb->posts}.post_content LIKE %s
			OR {$wpdb->posts}.ID IN (
				SELECT distinct r.object_id
				FROM {$wpdb->term_relationships} AS r
					INNER JOIN {$wpdb->term_taxonomy} AS tt ON r.term_taxonomy_id = tt.term_taxonomy_id
					INNER JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id
				WHERE
					t.name LIKE %s
					OR t.slug LIKE %s
					OR tt.description LIKE %s
			)
			OR {$wpdb->posts}.ID IN (
				SELECT distinct p.post_id
				FROM {$wpdb->postmeta} AS p
				WHERE p.meta_value LIKE %s
			)
		)";

		$wheres = array();
		foreach ( $search_words as $search_word ) {
			$search_word = '%' . $wpdb->esc_like( $search_word ) . '%';

			$wheres[] = $wpdb->prepare( $sql, ...array_fill( 0, 6, $search_word ) );
		}

		return empty( $wheres )
			? $where
			: $where . ' AND ' . join( ' AND ', $wheres );
	}
}
