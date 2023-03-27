<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\Taxonomy\TaxonomyFactory;


class RegisterTaxonomies
{
	/** @var    PostTypeMeta[] */
	private $post_type_metas;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$this->post_type_metas = $post_type_metas;
	}

	/**
	 * @return    void
	 */
	public function __invoke(): void
	{
		$factory = new TaxonomyFactory();

		foreach ( $this->post_type_metas as $post_type_meta ) {
			$taxonomies = $factory->create( $post_type_meta );
			foreach ( $taxonomies as $taxonomy ) {
				$taxonomy->register();
			}
		}
	}
}
