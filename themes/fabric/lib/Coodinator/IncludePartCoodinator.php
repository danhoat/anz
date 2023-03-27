<?php
namespace Fabric\Coodinator;

use Fabric\Action\IncludePart\IncludePart;


/**
 * @see    https://arkhe-theme.com/ja/manual/hooks/
 */
class IncludePartCoodinator
{
	public function __construct()
	{
		add_action( 'wp', array( $this, 'add_action' ) );
	}

	/**
	 * @param    \WP    $wp
	 * @return    void
	 */
	public function add_action( \WP $wp ): void
	{
		$screen = $this->screen();

		if ( ! $screen ) { return; }

		// ヘッダー
		$part_name = 'before_header';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'after_header';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'header_bar_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'header_left_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'header_right_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// サイドバー
		$part_name = 'start_sidebar';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'end_sidebar';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// フッター
		$part_name = 'before_footer';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'start_footer_inner';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'start_footer_foot_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'after_footer';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// コンテンツ
		$part_name = 'start_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, "{$part_name}_before" ), 8 );  // パンくずリストの上
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );                // パンくずリストの下
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, "{$part_name}_after" ), 12 );  // パンくずリストの下

		$part_name = 'end_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// アーカイブ
		$part_name = 'start_archive_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'end_archive_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'before_archive_post_list';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// フロントページ
		$part_name = 'start_front_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'end_front_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'before_front_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'after_front_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// 固定ページ
		$part_name = 'start_page_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'end_page_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'before_page_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'after_page_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// 投稿ページ
		$part_name = 'start_entry_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'end_entry_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'before_entry_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'after_entry_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ), 8 );

		$part_name = 'start_entry_foot';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'end_entry_foot';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );


		// 404ページ
		$part_name = 'start_404_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'end_404_main';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'before_404_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );

		$part_name = 'after_404_content';
		add_action( "arkhe_{$part_name}", new IncludePart( $screen, $part_name ) );
	}

	/**
	 * @return    string|null
	 */
	private function screen(): ?string
	{
		if ( is_404() ) { return '404'; }

		if ( is_front_page() ) { return 'front_page'; }

		if ( is_page() ) { return 'page'; }

		if ( is_archive() ) { return get_query_var( 'post_type' ); }

		if ( is_single() ) {
			global $post;
			return $post->post_type;
		}

		return null;
	}
}
