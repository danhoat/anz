<?php
/*
Plugin Name: QMS3 Form Brick
Plugin URI: https://bitbucket.org/team_qh/qms3_form_brick/src/master/
Description: 株式会社あつまる フォームシステム
Version: 1.6.1
Author: 株式会社あつまる
Author URI: https://atsu-maru.co.jp/
 */

require_once( __DIR__ . '/vendor/autoload.php' );


define( 'QMS3_FORM_BRICK_PLUGIN_FILE', __FILE__ );


// 実行に必要な PHP バージョン条件を満たしているかチェックする
new QMS3\Brick\Util\VersionCheck();


// // 管理画面、フォーム一覧のカラム設定
// $column_settings = new QMS3\BrickAdmin\AdminColumns\ColumnSettings();
// register_activation_hook(__FILE__, [$column_settings, "add_column_settings"]);

// JavaScript の登録・読み込み
new QMS3\BrickAdmin\Coodinator\ScriptsCoodinator();

// フォーム登録のためのカスタム投稿タイプを追加
new QMS3\BrickAdmin\Coodinator\PostTypesCoodinator();

new QMS3\BrickAdmin\Coodinator\ColumnSettingsCoodinator();

// カスタムフィールド登録・メタボックス追加
new QMS3\BrickAdmin\Coodinator\PostMetaCoodinator();

// REST API を拡張
new QMS3\BrickAdmin\Coodinator\RestCoodinator();

// カスタムブロック登録
new QMS3\BrickAdmin\Coodinator\CustomBlockCoodinator();

new QMS3\BrickAdmin\Coodinator\FormRouteCoodinator();

// フォーム複製権限を制限する
new QMS3\BrickAdmin\DuplicatePost\RestrictOfForms();


$capability_service = new QMS3\BrickAdmin\Capabilities\CapabilityService();
register_activation_hook( __FILE__, array( $capability_service, 'add_caps' ) );

// ========================================================================== //

require_once( __DIR__ . '/output/qms3_form_init.php' );
require_once( __DIR__ . '/output/qms3_form_js_url.php' );
require_once( __DIR__ . '/output/qms3_form_css_url.php' );
require_once( __DIR__ . '/output/pre_process/qms3_form_recaptcha.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_kintone.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_digima.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_kasika.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_wp_post.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_chatwork_message.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_zoho.php' );
require_once( __DIR__ . '/output/mail_generator/qms3_form_thanks_from_master.php' );
require_once( __DIR__ . '/output/mail_generator/qms3_form_notification_from_master.php' );

// ========================================================================== //

define( 'QMS3_FORM_ROOT', __DIR__ );

$qms3_form_file_upload_dir = wp_upload_dir();
define( 'QMS3_FORM_UPLOAD_DIR', "{$qms3_form_file_upload_dir['basedir']}/qms3_form_attachment/" );
define( 'QMS3_FORM_UPLOAD_ACCEPT', 'image/jpeg,image/png,image/gif,image/webp' );
define( 'QMS3_FORM_UPLOAD_RANDOM_FILENAME', false );

// フォーム項目設定ファイルの置き場所
define( 'QMS3_FORM_STRUCTURE_FORMAT', 'WORDPRESS' );
define( 'QMS3_FORM_STRUCTURE_DIR', '' );

// メール設定ファイルの置き場所
define( 'QMS3_FORM_MAIL_SETTING_FORMAT', 'WORDPRESS' );
define( 'QMS3_FORM_MAIL_SETTING_DIR', '' );

// メール送信設定
$qms3_form_smtp_settings = QMS3\BrickAdmin\Settings\SmtpSettings::get();
define( 'QMS3_FORM_SMTP_ACTIVATE', $qms3_form_smtp_settings->activate );
define( 'QMS3_FORM_SMTP_HOST', $qms3_form_smtp_settings->host );
define( 'QMS3_FORM_SMTP_PORT', $qms3_form_smtp_settings->port );
define( 'QMS3_FORM_SMTP_PROTOCOL', $qms3_form_smtp_settings->protocol );
define( 'QMS3_FORM_SMTP_USER', $qms3_form_smtp_settings->user );
define( 'QMS3_FORM_SMTP_PASS', $qms3_form_smtp_settings->pass );

// ログ設定
$qms3_form_log_settings = QMS3\BrickAdmin\Settings\LogSettings::get();
$qms3_form_log_upload_dir = wp_upload_dir();
define( 'QMS3_FORM_LOG_LEVEL', $qms3_form_log_settings->level );
define( 'QMS3_FORM_LOG_PATH', "{$qms3_form_log_upload_dir['basedir']}/qms3_form/logs/" );

// reCAPTCHA 設定
$qms3_form_recaptcha_settings = QMS3\BrickAdmin\Settings\RecaptchaSettings::get();
define( 'QMS3_FROM_RECAPTCHA_ACTIVATE', $qms3_form_recaptcha_settings->activate );
define( 'QMS3_FROM_RECAPTCHA_SITEKEY', $qms3_form_recaptcha_settings->sitekey );
define( 'QMS3_FROM_RECAPTCHA_SECRET', $qms3_form_recaptcha_settings->secret );
