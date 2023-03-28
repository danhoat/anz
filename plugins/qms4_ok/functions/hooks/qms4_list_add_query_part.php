<?php

use QMS4\QueryBuilderPart\QueryBuilderPart;


/**
 * @param    QueryBuilderPart|string    $query_part
 * @param    string|string[]    $post_types
 */
function qms4_list_add_query_part(
	$query_part,
	$post_types = array()
)
{
	if ( is_string( $query_part ) ) {
		if ( class_exists( $query_part ) ) {
			$query_part = new $query_part();
		} else {
			throw new \InvalidArgumentException( "指定されたクラスが見つかりませんでした。: \$class_name: {$query_part}" );
		}
	}

	if ( ! ( $query_part instanceof QueryBuilderPart ) ) {
		$class_name = get_class( $query_part );
		throw new \InvalidArgumentException( "QueryBuilderPart を継承したクラスを指定してください。: \$query_part :: {$class_name}" );
	}

	$post_types = is_array( $post_types ) ? $post_types : array( $post_types );

	add_filter(
		'qms4_list_queries',
		function ( $query_builders, $_post_types ) use ( $query_part, $post_types ) {
			if ( ! empty( $post_types ) ) {
				$intersect = array_intersect( $_post_types, $post_types );
				if ( empty( $intersect ) ) { return $query_builders; }
			}

			$query_builders[] = $query_part;

			return $query_builders;
		},
		/* $priority = */ 10,
		/* accepted_args = */ 2
	);
}
