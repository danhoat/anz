<?php

namespace QMS4\Coodinator;

use QMS4\Action\PostMeta\EnqueueEventStyle;
use QMS4\Action\PostMeta\RegisterAreaMetaBox;
use QMS4\Action\PostMeta\RegisterEventMetaBox;
use QMS4\Action\PostMeta\RegisterEventPostMeta;
use QMS4\Action\PostMeta\RegisterEventRestField;
use QMS4\Action\PostMeta\RegisterMemoMetaBox;
use QMS4\Action\PostMeta\RegisterTermColorMetaBox;
use QMS4\Action\PostMeta\SaveEventPostMeta;
use QMS4\Action\PostMeta\ShowSlugMetaBox;
use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\TaxonomyMeta\TaxonomyMetaFactory;


class PostMetaCoodinator
{
	/** @var    PostTypeMeta[]|null */
	private $post_type_metas = null;

	public function __construct()
	{
		call_user_func( new RegisterEventPostMeta( 'qms4__block_template' ) );
		add_action( 'add_meta_boxes', new RegisterEventMetaBox( 'qms4__block_template' ), 10, 2 );
		add_action( 'save_post', new SaveEventPostMeta( 'qms4__block_template' ), 10, 2 );
		add_action( 'rest_api_init', new RegisterEventRestField( 'qms4__block_template' ) );
		add_filter( 'default_hidden_meta_boxes', new ShowSlugMetaBox( 'qms4__area_master' ), 10, 2 );

		foreach ( $this->post_type_metas() as $post_type_meta ) {
			if ( $post_type_meta->func_type() === 'event' ) {
				call_user_func( new RegisterEventPostMeta( $post_type_meta->name() ) );
				add_action( 'add_meta_boxes', new RegisterEventMetaBox( $post_type_meta->name() ), 10, 2 );
				add_action( 'save_post', new SaveEventPostMeta( $post_type_meta->name() ), 10, 2 );
				add_action( 'rest_api_init', new RegisterEventRestField( $post_type_meta->name() ) );
				add_action( 'admin_enqueue_scripts', new EnqueueEventStyle( $post_type_meta->name() ) );
			}


			$components = $post_type_meta->components();
			if ( in_array( 'memo', $components, true ) ) {
				add_action( 'acf/init', new RegisterMemoMetaBox( $post_type_meta ) );
			}
			if ( in_array( 'area', $components, true ) ) {
				add_action( 'acf/init', new RegisterAreaMetaBox( $post_type_meta ) );
			}


			$factory = new TaxonomyMetaFactory();
			$taxonomy_metas = $factory->from_post_type( $post_type_meta->name() );

			if ( ! empty( $taxonomy_metas ) ) {
				add_action( 'acf/init', new RegisterTermColorMetaBox( $taxonomy_metas ) );
			}
		}
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
