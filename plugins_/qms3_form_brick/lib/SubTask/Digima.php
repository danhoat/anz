<?php
declare(strict_types=1);

namespace QMS3\Brick\SubTask;

use Digima\Client;
use Detection\MobileDetect;
use QMS3\Brick\Config\ConfigInterface as Config;
use QMS3\Brick\Exception\DigimaValidationException;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;


/**
 * フォームから送信された値を Digima に送信してレコード登録するドライバ
 *
 * @since    1.5.2
 */
class Digima implements SubTaskInterface
{
    /** @var string */
    private $account_code;

    /** @var string */
    private $form_code;

    /** @var array<string,Config> */
    private $options;

    /**
     * @param     string                  $account_code
     * @param     string                  $form_code
     * @param     array<string,Config>    $options
     */
    public function __construct(
        $account_code,
        $form_code,
        array $options
    )
    {
        $this->account_code = $account_code;
        $this->form_code    = $form_code;
        $this->options      = $options;
    }

    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    bool
     */
    public function process(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    )
    {
        if (!$step->is('submit')) { return; }

        $client = new Client($this->account_code);
        $digima = $client->makeForm($this->form_code);


        if (isset($this->options["page_title"])) {
            $config = $this->options["page_title"];
            $page_title = $config->get_value(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );

            $digima->setPageTitle($page_title);

            unset($this->options["page_title"]);
        }

        if (isset($this->options["page_url"])) {
            $config = $this->options["page_url"];
            $page_url = $config->get_value(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );

            $digima->setPageUrl($page_url);

            unset($this->options["page_url"]);
        }


        $static_fields = $this->static_fields();


        // 属性項目 (Static Fields)
        $items = [];
        foreach ($this->options as $label => $config) {
            if (!in_array($label, $static_fields, /* $strict = */ true)) { continue; }

            $items[$label] = $config->get_value(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );
        }

        $digima->contact()->staticFields()->setMany($items);


        // カスタム項目 (Custom Fields)
        $items = [];
        foreach ($this->options as $label => $config) {
            if (in_array($label, $static_fields, /* $strict = */ true)) { continue; }

            $items[$label] = $config->get_value(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );
        }

        $digima->contact()->customFields()->setMany($items);

        $result = $digima->validate();
        if ($result->hasError()) {
            $errors = $result->getErrors();

            $error_strs = [];
            foreach ($errors as $err) {
                $error_strs[] = (string) $err;
            }

            $message_body = [
                "account_code"  => $this->account_code,
                "form_code"     => $digima->getCode(),
                "page_title"    => $digima->getPageTitle(),
                "page_url"      => $digima->getPageUrl(),
                "static_fields" => $digima->contact()->staticFields()->all(),
                "custom_fields" => $digima->contact()->customFields()->all(),
                "errors"        => $error_strs,
            ];
            $json = json_encode($message_body, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

            throw new DigimaValidationException($json);
        }

        return $digima->submit();
    }

    // ====================================================================== //

    /**
     * @return    string[]
     */
    private function static_fields()
    {
        return [
            "company_id",               // 会社ID
            "contact_uid",              // 顧客UID
            "person_in_charge",         // 担当者
            "person_in_charge_id",      // 担当者ID
            "person_in_charge_email",   // 担当者メールアドレス
            "status",                   // 顧客ステータス
            "status_id",                // ステータスID
            "status_name",              // ステータス名
            "first_name",               // 名
            "first_name_kana",          // 名（カナ）
            "last_name",                // 姓
            "last_name_kana",           // 姓（カナ）
            "email",                    // メールアドレス
            "gender",                   // 性別
            "personal_website_url",     // 個人ウェブサイトURL
            "personal_landline_number", // 自宅固定電話番号
            "personal_mobile_number",   // 個人携帯電話番号
            "personal_fax_number",      // 自宅ファックス番号
            "personal_country",         // 自宅国
            "personal_region",          // 自宅都道府県
            "personal_city",            // 自宅市区町村
            "personal_postal_code",     // 自宅郵便番号
            "personal_address_1",       // 自宅住所１
            "personal_address_2",       // 自宅住所２
            "work_company_name",        // 勤務先会社名
            "work_website_url",         // 勤務先会社ウェブサイトURL
            "work_department",          // 勤務先部署名
            "work_job_title",           // 勤務先役職名
            "work_landline_number",     // 勤務先固定電話番号
            "work_mobile_number",       // 勤務先携帯電話番号
            "work_fax_number",          // 勤務先ファックス番号
            "work_country",             // 勤務先国
            "work_region",              // 勤務先都道府県
            "work_city",                // 勤務先市区町村
            "work_postal_code",         // 勤務先郵便番号
            "work_address_1",           // 勤務先住所１
            "work_address_2",           // 勤務先住所２
            "notes",                    // メモ
            "display_name_order",       // display_name_order
            "group_ids",                // グループID
            "group_names",              // グループ名
            "company_uid",              // 会社UID
            "name",                     // 会社名
            "website_url",              // ウェブサイトURL
            "phone_number",             // 電話番号
            "fax_number",               // ファックス番号
            "country",                  // 国
            "region",                   // 都道府県
            "city",                     // 市区町村
            "postal_code",              // 郵便番号
            "address_1",                // 住所1
            "address_2",                // 住所2
            "domains",                  // メールドメイン
        ];
    }
}
