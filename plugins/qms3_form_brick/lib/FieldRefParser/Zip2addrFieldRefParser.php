<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldRefParser;

use QMS3\Brick\FieldRef\Zip2addrFieldRef;
use RuntimeException;

class Zip2addrFieldRefParser
{
    /**
     * @param     string                $src
     * @return    Zip2addrFieldRef[]
     */
    public function parse($src)
    {
        $lines = explode("\n", $src);
        $lines = array_filter(array_map("trim", $lines));

        foreach ($lines as $line) {
            $result = $this->parse_item($line);

            if ($result && count($result) == 2) {
                return [
                    new Zip2addrFieldRef($result[0], "pref"),
                    new Zip2addrFieldRef($result[1], "addr"),
                ];
            } else if ($result && count($result) == 1) {
                return [
                    new Zip2addrFieldRef($result[0], "both"),
                ];
            }
        }

        return [];
    }

    /**
     * @param     string           $line
     * @return    string[]|null
     */
    private function parse_item($line)
    {
        if (strpos(/* $haystack = */ $line, /* $needle = */ "=>") === false) {
            return null;
        }

        list($key, $target) = explode("=>", $line, /* $limit = */ 2);

        if (trim($key) != "ZIP") { return null; }

        if (trim($target) == false) {
            throw new RuntimeException("住所自動反映の対象が指定されていません。 'ZIP=>' に続いて対象の name 属性が指定されている必要があります");
        }

        $pref_and_addr = explode(",", $target);
        return array_filter(array_map("trim", $pref_and_addr));
    }
}
