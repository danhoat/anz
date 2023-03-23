<?php
declare(strict_types=1);

namespace QMS3\Brick\FieldRefParser;

use QMS3\Brick\FieldRef\EqualToFieldRef;


class EqualToFieldRefParser
{
    /**
     * @param     string               $src
     * @return    EqualToFieldRef[]
     */
    public function parse($src)
    {
        $lines = explode("\n", $src);
        $lines = array_filter(array_map("trim", $lines));

        foreach ($lines as $line) {
            $target = $this->parse_item($line);

            if ($target) {
                return [ new EqualToFieldRef($target) ];
            }
        }

        return [];
    }

    /**
     * @param     string         $line
     * @return    string|null
     */
    private function parse_item($line)
    {
        if (strpos(/* $haystack = */ $line, /* $needle = */ "=>") === false) {
            return null;
        }

        list($key, $target) = explode("=>", $line, /* $limit = */ 2);

        if (in_array(trim($key), [ "EQUALTO", "EQUAL", "EQ" ], /* $strict = */ true)) {
            return trim($target);
        }
    }
}
