<?php

namespace Digima\Exceptions;

use Digima\Http\Requests\AbstractRequest;
use Throwable;

class HttpRequestUnauthorizedException extends HttpRequestException
{
    /**
     * @param AbstractRequest $request
     * @param string $message
     * @param Throwable $previousException
     * @return void
     */
    public function __construct(AbstractRequest $request, $message = null, Throwable $previousException = null)
    {
        if ($message === null) {
            $message = 'The request authorization failed. Please check the credentials.';
        }

        parent::__construct($request, $message, $previousException);
    }
}
