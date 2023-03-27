<?php
declare(strict_types=1);

namespace QMS3\Brick\MailGenerator;

use QMS3\BrickAdmin\Settings\NotificationSettings;
use QMS3\Brick\Form\Form;
use QMS3\Brick\MailGenerator\ThanksMailGenerator;
use QMS3\Brick\MailSetting\MailSetting;
use QMS3\Brick\Util\DeviceDetector;


class ThanksMailFromMasterGenerator extends ThanksMailGenerator
{
    /** @var    array<string,string> */
    private $settings = null;

    /** @var    string */
    private $name;

    /** @var    array<string,string> */
    private $map;

    /**
     * @param    string    $name
     * @param    array<string,string>    $map
     * @param    DeviceDetector|null    $device_detector
     */
    public function __construct( $name, array $map, ?DeviceDetector $device_detector = null )
    {
        $this->name = $name;
        $this->map = $map;

        parent::__construct( $device_detector );
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function from( MailSetting $mail_setting, Form $form )
    {
        $settings = $this->settings( $form );

        return empty( $settings[ 'from' ] )
            ? $mail_setting->from
            : $settings[ 'from' ];
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function from_name( MailSetting $mail_setting, Form $form )
    {
        $settings = $this->settings( $form );

        return empty( $settings[ 'from_name' ] )
            ? $mail_setting->from_name
            : $settings[ 'from_name' ];
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function subject( MailSetting $mail_setting, Form $form )
    {
        $settings = $this->settings( $form );

        $subject_template = empty( $settings[ 'subject_template' ] )
            ? $mail_setting->subject_template
            : $settings[ 'subject_template' ];

        return $this->replace( $subject_template, $form );
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function main_text(MailSetting $mail_setting, Form $form)
    {
        $settings = $this->settings( $form );

        $main_text_template = empty( $settings[ 'main_text_template' ] )
            ? $mail_setting->main_text_template
            : $settings[ 'main_text_template' ];

        return $this->replace( $main_text_template, $form );
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function after_text( MailSetting $mail_setting, Form $form )
    {
        $settings = $this->settings( $form );

        $after_text_template = empty( $settings[ 'after_text_template' ] )
            ? $mail_setting->after_text_template
            : $settings[ 'after_text_template' ];

        return $this->replace( $after_text_template, $form );
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function signature( MailSetting $mail_setting, Form $form )
    {
        $settings = $this->settings( $form );

        $signature_template = empty( $settings[ 'signature_template' ] )
            ? $mail_setting->signature_template
            : $settings[ 'signature_template' ];

        return $this->replace( $signature_template, $form );
    }

    // ====================================================================== //

    /**
     * @param    Form    $form
     * @return    array<string,string>
     */
    private function settings( Form $form )
    {
        if ( ! is_null( $this->settings ) ) { return $this->settings; }

        $slug = (string) $form->fields[ $this->name ]->value;

        if ( ! empty( $this->map[ $slug ] ) ) { $slug = $this->map[ $slug ]; }

        $notification = NotificationSettings::from_slug( $slug );

        return $this->settings = $notification->mail_settings[ 'thanks' ];
    }
}
