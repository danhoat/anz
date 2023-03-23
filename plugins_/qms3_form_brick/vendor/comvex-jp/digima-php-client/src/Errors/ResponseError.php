<?php

namespace Digima\Errors;

class ResponseError extends Error
{
    /**
     * This error code means that the received response body is not formatted correctly.
     * Please check that you have the latest version of the Digima PHP client.
     */
    const CODE_INVALID_RESPONSE_FORMAT = 'invalid_response_format';

    /**
     * @param string $code
     * @param string $message
     * @return void
     */
    public function __construct($code, $message = null)
    {
        parent::__construct($code, $message);
        $this->type = self::TYPE_RESPONSE_ERROR;
    }
}
