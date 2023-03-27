<?php

namespace QMS4\PostForest\MapReduce;


interface MapperInterface
{
	/**
	 * @param    \WP_Post    $wp_post
	 * @return    array<string,mixed>
	 */
	public function map( \WP_Post $wp_post ): array;
}
