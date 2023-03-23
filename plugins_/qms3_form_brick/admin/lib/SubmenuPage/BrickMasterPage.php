<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\SubmenuPage;

use RuntimeException;
use QMS3\BrickAdmin\Capabilities\CapabilityService;
use QMS3\BrickAdmin\Settings\DefaultMailSettings;
use QMS3\BrickAdmin\Settings\LogSettings;
use QMS3\BrickAdmin\Settings\RecaptchaSettings;
use QMS3\BrickAdmin\Settings\SmtpSettings;


class BrickMasterPage
{
    const VERSION = "20211212";

    public function add_submenu_page()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $this->verify_nonce()) {
            if (isset($_POST["action"]) && $_POST["action"] == "reset_capability_setting") {
                $this->reset_capability_setting();
            } else {
                $this->save_mail_setting();
                $this->save_smtp_options();
                $this->save_recaptcha_options();
                $this->save_log_options();
            }
        }

        if (!current_user_can("atsumaru") && !current_user_can("administrator")) { return; }

        add_submenu_page(
            /* $parent_slug = */ "edit.php?post_type=brick",
            /* $page_title  = */ "マスター設定",
            /* $menu_title  = */ "マスター設定",
            /* $capability  = */ "edit_posts",
            /* $menu_slug   = */ "brick_master",
            /* $function    = */ [$this, "render_submenu_page"]
        );
    }

    public function render_submenu_page()
    {
        $handle = "qms3_form__submenu_page__brick_master.js";

        wp_register_script(
            /* $handle   = */ $handle,
            /* $src      = */ plugins_url("../../assets/js/qms3_form__submenu_page__brick_master.min.js", __FILE__),
            /* $deps     = */ [ "jsuites", "jspreadsheet" ],
            /* $ver      = */ self::VERSION
        );

        $mail = DefaultMailSettings::get();
        $smtp = SmtpSettings::get();
        $recaptcha = RecaptchaSettings::get();
        $log = LogSettings::get();

        wp_localize_script(
            /* $handle = */ $handle,
            /* $name   = */ "QMS3_FORM__SUBMENU_PAGE__BRICK_MASTER",
            /* $data   = */ [
                "nonce" => wp_create_nonce("qms3_form__submenu_page__brick_master__nonce"),

                "mail_setting" => $mail->default_mail_setting,

                "smtp_activate" => $smtp->activate,
                "smtp_host"     => $smtp->host,
                "smtp_port"     => $smtp->port,
                "smtp_protocol" => $smtp->protocol,
                "smtp_user"     => $smtp->user,
                "smtp_pass"     => $smtp->pass,

                "recaptcha_activate" => $recaptcha->activate,
                "recaptcha_sitekey"  => $recaptcha->sitekey,
                "recaptcha_secret"   => $recaptcha->secret,

                "log_level" => $log->level,

                "capability_setting_completed" => get_option("qms3_form_capability_setting_completed"),
            ]
        );

        wp_enqueue_script($handle);

        include(__DIR__."/../../assets/templates/qms3_form__submenu_page__brick_master.php");
    }

    public function enqueue_style()
    {
        wp_enqueue_style(
            /* $handle = */ "jsuites",
            /* $src    = */ plugins_url("../../assets/css/jsuites.css", __FILE__),
            /* $deps   = */ []
        );

        wp_enqueue_style(
            /* $handle = */ "jspreadsheet",
            /* $src    = */ plugins_url("../../assets/css/jspreadsheet.css", __FILE__),
            /* $deps   = */ []
        );

        wp_enqueue_style(
            /* $handle = */ "qms3_form__submenu_page__brick_master.css",
            /* $src    = */ plugins_url("../../assets/css/qms3_form__submenu_page__brick_master.css", __FILE__),
            /* $deps   = */ [ "jsuites", "jspreadsheet" ],
            /* $ver    = */ self::VERSION
        );
    }

    // ====================================================================== //

    /**
     * @return    bool
     */
    private function verify_nonce()
    {
        if (!isset($_POST["qms3_form__submenu_page__brick_master__nonce"])) {
            return false;
        }

        return wp_verify_nonce(
            $_POST["qms3_form__submenu_page__brick_master__nonce"],
            "qms3_form__submenu_page__brick_master__nonce"
        );
    }

    private function reset_capability_setting()
    {
        $capability_service = new CapabilityService();
        $capability_service->reset_caps();
    }

    private function save_mail_setting()
    {
        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) { return; }

        if (!current_user_can("atsumaru") && !current_user_can("administrator")) { return; }


        if (!isset($_POST["qms3_form__submenu_page__brick_master__mail_setting"])) { return; }

        $mail_setting_json = stripslashes($_POST["qms3_form__submenu_page__brick_master__mail_setting"]);
        $mail_setting = json_decode($mail_setting_json, /* $assoc = */ true);

        if (is_null($mail_setting)) {
            throw new RuntimeException("不正な JSON データです： \$_POST[\"qms3_form__submenu_page__brick_master__mail_setting\"]: $mail_setting_json");
        }


        $mail = DefaultMailSettings::get();

        $mail->default_mail_setting = $mail_setting;

        $mail->save();
    }

    private function save_smtp_options()
    {
        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) { return; }

        if (!current_user_can("atsumaru") && !current_user_can("administrator")) { return; }


        $smtp = SmtpSettings::get();

        $smtp->activate = isset($_POST["smtp_activate"]) ? $_POST["smtp_activate"] : null;
        $smtp->host     = isset($_POST["smtp_host"])     ? $_POST["smtp_host"]     : null;
        $smtp->port     = isset($_POST["smtp_port"])     ? $_POST["smtp_port"]     : null;
        $smtp->protocol = isset($_POST["smtp_protocol"]) ? $_POST["smtp_protocol"] : null;
        $smtp->user     = isset($_POST["smtp_user"])     ? $_POST["smtp_user"]     : null;
        $smtp->pass     = isset($_POST["smtp_pass"])     ? $_POST["smtp_pass"]     : null;

        $smtp->save();
    }

    private function save_recaptcha_options()
    {
        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) { return; }

        if (!current_user_can("atsumaru") && !current_user_can("administrator")) { return; }


        $recaptcha = RecaptchaSettings::get();

        $recaptcha->activate = isset($_POST["recaptcha_activate"]) ? $_POST["recaptcha_activate"] : null;
        $recaptcha->sitekey  = isset($_POST["recaptcha_sitekey"])  ? $_POST["recaptcha_sitekey"]  : null;
        $recaptcha->secret   = isset($_POST["recaptcha_secret"])   ? $_POST["recaptcha_secret"]   : null;

        $recaptcha->save();
    }

    private function save_log_options()
    {
        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) { return; }

        if (!current_user_can("atsumaru") && !current_user_can("administrator")) { return; }


        $log = LogSettings::get();

        $log->level = isset($_POST["log_level"]) ? $_POST["log_level"] : null;

        $log->save();
    }
}
