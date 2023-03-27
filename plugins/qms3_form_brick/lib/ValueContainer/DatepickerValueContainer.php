<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueContainer;

use ArrayIterator;
use DateTime;
use Exception;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\ValueContainer\ValueContainerInterface;


/**
 * @property-read    string      $value
 * @property-read    bool        $is_default
 * @property-read    bool        $valid_date
 * @property-read    DateTime    $date
 */
class DatepickerValueContainer implements ValueContainerInterface
{
    use ReadonlyProps;

    /** @var    string */
    private $value;

    /** @var    bool */
    private $is_default;

    /**
     * @param    string    $value
     * @param    bool      $is_default
     */
    public function __construct($value = "", $is_default = false)
    {
        $this->value      = trim($value);
        $this->is_default = $is_default;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        return $this->value;
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function _value()
    {
        return $this->value;
    }

    /**
     * @return    bool
     */
    protected function _is_default()
    {
        return $this->is_default;
    }

    /**
     * @return    bool
     */
    protected function _valid_date()
    {
        if (empty($this->value)) { return false; }

        try {
            new DateTime($this->value);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return    DateTime|null
     */
    protected function _date()
    {
        if (empty($this->value)) { return null; }

        try {
            return new DateTime($this->value);
        }
        catch (Exception $e) {
            return null;
        }
    }

    // ====================================================================== //

    /**
     * @param    string    $date_format
     */
    public function format($date_format)
    {
        return $this->date->format($date_format);
    }

    // ====================================================================== //

    /**
     * @return     ArrayIterator<DatepickerValueContainer>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator([$this]);
    }
}
