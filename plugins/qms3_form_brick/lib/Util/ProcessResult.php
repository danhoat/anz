<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;


class ProcessResult
{
    /**
     * @param     mixed[]    $results
     * @return    bool
     */
    public static function passed(array $results)
    {
        foreach ($results as $result) {
            if ($result !== true) { return false; }
        }

        return true;
    }
}
