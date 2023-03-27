<?php
declare(strict_types=1);

namespace QMS3\Brick\Enum;

use QMS3\Brick\Enum\Enum;


class ExtraInputType extends Enum
{
    /**
     * input[type=text]
     */
    const TEXT = "TEXT";

    /**
     * textarea
     */
    const TEXTAREA = "TEXTAREA";
}
