<?php
declare(strict_types=1);

namespace QMS3\Brick\StructureTableLoader\CsvLoader;

use QMS3\Brick\Exception\DetectHeaderFailureException;


class CsvHeaderDetector
{
    /**
     * @param     string[]    $header
     * @return    string[]
     * @throws    DetectHeaderFailureException
     */
    public function detect(array $header)
    {
        try {
            return $this->try_detecting_v2021($header);
        }
        catch (DetectHeaderFailureException $_) {
            // 握りつぶす
        }

        try {
            return $this->try_detecting_v2017($header);
        }
        catch (DetectHeaderFailureException $_) {
            // 握りつぶす
        }

        throw new DetectHeaderFailureException();
    }

    /**
     * @param     string[]    $header
     * @return    string[]
     * @throws    DetectHeaderFailureException
     */
    public function try_detecting_v2021(array $header)
    {
        $map = [
            "block"         => "block",
            "label"         => "label",
            "type"          => "type",
            "name"          => "name",
            "default"       => "default",
            "prepend"       => "prepend",
            "append"        => "append",
            "header_notice" => "header_notice",
            "body_notice"   => "body_notice",
            "options"       => "options",
            "placeholder"   => "placeholder",
            "for_bcc"       => "for_bcc",
            "thanks_to"     => "thanks_to",
            "required"      => "required",
            "attributes"    => "attributes",
        ];
        $keys = array_keys($map);

        if (array_diff($keys, $header)) { throw new DetectHeaderFailureException(); }

        return $header;
    }

    /**
     * @param     string[]    $header
     * @return    string[]
     * @throws    DetectHeaderFailureException
     */
    public function try_detecting_v2017(array $header)
    {
        $map = [
            "BCC" => "for_bcc",
            "必須" => "required",
            "ラベル" => "label",
            "name" => "name",
            "type" => "type",
            "前" => "prepend",
            "後" => "append",
            "option" => "options",
            "param" => "attributes",
            "placeholder" => "placeholder",
            "注釈" => "body_notice",
            "ブロッククラス" => "block",
            // "default" => "default",
            // "header_notice" => "header_notice",
            // "thanks_to" => "thanks_to",
        ];
        $keys = array_keys($map);

        if (array_diff($keys, $header)) { throw new DetectHeaderFailureException(); }

        $normalized = [];
        foreach ($header as $name) {
            $normalized[] = isset($map[$name])
                ? $map[$name]
                : $name;
        }

        return $normalized;
    }
}
