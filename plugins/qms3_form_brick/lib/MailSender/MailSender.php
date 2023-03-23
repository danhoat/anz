<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSender;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use QMS3\Brick\Logger\MailLogger;
use QMS3\Brick\Mail\Mail;
use QMS3\Brick\Param\Param;


class MailSender
{
    /** @var    Param */
    private $param;

    /**
     * @param    Param    $param
     */
    public function __construct(Param $param)
    {
        $this->param = $param;
    }

    /**
     * メール送信処理
     *
     * @param     Mail    $mail
     * @return    bool             送信処理が全ての宛先に対して正常終了していれば true, さもなくば false
     */
    public function send(Mail $mail)
    {
        $results = [];

        foreach ($mail->tos as $to) {
            try {
                $sender = $this->init_sender($mail->from);

                $sender->addAddress($to);
                $sender->setFrom($mail->from, $mail->from_name ?: null);
                $sender->addReplyTo($mail->from, $mail->from_name ?: null);

                $sender->Subject = $mail->subject;
                $sender->Body    = $mail->body;

                $results[] = $sender->send();
            }
            catch (PHPMailerException $e) {
                // TODO: 例外処理ちゃんとする
                $results[] = false;
            }
        }

        return array_filter($results, function ($result) { return !$result; }) == false;
    }

    /**
     * @param     Param          $param
     * @param     string|null    $from
     * @return    PHPMailer
     */
    private function init_sender($from = null)
    {
        $sender = new PHPMailer(true);

        $sender->SMTPDebug = SMTP::DEBUG_SERVER;
        $sender->CharSet = 'UTF-8';

        if (
            $this->param->smtp_activate
            && $this->param->smtp_host
            && $this->param->smtp_user
            && $this->param->smtp_pass
        ) {
            $sender->isSMTP();
            $sender->Host       = $this->param->smtp_host;
            $sender->SMTPAuth   = true;
            $sender->Username   = $this->param->smtp_user;
            $sender->Password   = $this->param->smtp_pass;
            $sender->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $sender->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $sender->Port       = $this->param->smtp_port;
        }

        $sender->Debugoutput = function ($message, $level) {
            // TODO: LogLevel ちゃんとする
            MailLogger::info($message);
        };

        return $sender;
    }
}
