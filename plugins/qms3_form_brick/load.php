<?php
require_once( __DIR__ . '/vendor/autoload.php' );
require_once( __DIR__ . '/output/qms3_form_init.php' );
require_once( __DIR__ . '/output/pre_process/qms3_form_recaptcha.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_kintone.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_digima.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_kasika.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_chatwork_message.php' );
require_once( __DIR__ . '/output/sub_task/qms3_form_zoho.php' );

use Monolog\Logger;


// 実行に必要な PHP バージョン条件を満たしているかチェックする
new QMS3\Brick\Util\VersionCheck();


define( 'QMS3_FORM_CORE_VERSION', '1.6.1' );
define( 'QMS3_FORM_ROOT', __DIR__ );

define( 'QMS3_FORM_UPLOAD_DIR', __DIR__ . '/demo/uploads/' );
define( 'QMS3_FORM_UPLOAD_ACCEPT', 'image/jpeg,image/png,image/gif,image/webp' );
define( 'QMS3_FORM_UPLOAD_RANDOM_FILENAME', false );

// ========================================================================== //
// フォーム項目設定ファイルの置き場所

define( 'QMS3_FORM_STRUCTURE_FORMAT', 'CSV' );
define( 'QMS3_FORM_STRUCTURE_DIR', '../system_inc/form_setting/structure' );

// ========================================================================== //
// メール設定ファイルの置き場所

define( 'QMS3_FORM_MAIL_SETTING_FORMAT', 'CSV' );
define( 'QMS3_FORM_MAIL_SETTING_DIR', '../system_inc/form_setting/mail' );

// ========================================================================== //
// メール送信設定

define( 'QMS3_FORM_SMTP_ACTIVATE', false );
// define( 'QMS3_FORM_SMTP_HOST', 'ms20.kagoya.net' );
// define( 'QMS3_FORM_SMTP_PORT', 587 );
// define( 'QMS3_FORM_SMTP_PROTOCOL', 'SMTP_AUTH' );
// define( 'QMS3_FORM_SMTP_USER', 'kir391168.notification' );
// define( 'QMS3_FORM_SMTP_PASS', 'wkcGzwYZ7STQVpuu' );

// ========================================================================== //

// define( 'QMS3_FORM_LOG_LEVEL', Logger::DEBUG );
define( 'QMS3_FORM_LOG_LEVEL', Logger::INFO );
// define( 'QMS3_FORM_LOG_PATH', null );

// ========================================================================== //
// reCAPTCHA 設定

define( 'QMS3_FROM_RECAPTCHA_ACTIVATE', false );
define( 'QMS3_FROM_RECAPTCHA_SITEKEY', '' );
define( 'QMS3_FROM_RECAPTCHA_SECRET', '' );

// ========================================================================== //
