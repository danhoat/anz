<?php

require_once( __DIR__ . '/lib/autoload.php' );

use Fabric\Util\GetPart;
use Fabric\Util\RegisterBlockStyle;
use Fabric\Util\RegisterSidebar;
use Fabric\Util\SetImageEditor;
use Fabric\Util\WpFooter;
use Fabric\Util\WpHead;

new Fabric\Coodinator\BreadcrumbCoodinator();
new Fabric\Coodinator\StyleCoodinator();
new Fabric\Coodinator\ScriptCoodinator();
new Fabric\Coodinator\ShortcodeCoodinator();
new Fabric\Coodinator\IncludePartCoodinator();
new Fabric\Coodinator\YoastSeoCoodinator();

require_once( __DIR__ . '/functions/fabric_load_item.php' );
require_once( __DIR__ . '/functions/fabric_load_setting.php' );
require_once( __DIR__ . '/custom_fabric.php' );
// ========================================================================== //

/**
 * ImageMagic よりも GD を優先して使う設定
 *
 * ImageMagic に起因してエラーが起こるようであれば下記設定を有効化する
 */

// new SetImageEditor( 'GD' );

// ========================================================================== //

/* <head> タグの中で実行される処理を登録 */
new WpHead( function() {
	/* Webフォント読み込み */
	Arkhe::get_part( 'web_fonts' );

	/* analytics読み込み */
	Arkhe::get_part( 'analytics' );
} );

/* </body> タグの直前で実行される処理を登録 */
new WpFooter( function() {

	/* KASHIKAタグ読み込み */
	Arkhe::get_part( 'tag01' );

} );

// ========================================================================== //

/* サイドバー 新規登録 */

// 例： new RegisterSidebar( 'sidebar-news', 'サイドカラム (新着情報)' );
// 例： new RegisterSidebar( 'sidebar-staff', 'サイドカラム (スタッフ紹介)' );
new RegisterSidebar( 'sidebar-news', 'サイドバー (新着情報)' );
new RegisterSidebar( 'sidebar-blog', 'サイドバー (ブログ)' );

// ========================================================================== //

/* Arkhe::get_part() の読み込みパスを上書き */

// 例： new GetPart( 'sidebar', 'sidebar' );
// 例： new GetPart( 'single/head', 'single_head' );



new GetPart( 'sidebar', 'sidebar' );
new GetPart( 'archive/title', 'archive_title' );

new GetPart( 'page/title', 'page_title' );

new GetPart( 'post_list/main_query', 'post_list__main_query' );

new GetPart( 'post_list/item/meta/category', 'post_list__category' );
new GetPart( 'post_list/style/normal', 'p-postList' );

new GetPart( 'single/head/title', 'single_title' );
new GetPart( 'single/head/thumbnail', 'single_thumbnail' );
new GetPart( 'single/head/meta', 'single_meta' );
new GetPart( 'single/foot/prev_next_link', 'single_other_article' );

// ========================================================================== //

/* apply_filters() の読み込みパスを上書き */
add_filter( 'ark_the__pnlin', function ( $return, $args ) {
	echo "<!--\n";
	var_dump( array(
		'$return' => $return,
		'$args' => $args,
	) );
	echo "-->\n";

	$post_id = isset( $args['id']) ? $args['id'] : 0;
	$item = qms4_detail( $post_id );

	echo "-->\n";
	var_dump( array(
		'$return' => $return,
		'$item' => $item,
	));
	echo "-->\n";

	ob_start();
	include( __DIR__ . '/template-parts/single_other_article.php' );
	return ob_get_clean();

}, 10,2 );





// ========================================================================== //

/* ブロックスタイルを登録 */

/* ボタン */
new RegisterBlockStyle( 'core/button', 'fill', '塗りつぶし' );
new RegisterBlockStyle( 'core/button', 'outline', '輪郭' );
new RegisterBlockStyle( 'core/button', 'fill_action01', 'カラー①' );
new RegisterBlockStyle( 'core/button', 'fill_action02', 'カラー②' );
new RegisterBlockStyle( 'core/button', 'anchor', '輪郭アンカー' );

/* 見出し */
new RegisterBlockStyle( 'core/heading', 'border01-heading', '枠線の見出し①' );
new RegisterBlockStyle( 'core/heading', 'border02-heading', '枠線の見出し②' );
new RegisterBlockStyle( 'core/heading', 'under_line01-heading', '下線の見出し①' );
new RegisterBlockStyle( 'core/heading', 'under_line02-heading', '下線の見出し②' );
new RegisterBlockStyle( 'core/heading', 'under_line03-heading', '下線の見出し③' );
new RegisterBlockStyle( 'core/heading', 'en_title-heading', '英語タイトルフォント' );

// ========================================================================== //

/**
 * QMS3系フックに先駆けて$_GETのパラメータをサニタイズする
 *
 * @priority: 9
 */
add_filter( 'qms3_form_param', function( $param, $form_type ) {
	if ( ! empty( $_GET ) ) {
		foreach ( $_GET as $key => $value ) {
			$_GET[ $key ] = htmlspecialchars( $value );
		}
	}

	return $param;
}, 9, 2 );

add_filter( 'qms3_form_param', function( $param, $form_type ) {
	if ( $form_type !== 'contact' ) { return $param; }

	$items = array( '=>選択' );

	$query = new WP_Query( array(
		'post_type' => 'house',
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => 999999,
	) );

	foreach ( $query->posts as $wp_post ) {
		$items[] = $wp_post->post_title;
	}

	$query = new WP_Query( array(
		'post_type' => 'land',
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => 999999,
	) );

	foreach ( $query->posts as $wp_post ) {
		$items[] = $wp_post->post_title;
	}

	$param[ 'options' ] = array();
	$param[ 'options' ][ 'place' ] = join( "\n", $items );

	return $param;
}, 10, 2 );

add_filter( 'qms3_form_param', function( $param, $form_type ) {
	if ( $form_type !== 'reserve' ) { return $param; }

	$query = new WP_Query( array(
		'post_type' => 'modelhouse',
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => 999999,
	) );

	$items = array( '=>選択' );
	foreach ( $query->posts as $wp_post ) {
		$items[] = htmlspecialchars( strip_tags( $wp_post->post_title ) );
	}

	$param[ 'options' ] = array();
	$param[ 'options' ][ 'place' ] = join( "\n", $items );

	return $param;
}, 10, 2 );


