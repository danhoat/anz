<?php

namespace Digima\Http\Responses;

use Digima\Errors\Error;
use Digima\Http\Requests\AbstractRequest;

abstract class AbstractResponse
{
    /**
     * @var string|null
     */
    private $body;

    /**
     * @var string|null
     */
    private $contentType;

    /**
     * @var array
     */
    private $errors = array();

    /**
     * @var array|null
     */
    private $headers;

    /**
     * @var AbstractRequest|null
     */
    private $request;

    /**
     * @var int|null
     */
    private $statusCode;

    /**
     * @var array
     */
    private $validationErrors = array();

    /**
     * @param AbstractRequest $request
     * @param $statusCode
     * @param $body
     * @param $contentType
     * @param array $headers
     * @return void
     */
    public function __construct(
        AbstractRequest $request = null,
        $statusCode = null,
        $body = null,
        $contentType = null,
        array $headers = null
    ) {
        $this->body = $body;
        $this->contentType = $contentType;
        $this->headers = $headers;
        $this->request = $request;
        $this->statusCode = $statusCode;

        $this->parse();
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return AbstractRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return count($this->errors) > 0;
    }

    /**
     * @return bool
     */
    public function hasValidationError()
    {
        return count($this->validationErrors) > 0;
    }

    /**
     * @return mixed
     */
    abstract public function parse();

    /**
     * @param string $body
     * @return AbstractResponse
     */
    public function setBody($body = null)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param string $contentType
     * @return AbstractResponse
     */
    public function setContentType($contentType = null)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @param array $headers
     * @return AbstractResponse
     */
    public function setHeaders($headers = null)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param AbstractRequest $request
     * @return AbstractResponse
     */
    public function setRequest($request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param int $statusCode
     * @return AbstractResponse
     */
    public function setStatusCode($statusCode = null)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param Error $error
     * @return AbstractResponse
     */
    protected function addError(Error $error)
    {
        $this->errors[] = $error;

        if ($error->getType() === Error::TYPE_DATA_VALIDATION_ERROR) {
            $this->validationErrors[] = $error;
        }

        return $this;
    }

    /**
     * @param array $errors
     * @return AbstractResponse
     */
    protected function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }
}
