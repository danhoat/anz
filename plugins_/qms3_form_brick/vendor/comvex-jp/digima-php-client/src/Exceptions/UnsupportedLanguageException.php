<?php

namespace Digima\Exceptions;

class UnsupportedLanguageException extends Exception
{
    /**
     * @param string $languageKey
     * @return void
     */
    public function __construct($languageKey)
    {
        parent::__construct($languageKey.' is not a supported language.');
    }
}
