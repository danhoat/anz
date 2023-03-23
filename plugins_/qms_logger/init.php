<?php
/**
 * Plugin Name:       QMS Logger
 * Description:       株式会社あつまる ロガー
 * Requires at least: 6.0
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            株式会社あつまる
 * Author URI:        https://atsu-maru.co.jp/
 * Text Domain:       qms_logger
 */

require_once( __DIR__ . '/vendor/autoload.php' );


new QMS\Logger\Subscribe\Core();
new QMS\Logger\Subscribe\Incident();
