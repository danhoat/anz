<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueItem;

use QMS3\Brick\Util\ReadonlyProps;


/**
 * @property-read    int                    $index
 * @property-read    string                 $label
 * @property-read    string                 $value
 *
 * @method    int                    index()
 * @method    string                 label()
 * @method    string                 value()
 */
class SelectValueItem
{
    use ReadonlyProps;

    /** @var    int */
    private $index;

    /** @var    string */
    private $label;

    /** @var    string */
    private $value;

    /**
     * @param    int                    $index
     * @param    string                 $label
     * @param    string                 $value
     */
    public function __construct(
        $index,
        $label,
        $value
    )
    {
        $this->index = $index;
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    // ====================================================================== //

    /**
     * @return    int
     */
    protected function _index()
    {
        return $this->index;
    }

    /**
     * @return    string
     */
    protected function _label()
    {
        return $this->label;
    }

    /**
     * @return    string
     */
    protected function _value()
    {
        return $this->value;
    }
}
