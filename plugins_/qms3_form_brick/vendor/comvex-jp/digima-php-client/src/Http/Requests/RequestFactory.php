<?php

namespace Digima\Http\Requests;

class RequestFactory
{
    /**
     * @return RequestInterface
     */
    public static function create()
    {
        return new StreamContextRequest();
    }
}
