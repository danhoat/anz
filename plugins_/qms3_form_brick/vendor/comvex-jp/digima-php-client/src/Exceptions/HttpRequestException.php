<?php

namespace Digima\Exceptions;

use Digima\Http\Requests\AbstractRequest;
use Throwable;

class HttpRequestException extends Exception
{
    /**
     * @var AbstractRequest
     */
    private $request;

    /**
     * @param AbstractRequest $request
     * @param string|null $message
     * @param Throwable|null $previousException
     * @return void
     */
    public function __construct(AbstractRequest $request, $message = null, Throwable $previousException = null)
    {
        $this->request = $request;

        if ($message === null) {
            $message = 'Http Request exception';
        }

        parent::__construct($message, 0, $previousException);
    }

    /**
     * @return AbstractRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
}
