<?php
declare(strict_types=1);

namespace QMS3\Brick\Exception;

use RuntimeException;


class NotSupportKeyException extends RuntimeException
{
    /** @var    mixed */
    private $default;

    /**
     * @param    string    $key        未対応だったキー名
     * @param    mixed     $default    代わりに返されるデフォルト値
     * @param    string    $message
     */
    public function __construct(
        $key,
        $default = "",
        $message = null
    )
    {
        $this->default = $default;

        $message = $message ?: "未対応のプロパティです: key: {$key}";

        parent::__construct($message);
    }

    /**
     * @return    mixed
     */
    public function get_default()
    {
        return $this->default;
    }
}
