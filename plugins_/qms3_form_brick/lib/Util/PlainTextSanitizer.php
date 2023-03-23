<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;


class PlainTextSanitizer
{
    /**
     * @param     string    $str
     * @return    string
     */
    public function sanitize( $str )
    {
        $str = strip_tags( $str );
        $str = trim( $str );
        return preg_replace( '/\s+/u', ' ', $str );
    }
}
