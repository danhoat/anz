<?php

namespace Digima\Errors;

class RequestError extends Error
{
    /**
     * This error code means that the request's http method is invalid.
     * Please check that you have the latest version of the Digima PHP client.
     */
    const CODE_METHOD_NOT_ALLOWED = 'method_not_allowed';

    /**
     * This error code means that the requested resource could not be found.
     * Please check the resource ID.
     */
    const CODE_RESOURCE_NOT_FOUND = 'resource_not_found';

    /**
     * This error code means that the Digima service is unavailable.
     * If this error persist, please contact a Digima representative.
     */
    const CODE_SERVICE_ERROR = 'service_error';

    /**
     * This error code means that the request was not authorized.
     * Please check the API credentials (account code, Web Form code, etc.).
     */
    const CODE_UNAUTHORIZED = 'unauthorized';

    /**
     * This error code means that we could not recognize the error.
     * Please check that you have the latest version of the Digima PHP client.
     */
    const CODE_UNKNOWN_ERROR = 'unknown_error';

    /**
     * @param string $code
     * @param string $message
     * @return void
     */
    public function __construct($code, $message = null)
    {
        parent::__construct($code, $message);

        $this->type = self::TYPE_REQUEST_ERROR;
    }

    /**
     * @param array $data
     * @return RequestError
     */
    public static function create(array $data)
    {
        $code = self::CODE_UNKNOWN_ERROR;
        $message = null;

        if (array_key_exists('code', $data)) {
            $code = $data['code'];
        }

        if (array_key_exists('message', $data)) {
            $message = $data['message'];
        }

        return new RequestError($code, $message);
    }
}
