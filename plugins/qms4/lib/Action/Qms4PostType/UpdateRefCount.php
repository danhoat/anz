<?php

namespace QMS4\Action\Qms4PostType;

use QMS4\PostMeta\Area;
use QMS4\PostTypeMeta\PostTypeMeta;


class UpdateRefCount
{
	/** @var    PostTypeMeta[] */
	private $post_type_metas;

	/** @var    string[] */
	private $post_types;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$_post_type_metas = array();
		$post_types = array();
		foreach ( $post_type_metas as $post_type_meta ) {
			if ( in_array( 'area', $post_type_meta->components(), true ) ) {
				$_post_type_metas[] = $post_type_meta;
				$post_types[] = $post_type_meta->name();
			}
		}

		$this->post_type_metas = $_post_type_metas;
		$this->post_types = $post_types;
	}

	/**
	 * @return    void
	 */
	public function __invoke( int $_post_id, \WP_Post $post ): void
	{
		if ( ! in_array( $post->post_type, $this->post_types, true ) ) {
			return;
		}

		$ref_counts = array();
		foreach ( $this->post_types as $post_type ) {
			$ref_counts[ $post_type ] = $this->ref_count( $post_type );
		}

		$this->update( $ref_counts );
	}

	/**
	 * @param    string    $post_type
	 * @return    array<int,int>
	 */
	private function ref_count( string $post_type ): array
	{
		$query = new \WP_Query( array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
		) );

		$ref_count = array();
		foreach ( $query->posts as $post_id ) {
			$area_id = Area::get_post_meta( $post_id );

			if ( isset( $ref_count[ $area_id ] ) ) {
				$ref_count[ $area_id ]++;
			} else {
				$ref_count[ $area_id ] = 1;
			}
		}

		return $ref_count;
	}

	/**
	 * @param    array<string,array>
	 */
	private function update( array $ref_counts ): void
	{
		$query = new \WP_Query( array(
			'post_type' => 'qms4__area_master',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
		) );

		foreach ( $query->posts as $area_id ) {
			$ref_count = array();
			foreach ( $this->post_types as $post_type ) {
				$ref_count[ $post_type ] = empty( $ref_counts[ $post_type ][ $area_id ] )
					? 0
					: $ref_counts[ $post_type ][ $area_id ];
			}

			update_post_meta( $area_id, 'qms4__area__ref_count', $ref_count );
		}
	}
}
