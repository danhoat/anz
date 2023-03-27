<?php
declare(strict_types=1);

namespace QMS3\Brick\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;


class SubTaskLogger
{
    const FILENAME = 'subtasklog';

    /** @var    MonologLogger[] */
    private static $loggers = array();

    /**
     * @param    string    $channel
     */
    static private function setup( $channel )
    {
        self::create_htaccess( QMS3_FORM_LOG_PATH );


        $logger = new MonologLogger( $channel );

        $logger->setTimezone( new \DateTimeZone( 'Asia/Tokyo' ) );


        $output = "[%datetime%]\t[%level_name%]\t%message%\t%context%\t%extra%\n";
        $date_format = 'Y-m-d H:i:s';
        $formatter = new LineFormatter( $output, $date_format, /* $allowInlineLineBreaks = */true );
        $formatter->includeStacktraces( true );  // Logger に exception を渡したとき、スタックトレースまでをログに含めるようにする


        // PHP Builtin Web Server で実行されている場合
        if ( php_sapi_name() == 'cli-server' ) {
            // 標準出力ハンドラ、デバッグ用
            $stream_handler = new StreamHandler( 'php://stdout', MonologLogger::DEBUG );
            $stream_handler->setFormatter( $formatter );
            $logger->pushHandler( $stream_handler );
        }

        // log ファイルへ出力(月単位)
        $filepath = rtrim( QMS3_FORM_LOG_PATH, '/' ) . '/' . self::FILENAME . '.log';
        $rotating_file_handler = new RotatingFileHandler( $filepath, 0, QMS3_FORM_LOG_LEVEL );
        $rotating_file_handler->setFilenameFormat( '{filename}_{date}', 'Ym' );
        $rotating_file_handler->setFormatter( $formatter );
        $logger->pushHandler( $rotating_file_handler );


        // メール通知
        $to = 'system@atsu-maru.co.jp';
        $from = 'system@atsu-maru.co.jp';
        $subject = '【エラー通知】 ' . self::current_url();
        $native_mailer_handler = new NativeMailerHandler( $to, $subject, $from, MonologLogger::ERROR );
        $native_mailer_handler->setFormatter( $formatter );
        $logger->pushHandler($native_mailer_handler);


        self::$loggers[ $channel ] = $logger;
        return self::$loggers[ $channel ];
    }

    /**
     * @param    string    $channel
     * @return    MonologLogger
     */
    static public function channel( $channel )
    {
        if ( isset( self::$loggers[ $channel ] ) ) {
            return self::$loggers[ $channel ];
        }

        return self::$loggers[ $channel ] = self::setup( $channel );
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    private static function current_url()
    {
        $protocol = empty( $_SERVER[ 'HTTPS' ] ) ? 'http://' : 'https://';

        return $protocol . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
    }

    /**
     * @param    string    $dirpath
     */
    private static function create_htaccess( $dirpath )
    {
        $dirpath = rtrim( $dirpath, '/' );
        $htaccess = "{$dirpath}/.htaccess";

        if ( file_exists( $htaccess ) ) { return; }

        if ( ! is_dir( $dirpath )) {
            mkdir( $dirpath, 0777, /* $recursive = */ true );
        }

        file_put_contents( $htaccess, trim('
Order Deny,Allow
Deny from all
        ') . "\n" );
    }
}
