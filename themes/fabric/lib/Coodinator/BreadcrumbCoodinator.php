<?php
namespace Fabric\Coodinator;


/**
 * パンくずリストの表示をカスタマイズ
 *
 * 投稿詳細ページにいるときにターム一覧へのリンクを表示させないようにする
 */
class BreadcrumbCoodinator
{
	public function __construct()
	{
		$path = 'other/breadcrumb';
		add_action( "arkhe_pre_get_part__{$path}", function() {
			add_filter( 'get_the_terms', array( $this, '__return_empty_array' ) );
		} );
		add_action( "arkhe_did_get_part__{$path}", function() {
			remove_filter( 'get_the_terms', array( $this, '__return_empty_array' ) );
		} );
	}

	/**
	 * @return    array
	 */
	public function __return_empty_array(): array
	{
		return array();
	}
}
