<?php

namespace QMS4\Taxonomy;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\Taxonomy\Category;
use QMS4\Taxonomy\Taxonomy;


class TaxonomyFactory
{
	/**
	 * @param    PostTypeMeta    $post_type_meta
	 * @return    Taxonomy[]
	 */
	public function create( PostTypeMeta $post_type_meta )
	{
		$taxonomies = array();
		foreach ( $post_type_meta->taxonomies() as $name => $_ ) {
			$taxonomies[] = new Category( $post_type_meta, $name );
		}

		return $taxonomies;
	}
}
