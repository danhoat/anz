<?php

namespace QMS4\Block;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\QueryString\QueryString;


class TermList
{
	/** @var    string */
	private $name = 'term-list';

	public function register()
	{
		register_block_type(
			QMS4_DIR . "/blocks/build/{$this->name}",
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param    array<string,mixed>    $attributes
	 * @param    string|null    $content
	 * @return    string
	 */
	public function render( array $attributes, ?string $content ): string
	{
		$taxonomy = $attributes[ 'taxonomy' ];
		$show_count = $attributes[ 'showCount' ];

		$terms = get_terms( array( 'taxonomy' => $taxonomy ) );

		$taxonomy_object = get_taxonomy( $taxonomy );
		$post_type = empty( $taxonomy_object->object_type )
			? 'post'
			: $taxonomy_object->object_type[ 0 ];

		$query_keys = $this->all_query_keys( $post_type, $taxonomy );
		$query_key = $query_keys[ $taxonomy ];

		$query_string = QueryString::from_global_get(
			$_GET,
			... array_values( $query_keys )
		);

		ob_start();
		if ( file_exists( QMS4_DIR . "/blocks/templates/{$this->name}.php" ) ) {
			require( QMS4_DIR . "/blocks/templates/{$this->name}.php" );
		}
		return ob_get_clean();
	}

	// /**
	//  * @param    string    $post_type
	//  * @param    string    $taxonomy_name
	//  * @return    string
	//  */
	// private function query_key( string $post_type, string $taxonomy_name ): string
	// {
	// 	$post_type_meta = PostTypeMeta::from_name( array( $post_type ) );
	// 	$taxonomies = $post_type_meta->taxonomies();

	// 	foreach ( $taxonomies as $taxonomy ) {
	// 		if ( $taxonomy->taxonomy() == $taxonomy_name ) {
	// 			return $taxonomy->query();
	// 		}
	// 	}

	// 	return $taxonomy_name;
	// }

	/**
	 * @param    string    $post_type
	 * @param    string    $taxonomy_name
	 * @return    string[]
	 */
	private function all_query_keys( string $post_type, string $taxonomy_name ): array
	{
		$post_type_meta = PostTypeMeta::from_name( array( $post_type ) );
		$taxonomies = $post_type_meta->taxonomies();

		$query_keys = array();
		foreach ( $taxonomies as $taxonomy ) {
			$query_keys[ $taxonomy->taxonomy() ] = $taxonomy->query();
		}

		return $query_keys;
	}
}
