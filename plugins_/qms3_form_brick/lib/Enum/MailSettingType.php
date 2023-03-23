<?php
declare(strict_types=1);

namespace QMS3\Brick\Enum;

use QMS3\Brick\Enum\Enum;


class MailSettingType extends Enum
{
    /**
     * サンクスメール設定
     */
    const THANKS = "THANKS";

    /**
     * 先方通知メール
     */
    const NOTIFICATION = "NOTIFICATION";

    /**
     * 社内通知メール
     */
    const BCC_NOTIFICATION = "BCC_NOTIFICATION";
}
