<?php

namespace QMS4\Coodinator;

use QMS4\Action\Qms4PostType\AddCapabilities;
use QMS4\Action\Qms4PostType\UpdateChildIds;
use QMS4\Action\Qms4PostType\UpdateRefCount;
use QMS4\Action\Qms4PostType\RegisterFieldGroups;
use QMS4\Action\Qms4PostType\RegisterAreaMasterPostType;
use QMS4\Action\Qms4PostType\RegisterPostMeta;
use QMS4\Action\Qms4PostType\RegisterPostType;
use QMS4\Action\Qms4PostType\RemoveCapabilities;
use QMS4\PostTypeMeta\PostTypeMeta;


class Qms4PostTypeCoodinator
{
	/** @var    PostTypeMeta[]|null */
	private $post_type_metas = null;

	public function __construct()
	{
		add_action( 'activate_' . QMS4_BASENAME, new AddCapabilities() );
		add_action( 'deactivate_' . QMS4_BASENAME, new RemoveCapabilities() );

		add_action( 'init', new RegisterPostType() );

		$post_type_metas = $this->post_type_metas();
		add_action( 'init', new RegisterAreaMasterPostType( $post_type_metas ) );

		$post_type = 'qms4__area_master';
		add_action( "save_post_{$post_type}", new UpdateChildIds() );
		add_action( "save_post", new UpdateRefCount( $post_type_metas ), 10, 2 );

		call_user_func( new RegisterPostMeta() );

		add_action( 'init', new RegisterFieldGroups() );
	}

	// ====================================================================== //

	/**
	 * @return    PostTypeMeta[]
	 */
	private function post_type_metas()
	{
		if ( ! is_null( $this->post_type_metas ) ) { return $this->post_type_metas; }

		$query = new \WP_Query( array(
			'post_type' => 'qms4',
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1,
		) );

		$post_type_metas = array();
		foreach ( $query->posts as $wp_post ) {
			$post_type_metas[] = new PostTypeMeta( $wp_post );
		}

		$this->post_type_metas = $post_type_metas;
		return $this->post_type_metas;
	}
}
