<?php

namespace QMS4\Area;

use QMS4\Area\AreaTree;
use QMS4\Area\MapReduce\AreaMapper;
use QMS4\Area\MapReduce\AreaReducer;
use QMS4\PostForest\PostForestFactory;

class _AreaTreeFactory
{
	/**
	 * @return    AreaTree
	 */
	public function create(): AreaTree
	{
		$factory = new PostForestFactory();
		$area_tree = $factory->from_post_type( 'qms4__area_master' );

		$area_tree->init_fields( new AreaMapper(), new AreaReducer() );

		return new AreaTree( $area_tree );
	}
}
