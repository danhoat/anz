<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSettingsLoader;

use QMS3\Brick\MailSetting\MailSettingOption;
use QMS3\Brick\MailSettingsLoader\MailSettingsLoaderInterface;


class JsonLoader implements MailSettingsLoaderInterface
{
    /**
     * @param     string    $filepath
     * @return    array<string,MailSettingOption>
     */
    public function load($filepath)
    {
        $json = file_get_contents($filepath);
        $data = json_decode($json, /* $associative = */ true);

        $setting_options = [];
        foreach ($data as $type_str => $setting_option) {
            $setting_options[$type_str] = new MailSettingOption(
                /* $tos_str              = */ trim($setting_option["tos"]),
                /* $from                 = */ trim($setting_option["from"]),
                /* $from_name            = */ trim($setting_option["from_name"]),
                /* $subject_template     = */ trim($setting_option["subject"]),
                /* $main_text_template   = */ $setting_option["main_text"],
                /* $block_filter         = */ $setting_option["block_filter"],
                /* $post_result_template = */ $setting_option["post_result"],
                /* $after_text_template  = */ $setting_option["after_text"],
                /* $signature_template   = */ $setting_option["signature"]
            );
        }

        return $setting_options;
    }
}
