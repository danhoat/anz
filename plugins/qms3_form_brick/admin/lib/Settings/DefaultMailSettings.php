<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Settings;

use RuntimeException;
use ReflectionMethod;
use QMS3\BrickAdmin\Exception\UnknownKeyException;


/**
 * @property    array<string,mixed>    $default_mail_setting
 */
class DefaultMailSettings
{
    /** @var    array[] */
    private $default_mail_setting;

    /**
     * @param    array[]    $default_mail_setting
     */
    private function __construct($default_mail_setting)
    {
        $this->default_mail_setting = $default_mail_setting;
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
     * @return    array[]
     */
    protected function _get__default_mail_setting()
    {
        return $this->default_mail_setting;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__default_mail_setting($value)
    {
        if (is_null($value)) { $value = self::default_default_mail_setting(); }

        $this->default_mail_setting = $this->sanitize($value);
    }

    // ====================================================================== //

    /**
     * @return    DefaultMailSettings
     */
    public static function get()
    {
        if (!function_exists("get_option")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        $default_mail_setting = get_option("brick_master__mail_setting", self::default_default_mail_setting());

        return new self($default_mail_setting);
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

        $results["default_mail_setting"] = update_option("brick_master__mail_setting", $this->default_mail_setting);

        return $results;
    }

    // ====================================================================== //

    /**
     * @return    array<string,mixed>
     */
    private static function default_default_mail_setting()
    {
        return [
            "thanks" => [
                "tos_str"              => "",
                "from"                 => "",
                "from_name"            => "",
                "subject_template"     => "[form_name]を承りました",
                "main_text_template"   => "",
                "block_filter"         => "",
                "post_result_template" => "",
                "after_text_template"  => "",
                "signature_template"   => "",
            ],
            "notification" => [
                "tos_str"              => "",
                "from"                 => "",
                "from_name"            => "",
                "subject_template"     => "【[device]】[form_name]を承りました",
                "main_text_template"   => "[form_name]を承りました。\n\n=================================\n",
                "block_filter"         => "",
                "post_result_template" => "",
                "after_text_template"  => "",
                "signature_template"   => "",
            ],
            "bccNotification" => [
                "tos_str"              => "",
                "from"                 => "",
                "from_name"            => "",
                "subject_template"     => "【[device]】[form_name]を承りました",
                "main_text_template"   => "[form_name]を承りました。\n\n=================================\n",
                "block_filter"         => "",
                "post_result_template" => "",
                "after_text_template"  => "",
                "signature_template"   => "",
            ],
        ];
    }

    /**
     * @param     array[]    $mail_settings
     * @return    array[]
     */
    private function sanitize($mail_setting)
    {
        return [
            "thanks" => array_merge(
                $mail_setting["thanks"],
                [
                    "tos_str"          => $this->sanitize_email($mail_setting["thanks"]["tos_str"]),
                    "from"             => trim($mail_setting["thanks"]["from"]),
                    "from_name"        => trim($mail_setting["thanks"]["from_name"]),
                    "subject_template" => trim($mail_setting["thanks"]["subject_template"]),
                ]
            ),
            "notification" => array_merge(
                $mail_setting["notification"],
                [
                    "tos_str"          => $this->sanitize_email($mail_setting["notification"]["tos_str"]),
                    "from"             => trim($mail_setting["notification"]["from"]),
                    "from_name"        => trim($mail_setting["notification"]["from_name"]),
                    "subject_template" => trim($mail_setting["notification"]["subject_template"]),
                ]
            ),
            "bccNotification" => array_merge(
                $mail_setting["bccNotification"],
                [
                    "tos_str"          => $this->sanitize_email($mail_setting["bccNotification"]["tos_str"]),
                    "from"             => trim($mail_setting["bccNotification"]["from"]),
                    "from_name"        => trim($mail_setting["bccNotification"]["from_name"]),
                    "subject_template" => trim($mail_setting["bccNotification"]["subject_template"]),
                ]
            ),
        ];
    }

    /**
     * @param     string    $emails_str
     * @return    string
     */
    private function sanitize_email($emails_str)
    {
        $email_strs = preg_split("/[,\n]/", $emails_str, -1, PREG_SPLIT_NO_EMPTY);

        $emails = [];
        foreach ($email_strs as $email) {
            $email = trim($email, " \n\r\t\v\0　");

            if ($email) { $emails[] = $email; }
        }

        return join("\n", $emails);
    }
}
