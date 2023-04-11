<?php

namespace QMS4\TaxonomyMeta;

use QMS4\TaxonomyMeta\Factory\All;
use QMS4\TaxonomyMeta\Factory\FromPostType;
use QMS4\TaxonomyMeta\TaxonomyMeta;


class TaxonomyMetaFactory
{
	/**
	 * @param    string|string[]    $post_type
	 * @return    TaxonomyMeta[]
	 */
	public function from_post_type( $post_type ): array
	{
		$post_types = is_array( $post_type ) ? $post_type : array( $post_type );

		$factory = new FromPostType();

		$taxonomy_metas = array();
		foreach ( $post_types as $post_type ) {
			$taxonomy_metas = array_merge(
				$taxonomy_metas,
				$factory->create( $post_type )
			);
		}

		return $taxonomy_metas;
	}

	/**
	 * @return    TaxonomyMeta[]
	 */
	public function all(): array
	{
		$factory = new All();
		return $factory->create();
	}
}
