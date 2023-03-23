<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSettingLibFactory;

use RuntimeException;
use QMS3\Brick\Enum\MailSettingFormat;
use QMS3\Brick\Enum\MailSettingFormat as Format;
use QMS3\Brick\Enum\MailSettingType as Type;
use QMS3\Brick\MailSettingLib\MailSettingLib;
use QMS3\Brick\MailSettingLibLoader\WordPressLoader;
use QMS3\Brick\MailSettingsLoader\CsvLoader;
use QMS3\Brick\MailSettingsLoader\JsonLoader;
use QMS3\Brick\MailSettingsLoader\PhpLoader;
use QMS3\Brick\Param\Param;


class MailSettingLibFactory
{
    /**
     * @param     string            $form_type
     * @param     Param             $param
     * @return    MailSettingLib
     */
    public function create($form_type, $param)
    {
        if ($param->mail_setting_format == Format::WORDPRESS) {
            $loader = new WordPressLoader();
            return $loader->load($form_type, $param->mail_setting_name);
        }

        $filepaths = $this->filepaths($param);

        $lib = new MailSettingLib();
        foreach ($filepaths as list($mail_setting_format, $filepath)) {
            if ($mail_setting_format->is(MailSettingFormat::CSV)) {
                $loader = new CsvLoader();
            } else if ($mail_setting_format->is(MailSettingFormat::JSON)) {
                $loader = new JsonLoader();
            } else if ($mail_setting_format->is(MailSettingFormat::PHP)) {
                $loader = new PhpLoader();
            } else {
                throw new RuntimeException("不明な structure_format が指定されています。structure_format は JSON, PHP, CSV のいずれかである必要があります");
            }

            $setting_options = $loader->load($filepath);

            foreach ($setting_options as $setting_type_str => $setting_option) {
                $lib->add(new Type($setting_type_str), $setting_option);
            }
        }

        return $lib;
    }

    /**
     * @param     Param      $param
     * @return    mixed[]
     */
    private function filepaths(Param $param)
    {
        $format   = $param->mail_setting_format;
        $dir      = $param->mail_setting_dir;
        $filename = $param->mail_setting_name;
        $ext      = $param->mail_setting_ext;

        $filepaths = [];

        if (file_exists("{$dir}/{$filename}.{$ext}")) {
            $filepaths[] = [$format, "{$dir}/{$filename}.{$ext}"];
        }

        if (file_exists("{$dir}/__default.{$ext}")) {
            $filepaths[] = [$format, "{$dir}/__default.{$ext}"];
        }

        // TODO: なんだこれ、ちゃんとする
        if (file_exists(__DIR__ . "/../../mail_setting/__default.php")) {
            $filepaths[] = [new Format(Format::PHP), __DIR__ . "/../../mail_setting/__default.php"];
        }

        return $filepaths;
    }
}
