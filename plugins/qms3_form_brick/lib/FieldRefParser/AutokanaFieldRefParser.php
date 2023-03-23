<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldRefParser;

use QMS3\Brick\FieldRef\AutokanaFieldRef;


class AutokanaFieldRefParser
{
    /**
     * @param     string                $src
     * @return    AutokanaFieldRef[]
     */
    public function parse($src)
    {
        $lines = explode("\n", $src);
        $lines = array_filter(array_map("trim", $lines));

        foreach ($lines as $line) {
            $result = $this->parse_item($line);

            if ($result) {
                list($target, $katakana) = $result;

                return [ new AutokanaFieldRef($target, $katakana) ];
            }
        }

        return [];
    }

    /**
     * @param     string          $line
     * @return    mixed[]|null
     */
    private function parse_item($line)
    {
        if (strpos(/* $haystack = */ $line, /* $needle = */ "=>") === false) {
            return null;
        }

        list($key, $target) = explode("=>", $line, /* $limit = */ 2);

        switch (trim($key)) {
            case "KANA"    : return [ trim($target), /* $katakana = */ false ];
            case "HIRAKANA": return [ trim($target), /* $katakana = */ false ];
            case "HIRAGANA": return [ trim($target), /* $katakana = */ false ];
            case "KATAKANA": return [ trim($target), /* $katakana = */ true  ];
            case "KATAGANA": return [ trim($target), /* $katakana = */ true  ];
            default: return null;
        }
    }
}
