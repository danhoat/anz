<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSettingsLoader;

use InvalidArgumentException;
use RuntimeException;
use SplFileObject;
use SplTempFileObject;
use QMS3\Brick\Enum\MailSettingType;
use QMS3\Brick\MailSetting\MailSettingOption;
use QMS3\Brick\MailSettingsLoader\MailSettingsLoaderInterface;


class CsvLoader implements MailSettingsLoaderInterface
{
    // keys の並び順は読み込む CSV の行の順番と対応していなければいけない
    // CSV のフォーマットを変更したらこの並び順も変更が必要になる
    private $keys = [
        "tos",
        "from",
        "from_name",
        "subject",
        "main_text",
        "block_filter",
        "post_result",
        "after_text",
        "signature",
    ];

    /**
     * @param     string    $filepath
     * @return    array<string,MailSettingOption>
     */
    public function load($filepath)
    {
        $csv = $this->load_csv($filepath);

        // 読み込んだ CSV を列単位で取り扱いたいので、まず転置する
        $cols = $this->transpose($csv);

        // 1列目は見出し列なので捨てる
        array_shift($cols);

        $mail_setting_types = [
            new MailSettingType(MailSettingType::THANKS),
            new MailSettingType(MailSettingType::NOTIFICATION),
            new MailSettingType(MailSettingType::BCC_NOTIFICATION),
        ];

        $setting_options = [];
        foreach ($mail_setting_types as $mail_setting_type) {
            $fields = $this->search_mail_setting($cols, $mail_setting_type);
            $setting_option = array_combine($this->keys, $fields);

            $setting_options[$mail_setting_type->value()] = new MailSettingOption(
                /* $tos_str              = */ trim($setting_option["tos"]),
                /* $from                 = */ trim($setting_option["from"]),
                /* $from_name            = */ trim($setting_option["from_name"]),
                /* $subject_template     = */ trim($setting_option["subject"]),
                /* $main_text_template   = */ $setting_option["main_text"],
                /* $block_filter         = */ $setting_option["block_filter"],
                /* $post_result_template = */ $setting_option["post_result"],
                /* $after_text_template  = */ $setting_option["after_text"],
                /* $signature_template   = */ $setting_option["signature"]
            );
        }

        return $setting_options;
    }

    /**
     * @param     string     $filepath
     * @return    array[]
     */
    private function load_csv($filepath)
    {
        $file = new SplFileObject($filepath, "rb");
        $temp = new SplTempFileObject();

        $bom = hex2bin("EFBBBF");

        // 入力文字列の文字コードをいったん SJIS に変換する
        // SplFileObject::READ_CSV で CSV をパースするとき、
        // 入力が UTF-8 だとカンマ区切りの分割が正常に行われないことがある
        //
        // この処理によって入力文字列の文字コードが UTF-8 でも SJIS でも
        // 統一的に取り扱えるようになる
        while (!$file->eof()) {
            $line = $file->fgets();

            if ($line === false) { continue; }

            $line = preg_replace("/^{$bom}/", "", $line);
            $line = mb_convert_encoding($line, "SJIS-win", ["UTF-8", "SJIS-win"]);

            $temp->fwrite($line);
        }

        $temp->seek(0);
        $temp->setFlags(SplFileObject::READ_CSV);

        $rows = [];
        foreach ($temp as $row) {
            // 空行を読み飛ばす
            $filtered_row = array_filter(array_map("trim", $row));
            if (empty($filtered_row)) { continue; }

            $converted = [];
            foreach ($row as $field) {
                $converted[] = mb_convert_encoding($field, "UTF-8", "SJIS-win");
            }

            $rows[] = $converted;
        }

        return $rows;
    }

    /**
     * 行列の転置
     *
     * @param     array<int,array>    $arr
     * @return    array<int,array>
     */
    private function transpose(array $arr)
    {
        /**
         * 読みづらいコードだけど、ここでやってるのは
         *
         * list($row1, $row2, $row3, ...) = $arr;
         * return array_map(null, $row1, $row2, $row3, ...);
         *
         * みたいなこと
         *
         * array_map() は $callback = null として呼び出すと後続の引数を zip して返す
         *
         * @see https://www.php.net/manual/ja/function.array-map.php#example-5440
         */

        $args = array_merge(
            [ null ],
            $arr
        );

        return call_user_func_array("array_map", $args);
    }

    /**
     * @param     array[]            $cols
     * @param     MailSettingType    $type
     * @return    string[]
     */
    private function search_mail_setting(array $cols, MailSettingType $type)
    {
        switch ($type->value()) {
            case MailSettingType::THANKS          : $header = "サンクスメール"; break;
            case MailSettingType::NOTIFICATION    : $header = "先方通知メール"; break;
            case MailSettingType::BCC_NOTIFICATION: $header = "社内通知メール"; break;
            default: throw new InvalidArgumentException("不明な MailSettingType です: \$type: {$type->value()}");
        }

        foreach ($cols as $fields) {
            if (trim($fields[0]) == $header) {
                return array_slice($fields, 1, count($this->keys));
            }
        }

        throw new RuntimeException("メール設定ファイルの中で指定された列が見つかりません: \$header: {$header}");
    }
}
