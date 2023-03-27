<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueContainer;

use ArrayIterator;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\ValueContainer\ValueContainerInterface;
use QMS3\Brick\ValueItem\CheckboxValueItem;


class CheckboxValueContainer implements ValueContainerInterface
{
    use ReadonlyProps;

    /** @var    CheckboxValueItem[] */
    private $items;

    /** @var    bool */
    private $is_default;

    /**
     * @param    CheckboxValueItem[]    $items
     * @param    bool                   $is_default
     */
    public function __construct(array $items, $is_default = false)
    {
        $this->items      = $items;
        $this->is_default = $is_default;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        return join("、", $this->items);  // "、" 区切りで本当に大丈夫？ "\n" 区切りにしたくなったらどうする？
    }

    // ====================================================================== //

    /**
     * @return    CheckboxValueItem[]
     */
    protected function _value()
    {
        return $this->items;
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
     * @return     ArrayIterator<CheckboxValueItem>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
