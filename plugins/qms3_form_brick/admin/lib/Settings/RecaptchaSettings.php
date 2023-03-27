<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Settings;

use RuntimeException;
use ReflectionMethod;
use QMS3\BrickAdmin\Exception\UnknownKeyException;


/**
 * @property    bool      $activate
 * @property    string    $sitekey
 * @property    string    $secret
 */
class RecaptchaSettings
{
    const DEFAULT_ACTIVATE = false;
    const DEFAULT_SITEKEY  = "";
    const DEFAULT_SECRET   = "";

    /** @var    bool */
    private $activate;

    /** @var    string */
    private $sitekey;

    /** @var    string */
    private $secret;

    /**
     * @param    bool      $activate
     * @param    string    $sitekey
     * @param    string    $secret
     */
    private function __construct($activate, $sitekey, $secret)
    {
        $this->activate = $activate;
        $this->sitekey  = $sitekey;
        $this->secret   = $secret;
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
    protected function _get__sitekey()
    {
        return $this->sitekey;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__sitekey($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_SITEKEY; }

        $this->sitekey = trim($value);
    }

    /**
     * @return    string
     */
    protected function _get__secret()
    {
        return $this->secret;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__secret($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_SECRET; }

        $this->secret = trim($value);
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
            /* $activate = */ get_option("brick_master__recaptcha_activate", self::DEFAULT_ACTIVATE),
            /* $sitekey  = */ get_option("brick_master__recaptcha_sitekey" , self::DEFAULT_SITEKEY),
            /* $secret   = */ get_option("brick_master__recaptcha_secret"  , self::DEFAULT_SECRET)
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

        $results["activate"] = update_option("brick_master__recaptcha_activate", $this->activate);
        $results["sitekey"]  = update_option("brick_master__recaptcha_sitekey" , $this->sitekey);
        $results["secret"]   = update_option("brick_master__recaptcha_secret"  , $this->secret);

        return $results;
    }
}
