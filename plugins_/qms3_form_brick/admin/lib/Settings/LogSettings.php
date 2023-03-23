<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Settings;

use RuntimeException;
use ReflectionMethod;
use Monolog\Logger;
use QMS3\BrickAdmin\Exception\UnknownKeyException;


/**
 * @property    int    $level
 */
class LogSettings
{
    const DEFAULT_LEVEL = Logger::INFO;

    /** @var    int */
    private $level;

    /**
     * @param    int      $level
     */
    private function __construct($level)
    {
        $this->level = $level;
    }

    /**
     * @param     string    $name
     * @return    mixed
     */
    public function __get($name)
    {
        $method_name = "_get__{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        return call_user_func([$this, $method_name]);
    }

    /**
     * @param     string    $name
     * @param     mixed     $value
     * @return    void
     */
    public function __set($name, $value)
    {
        $method_name = "_set__{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        call_user_func([$this, $method_name], $value);
    }

    // ====================================================================== //

    /**
     * @return    int
     */
    protected function _get__level()
    {
        return $this->level;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__level($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_LEVEL; }

        $this->level = (int) $value;
    }

    // ====================================================================== //

    /**
     * @return    LogSettings
     */
    public static function get()
    {
        if (!function_exists("get_option")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        return new self(
            /* $level = */ get_option("brick_master__log_level", self::DEFAULT_LEVEL)
        );
    }

    /**
     * @return    array<string,bool>
     */
    public function save()
    {
        if (!function_exists("update_option")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        $results = [];

        $results["level"] = update_option("brick_master__log_level", $this->level);

        return $results;
    }
}
