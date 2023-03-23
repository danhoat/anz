<?php

namespace Digima\Language;

use Digima\Support\Dictionary;

class EnglishDictionary extends Dictionary
{
    /**
     * @param array $items
     * @return void
     */
    public function __construct(array $items = array())
    {
        parent::__construct(
            array(
                'error.required' => 'This field is required.',
            )
        );
    }

}
