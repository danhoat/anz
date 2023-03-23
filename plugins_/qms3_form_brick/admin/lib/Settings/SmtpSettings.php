<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Settings;

use RuntimeException;
use ReflectionMethod;
use QMS3\BrickAdmin\Exception\UnknownKeyException;


/**
 * @property    bool      $activate
 * @property    string    $host
 * @property    int       $port
 * @property    string    $protocol
 * @property    string    $user
 * @property    string    $pass
 */
class SmtpSettings
{
    const DEFAULT_ACTIVATE = false;
    const DEFAULT_HOST     = "";
    const DEFAULT_PORT     = 587;
    const DEFAULT_PROTOCOL = "SMTP_AUTH";
    const DEFAULT_USER     = "";
    const DEFAULT_PASS     = "";

    /** @var    bool */
    private $activate;

    /** @var    string */
    private $host;

    /** @var    int */
    private $port;

    /** @var    string */
    private $protocol;

    /** @var    string */
    private $user;

    /** @var    string */
    private $pass;

    /**
     * @param    bool      $activate
     * @param    string    $host
     * @param    int       $port
     * @param    string    $protocol
     * @param    string    $user
     * @param    string    $pass
     */
    private function __construct(
        $activate,
        $host,
        $port,
        $protocol,
        $user,
        $pass
    )
    {
        $this->activate = $activate;
        $this->host     = $host;
        $this->port     = $port;
        $this->protocol = $protocol;
        $this->user     = $user;
        $this->pass     = $pass;
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
     * @return    bool
     */
    protected function _get__activate()
    {
        return $this->activate;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__activate($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_ACTIVATE; }

        $this->activate = (bool) $value;
    }

    /**
     * @return    string
     */
    protected function _get__host()
    {
        return $this->host;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__host($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_HOST; }

        $this->host = trim($value);
    }

    /**
     * @return    int
     */
    protected function _get__port()
    {
        return $this->port;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__port($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_PORT; }

        $this->port = (int) $value;
    }

    /**
     * @return    string
     */
    protected function _get__protocol()
    {
        return $this->protocol;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__protocol($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_PROTOCOL; }

        $this->protocol = trim($value);
    }

    /**
     * @return    string
     */
    protected function _get__user()
    {
        return $this->user;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__user($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_USER; }

        $this->user = trim($value);
    }

    /**
     * @return    string
     */
    protected function _get__pass()
    {
        return $this->pass;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__pass($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_PASS; }

        $this->pass = trim($value);
    }

    // ====================================================================== //

    /**
     * @return    SmtpSettings
     */
    public static function get()
    {
        if (!function_exists("get_option")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        return new self(
            /* $activate = */ get_option("brick_master__smtp_activate", self::DEFAULT_ACTIVATE),
            /* $host     = */ get_option("brick_master__smtp_host"    , self::DEFAULT_HOST),
            /* $port     = */ get_option("brick_master__smtp_port"    , self::DEFAULT_PORT),
            /* $protocol = */ get_option("brick_master__smtp_protocol", self::DEFAULT_PROTOCOL),
            /* $user     = */ get_option("brick_master__smtp_user"    , self::DEFAULT_USER),
            /* $pass     = */ get_option("brick_master__smtp_pass"    , self::DEFAULT_PASS)
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

        $results["activate"] = update_option("brick_master__smtp_activate", $this->activate);
        $results["host"]     = update_option("brick_master__smtp_host"    , $this->host);
        $results["port"]     = update_option("brick_master__smtp_port"    , $this->port);
        $results["protocol"] = update_option("brick_master__smtp_protocol", $this->protocol);
        $results["user"]     = update_option("brick_master__smtp_user"    , $this->user);
        $results["pass"]     = update_option("brick_master__smtp_pass"    , $this->pass);

        return $results;
    }
}
