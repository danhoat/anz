<?php
declare(strict_types=1);

namespace QMS3\Brick\Test\MailGenerator;

use PHPUnit\Framework\TestCase;
use QMS3\Brick\Form\Form;
use QMS3\Brick\MailGenerator\ThanksMailGenerator;
use QMS3\Brick\MailSetting\MailSetting;
use QMS3\Brick\Step\Step;
use QMS3\Brick\StructureFactory\StructureFactory;
use QMS3\Brick\Values\Values;

require_once("vendor/autoload.php");


class ThanksMailGeneratorTest extends TestCase
{
    /** @var    MailSetting */
    private $mail_setting;

    /** @var    Form */
    private $form;

    public function setUp()
    {
        $this->mail_setting = new MailSetting(
            /* $tos_str              = */ "",
            /* $from                 = */ "thanks-from@atsu-maru.co.jp",
            /* $from_name            = */ "テスト株式会社 テスト担当",
            /* $subject_template     = */ "[form_name]を承りました",
            /* $main_text_template   = */ "[name01] 様、

テスト株式会社よりご連絡申し上げます。
この度は[form_name]を承りまして、誠にありがとうございます。

お申込み内容を確認させていただき、
改めて担当者よりご連絡差し上げますので、
今しばらくお待ちくださいますようお願い申し上げます。",
            /* $block_filter         = */ "",
            /* $post_result_template = */ "",
            /* $after_text_template  = */ "
=================================

通常、お問い合わせのご返信は2営業日以内を心がけております。
数日経過致しましても当社からの返信がない場合は、
ご入力いただいたメールアドレスに間違い、又はお客様のメールサーバーなどにより
自動的に「迷惑メール」として格納または削除された可能性がございます。
以上をご確認の上、お手数ですが再度お問い合わせくださいますようお願い申し上げます。",
            /* $signature_template   = */ "
=================================
【テスト株式会社】　
〒000-0000　東京都新宿区西新宿2丁目8-1
お問い合わせ　TEL　0120-000-000
HP: https://example.com
================================="
        );


        $name = "お問い合わせ";

        $factory = new StructureFactory();
        $structure_table = require(__DIR__."/assets/valid_structure_table.php");
        $structure = $factory->create($structure_table);

        $values = new Values(
            /* $global_get  = */ [],
            /* $global_post = */ [
                "company" => "株式会社カンパニー",
                "name01"  => "山田太郎",
                "dept"    => "営業部",
                "email"   => "test@example.com",
                "tel"     => "0120-000-000",
                "content" => [ "集客", "DI" ],
                "contact" => "テストお困りごと\nテスト\nテスト",
            ],
        );

        $step = new Step(Step::SUBMIT);

        $this->form = new Form($name, $structure, $values, $step);
    }

    public function test_()
    {
        $generator = new ThanksMailGenerator();
        $mail = $generator->generate($this->mail_setting, $this->form);

        $this->assertEquals(
            $mail->to_array(),
            [
                "tos"       => [ "test@example.com" ],
                "from"      => "thanks-from@atsu-maru.co.jp",
                "from_name" => "テスト株式会社 テスト担当",
                "subject"   => "お問い合わせを承りました",
                "body"      => "山田太郎 様、

テスト株式会社よりご連絡申し上げます。
この度はお問い合わせを承りまして、誠にありがとうございます。

お申込み内容を確認させていただき、
改めて担当者よりご連絡差し上げますので、
今しばらくお待ちくださいますようお願い申し上げます。

会社名　：　株式会社カンパニー
お名前　：　山田太郎
部署名　：　営業部
メールアドレス　：　test@example.com
携帯電話　：　0120-000-000
お問合せ内容　：　集客、DI
今のお困りごと　：　テストお困りごと
テスト
テスト


=================================

通常、お問い合わせのご返信は2営業日以内を心がけております。
数日経過致しましても当社からの返信がない場合は、
ご入力いただいたメールアドレスに間違い、又はお客様のメールサーバーなどにより
自動的に「迷惑メール」として格納または削除された可能性がございます。
以上をご確認の上、お手数ですが再度お問い合わせくださいますようお願い申し上げます。


=================================
【テスト株式会社】　
〒000-0000　東京都新宿区西新宿2丁目8-1
お問い合わせ　TEL　0120-000-000
HP: https://example.com
=================================",
            ]
        );
    }
}
