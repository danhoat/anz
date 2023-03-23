<?php

namespace Digima\Errors;

use Digima\Exceptions\UnsupportedLanguageException;
use Digima\Language\Language;

class Error
{
    /**
     * Errors of this type occur when some data failed to pass the validation.
     */
    const TYPE_DATA_VALIDATION_ERROR = 'data_validation_error';

    /**
     * Errors of this type occur when the request could not be sent.
     * Possible causes are:
     * - Invalid API credentials (account code, Web Form code, etc.)
     * - PHP library outdated: please check if you are using the latest version of the Digima PHP client.
     * - Digima API unreachable (maintenance, etc.)
     * - Network issues
     * Check the code of the error for more details.
     */
    const TYPE_REQUEST_ERROR = 'request_error';

    /**
     * Errors of this type occur when the response content is invalid.
     * Possible causes are:
     * - PHP library outdated: please check if you are using the latest version of the Digima PHP client.
     * - Network error: the request was interrupted and we could not receive the full response.
     */
    const TYPE_RESPONSE_ERROR = 'request_error';

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $code
     * @param string $message
     * @return void
     */
    public function __construct($code, $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '['.get_called_class().'] '.$this->getCode().': '.$this->getMessage();
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if ($this->message !== null) {
            return $this->message;
        }

        try {
            return Language::getInstance()->getError($this->code, $this->code);
        } catch (UnsupportedLanguageException $e) {
            return $this->code;
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
