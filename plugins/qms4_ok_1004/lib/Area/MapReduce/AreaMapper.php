<?php

namespace QMS4\Area\MapReduce;

use QMS4\PostForest\MapReduce\MapperInterface;

class AreaMapper implements MapperInterface
{
	public function map( \WP_Post $wp_post ): array
	{
		$ref_count = get_post_meta( $wp_post->ID, 'qms4__area__ref_count', true );
		$ref_count = $ref_count ?: array();

		return array(
			'length' => 1,
			'ref_count' => $ref_count,
			'child_ids' => array( $wp_post->ID ),
		);
	}
}
