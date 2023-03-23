<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;

use RuntimeException;


class HandleError
{
    /**
     * @param     callable    $function
     * @param     mixed[]     ...$args
     * @return    mixed
     * @throws    RuntimeException
     */
    public static function invoke(callable $function)
    {
        $args = array_slice(func_get_args(), 1);

        set_error_handler(function($severity, $message) {
            throw new RuntimeException($message);
        });

        try {
            return call_user_func_array($function, $args);
        }
        finally {
            restore_error_handler();
        }
    }
}
