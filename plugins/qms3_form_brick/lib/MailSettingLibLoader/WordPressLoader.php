<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSettingLibLoader;

use RuntimeException;
use QMS3\Brick\Enum\MailSettingType as Type;
use QMS3\Brick\MailSettingLib\MailSettingLib;
use QMS3\Brick\MailSetting\MailSettingOption;
use QMS3\BrickAdmin\Settings\CustomMailSettings;
use QMS3\BrickAdmin\Settings\DefaultMailSettings;


class WordPressLoader
{
    /**
     * @param     string    $slug
     * @param     string    $mail_setting_name
     * @return    MailSettingLib
     */
    public function load($slug, $mail_setting_name)
    {
        if (
            !class_exists("WP_Query")
            || !function_exists("get_post_meta")
            || !function_exists("get_option")
        ) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        if (trim($slug) == false) {
            throw new RuntimeException("不正な \$form_name です。 \$form_name は空文字ではいけません。");
        }

        $lib = new MailSettingLib();

        $options = $this->load_from_post($slug, $mail_setting_name);
        if ($options) {
            foreach ($options as $mail_setting_type => $mail_setting_option) {
                $lib->add(new Type($mail_setting_type), $mail_setting_option);
            }
        }

        $options = $this->load_from_option();
        if ($options) {
            foreach ($options as $mail_setting_type => $mail_setting_option) {
                $lib->add(new Type($mail_setting_type), $mail_setting_option);
            }
        }

        return $lib;
    }

    /**
     * @param     string    $slug
     * @param     string    $mail_setting_name
     * @return    array<string,MailSettingOption>
     */
    private function load_from_post($slug, $mail_setting_name)
    {
        $mail = CustomMailSettings::get($slug);

        foreach ($mail->mail_settings as $mail_setting) {
            if (trim($mail_setting["name"]) != $mail_setting_name) { continue; }

            return [
                "THANKS" => new MailSettingOption(
                    /* $tos_str              = */ $mail_setting["thanks"]["tos_str"],
                    /* $from                 = */ $mail_setting["thanks"]["from"],
                    /* $from_name            = */ $mail_setting["thanks"]["from_name"],
                    /* $subject_template     = */ $mail_setting["thanks"]["subject_template"],
                    /* $main_text_template   = */ $mail_setting["thanks"]["main_text_template"],
                    /* $block_filter         = */ $mail_setting["thanks"]["block_filter"],
                    /* $post_result_template = */ $mail_setting["thanks"]["post_result_template"],
                    /* $after_text_template  = */ $mail_setting["thanks"]["after_text_template"],
                    /* $signature_template   = */ $mail_setting["thanks"]["signature_template"]
                ),

                "NOTIFICATION" => new MailSettingOption(
                    /* $tos_str              = */ $mail_setting["notification"]["tos_str"],
                    /* $from                 = */ $mail_setting["notification"]["from"],
                    /* $from_name            = */ $mail_setting["notification"]["from_name"],
                    /* $subject_template     = */ $mail_setting["notification"]["subject_template"],
                    /* $main_text_template   = */ $mail_setting["notification"]["main_text_template"],
                    /* $block_filter         = */ $mail_setting["notification"]["block_filter"],
                    /* $post_result_template = */ $mail_setting["notification"]["post_result_template"],
                    /* $after_text_template  = */ $mail_setting["notification"]["after_text_template"],
                    /* $signature_template   = */ $mail_setting["notification"]["signature_template"]
                ),

                "BCC_NOTIFICATION" => new MailSettingOption(
                    /* $tos_str              = */ $mail_setting["bccNotification"]["tos_str"],
                    /* $from                 = */ $mail_setting["bccNotification"]["from"],
                    /* $from_name            = */ $mail_setting["bccNotification"]["from_name"],
                    /* $subject_template     = */ $mail_setting["bccNotification"]["subject_template"],
                    /* $main_text_template   = */ $mail_setting["bccNotification"]["main_text_template"],
                    /* $block_filter         = */ $mail_setting["bccNotification"]["block_filter"],
                    /* $post_result_template = */ $mail_setting["bccNotification"]["post_result_template"],
                    /* $after_text_template  = */ $mail_setting["bccNotification"]["after_text_template"],
                    /* $signature_template   = */ $mail_setting["bccNotification"]["signature_template"]
                ),
            ];
        }

        return [];
    }

    /**
     * @return    array<string,MailSettingOption>
     */
    private function load_from_option()
    {
        $mail = DefaultMailSettings::get();
        $mail_setting = $mail->default_mail_setting;

        return [
            "THANKS" => new MailSettingOption(
                /* $tos_str              = */ $mail_setting["thanks"]["tos_str"],
                /* $from                 = */ $mail_setting["thanks"]["from"],
                /* $from_name            = */ $mail_setting["thanks"]["from_name"],
                /* $subject_template     = */ $mail_setting["thanks"]["subject_template"],
                /* $main_text_template   = */ $mail_setting["thanks"]["main_text_template"],
                /* $block_filter         = */ $mail_setting["thanks"]["block_filter"],
                /* $post_result_template = */ $mail_setting["thanks"]["post_result_template"],
                /* $after_text_template  = */ $mail_setting["thanks"]["after_text_template"],
                /* $signature_template   = */ $mail_setting["thanks"]["signature_template"]
            ),

            "NOTIFICATION" => new MailSettingOption(
                /* $tos_str              = */ $mail_setting["notification"]["tos_str"],
                /* $from                 = */ $mail_setting["notification"]["from"],
                /* $from_name            = */ $mail_setting["notification"]["from_name"],
                /* $subject_template     = */ $mail_setting["notification"]["subject_template"],
                /* $main_text_template   = */ $mail_setting["notification"]["main_text_template"],
                /* $block_filter         = */ $mail_setting["notification"]["block_filter"],
                /* $post_result_template = */ $mail_setting["notification"]["post_result_template"],
                /* $after_text_template  = */ $mail_setting["notification"]["after_text_template"],
                /* $signature_template   = */ $mail_setting["notification"]["signature_template"]
            ),

            "BCC_NOTIFICATION" => new MailSettingOption(
                /* $tos_str              = */ $mail_setting["bccNotification"]["tos_str"],
                /* $from                 = */ $mail_setting["bccNotification"]["from"],
                /* $from_name            = */ $mail_setting["bccNotification"]["from_name"],
                /* $subject_template     = */ $mail_setting["bccNotification"]["subject_template"],
                /* $main_text_template   = */ $mail_setting["bccNotification"]["main_text_template"],
                /* $block_filter         = */ $mail_setting["bccNotification"]["block_filter"],
                /* $post_result_template = */ $mail_setting["bccNotification"]["post_result_template"],
                /* $after_text_template  = */ $mail_setting["bccNotification"]["after_text_template"],
                /* $signature_template   = */ $mail_setting["bccNotification"]["signature_template"]
            ),
        ];
    }
}
