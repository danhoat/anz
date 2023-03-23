<?php

namespace QMS4\Action\PostTyeps;

use QMS4\Area\AreaIdsRepository;
use QMS4\PostMeta\Area;
use QMS4\PostTypeMeta\PostTypeMeta;


class SetAreaFilter
{
	/** @var    string[] */
	private $area_master_available;

	/**
	 * @param    PostTypeMeta[]    $post_type_metas
	 */
	public function __construct( array $post_type_metas )
	{
		$area_master_available = array();
		foreach ( $post_type_metas as $post_type_meta ) {
			if ( in_array( 'area', $post_type_meta->components(), true ) ) {
				$area_master_available[] = $post_type_meta->name();
			}
		}

		$this->area_master_available = $area_master_available;
	}

	/**
	 * @param    \WP_Query    $query
	 * @return    void
	 */
	public function __invoke( \WP_Query $query ): void
	{
		if ( is_admin() ) { return; }
		if ( ! $query->is_main_query() ) { return; }
		if ( ! $query->is_archive() ) { return; }
		if ( empty( $query->query_vars[ 'area' ] ) ) { return; }

		$post_type = $query->get( 'post_type' );
		$post_type = is_array( $post_type ) ? $post_type[ 0 ] : $post_type;

		if ( ! in_array( $post_type, $this->area_master_available, true ) ) {
			return;
		}

		$slugs = explode( ',', $query->query_vars[ 'area' ] );
		$slugs = array_filter( array_map( 'trim', $slugs ) );

		$repository = new AreaIdsRepository();
		$area_ids = $repository->find_by_slug( $slugs );

		$meta_query = array(
			'key' => Area::KEY,
			'value' => $area_ids,
			'compare' => 'IN',
		);

		if ( empty( $query->get( 'meta_query' ) ) ) {
			$query->set( 'meta_query', array( $meta_query ) );
		} else {
			$new_meta_query = $query->get( 'meta_query' );
			$new_meta_query[] = $meta_query;
			$query->set( 'meta_query', $new_meta_query );
		}
	}
}
