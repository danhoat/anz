<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Settings;

use RuntimeException;
use ReflectionMethod;
use WP_Query;
use QMS3\BrickAdmin\Exception\UnknownKeyException;


/**
 * @property    string              $main
 * @property    array<int,array>    $mail_settings
 */
class CustomMailSettings
{
    const DEFAULT_MAIN = "__main__";

    /** @var    int */
    private $post_id;

    /** @var    array[] */
    private $mail_settings;

    /**
     * @param    int        $post_id
     * @param    array[]    $mail_settings
     */
    private function __construct($post_id, $mail_settings)
    {
        $this->post_id       = $post_id;
        $this->mail_settings = $mail_settings;
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
    protected function _get__main()
    {
        return isset($this->mail_settings[0]["name"])
            ? $this->mail_settings[0]["name"]
            : self::DEFAULT_MAIN;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__main($value)
    {
        if (is_null($value)) { $value = self::DEFAULT_MAIN; }

        if (isset($this->mail_settings[0]) && is_array($this->mail_settings[0])) {
            $this->mail_settings[0]["name"] = trim($value);
        }
    }

    /**
     * @return    array[]
     */
    protected function _get__mail_settings()
    {
        return $this->mail_settings;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__mail_settings($value)
    {
        if (is_null($value)) { $value = self::default_mail_settings(); }

        $this->mail_settings = $this->sanitize($value);
    }

    // ====================================================================== //

    /**
     * @param     int|string      $post_id
     * @return    CustomMailSettings
     */
    public static function get($post_id)
    {
        if (!function_exists("get_post_meta")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        if (!is_numeric($post_id)) { $post_id = self::find_post_id($post_id); }


        $mail_settings = get_post_meta($post_id, "mail_settings", /* $single = */ true);

        if (empty($mail_settings)) { $mail_settings = self::default_mail_settings(); }

        return new self($post_id, $mail_settings);
    }

    /**
     * @return    array<string,bool>
     */
    public function save()
    {
        $results = [];

        $results["mail_settings"] = update_post_meta(
            $this->post_id,
            "mail_settings",
            $this->mail_settings
        );

        return $results;
    }

    // ====================================================================== //

    /**
     * @return    array[]
     */
    private static function default_mail_settings()
    {
        return [
            [
                "name" => "__main__",
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
            ],
        ];
    }

    /**
     * @param     string    $slug
     * @return    int
     */
    private static function find_post_id($slug)
    {
        if (!class_exists("WP_Query")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        $query = new WP_Query([
            "post_type"   => "brick",
            "post_status" => "publish",
            "name"        => trim($slug),
            "fields"      => "ids",
        ]);

        if (!$query->found_posts) {
            throw new RuntimeException("フォーム設定が見つかりません: \$slug: $slug");
        }

        return $query->posts[0];
    }

    /**
     * @param     array[]    $mail_settings
     * @return    array[]
     */
    private function sanitize($mail_settings)
    {
        $sanitized = [];
        foreach ($mail_settings as $mail_setting) {
            $sanitized[] = [
                "name"   => trim($mail_setting["name"]),
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

        return $sanitized;
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
