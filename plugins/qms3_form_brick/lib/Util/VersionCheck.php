<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;


/**
 * 実行に必要な PHP バージョン条件を満たしているかチェックする
 */
class VersionCheck
{
    const REQUIRED_VERSION = '5.6.0';

    public function __construct()
    {
        if ( version_compare( '5.6.0', PHP_VERSION, '>' ) ) {
            fwrite(
                STDERR,
                sprintf(
                    'QMS3 Form Brick の動作には PHP 5.6. 以上が必要です。' . PHP_EOL .
                    '現在の PHP バージョンは %s (%s) です。' . PHP_EOL,
                    PHP_VERSION,
                    PHP_BINARY
                )
            );

            exit(1);
        }
    }
}
