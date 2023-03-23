<?php
declare(strict_types=1);

namespace QMS3\Brick\Test\MailSettingFactory;

use PHPUnit\Framework\TestCase;
use QMS3\Brick\Enum\MailSettingType;
use QMS3\Brick\MailSettingFactory\MailSettingFactory;
use QMS3\Brick\ParamFactory\ParamFactory;

require_once("vendor/autoload.php");


class MailSettingFactoryTest extends TestCase
{
    public function test_サンクスメール設定を読み込む()
    {
        $param_factory = new ParamFactory();
        $param = $param_factory->create(
            "contact",
            [
                "structure_format"    => "JSON",
                "structure_dir"       => "structure_dir",
                "mail_setting_format" => "CSV",
                "mail_setting_dir"    => __DIR__."/assets",
                "thanks_path"         => "",
            ]
        );

        $mail_setting_type = new MailSettingType(MailSettingType::THANKS);

        $factory = new MailSettingFactory();
        $mail_setting = $factory->create_from_form_type(
            /* $param             = */ $param,
            /* $mail_setting_type = */ $mail_setting_type
        );

        $this->assertIsArray($mail_setting->tos);
        $this->assertEmpty($mail_setting->tos, '$tos is not empty.');

        $this->assertEquals("thanks-from@example.com", $mail_setting->from);

        $this->assertEquals("テスト株式会社", $mail_setting->from_name);

        $this->assertIsString($mail_setting->subject_template);
        $this->assertNotEmpty($mail_setting->subject_template, '$subject_template is empty.');

        $this->assertIsString($mail_setting->main_text_template);
        $this->assertNotEmpty($mail_setting->main_text_template, '$main_text_template is empty.');

        $this->assertIsArray($mail_setting->block_names);
        $this->assertNotEmpty($mail_setting->block_names, '$blocks is empty.');

        $this->assertIsString($mail_setting->post_result_template);
        $this->assertEmpty($mail_setting->post_result_template, '$post_result_template is not empty.');

        $this->assertIsString($mail_setting->after_text_template);
        $this->assertNotEmpty($mail_setting->after_text_template, '$after_text_template is empty.');

        $this->assertIsString($mail_setting->signature_template);
        $this->assertNotEmpty($mail_setting->signature_template, '$signature_template is empty.');
    }

    public function test_先方通知メール設定を読み込む()
    {
        $param_factory = new ParamFactory();
        $param = $param_factory->create(
            "contact",
            [
                "structure_format"    => "JSON",
                "structure_dir"       => "structure_dir",
                "mail_setting_format" => "CSV",
                "mail_setting_dir"    => __DIR__."/assets",
                "thanks_path"         => "",
            ]
        );

        $mail_setting_type = new MailSettingType(MailSettingType::NOTIFICATION);

        $factory = new MailSettingFactory();
        $mail_setting = $factory->create_from_form_type(
            /* $param             = */ $param,
            /* $mail_setting_type = */ $mail_setting_type
        );

        $this->assertIsArray($mail_setting->tos);
        $this->assertNotEmpty($mail_setting->tos, '$tos is empty.');

        $this->assertEquals("notification-from@example.com", $mail_setting->from);

        $this->assertEquals("テスト株式会社", $mail_setting->from_name);

        $this->assertIsString($mail_setting->subject_template);
        $this->assertNotEmpty($mail_setting->subject_template, '$subject_template is empty.');

        $this->assertIsString($mail_setting->main_text_template);
        $this->assertNotEmpty($mail_setting->main_text_template, '$main_text_template is empty.');

        $this->assertIsArray($mail_setting->block_names);
        $this->assertEmpty($mail_setting->block_names, '$blocks is not empty.');

        $this->assertIsString($mail_setting->post_result_template);
        $this->assertEmpty($mail_setting->post_result_template, '$post_result_template is not empty.');

        $this->assertIsString($mail_setting->after_text_template);
        $this->assertEmpty($mail_setting->after_text_template, '$after_text_template is not empty.');

        $this->assertIsString($mail_setting->signature_template);
        $this->assertEmpty($mail_setting->signature_template, '$signature_template is not empty.');
    }

    public function test_社内通知メール設定を読み込む()
    {
        $param_factory = new ParamFactory();
        $param = $param_factory->create(
            "contact",
            [
                "structure_format"    => "JSON",
                "structure_dir"       => "structure_dir",
                "mail_setting_format" => "CSV",
                "mail_setting_dir"    => __DIR__."/assets",
                "thanks_path"         => "",
            ]
        );

        $mail_setting_type = new MailSettingType(MailSettingType::BCC_NOTIFICATION);

        $factory = new MailSettingFactory();
        $mail_setting = $factory->create_from_form_type(
            /* $param             = */ $param,
            /* $mail_setting_type = */ $mail_setting_type
        );

        $this->assertIsArray($mail_setting->tos);
        $this->assertNotEmpty($mail_setting->tos, '$tos is empty.');

        $this->assertEquals("bcc-notification-from@example.com", $mail_setting->from);

        $this->assertEquals("テスト株式会社", $mail_setting->from_name);

        $this->assertIsString($mail_setting->subject_template);
        $this->assertNotEmpty($mail_setting->subject_template, '$subject_template is empty.');

        $this->assertIsString($mail_setting->main_text_template);
        $this->assertNotEmpty($mail_setting->main_text_template, '$main_text_template is empty.');

        $this->assertIsArray($mail_setting->block_names);
        $this->assertEmpty($mail_setting->block_names, '$blocks is not empty.');

        $this->assertIsString($mail_setting->post_result_template);
        $this->assertEmpty($mail_setting->post_result_template, '$post_result_template is not empty.');

        $this->assertIsString($mail_setting->after_text_template);
        $this->assertEmpty($mail_setting->after_text_template, '$after_text_template is not empty.');

        $this->assertIsString($mail_setting->signature_template);
        $this->assertEmpty($mail_setting->signature_template, '$signature_template is not empty.');
    }

    public function test_指定した_form_type_に対応するファイルが見つからないときは_default_が使われる()
    {
        $param_factory = new ParamFactory();
        $param = $param_factory->create(
            "unknown_form_type",
            [
                "structure_format"    => "JSON",
                "structure_dir"       => "structure_dir",
                "mail_setting_format" => "CSV",
                "mail_setting_dir"    => __DIR__."/assets",
                "thanks_path"         => "",
            ]
        );

        $mail_setting_type = new MailSettingType(MailSettingType::THANKS);

        $factory = new MailSettingFactory();
        $mail_setting = $factory->create_from_form_type(
            /* $param             = */ $param,
            /* $mail_setting_type = */ $mail_setting_type
        );

        $this->assertIsArray($mail_setting->tos);
        $this->assertEmpty($mail_setting->tos, '$tos is not empty.');

        $this->assertEquals("thanks-from@example.com", $mail_setting->from);

        $this->assertEquals("テスト株式会社", $mail_setting->from_name);

        $this->assertIsString($mail_setting->subject_template);
        $this->assertNotEmpty($mail_setting->subject_template, '$subject_template is empty.');

        $this->assertIsString($mail_setting->main_text_template);
        $this->assertNotEmpty($mail_setting->main_text_template, '$main_text_template is empty.');

        $this->assertIsArray($mail_setting->block_names);
        $this->assertNotEmpty($mail_setting->block_names, '$blocks is empty.');

        $this->assertIsString($mail_setting->post_result_template);
        $this->assertEmpty($mail_setting->post_result_template, '$post_result_template is not empty.');

        $this->assertIsString($mail_setting->after_text_template);
        $this->assertNotEmpty($mail_setting->after_text_template, '$after_text_template is empty.');

        $this->assertIsString($mail_setting->signature_template);
        $this->assertNotEmpty($mail_setting->signature_template, '$signature_template is empty.');
    }
}
