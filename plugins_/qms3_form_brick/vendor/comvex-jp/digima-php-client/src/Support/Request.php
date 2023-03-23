<?php

namespace Digima\Support;

class Request
{
    /**
     * @return string|null
     */
    public static function getRemoteIpAddress()
    {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public static function getPageUrl()
    {
        if (! array_key_exists('REQUEST_SCHEME', $_SERVER)) {
            return null;
        }

        if (! array_key_exists('HTTP_HOST', $_SERVER)) {
            return null;
        }

        if (! array_key_exists('REQUEST_URI', $_SERVER)) {
            return null;
        }

        $requestScheme = $_SERVER['REQUEST_SCHEME'];
        $requestHost = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];

        return $requestScheme.'://'.$requestHost.$requestUri;
    }
}
