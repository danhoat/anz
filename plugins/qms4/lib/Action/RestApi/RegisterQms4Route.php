<?php

namespace QMS4\Action\RestApi;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\TaxonomyMeta\TaxonomyMeta;


class RegisterQms4Route
{
	public function __invoke()
	{
		register_rest_route(
			'qms4/v1',
			'/qms4/',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'get' ),
				'permission_callback' => array( $this, 'permission' ),
			)
		);
	}

	/**
	 * @param    \WP_REST_Request    $request
	 * @return    \WP_REST_Response|\WP_Error
	 */
	public function get( \WP_REST_Request $request )
	{
		$query = new \WP_Query( array(
			'post_type' => 'qms4',
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1,
		) );

		$items = array();
		foreach ( $query->posts as $wp_post ) {
			$post_type_meta = new PostTypeMeta( $wp_post );

			$items[] = array(
				'id' => $post_type_meta->id(),
				'name' => $post_type_meta->name(),
				'label' => $post_type_meta->label(),
				'is_public' => $post_type_meta->is_public(),
				'permalink_type' => $post_type_meta->permalink_type(),
				'func_type' => $post_type_meta->func_type(),
				'editor' => $post_type_meta->editor(),
				'cal_base_date' => $post_type_meta->cal_base_date(),
				'components' => $post_type_meta->components(),
				'taxonomies' => array_map( function( TaxonomyMeta $taxonomy_meta ) {
					return array(
						'taxonomy' => $taxonomy_meta->taxonomy(),
						'object_name' => $taxonomy_meta->object_name(),
						'name' => $taxonomy_meta->name(),
						'label' => $taxonomy_meta->label(),
						'query' => $taxonomy_meta->query(),
					);
				}, $post_type_meta->taxonomies() ),
				'orderby' => $post_type_meta->orderby(),
				'order' => $post_type_meta->order(),
				'new_date' => $post_type_meta->new_date(),
				'new_class' => $post_type_meta->new_class(),
				'posts_per_page' => $post_type_meta->posts_per_page(),
				'term_html' => $post_type_meta->term_html(),
			);
		}

		return $items;
	}

	/**
	 * @return    bool|\WP_Error
	 */
	public function permission()
	{
		// TODO: なんとかする
		return true;  // current_user_can( 'edit_post' );
	}
}
