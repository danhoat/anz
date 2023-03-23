<?php

namespace Digima\Errors;

class DataValidationError extends Error
{
    /**
     * This is the default error code when no more specific one can be determined.
     * Please check that you have the latest version of the Digima PHP client.
     */
    const CODE_UNKNOWN_VALIDATION_ERROR = 'unknown_validation_error';

    /**
     * @var string
     */
    private $field;

    /**
     * @param string $field
     * @param string $code
     * @param string $message
     * @return void
     */
    public function __construct($field, $code, $message = null)
    {
        parent::__construct($code, $message);

        $this->field = $field;
        $this->type = self::TYPE_DATA_VALIDATION_ERROR;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '['.get_called_class().'] '.$this->getField().': '.$this->getCode().': '.$this->getMessage();
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return bool
     */
    public function isContactCustomFieldError()
    {
        return strpos($this->field, 'contact.custom_fields.') === 0;
    }

    /**
     * @return bool
     */
    public function isContactFieldError()
    {
        return strpos($this->field, 'contact.') === 0;
    }

    /**
     * @return bool
     */
    public function isContactStaticFieldError()
    {
        return $this->isContactFieldError() && ! $this->isContactCustomFieldError();
    }

    /**
     * @param array $data
     * @return DataValidationError
     */
    public static function create(array $data)
    {
        $code = self::CODE_UNKNOWN_VALIDATION_ERROR;
        $field = 'unknown_field';
        $message = null;

        if (array_key_exists('code', $data)) {
            $code = $data['code'];
        }

        if (array_key_exists('field', $data)) {
            $field = $data['field'];
        }

        if (array_key_exists('message', $data)) {
            $message = $data['message'];
        }

        return new DataValidationError($field, $code, $message);
    }
}
