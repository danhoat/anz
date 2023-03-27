<?php
declare(strict_types=1);

namespace QMS3\Brick\Logger;

use Monolog\Logger as MonologLogger;
use DateTimeZone;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NativeMailerHandler;


class ArchiveLogger
{
    static $log = null;

    /**
     * @param     string    $logname
     * @return    void
     */
    static public function setup($logname)
    {
        if (self::$log) { return; }


        self::$log = new MonologLogger($logname);

        self::$log->setTimezone(new DateTimeZone("Asia/Tokyo"));

        $to      = "system@atsu-maru.co.jp";
        $from    = "system@atsu-maru.co.jp";
        $subject = "【エラー通知】";

        $date_format = "Y-m-d H:i:s";
        $output     = "[%datetime%]\t%message%\n";
        $formatter  = new LineFormatter($output, $date_format);

        $native_mailer_handler = new NativeMailerHandler($to, $subject, $from, MonologLogger::CRITICAL);
        $native_mailer_handler->setFormatter($formatter);
        self::$log->pushHandler($native_mailer_handler);
    }
}
