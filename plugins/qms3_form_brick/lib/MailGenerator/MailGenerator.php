<?php
declare(strict_types=1);

namespace QMS3\Brick\MailGenerator;

use QMS3\Brick\Form\Form;
use QMS3\Brick\Mail\Mail;
use QMS3\Brick\MailSetting\MailSetting;
use QMS3\Brick\Util\DeviceDetector;
use QMS3\Brick\ValueContainer\ValueContainerInterface as ValueContainer;


abstract class MailGenerator
{
    /** @var    array<string,ValueContainer> */
    protected $_values = null;

    /** @var    DeviceDetector */
    protected $device_detector;

    public function __construct( ?DeviceDetector $device_detector = null )
    {
        $this->device_detector = $device_detector
            ? $device_detector
            : new DeviceDetector($_SERVER);
    }

    // ====================================================================== //

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    Mail
     */
    final public function generate(MailSetting $mail_setting, Form $form)
    {
        $main_text   = $this->main_text($mail_setting, $form);
        $post_result = $this->post_result($mail_setting, $form);
        $after_text  = $this->after_text($mail_setting, $form);
        $signature   = $this->signature($mail_setting, $form);

        $body = join(
            "\n\n",
            array_filter([$main_text, $post_result, $after_text, $signature])
        );

        return new Mail(
            /* $tos       = */ $this->tos($mail_setting, $form),
            /* $from      = */ $this->from($mail_setting, $form),
            /* $from_name = */ $this->from_name($mail_setting, $form),
            /* $subject   = */ $this->subject($mail_setting, $form),
            /* $body      = */ $body
        );
    }

    // ====================================================================== //

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string[]
     */
    abstract protected function tos(MailSetting $mail_setting, Form $form);

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    abstract protected function from(MailSetting $mail_setting, Form $form);

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    abstract protected function from_name(MailSetting $mail_setting, Form $form);

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    abstract protected function subject(MailSetting $mail_setting, Form $form);

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    abstract protected function main_text(MailSetting $mail_setting, Form $form);

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    abstract protected function post_result(MailSetting $mail_setting, Form $form);

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    abstract protected function after_text(MailSetting $mail_setting, Form $form);

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    abstract protected function signature(MailSetting $mail_setting, Form $form);

    // ====================================================================== //

    /**
     * @param     Form                   $form
     * @return    array<string,ValueContainer>
     */
    protected function values(Form $form)
    {
        if (!is_null($this->_values)) { return $this->_values; }

        $values = [];
        foreach ($form->fields as $field) {
            if ($field->type == "title") { continue; }

            $values[$field->name] = $field->value;
        }

        $this->_values = $values;
        return $this->_values;
    }

    /**
     * @param     string                 $template
     * @param     Form                   $form
     * @return    array<string,mixed>
     */
    protected function replace($template, Form $form)
    {
        $device = $this->device_detector->detect();

        $raw_values = array_merge(
            [
                "form_name" => $form->form_name,
                "device"    => $device->value(),
            ],
            $this->values($form)
        );


        $values = [];
        foreach ($raw_values as $raw_value) {
            $values[] = is_array($raw_value)
                ? join("、", $raw_value)
                : $raw_value;
        }

        $placeholders = [];
        foreach (array_keys($raw_values) as $key) {
            $placeholders[] = "[{$key}]";
        }

        return str_replace($placeholders, $values, $template);
    }
}
