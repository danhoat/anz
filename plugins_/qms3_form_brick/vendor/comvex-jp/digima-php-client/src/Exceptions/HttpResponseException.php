<?php

namespace Digima\Exceptions;

class HttpResponseException extends Exception
{
    /**
     * @var mixed|null
     */
    private $rawResponse;

    /**
     * @param mixed|null $rawResponse
     * @return void
     */
    public function __construct($rawResponse = null)
    {
        parent::__construct('Invalid response format.');

        $this->rawResponse = $rawResponse;
    }

    /**
     * @return mixed|null
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }
}
