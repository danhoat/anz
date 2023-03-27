<?php
declare(strict_types=1);

namespace QMS3\Brick\Logger;

use UnexpectedValueException;
use DateTimeZone;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use QMS3\Brick\Param\Param;


/**
 * エラーログ
 *
 * @see    https://kahoo.blog/how-to-use-monolog/
 */
class ErrorLogger
{
    static $log = null;

    const FILENAME = "errorlog";

    /**
     * @param     string    $logname
     * @param     Param     $param
     * @return    void
     */
    static public function setup($logname, Param $param)
    {
        if (self::$log) { return; }


        self::$log = new MonologLogger($logname);

        self::$log->setTimezone(new DateTimeZone("Asia/Tokyo"));


        $date_format = "Y-m-d H:i:s";
        $output     = "[%datetime%]\t[%level_name%]\t%message%\n";
        $formatter  = new LineFormatter($output, $date_format);
        $formatter->includeStacktraces(true);  // Logger に exception を渡したとき、スタックトレースまでをログに含めるようにする


        // PHP Builtin Web Server で実行されている場合
        if (php_sapi_name() == "cli-server") {
            // 標準出力ハンドラ、デバッグ用
            $stream_handler = new StreamHandler("php://stdout", MonologLogger::DEBUG);
            $stream_handler->setFormatter($formatter);
            self::$log->pushHandler($stream_handler);
        }

        self::create_htaccess($param->log_path);

        // log ファイルへ出力(月単位)
        $log_filepath = rtrim($param->log_path, "/") . "/" . self::FILENAME . ".log";
        $rotating_file_handler = new RotatingFileHandler($log_filepath, 0, $param->log_level);
        $rotating_file_handler->setFilenameFormat("{filename}_{date}", "Ym");
        $rotating_file_handler->setFormatter($formatter);
        self::$log->pushHandler($rotating_file_handler);

        // メール通知
        $to      = "system@atsu-maru.co.jp";
        $from    = "system@atsu-maru.co.jp";
        $subject = "【エラー通知】 " . self::current_url();
        $native_mailer_handler = new NativeMailerHandler($to, $subject, $from, MonologLogger::ERROR);
        $native_mailer_handler->setFormatter($formatter);
        self::$log->pushHandler($native_mailer_handler);
    }

    // =======================================================================//

    /**
     * Adds a log record at the DEBUG level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     mixed     $level      The log level
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function log($level, $message, array $context = [])
    {
        try {
            return self::$log->log($level, $message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    /**
     * Adds a log record at the DEBUG level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function debug($message, array $context = [])
    {
        try {
            return self::$log->debug($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    /**
     * Adds a log record at the INFO level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function info($message, array $context = [])
    {
        try {
            return self::$log->info($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function notice($message, array $context = [])
    {
        try {
            return self::$log->notice($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function warning($message, array $context = [])
    {
        try {
            return self::$log->warning($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function error($message, array $context = [])
    {
        try {
            return self::$log->error($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
   }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function critical($message, array $context = [])
    {
        try {
            return self::$log->critical($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function alert($message, array $context = [])
    {
        try {
            return self::$log->alert($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param     string    $message    The log message
     * @param     array     $context    The log context
     * @return    bool                  Whether the record has been processed
     */
    static public function emergency($message, array $context = [])
    {
        try {
            return self::$log->emergency($message, $context);
        }
        catch (UnexpectedValueException $e) {
        }
    }

    // =======================================================================//

    /**
     * @return    string
     */
    private static function current_url()
    {
        $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";

        return $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    }

    /**
     * @param    string    $dirpath
     */
    private static function create_htaccess($dirpath)
    {
        $dirpath = rtrim($dirpath, "/");
        $htaccess = "{$dirpath}/.htaccess";

        if (file_exists($htaccess)) { return; }

        if (!is_dir($dirpath)) {
            mkdir($dirpath, 0777, /* $recursive = */ true);
        }

        file_put_contents($htaccess, trim("
Order Deny,Allow
Deny from all
        ") . "\n");
    }
}
