<?php

namespace Digima\Http\Responses;

use Digima\Errors\DataValidationError;
use Digima\Errors\RequestError;
use Digima\Errors\ResponseError;

class JsonResponse extends AbstractResponse
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $properties;

    /**
     * @param string $name
     * @param mixed $default
     * @param array $container Optional array where to look for the property. Defaults to "$this->properties".
     * @return mixed
     */
    public function getProperty($name, $default = null, array $container = null)
    {
        if ($container === null) {
            $container = $this->properties;
        }

        if (! array_key_exists($name, $container)) {
            return $default;
        }

        return $container[$name];
    }

    /**
     * @return void
     */
    public function parse()
    {
        if (! $this->retrieveProperties()) {
            return;
        }

        $this->checkRequestError();

        if (! $this->retrieveData()) {
            return;
        }

        $this->checkDataErrors();
    }

    /**
     * @return void
     */
    private function checkDataErrors()
    {
        $dataErrors = $this->getProperty('errors', null, $this->data);

        if (! is_array($dataErrors)) {
            return;
        }

        foreach ($dataErrors as $dataError) {
            $this->addError(DataValidationError::create($dataError));
        }
    }

    /**
     * @return void
     */
    private function checkRequestError()
    {
        $error = $this->getProperty('error');

        if (! is_array($error)) {
            return;
        }

        $this->addError(RequestError::create($error));
    }

    /**
     * @return bool
     */
    private function retrieveData()
    {
        $this->data = $this->getProperty('data');

        return $this->data !== null;
    }

    /**
     * @return bool
     */
    private function retrieveProperties()
    {
        $this->properties = json_decode($this->getBody(), true);

        if (! is_array($this->properties)) {
            $code = ResponseError::CODE_INVALID_RESPONSE_FORMAT;
            $message = 'The response format must be JSON.';

            $this->addError(new ResponseError($code, $message));

            return false;
        }

        return true;
    }
}
