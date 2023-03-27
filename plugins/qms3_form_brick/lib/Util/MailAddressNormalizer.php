<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;

use QMS3\Brick\Exception\InvalidEmailException;


class MailAddressNormalizer
{
    const MAIL_ADDRESS_PATTERN = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/";

    /**
     * @param     string      $str
     * @param     bool        $strict
     * @return    string[]
     */
    public function normalize($str, $strict = true)
    {
        $lines = preg_split("/[\s,]+/", $str);
        $lines = array_filter(array_map("trim", $lines));

        $tos = array();
        foreach ($lines as $line) {
            if (
                $strict
                && !preg_match(self::MAIL_ADDRESS_PATTERN, $line, $matches)
            ) {
                throw new InvalidEmailException("不正な形式のメールアドレスです: $line");
            }

            $tos[] = $line;
        }

        return $tos;
    }
}
