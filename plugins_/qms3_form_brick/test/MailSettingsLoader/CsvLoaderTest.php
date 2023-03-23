<?php
declare(strict_types=1);

namespace QMS3\Brick\Test\MailSettingsLoader;

use PHPUnit\Framework\TestCase;
use QMS3\Brick\Enum\MailSettingType;
use QMS3\Brick\MailSetting\MailSetting;
use QMS3\Brick\MailSettingsLoader\CsvLoader;

require_once("vendor/autoload.php");


class CsvLoaderTest extends TestCase
{
    public function test_CSV_形式の_Mail_Setting_を読み込む()
    {
        $type = new MailSettingType(MailSettingType::THANKS);

        $loader = new CsvLoader();
        $mail_setting = $loader->load(__DIR__."/assets/default.csv", $type);

        $this->assertInstanceOf(MailSetting::class, $mail_setting);

        $this->assertEquals([], $mail_setting->tos);
        $this->assertEquals("thanks-from@example.com", $mail_setting->from);
        $this->assertEquals("テスト株式会社", $mail_setting->from_name);
        $this->assertEquals("[form_name]を承りました", $mail_setting->subject_template);
        $this->assertEquals(["form_block01"], $mail_setting->block_names);

        $this->assertEquals("[name01] 様、

テスト株式会社よりご連絡申し上げます。
この度は[form_name]を承りまして、誠にありがとうございます。

お申込み内容を確認させていただき、
改めて担当者よりご連絡差し上げますので、
今しばらくお待ちくださいますようお願い申し上げます。
", $mail_setting->main_text_template);

        $this->assertEquals("
=================================

通常、お問い合わせのご返信は2営業日以内を心がけております。
数日経過致しましても当社からの返信がない場合は、
ご入力いただいたメールアドレスに間違い、又はお客様のメールサーバーなどにより
自動的に「迷惑メール」として格納または削除された可能性がございます。
以上をご確認の上、お手数ですが再度お問い合わせくださいますようお願い申し上げます。", $mail_setting->after_text_template);


$this->assertEquals("
=================================
【テスト株式会社】　
〒000-0000　東京都新宿区西新宿２丁目８?１
お問い合わせ　TEL　0120-000-000
HP: https://example.com
=================================", $mail_setting->signature_template);
    }
}
