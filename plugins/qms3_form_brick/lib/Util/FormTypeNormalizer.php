<?php
declare(strict_types=1);

namespace QMS3\Brick\Util;

use InvalidArgumentException;


class FormTypeNormalizer
{
    /**
     * @param     string    $form_type
     * @return    string
     */
    public function normalize($form_type)
    {
        $form_type = trim($form_type);

        // if (!preg_match("/^\w+$/", $form_type)) {
        //     throw new InvalidArgumentException("\$form_type に使用可能な文字は半角英数字とアンダースコア '_' のみで、1文字以上の長さでなければいけません: \$form_type: {$form_type}");
        // }

        return $form_type;
    }
}
