<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueContainer;

use ArrayIterator;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\ValueContainer\ValueContainerInterface;
use QMS3\Brick\ValueItem\RadioValueItem;


class RadioValueContainer implements ValueContainerInterface
{
    use ReadonlyProps;

    /** @var    RadioValueItem */
    private $item;

    /** @var    bool */
    private $is_default;

    /**
     * @param    RadioValueItem    $items
     * @param    bool              $is_default
     */
    public function __construct(RadioValueItem $item, $is_default = false)
    {
        $this->item       = $item;
        $this->is_default = $is_default;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        return (string) $this->item;
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function _value()
    {
        return $this->item;
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
     * @return     ArrayIterator<RadioValueItem>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator([ $this->item ]);
    }
}
