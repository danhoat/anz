<?php

namespace QMS4\Action\PostMeta;

use QMS4\TaxonomyMeta\TaxonomyMeta;
use QMS4\PostMeta\TermColor;


class RegisterTermColorMetaBox
{
	/** @var    TaxonomyMeta[] */
	private $taxonomy_metas;

	/**
	 * @param    TaxonomyMeta[]    $taxonomy_metas
	 */
	public function __construct( array $taxonomy_metas )
	{
		$this->taxonomy_metas = $taxonomy_metas;
	}

	/**
	 * @return    void
	 */
	public function __invoke()
	{
		foreach ( $this->taxonomy_metas as $taxonomy_meta ) {
			if ( $taxonomy_meta->color_available() ) {
				( new TermColor( $taxonomy_meta->taxonomy() ) )->register_meta();
			}
		}
	}
}
