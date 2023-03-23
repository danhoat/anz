<?php

namespace Digima\Language;

use Digima\Support\Dictionary;

class JapaneseDictionary extends Dictionary
{
    /**
     * @param array $items
     * @return void
     */
    public function __construct(array $items = array())
    {
        parent::__construct(
            array(
                'error.required'       => 'この項目が必要です。',
                'request.unauthorized' => '認証エラーが発生しました。',
            )
        );
    }

}
