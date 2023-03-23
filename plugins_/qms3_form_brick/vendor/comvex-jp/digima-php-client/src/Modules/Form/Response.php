<?php

namespace Digima\Modules\Form;

use Digima\Http\Responses\AbstractResponse;

class Response
{
    const FIELD_PARTS_SEPARATOR = '.';

    /**
     * @var array
     */
    private $customFieldErrors = array();

    /**
     * @var AbstractResponse|null
     */
    private $httpResponse;

    /**
     * @var array
     */
    private $staticFieldErrors = array();

    /**
     * Return a specific field errors.
     * If the `field` argument is null, all custom field errors are returned.
     *
     * @param string|null $field
     * @return array
     */
    public function getCustomFieldErrors($field = null)
    {
        if (! $field) {
            return $this->customFieldErrors;
        }

        $errors = array();

        foreach ($this->customFieldErrors as $error) {
            $fieldNameParts = explode(self::FIELD_PARTS_SEPARATOR, $error->getField());

            if (count($fieldNameParts) < 3) {
                continue;
            }

            if ($fieldNameParts[2] === $field) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        if (! $this->httpResponse) {
            return array();
        }

        return $this->httpResponse->getErrors();
    }

    /**
     * @return AbstractResponse
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * Return a specific field errors.
     * If the `field` argument is null, all static field errors are returned.
     *
     * @param string|null $field
     * @return array
     */
    public function getStaticFieldErrors($field = null)
    {
        if (! $field) {
            return $this->staticFieldErrors;
        }

        $errors = array();

        foreach ($this->staticFieldErrors as $error) {
            $fieldNameParts = explode(self::FIELD_PARTS_SEPARATOR, $error->getField());

            if (count($fieldNameParts) < 2) {
                continue;
            }

            if ($fieldNameParts[1] === $field) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function hasCustomFieldErrors($field)
    {
        $errors = $this->getCustomFieldErrors($field);

        return count($errors) > 0;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        if (! $this->httpResponse) {
            return false;
        }

        return $this->httpResponse->hasError();
    }

    /**
     * @param string $field
     * @return bool
     */
    public function hasStaticFieldErrors($field)
    {
        $errors = $this->getStaticFieldErrors($field);

        return count($errors) > 0;
    }

    /**
     * @return void
     */
    public function parse()
    {
        if (! $this->httpResponse) {
            return;
        }

        // Separate static field errors and custom field errors
        $errors = $this->httpResponse->getValidationErrors();

        if (empty($errors)) {
            return;
        }

        foreach ($errors as $error) {
            if (! $error->isContactFieldError()) {
                continue;
            }

            if ($error->isContactCustomFieldError()) {
                $this->customFieldErrors[] = $error;
                continue;
            }

            $this->staticFieldErrors[] = $error;
        }
    }

    /**
     * @param AbstractResponse $httpResponse
     * @return Response
     */
    public function setHttpResponse($httpResponse = null)
    {
        $this->httpResponse = $httpResponse;

        return $this;
    }
}
