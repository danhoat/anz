<?php

namespace QMS4\Coodinator;

use QMS4\Action\Columns\AddColumnsAreaMaster;
use QMS4\Action\Columns\AddColumnsBlockTemplate;
use QMS4\Action\Columns\AddColumnsEvent;
use QMS4\Action\Columns\AddColumnsQms4;
use QMS4\Action\Columns\AddColumnsSitePart;
use QMS4\Action\Columns\AddColumnsTermColor;
use QMS4\Action\Columns\DisplayColumnEventDate;
use QMS4\Action\Columns\DisplayColumnEventTimetable;
use QMS4\Action\Columns\DisplayColumnRelPostType;
use QMS4\Action\Columns\DisplayColumnMemo;
use QMS4\Action\Columns\DisplayColumnSitePartPostId;
use QMS4\Action\Columns\DisplayColumnSlug;
use QMS4\Action\Columns\DisplayColumnTermColor;
use QMS4\Action\Columns\EnqueueScript;
use QMS4\Action\Columns\EnqueueStyle;
use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\TaxonomyMeta\TaxonomyMeta;
use QMS4\TaxonomyMeta\TaxonomyMetaFactory;


class ColumnsCoodinator
{
	/** @var    TaxonomyMeta[] */
	private $taxonomy_metas = null;

	/** @var    PostTypeMeta */
	private $post_type_metas = null;

	public function __construct()
	{
		$post_type = 'qms4';
		add_filter( "manage_{$post_type}_posts_columns", new AddColumnsQms4() );
		add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnMemo(), 10, 2 );

		$post_type = 'qms4__block_template';
		add_filter( "manage_{$post_type}_posts_columns", new AddColumnsBlockTemplate() );
		add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnRelPostType(), 10, 2 );
		add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnMemo(), 10, 2 );

		$post_type = 'qms4__site_part';
		add_filter( "manage_{$post_type}_posts_columns", new AddColumnsSitePart() );
		add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnMemo(), 10, 2 );
		add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnSitePartPostId(), 10, 2 );

		$post_type = 'qms4__area_master';
		add_filter( "manage_{$post_type}_posts_columns", new AddColumnsAreaMaster() );
		add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnMemo(), 10, 2 );
		add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnSlug(), 10, 2 );

		foreach ( $this->taxonomy_metas() as $taxonomy_meta ) {
			$taxonomy = $taxonomy_meta->taxonomy();
			add_filter( "manage_edit-{$taxonomy}_columns", new AddColumnsTermColor( $taxonomy_meta ) );
			add_filter( "manage_{$taxonomy}_custom_column", new DisplayColumnTermColor( $taxonomy_meta ), 10, 3 );
		}

		foreach ( $this->post_type_metas() as $post_type_meta ) {
			if ( $post_type_meta->func_type() === 'event' ) {
				$post_type = $post_type_meta->name();
				add_filter( "manage_{$post_type}_posts_columns", new AddColumnsEvent( $post_type_meta ) );
				add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnEventDate( $post_type_meta ), 10, 2 );
				add_action( "manage_{$post_type}_posts_custom_column", new DisplayColumnEventTimetable( $post_type_meta ), 10, 2 );
			}
		}

		add_action( 'admin_enqueue_scripts', new EnqueueScript() );
		add_action( 'admin_enqueue_scripts', new EnqueueStyle() );
	}

	// ====================================================================== //

	/**
	 * @return    TaxonomyMeta[]
	 */
	private function taxonomy_metas(): array
	{
		if ( ! is_null( $this->taxonomy_metas ) ) {
			return $this->taxonomy_metas;
		}

		$factory = new TaxonomyMetaFactory();

		return $this->taxonomy_metas = $factory->all();
	}

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
