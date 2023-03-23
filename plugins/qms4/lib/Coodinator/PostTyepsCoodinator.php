<?php

namespace QMS4\Coodinator;

use QMS4\PostTypeMeta\PostTypeMeta;

use QMS4\Action\PostTyeps\AddAdminEventCalendarPage;
use QMS4\Action\PostTyeps\AddPermastruct;
use QMS4\Action\PostTyeps\AddTelLink;
use QMS4\Action\PostTyeps\DeactivateBlockEditor;
use QMS4\Action\PostTyeps\DefaultContent;
use QMS4\Action\PostTyeps\DeleteSchedulePost;
use QMS4\Action\PostTyeps\PostTypeLink;
use QMS4\Action\PostTyeps\RegisterPostType;
use QMS4\Action\PostTyeps\RegisterTaxonomies;
use QMS4\Action\PostTyeps\SetArchiveLayout;
use QMS4\Action\PostTyeps\SetAreaFilter;
use QMS4\Action\PostTyeps\SetKeywordSearchFilter;
use QMS4\Action\PostTyeps\SetPostDateFilter;
use QMS4\Action\PostTyeps\SetPostsPerPage;
use QMS4\Action\PostTyeps\SetQueryVars;
use QMS4\Action\PostTyeps\SetRevisionsToKeep;
use QMS4\Action\PostTyeps\SetTaxonomyFilter;
use QMS4\Action\PostTyeps\SetValidEventFilter;
use QMS4\Action\PostTyeps\ShowSidebar;


class PostTyepsCoodinator
{
	/** @var    PostTypeMeta[]|null */
	private $post_type_metas = null;

	public function __construct()
	{
		$post_type_metas = $this->post_type_metas();

		// カスタム投稿タイプ登録/タクソノミー登録
		add_action( 'init', new RegisterPostType( $post_type_metas ) );
		add_action( 'init', new RegisterTaxonomies( $post_type_metas ) );
		add_filter( 'use_block_editor_for_post', new DeactivateBlockEditor( $post_type_metas ), 10, 2 );

		// リビジョン保存数を設定
		add_filter( 'wp_revisions_to_keep', new SetRevisionsToKeep( $post_type_metas ), 10, 2 );

		// パーマリンク設定
		add_action( 'init', new AddPermastruct( $post_type_metas ) );
		add_filter( 'post_type_link', new PostTypeLink( $post_type_metas ), 10, 2 );

		// 投稿本文のテンプレートを設定
		add_filter( 'default_content', new DefaultContent( $post_type_metas ), 10, 2 );

		add_filter( 'the_content', new AddTelLink(), 20 );

		// リストのレイアウトを制御
		add_filter( 'arkhe_list_type_on_archive', new SetArchiveLayout( $post_type_metas ), 10, 2);

		// サイドカラムの表示を制御
		add_filter( 'arkhe_is_show_sidebar', new ShowSidebar( $post_type_metas ) );

		// メインクエリ設定
		add_filter( 'query_vars', new SetQueryVars() );
		add_action( 'pre_get_posts', new SetValidEventFilter() );
		add_action( 'pre_get_posts', new SetPostDateFilter() );
		add_action( 'pre_get_posts', new SetTaxonomyFilter() );
		add_action( 'pre_get_posts', new SetAreaFilter( $post_type_metas ) );
		add_action( 'pre_get_posts', new SetPostsPerPage( $post_type_metas ) );
		add_filter( 'posts_where', new SetKeywordSearchFilter( $post_type_metas ), 10, 2 );

		// 管理画面側のイベントカレンダー画面を登録
		// add_action( 'admin_menu', new AddAdminEventCalendarPage( $post_type_metas ) );

		// イベント投稿が削除されたときの後処理
		add_action( 'before_delete_post', new DeleteSchedulePost(), 10, 2 );
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
