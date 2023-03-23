<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;


class HumanReadableFilesize
{
    /**
     * @param    int    $size    ファイルサイズ
     * @param    int    $precision    小数点以下の桁数
     * @return    string
     */
    public function reword( $size, $precision = 2 )
    {
        for ( $i = 0; ( $size / 1024 ) > 0.9; $i++, $size /= 1024 ) {}

        return round( $size, $precision ) . ' '
            . array( 'B','KB','MB','GB','TB','PB','EB','ZB','YB' )[ $i ];
    }
}
