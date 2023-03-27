<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSettingsLoader;

use InvalidArgumentException;
use LogicException;
use RuntimeException;
use SplFileObject;
use QMS3\Brick\Enum\MailSettingType;
use QMS3\Brick\MailSetting\MailSetting;


class ArrayLoader
{
    /**
     * @param     array<string,array>    $mail_settings
     * @param     MailSettingType        $type
     * @return    array<string,mixed>
     */
    public function load(array $mail_settings, MailSettingType $type)
    {
        switch ($type->value()) {
            case MailSettingType::THANKS:
                if (isset($mail_settings["サンクスメール"])) {
                    $mail_setting = $mail_settings["サンクスメール"];
                } else {
                    throw new RuntimeException("サンクスメール設定が見つかりません");
                }
                break;

            case MailSettingType::NOTIFICATION:
                if (isset($mail_settings["先方通知メール"])) {
                    $mail_setting = $mail_settings["先方通知メール"];
                } else {
                    throw new RuntimeException("先方通知メール設定が見つかりません");
                }
                break;

            case MailSettingType::BCC_NOTIFICATION:
                if (isset($mail_settings["社内通知メール"])) {
                    $mail_setting = $mail_settings["社内通知メール"];
                } else {
                    throw new RuntimeException("社内通知メール設定が見つかりません");
                }
                break;

            default: throw new LogicException("不明な MailSettingType です: \$type: {$type}");
        }

        return new MailSetting(
            /* $tos_str              = */ $mail_setting["tos_str"],
            /* $from                 = */ $mail_setting["from"],
            /* $from_name            = */ $mail_setting["from_name"],
            /* $subject_template     = */ $mail_setting["subject_template"],
            /* $main_text_template   = */ $mail_setting["main_text_template"],
            /* $post_result_template = */ $mail_setting["post_result_template"],
            /* $after_text_template  = */ $mail_setting["after_text_template"],
            /* $signature_template   = */ $mail_setting["signature_template"]
        );
    }
}
