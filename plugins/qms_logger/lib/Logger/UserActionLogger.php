<?php
namespace QMS\Logger\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use QMS\Logger\Logger\CurrentUserProcessor;


class UserActionLogger
{
	/** @var    Logger[] */
	private static $loggers = array();

	/**
	 * @param    string    $channel
	 * @param    string|null    $filename
	 * @return    Logger
	 */
	static public function channel( string $channel, ?string $filename = null )
	{
		$filename = empty( $filename ) ? 'user_action' : $filename;
		$cache_key = "{$channel}__{$filename}";

		return isset( self::$loggers[ $cache_key ] )
			? self::$loggers[ $cache_key ]
			: ( self::$loggers[ $cache_key ] = self::setup( $channel, $filename ) );
	}

	/**
	 * @param    string    $channel
	 * @param    string|null    $filename
	 * @return    Logger
	 */
	static public function setup( string $channel, ?string $filename = null )
	{
		$logger = new Logger( $channel );

		$logger->setTimezone( wp_timezone() );

		$logger->pushProcessor( new CurrentUserProcessor() );
		$logger->pushProcessor( new UidProcessor() );

		$output = "[%datetime%]\t%channel%\t%level_name%\t%message%\t%context%\t%extra%\n";
		$date_format = 'Y-m-d H:i:s';
		$formatter = new LineFormatter( $output, $date_format, /* $allowInlineLineBreaks = */ true );
		$formatter->includeStacktraces( true );  // Logger に exception を渡したとき、スタックトレースまでをログに含めるようにする


		// PHP Builtin Web Server で実行されている場合
		if ( php_sapi_name() == 'cli-server' ) {
			// 標準出力ハンドラ、デバッグ用
			$stream_handler = new StreamHandler( 'php://stdout', Logger::DEBUG );
			$stream_handler->setFormatter( $formatter );
			$logger->pushHandler( $stream_handler );
		}


		// log ファイルへ出力(月単位)
		$filepath = self::filepath( $filename );
		$rotating_file_handler = new RotatingFileHandler( $filepath, 0, Logger::INFO );
		$rotating_file_handler->setFilenameFormat( '{filename}_{date}', RotatingFileHandler::FILE_PER_MONTH );
		$rotating_file_handler->setFormatter( $formatter );
		$logger->pushHandler( $rotating_file_handler );


		// メール通知
		$to = 'system@atsu-maru.co.jp';
		$from = 'system@atsu-maru.co.jp';
		$subject = '【エラー通知】 ' . self::current_url();
		$native_mailer_handler = new NativeMailerHandler( $to, $subject, $from, Logger::ERROR );
		$native_mailer_handler->setFormatter( $formatter );
		$logger->pushHandler($native_mailer_handler);


		return $logger;
	}

	// ====================================================================== //

	/**
	 * @param    string    $filename
	 * @return    string
	 */
	private static function filepath( string $filename ): string
	{
		$upload_dir = wp_upload_dir();
		$basedir = $upload_dir[ 'basedir' ];

		$dir = "{$basedir}/qms_logger/";

		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		return "{$dir}/{$filename}.log";
	}

	/**
	 * @return    string
	 */
	private static function current_url()
	{
		$protocol = empty( $_SERVER[ 'HTTPS' ] ) ? 'http://' : 'https://';

		return $protocol . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
	}

}
