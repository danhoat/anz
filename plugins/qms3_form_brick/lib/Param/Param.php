<?php
declare(strict_types=1);

namespace QMS3\Brick\Param;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;
use Monolog\Logger;
use QMS3\Brick\Enum\MailSettingFormat;
use QMS3\Brick\Enum\MailSettingType;
use QMS3\Brick\Enum\StructureFormat;
use QMS3\Brick\Exception\UnknownKeyException;
use QMS3\Brick\MailGenerator\MailGenerator;
use QMS3\Brick\MailGenerator\ThanksMailGenerator;
use QMS3\Brick\MailGenerator\NotificationMailGenerator;
use QMS3\Brick\MailGenerator\BccNotificationMailGenerator;
use QMS3\Brick\Step\Step;


/**
 * @property-read    string                         $form_name
 * @property-read    Step|null                      $step
 *
 * @property-read    StructureFormat                $structure_format
 * @property-read    string                         $structure_ext
 * @property-read    string                         $structure_dir
 * @property-read    string                         $structure_name
 *
 * @property-read    MailSettingFormat              $mail_setting_format
 * @property-read    string                         $mail_setting_ext
 * @property-read    string                         $mail_setting_dir
 * @property-read    string                         $mail_setting_name
 *
 * @property-read    bool                           $smtp_activate
 * @property-read    string                         $smtp_host
 * @property-read    int                            $smtp_port
 * @property-read    string                         $smtp_protocol
 * @property-read    string                         $smtp_user
 * @property-read    string                         $smtp_pass
 *
 * @property-read    int                            $log_level
 * @property-read    string                         $log_path
 * @property-read    string                         $log_filename
 *
 * @property-read    string                         $thanks_path
 * @property-read    string|null                    $pc_thanks_path
 * @property-read    string|null                    $sp_thanks_path
 *
 * @property-read    bool                           $hand_over
 *
 * @property-read    array<string,MailGenerator>    $mail_generators
 * @property-read    array<string,mixed>            $pre_processes
 * @property-read    array<string,mixed>            $post_processes
 * @property-read    array<string,mixed>            $sub_tasks
 *
 * @property-read    array<string,mixed>            $default
 * @property-read    array<string,string>           $options
 *
 * @property-read    bool                           $recaptcha_activate
 * @property-read    string|null                    $recaptcha_sitekey
 * @property-read    string|null                    $recaptcha_secret
 */
class Param
{
    /** @var    string */
    private $form_type;

    /** @var    array<string,mixed> */
    private $param;

    /**
     * @param    string                 $form_type
     * @param    array<string,mixed>    $param
     */
    public function __construct($form_type, array $param)
    {
        $this->form_type = $form_type;
        $this->param     = $param;
    }

    /**
     * @param    string    $name
     */
    public function __get($name)
    {
        $method_name = "_{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        $value = isset($this->param[$name]) ? $this->param[$name] : null;

        return call_user_func([$this, $method_name], $value);
    }

    /**
     * @return    array<string,mixed>
     */
    public function __debugInfo()
    {
        $class_ref = new ReflectionClass($this);

        $properties = [];

        $property_refs = $class_ref->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($property_refs as $property_ref) {
            $name  = $property_ref->getName();
            $properties[$name] = $this->$name;
        }

        $method_refs = $class_ref->getMethods(ReflectionMethod::IS_PROTECTED);
        foreach ($method_refs as $method_ref) {
            $method_name = $method_ref->getName();

            if (!preg_match("/^_[^_].+$/", $method_name)) { continue; }

            $name = substr($method_name, 1);
            $properties[$name] = $this->$name;
        }

        return $properties;
    }

    // ====================================================================== //

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _form_name($value)
    {
        return is_null($value) ? "" : trim($value);
    }

    /**
     * @param     string|null    $value
     * @return    Step|null
     */
    protected function _step($value)
    {
        return is_null($value) || trim($value) == false
            ? null
            : new Step($value);
    }

    /**
     * @param     string|null         $value
     * @return    StructureFormat
     */
    protected function _structure_format($value)
    {
        if (is_null($value) || trim($value) == false) {
            if (!defined("QMS3_FORM_STRUCTURE_FORMAT")) {
                throw new RuntimeException("定数 QMS3_FORM_STRUCTURE_FORMAT が定義されていません。");
            }

            $structure_format_str = strtoupper(QMS3_FORM_STRUCTURE_FORMAT);
            return new StructureFormat($structure_format_str);
        } else {
            $structure_format_str = strtoupper(trim($value));
            return new StructureFormat($structure_format_str);
        }
    }

    /**
     * @param     string|null         $value
     * @return    string
     */
    protected function _structure_ext($value)
    {
        return is_null($value) || trim($value) == false
            ? strtolower($this->structure_format->value())
            : trim($value);
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _structure_dir($value)
    {
        if (is_null($value) || trim($value) == false) {
            if (!defined("QMS3_FORM_STRUCTURE_DIR")) {
                throw new RuntimeException("定数 QMS3_FORM_STRUCTURE_DIR が定義されていません。");
            }

            return trim(QMS3_FORM_STRUCTURE_DIR);
        } else {
            return trim($value);
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _structure_name($value)
    {
        return is_null($value) || trim($value) == false
            ? $this->form_type
            : trim($value);
    }

    /**
     * @param     string|null          $value
     * @return    MailSettingFormat
     */
    protected function _mail_setting_format($value)
    {
        if (is_null($value) || trim($value) == false) {
            if (!defined("QMS3_FORM_MAIL_SETTING_FORMAT")) {
                throw new RuntimeException("定数 QMS3_FORM_MAIL_SETTING_FORMAT が定義されていません。");
            }

            $mail_setting_format_str = strtoupper(QMS3_FORM_MAIL_SETTING_FORMAT);
            return new MailSettingFormat($mail_setting_format_str);
        } else {
            $mail_setting_format_str = strtoupper(trim($value));
            return new MailSettingFormat($mail_setting_format_str);
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _mail_setting_ext($value)
    {
        return is_null($value) || trim($value) == false
            ? strtolower($this->mail_setting_format->value())
            : trim($value);
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _mail_setting_dir($value)
    {
        if (is_null($value) || trim($value) == false) {
            if (!defined("QMS3_FORM_MAIL_SETTING_DIR")) {
                throw new RuntimeException("定数 QMS3_FORM_MAIL_SETTING_DIR が定義されていません。");
            }

            return trim(QMS3_FORM_MAIL_SETTING_DIR);
        } else {
            return trim($value);
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _mail_setting_name($value)
    {
        return empty($value) ? $this->form_type : trim($value);
    }

    /**
     * @param     bool|null    $value
     * @return    bool
     */
    protected function _smtp_activate($value)
    {
        if (!is_null($value)) {
            return (bool) $value;
        } else if (defined("QMS3_FORM_SMTP_ACTIVATE")) {
            return QMS3_FORM_SMTP_ACTIVATE;
        } else {
            return false;
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _smtp_host($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FORM_SMTP_HOST")) {
            return trim(QMS3_FORM_SMTP_HOST);
        } else {
            return null;
        }
    }

    /**
     * @param     int|null    $value
     * @return    int
     */
    protected function _smtp_port($value)
    {
        if (!is_null($value)) {
            return (int) $value;
        } else if (defined("QMS3_FORM_SMTP_PORT")) {
            return QMS3_FORM_SMTP_PORT;
        } else {
            return 587;
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _smtp_protocol($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FORM_SMTP_PROTOCOL")) {
            return trim(QMS3_FORM_SMTP_PROTOCOL);
        } else {
            return "SMTP_AUTH";
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _smtp_user($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FORM_SMTP_USER")) {
            return trim(QMS3_FORM_SMTP_USER);
        } else {
            return null;
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _smtp_pass($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FORM_SMTP_PASS")) {
            return trim(QMS3_FORM_SMTP_PASS);
        } else {
            return null;
        }
    }

    /**
     * @param     int|null    $value
     * @return    int
     */
    protected function _log_level($value)
    {
        if (!is_null($value)) {
            return $value;
        } else if (defined("QMS3_FORM_LOG_LEVEL")) {
            return QMS3_FORM_LOG_LEVEL;
        } else {
            return Logger::INFO;
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _log_path($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FORM_LOG_PATH")) {
            return QMS3_FORM_LOG_PATH;
        } else {
            return QMS3_FORM_ROOT . "/log/";
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _log_filename($value)
    {

        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FORM_LOG_FILENAME")) {
            return QMS3_FORM_LOG_FILENAME;
        } else {
            $now = new DateTime();
            return "{$now->format('Ym')}.log";
        }
    }

    /**
     * @param     string|null    $value
     * @return    string
     */
    protected function _thanks_path($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else {
            return file_exists("thanks.php") ? "thanks.php" : "thanks.html";
        }
    }

    /**
     * @param     string|null    $value
     * @return    string|null
     */
    protected function _pc_thanks_path($value)
    {
        return is_null($value) || trim($value) == false
            ? null
            : trim($value);
    }

    /**
     * @param     string|null    $value
     * @return    string|null
     */
    protected function _sp_thanks_path($value)
    {
        return is_null($value) || trim($value) == false
            ? null
            : trim($value);
    }

    /**
     * @param     mixed    $value
     * @return    bool
     */
    protected function _hand_over($value)
    {
        return (bool) $value;
    }

    /**
     * @param     array<string,MailGenerator>|null    $value
     * @return    array<string,MailGenerator>
     */
    protected function _mail_generators($value)
    {
        return [
            MailSettingType::THANKS           => $this->thanks_mail_generator($value),
            MailSettingType::NOTIFICATION     => $this->notification_mail_generator($value),
            MailSettingType::BCC_NOTIFICATION => $this->bcc_notification_mail_generator($value),
        ];
    }

    /**
     * @param     array<string,MailGenerator>|null    $value
     * @return    array<string,MailGenerator>
     */
    private function thanks_mail_generator($value)
    {
        if (
            isset($value[MailSettingType::THANKS])
            && $value[MailSettingType::THANKS] instanceof MailGenerator
        ) {
            return $value[MailSettingType::THANKS];
        }

        if (
            isset($this->param["thanks_mail"])
            && $this->param["thanks_mail"] instanceof MailGenerator
        ) {
            return $this->param["thanks_mail"];
        }

        if (
            isset($this->param["thanks_mail"])
            && ($generator = $this->param["thanks_mail"])
            && is_string($generator)
            && class_exists($generator)
            && ($generator = new $generator)
            && $generator instanceof MailGenerator
        ) {
            return $generator;
        }

        return new ThanksMailGenerator();
    }

    /**
     * @param     array<string,MailGenerator>|null    $value
     * @return    array<string,MailGenerator>
     */
    private function notification_mail_generator($value)
    {
        if (
            isset($value[MailSettingType::NOTIFICATION])
            && $value[MailSettingType::NOTIFICATION] instanceof MailGenerator
        ) {
            return $value[MailSettingType::NOTIFICATION];
        }

        if (
            isset($this->param["notice_mail"])
            && $this->param["notice_mail"] instanceof MailGenerator
        ) {
            return $this->param["notice_mail"];
        }

        if (
            isset($this->param["notice_mail"])
            && ($generator = $this->param["notice_mail"])
            && is_string($generator)
            && class_exists($generator)
            && ($generator = new $generator)
            && $generator instanceof MailGenerator
        ) {
            return $generator;
        }

        return new NotificationMailGenerator();
    }

    /**
     * @param     array<string,MailGenerator>|null    $value
     * @return    array<string,MailGenerator>
     */
    private function bcc_notification_mail_generator($value)
    {
        if (
            isset($value[MailSettingType::BCC_NOTIFICATION])
            && $value[MailSettingType::BCC_NOTIFICATION] instanceof MailGenerator
        ) {
            return $value[MailSettingType::BCC_NOTIFICATION];
        }

        if (
            isset($this->param["bcc_mail"])
            && $this->param["bcc_mail"] instanceof MailGenerator
        ) {
            return $this->param["bcc_mail"];
        }

        if (
            isset($this->param["bcc_mail"])
            && ($generator = $this->param["bcc_mail"])
            && is_string($generator)
            && class_exists($generator)
            && ($generator = new $generator)
            && $generator instanceof MailGenerator
        ) {
            return $generator;
        }

        return new BccNotificationMailGenerator();
    }

    /**
     * @return    array<string,mixed>
     */
    protected function _pre_processes()
    {
        $valuess = [
            empty($this->param["preprocess"])    ? [] : $this->param["preprocess"],
            empty($this->param["preprocesses"])  ? [] : $this->param["preprocesses"],
            empty($this->param["pre_process"])   ? [] : $this->param["pre_process"],
            empty($this->param["pre_processes"]) ? [] : $this->param["pre_processes"],
        ];
        $values = array_merge(...$valuess);

        return array_values($values);
    }

    /**
     * @return    array<string,mixed>
     */
    protected function _post_processes()
    {
        $valuess = [
            empty($this->param["postprocess"])    ? [] : $this->param["postprocess"],
            empty($this->param["postprocesses"])  ? [] : $this->param["postprocesses"],
            empty($this->param["post_process"])   ? [] : $this->param["post_process"],
            empty($this->param["post_processes"]) ? [] : $this->param["post_processes"],
        ];
        $values = array_merge(...$valuess);

        return array_values($values);
    }

    /**
     * @return    array<string,mixed>
     */
    protected function _sub_tasks()
    {
        $valuess = [
            empty($this->param["postprocess"])    ? [] : $this->param["postprocess"],
            empty($this->param["postprocesses"])  ? [] : $this->param["postprocesses"],
            empty($this->param["post_process"])   ? [] : $this->param["post_process"],
            empty($this->param["post_processes"]) ? [] : $this->param["post_processes"],
            empty($this->param["sub_task"])       ? [] : $this->param["sub_task"],
            empty($this->param["sub_tasks"])      ? [] : $this->param["sub_tasks"],
        ];
        $values = array_merge(...$valuess);

        return array_values($values);
    }

    /**
     * @param     mixed|null    $value
     * @return    array<string,mixed>
     */
    protected function _default($value)
    {
        return is_array($value) ? $value : [];
    }

    /**
     * @param     mixed|null    $value
     * @return    array<string,mixed>
     */
    protected function _options($value)
    {
        return is_array($value) ? $value : [];
    }

    /**
     * @param     string|null    $value
     * @return    string|null
     */
    protected function _recaptcha_activate($value)
    {
        if (!is_null($value)) {
            return (bool) $value;
        } else if (
            !empty($this->param["recaptcha_sitekey"]) && trim($this->param["recaptcha_sitekey"]) != false
            && !empty($this->param["recaptcha_secret"]) && trim($this->param["recaptcha_secret"]) != false
        ) {
            return true;
        } else if (defined("QMS3_FROM_RECAPTCHA_ACTIVATE")) {
            return QMS3_FROM_RECAPTCHA_ACTIVATE;
        } else {
            return false;
        }
    }

    /**
     * @param     string|null    $value
     * @return    string|null
     */
    protected function _recaptcha_sitekey($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FROM_RECAPTCHA_SITEKEY")) {
            return QMS3_FROM_RECAPTCHA_SITEKEY;
        } else {
            return null;
        }
    }

    /**
     * @param     string|null    $value
     * @return    string|null
     */
    protected function _recaptcha_secret($value)
    {
        if (!is_null($value) && trim($value) != false) {
            return trim($value);
        } else if (defined("QMS3_FROM_RECAPTCHA_SECRET")) {
            return QMS3_FROM_RECAPTCHA_SECRET;
        } else {
            return null;
        }
    }
}
