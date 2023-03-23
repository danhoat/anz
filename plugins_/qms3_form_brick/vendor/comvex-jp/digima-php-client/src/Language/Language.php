<?php

namespace Digima\Language;

use Digima\Exceptions\UnsupportedLanguageException;
use Digima\Support\Dictionary;

class Language
{
    const ENGLISH = 'english';
    const JAPANESE = 'japanese';

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var Language
     */
    private static $instance;

    /**
     * @var string
     */
    private static $languageKey;

    /**
     * @return void
     */
    private function __construct()
    {
        $this->dictionary = new Dictionary(array());
    }

    /**
     * Return the translation for the provided key.
     * If the key is not found in the language dictionary,
     * the provided default value (or null) is returned.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->dictionary->get($key, $default);
    }

    /**
     * Return the translation for the provided error key.
     *
     * @see Language::get()
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getError($key, $default = null)
    {
        return $this->get('error.'.$key, $default);
    }

    /**
     * @param string $languageKey
     * @throws UnsupportedLanguageException
     * @return Language
     */
    public static function getInstance($languageKey = self::JAPANESE)
    {
        if (static::$instance === null) {
            static::$instance = new Language();
        }

        if ($languageKey === static::$languageKey) {
            return static::$instance;
        }

        switch ($languageKey) {
            case self::JAPANESE:
                static::$instance->setDictionary(new JapaneseDictionary());
                break;
            case self::ENGLISH:
                static::$instance->setDictionary(new EnglishDictionary());
                break;
            default:
                throw new UnsupportedLanguageException($languageKey);
        }

        return static::$instance;
    }

    /**
     * @param Dictionary $dictionary
     * @return Language
     */
    private function setDictionary(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;

        return $this;
    }
}
