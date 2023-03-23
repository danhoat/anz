<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueContainer;

use ArrayIterator;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\ValueContainer\ValueContainerInterface;


/**
 * @property-read    string    $value
 * @property-read    bool      $is_default
 */
class TextValueContainer implements ValueContainerInterface
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
        $this->value      = $value;
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

    // ====================================================================== //

    /**
     * @return     ArrayIterator<TextValueContainer>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator([$this]);
    }
}
