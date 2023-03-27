<?php
declare(strict_types=1);

namespace QMS3\Brick;

use QMS3\Brick\Enum\MailSettingType;
use QMS3\Brick\Form\Form;
use QMS3\Brick\MailSender\MailSender;
use QMS3\Brick\MailSettingLib\MailSettingLib;
use QMS3\Brick\MailSettingLibFactory\MailSettingLibFactory;
use QMS3\Brick\Param\Param;


class SendMails
{
    /** @var    Param */
    private $param;

    /** @var    MailSettingLib */
    private $mail_setting_lib;

    /** @var    MailSender */
    private $sender;

    /**
     * @param    string    $form_type
     * @param    Param     $param
     */
    public function __construct($form_type, Param $param)
    {
        $this->param     = $param;

        $factory = new MailSettingLibFactory();
        $this->mail_setting_lib = $factory->create($form_type, $param);

        $this->sender  = new MailSender($param);
    }

    // ====================================================================== //

    /**
     * @param     Form    $form
     * @return    void
     */
    public function send_mails(Form $form)
    {
        $this->send_thanks_mail($form);
        $this->send_notification_mail($form);
        $this->send_bcc_notification_mail($form);
    }

    /**
     * @param     Form    $form
     * @return    bool             メール送信処理が正常終了していれば true, さもなくば false
     */
    private function send_thanks_mail(Form $form)
    {
        $mail_setting_type = new MailSettingType(MailSettingType::THANKS);
        $mail_setting      = $this->mail_setting_lib->get($mail_setting_type);
        $mail_generator    = $this->param->mail_generators[$mail_setting_type->value()];

        $mail = $mail_generator->generate($mail_setting, $form);
        return $this->sender->send($mail);
    }

    /**
     * @param     Form    $form
     * @return    bool             メール送信処理が正常終了していれば true, さもなくば false
     */
    private function send_notification_mail(Form $form)
    {
        $mail_setting_type = new MailSettingType(MailSettingType::NOTIFICATION);
        $mail_setting      = $this->mail_setting_lib->get($mail_setting_type);
        $mail_generator    = $this->param->mail_generators[$mail_setting_type->value()];

        $mail = $mail_generator->generate($mail_setting, $form);
        return $this->sender->send($mail);
    }

    /**
     * @param     Form    $form
     * @return    bool             メール送信処理が正常終了していれば true, さもなくば false
     */
    private function send_bcc_notification_mail(Form $form)
    {
        $mail_setting_type = new MailSettingType(MailSettingType::BCC_NOTIFICATION);
        $mail_setting      = $this->mail_setting_lib->get($mail_setting_type);
        $mail_generator    = $this->param->mail_generators[$mail_setting_type->value()];

        $mail = $mail_generator->generate($mail_setting, $form);
        return $this->sender->send($mail);
    }
}
