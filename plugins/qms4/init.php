<?php
/**
 * Plugin Name:       QMS4
 * Description:       株式会社あつまる CMSパック
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            株式会社あつまる
 * Author URI:        https://atsu-maru.co.jp/
 * Text Domain:       qms4
 */

require_once( __DIR__ . '/vendor/autoload.php' );

define( 'QMS4_DIR', __DIR__ );
define( 'QMS4_BASENAME', plugin_basename( __FILE__ ) );

// ========================================================================== //

add_action( 'after_setup_theme', 'qms4_add_theme_thumbnails' );
function qms4_add_theme_thumbnails()
{
	add_theme_support( 'post-thumbnails' );
}

// ========================================================================== //

new QMS4\Coodinator\AdminPageCoodinator();
new QMS4\Coodinator\CustomBlockCoodinator();
new QMS4\Coodinator\FormatTypeCoodinator();
new QMS4\Coodinator\PostTyepsCoodinator();
new QMS4\Coodinator\PostMetaCoodinator();
new QMS4\Coodinator\Qms4PostTypeCoodinator();
new QMS4\Coodinator\RoleCapabilityCoodinator();
new QMS4\Coodinator\ColumnsCoodinator();
new QMS4\Coodinator\RestApiCoodinator();

// ========================================================================== //

require_once( __DIR__ . '/functions/ok.php' );
require_once( __DIR__ . '/functions/is_empty.php' );

require_once( __DIR__ . '/functions/qms4_list.php' );
require_once( __DIR__ . '/functions/qms4_detail.php' );

require_once( __DIR__ . '/functions/qms4_site_part.php' );

require_once( __DIR__ . '/functions/qms4_extend_queries.php' );

// hooks
require_once( __DIR__ . '/functions/hooks/qms4_set_item_class.php' );
require_once( __DIR__ . '/functions/hooks/qms4_list_set_item_class.php' );
require_once( __DIR__ . '/functions/hooks/qms4_detail_set_item_class.php' );
require_once( __DIR__ . '/functions/hooks/qms4_list_add_query_part.php' );
require_once( __DIR__ . '/functions/hooks/qms4_list_debug_mode.php' );

