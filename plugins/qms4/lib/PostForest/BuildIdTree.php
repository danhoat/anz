<?php

namespace QMS4\PostForest;


class BuildIdTree
{
	/**
	 * @param    array<int,array>    $child_ids_dict
	 * @param    int    $post_id
	 * @return    array<string,mixed>
	 */
	public static function build( array $child_ids_dict, int $post_id ): array
	{
		$child_ids = isset( $child_ids_dict[ $post_id ] )
			? $child_ids_dict[ $post_id ]
			: array();

		$sub_trees = array();
		foreach ( $child_ids as $child_id )	{
			$sub_trees[] = self::build( $child_ids_dict, $child_id );
		}

		return array(
			'post_id' => $post_id,
			'sub_trees' => $sub_trees
		);
	}
}
