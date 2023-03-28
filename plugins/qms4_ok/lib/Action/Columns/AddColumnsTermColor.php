<?php

namespace QMS4\Action\Columns;

use QMS4\TaxonomyMeta\TaxonomyMeta;


class AddColumnsTermColor
{
	/** @var    TaxonomyMeta */
	private $taxonomy_meta;

	/**
	 * @param    TaxonomyMeta    $taxonomy_meta
	 */
	public function __construct( TaxonomyMeta $taxonomy_meta )
	{
		$this->taxonomy_meta = $taxonomy_meta;
	}

	/**
	 * @param    array<string,string>    $post_columns
	 * @return    array<string,string>
	 */
	public function __invoke( array $post_columns ): array
	{
		if ( ! $this->taxonomy_meta->color_available() ) {
			return $post_columns;
		}

		$post_columns = array_merge(
			array_slice( $post_columns, 0, 2 ),
			array( 'qms4__term__color' => '色' ),
			array_slice( $post_columns, 2 ),
		);

		return $post_columns;
	}
}
