<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSettingLib;

use QMS3\Brick\Enum\MailSettingType;
use QMS3\Brick\MailSetting\MailSetting;
use QMS3\Brick\MailSetting\MailSettingOption;


class MailSettingLib
{
    /** @var    array<string,MailSetting> */
    private $settings = [];

    /**
     * @param     MailSettingType      $type
     * @param     MailSettingOption    $setting_option
     * @return    MailSettingLib
     */
    public function add(MailSettingType $type, MailSettingOption $setting_option)
    {
        $type_str = $type->value();

        if (!isset($this->settings[$type_str])) {
            $this->settings[$type_str] = new MailSetting();
        }

        $this->settings[$type_str]->add($setting_option);

        return $this;
    }

    /**
     * @param     MailSettingType    $type
     * @return    MailSetting
     */
    public function get(MailSettingType $type)
    {
        $type_str = $type->value();

        return isset($this->settings[$type_str])
            ? $this->settings[$type_str]
            : new MailSetting();
    }
}
