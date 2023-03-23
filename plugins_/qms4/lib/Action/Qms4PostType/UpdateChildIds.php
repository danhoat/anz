<?php

namespace QMS4\Action\Qms4PostType;

use QMS4\Area\AreaTree;
use QMS4\Area\AreaTreeFactory;
use QMS4\PostMeta\AreaChildIds;


class UpdateChildIds
{
	/**
	 * @return    void
	 */
	public function __invoke(): void
	{
		$factory = new AreaTreeFactory();
		$area_tree = $factory->create();

		$this->update( $area_tree );
	}

	private function update( AreaTree $area_tree ): void
	{
		foreach ( $area_tree as list( $wp_post, $sub_tree ) ) {
			$child_ids = $area_tree->child_ids( $wp_post->ID );
			AreaChildIds::update_post_meta( $wp_post->ID, $child_ids );

			if ( ! $sub_tree->is_empty() ) { $this->update( $sub_tree ); }
		}
	}
}
